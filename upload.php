<?php
header('Content-Type: text/plain; charset=utf-8');
include 'encode.php';
include 'decode.php';
try {

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upload_file']['error']) ||
        is_array($_FILES['upload_file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }
    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['upload_file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }
    // You should also check filesize here.
    if ($_FILES['upload_file']['size'] > 10485760) {
        throw new RuntimeException('文件必须小于10MB');
    }
    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    //$ext指文件后缀名
    if (false === $ext = array_search(
        $finfo->file($_FILES['upload_file']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
	    'jpg1' => 'image/jpg',
	    'jpg2' => 'image/pjpeg',
            'png1' => 'image/x-png',
            'doc' => 'application/msword',
	    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	    'xls' => 'application/vnd.ms-excel',
	    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	    'pdf' => 'application/pdf',
	    'pdf1' => 'application/x-pdf',
	    'ppt' => 'application/vnd.ms-powerpoint',
	    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ),
        true
    )) {
        throw new RuntimeException('文件类型不合法！');
    }
    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    //新文件名＝原先文件名+'-'+对文件sha1之后的结果
    $new_filename=$_FILES['upload_file']['name'].'-'.sha1_file($_FILES['upload_file']['tmp_name']);
    if (!move_uploaded_file(
        $_FILES['upload_file']['tmp_name'],
        sprintf('./upload/%s.%s',
            $new_filename,
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    echo 'File is uploaded successfully.';
    //文件加密存储


    //读取文件内容到变量
    $file_path = "/var/www/html/upload/".$new_filename.'.'.$ext;
    echo $file_path;
    if(file_exists($file_path))
    {
      encode($file_path);
      decode($file_path);

    /*$fp = fopen($file_path,"r");
    $str = fread($fp,filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
    echo "初始结果(hex):";
    echo "</br>";
    //转成hex形式
    $strhex = bin2hex($str);
    echo $strhex;
    $len = strlen($strhex);

    //文件加密　$result是iv和加密数据的混合
    $result=encode($str);
    echo "加密结果(hex):";
    //加密的最终结果
    //iv长度
    $lenthofiv=mcrypt_enc_get_iv_size(mcrypt_module_open('tripledes', '', 'cbc', ''));
    $lenthofiv = $lenthofiv*2;
    //$enresult是加密数据
    $enresult=substr($result,$lenthofiv);
    echo $enresult;
    //$iv是初始向量
    $iv = substr($result,0,$lenthofiv);
    echo "</br>IV:";
    echo $iv;

    //加密数据写入文件
    $myfile = fopen($file_path, "w");
    fwrite($myfile,$enresult);
    fclose($myfile);

　　//解密
    $deresult = decode($filepath);
  /*  echo "解密结果(hex)且去0:";
    //hex形式的字符串
    //echo $deresult;
    //$now_len=strlen($deresult);
    //解密后的ｈｅｘ形式
    $derehex = substr($deresult,0,$len);
    echo $derehex;
    //echo $derehex;
    //转成ｂｉｎ形式写入
    /*$myfile1 = fopen('./upload/1.jpg', "w");
    //echo hex2bin($derehex);
    fwrite($myfile1, hex2bin($derehex));
    fclose($myfile1);
*/
    }



  }
catch (RuntimeException $e) {
    echo $e->getMessage();
  }
?>
