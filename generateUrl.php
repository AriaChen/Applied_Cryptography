<?php

    function urlsafe_b64encode($string) {
       $data = base64_encode($string);
       $data = str_replace(array('+','/','='),array('-','_',''),$data);
       return $data;
    }

    function urlsafe_b64decode($string) {
       $data = str_replace(array('-','_'),array('+','/'),$string);
       $mod4 = strlen($data) % 4;
       if ($mod4) {
           $data .= substr('====', $mod4);
       }
       return base64_decode($data);
    }


    function DownloadUrl($file, $user, $expires = 360000){

	$baseUrl = "getDownload.php?file=$file";

        $deadline = time() + $expires;
        $baseUrl .= '&e=';
        $baseUrl .= $deadline; //url加上过期时间e参数，Unix时间戳

	$cwd = dirname(__FILE__);
	$priv_key = file_get_contents("file://$cwd/sigKey/$user.key");

	$Sign = hash_hmac('sha1', $baseUrl, $priv_key, true);//url字符串计算HMAC-SHA1签名
	$EncodedSign=urlsafe_b64encode($Sign);//对结果做URL安全的Base64编码

        $token = $user.":".$EncodedSign;//User名与上一步结果连接做Token
        return "$baseUrl&token=$token";
    }

?>

