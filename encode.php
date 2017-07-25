<?php
function encode($filepath,$key)
{

  $input = file_get_contents($filepath);
  $strhex = bin2hex($input);
  echo '明文hex:';
  echo $strhex;
    //明文长度
    //$num=str_pad($num,4,"0",STR_PAD_LEFT);
    $len = strlen($strhex);

//把十六进制字符串转成二进制字符串
if(!function_exists("hex2bin")) { // PHP 5.4起引入的hex2bin
    function hex2bin($data) {
        return pack("H*", $data);
    }
}
//打开算法和模式对应的模块　这里使用三层des加密并且使用cbc模式
$td = mcrypt_module_open('tripledes', '', 'cbc', '');
///创建初始向量
//MCRYPT_DEV_RANDOM　指从/dev/random　文件读取数据
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
//初始化加密所需的缓冲区
mcrypt_generic_init($td, $key, $iv);
//加密数据
$encrypted_data1 = mcrypt_generic($td, $input);
//对加密模块进行清理.会清理缓冲区但是不关闭模块
mcrypt_generic_deinit($td);
//手动关闭加密模块
mcrypt_module_close($td);
//print_r(bin2hex($encrypted_data1)."\n");
$encrypted_data= bin2hex($iv).bin2hex($encrypted_data1);
//加密数据写入文件

echo "encrypted_data";
echo $encrypted_data;

echo "iv(加密前):";
echo bin2hex($iv);
file_put_contents($filepath,$encrypted_data);

return $len;
}
?>
