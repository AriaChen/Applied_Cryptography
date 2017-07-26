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
			$sql_select = "select * from file where hashFile = $hash";  
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
				$result = openssl_private_decrypt($cipher_key,$baseKey,$priv_key);

				decode("$cwd/upload/$fileHash.$ext",$baseKey,$fileName);

				$file = fopen ("./upload/$fileName", "r" );      
		   		Header ( "Content-type: application/octet-stream" );    
		    		Header ( "Accept-Ranges: bytes" );    
		    		Header ( "Accept-Length: " . filesize ("./upload/$fileName") );    
		    		Header ( "Content-Disposition: attachment; filename=" . $fileName );     
		    		//读取文件内容并直接输出到浏览器    
		    		echo fread ( $file, filesize ("./upload/$fileName") );    
		    		fclose ( $file );    
				unlink("./upload/$fileName");
		    		exit ();  
			}
		}
		 

	}
}


?>
