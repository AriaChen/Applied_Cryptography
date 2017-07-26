<?php
function encode($filepath)
{
	$plaintext = file_get_contents($filepath);

	$plaintext_hash = hash_file('sha256', $filepath);// 计算原始明文的散列值
	echo "上传文件hash值: "; echo $plaintext_hash;

	$method = "aes-256-cbc"; // print_r(openssl_get_cipher_methods());
	$enc_key = bin2hex(openssl_random_pseudo_bytes(32)); // 对称加密秘钥，应妥善保存
	$enc_options = 0;
	$iv_length = openssl_cipher_iv_length($method);
	$iv = openssl_random_pseudo_bytes($iv_length);
	$ciphertext = openssl_encrypt($plaintext, $method, $enc_key, $enc_options, $iv);
	// 定义我们“私有”的密文结构
	$saved_ciphertext = sprintf('%s$%d$%s$%s', $method, $enc_options, bin2hex($iv), $ciphertext);

	file_put_contents($filepath,$saved_ciphertext);
	return $enc_key;
}

function decode($filepath,$enc_key,$fileName){
	$saved_ciphertext = file_get_contents($filepath);
	// 检查密文格式是否正确、符合我们的定义
	if(preg_match('/.*$.*$.*$.*/', $saved_ciphertext) !== 1) {
	    fprintf(STDERR, "无法解密的密文格式\n");
	    exit(1);
	}
	// 解析密文结构，提取解密所需各个字段
	list($extracted_method, $extracted_enc_options, $extracted_iv, $extracted_ciphertext) = explode('$', $saved_ciphertext); 
	$decryptedtext = openssl_decrypt($extracted_ciphertext, $extracted_method, $enc_key, $extracted_enc_options, hex2bin($extracted_iv));

	file_put_contents("./upload/$fileName",$decryptedtext);
	// 计算解密后密文的散列值
	$decryptedtext_hash = hash('sha256', $decryptedtext);
	//echo "下载文件hash值: "; echo $decryptedtext_hash;

}
?>
