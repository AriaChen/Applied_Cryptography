<?php
include "generateUrl.php";
include 'symmetric_enc.php';

$file = $_GET['file'];
$time = $_GET['e'];
$token = $_GET['token'];

if(($time-time())<=0){
	echo "文件已过期！";
}else{
	$pieces = explode(":",$token);
	$user = $pieces[0];
	$EncodedSign = $pieces[1];
	$pieces = explode(".", $file);
	$hash = $pieces[0];
	
	$cwd = dirname(__FILE__);
	$priv_key = file_get_contents("file://$cwd/sigKey/$user.key");
	$baseUrl = "getDownload.php?file=$file&e=$time";
	$calSign = urlsafe_b64encode(hash_hmac('sha1', $baseUrl, $priv_key, true));//计算HMAC-SHA1签名以验证
	if($calSign != $EncodedSign) echo "下载链接有误！";
	else{		
		$mysqli = new mysqli("localhost", "root", $_SERVER['MYSQL_PSW'],"login");
		if(!$mysqli)  echo "数据库连接失败";
		else {
			$sql_select = "select * from file where hashFile = '$hash' and user = '$user'";

			$res_select = $mysqli->query($sql_select);
			if(!$res_select) {
				$string = $mysqli->error; echo $string;
			}
			else{
				$field_info_arr = $res_select->fetch_fields();
			
				$row = $res_select->fetch_assoc();
				$cipher_key = $row['key']; $fileName = $row['fileName'];

				//解密对称密钥
				$priv_key = openssl_pkey_get_private("file://$cwd/sigKey/$user.key");
				$result = openssl_private_decrypt(hex2bin($cipher_key),$baseKey,$priv_key);

				$decryptedtext_hash = decode("./upload/$user/$file",$baseKey,$fileName,$user);
				file_put_contents("./upload/$user/$hash.txt","Download text sha256: $decryptedtext_hash\n",FILE_APPEND);
				$filePath = "$cwd/upload/$user/$fileName";	
   
		   		if (file_exists($filePath)) {
				    header('Content-Description: File Transfer');
				    header('Content-Type: application/octet-stream');
				    header('Content-Disposition: attachment; filename="'.$fileName.'"');
				    header('Expires: 0');
				    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				    header('Pragma: public');
				    header('Content-Length: ' . filesize($filePath));
				    ob_clean();
				    //print_r(error_get_last());
            			    flush();
				    readfile($filePath);
				} 
				//Header ( "Location:https://rachelaria.com/upload/$fileName" ); 
				unlink("./upload/$user/$fileName"); 
				
			}
		}
		 

	}
}


?>
