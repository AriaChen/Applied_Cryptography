<?php
/**
 * @description
 * @author huangwei
 */
 //接收参数：hex数据+hex iv
 function decode($filepath)
 {
   //参数：文件路径
   $fp = fopen($filepath,"r+");
   $str = fread($fp,filesize($filepath));//指定读取大小，这里把整个文件内容读取出来

   $lenthofiv=mcrypt_enc_get_iv_size(mcrypt_module_open('tripledes', '', 'cbc', ''));
   $lenthofiv = $lenthofiv*2;

  // $lenthoflen=substr($str,0,20);
   //长度
   $len=substr($str,0,20);
   echo "len:";
   echo $len;
   //密文
   $input_hex=substr($str,$lenthofiv+20);
   //初始向量
   $iv_hex = substr($str,20,$lenthofiv);

   //读取ｋｅｙ
$key = "this is a secret key";
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

fwrite($fp, $decrypted_data);
fclose($fp);

}
?>
