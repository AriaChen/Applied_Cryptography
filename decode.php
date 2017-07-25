<?php
/**
 * @description
 * @author huangwei
 */
 //接收参数：hex数据+hex iv
 function decode($filepath,$key)
 {


   $name=basename($filepath);
   $hash=explode('.', $name)[0];

   //参数：文件路径
   $fp = fopen($filepath,"r+");
   $str = fread($fp,filesize($filepath));//指定读取大小，这里把整个文件内容读取出来
   $str_hex=bin2hex($str);

   $lenthofiv=mcrypt_enc_get_iv_size(mcrypt_module_open('tripledes', '', 'cbc', ''));
   $lenthofiv = $lenthofiv*2;

   $mysqli = new mysqli("localhost", "root",$_SERVER['MYSQL_PSW'], "login");
   //读取数据库读出长度
   $sql1en = "select * from file where hashFile = '$hash'";
   $result1en = $mysqli->query($sql1en);
   $field_info_arr1 = $result1en->fetch_fields();
   while($row= $result1en->fetch_assoc())
   {
     //取出长度
     $len = $row['len'];
     //密文
     $input_hex=substr($str,$lenthofiv);
     //初始向量
     $iv_hex = substr($str,0,$lenthofiv);
     echo 'ivhex:';
     echo $iv_hex;
   }

//初始向量
if(!function_exists("hex2bin")) { // PHP 5.4起引入的hex2bin
    function hex2bin($data) {
        return pack("H*", $data);
    }
}
$iv = hex2bin($iv_hex);
//加密数据
$input = hex2bin($input_hex);
//打开算法和模式对应的模块　这里使用三层des加密并且使用cbc模式
$td = mcrypt_module_open('tripledes', '', 'cbc', '');
//初始化缓冲区
mcrypt_generic_init($td, $key, $iv);
//解密数据
$decrypted_data = mdecrypt_generic($td, $input);
mcrypt_generic_deinit($td);
mcrypt_module_close($td);
//文件写入

file_put_contents($filepath,$decrypted_data);
echo '$decrypted_data';
echo bin2hex($decrypted_data);

}
?>
