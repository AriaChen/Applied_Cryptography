<?php

header('Content-Type: text/plain; charset=utf-8');

include 'Signature.php';

session_start();

try {
   
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
    if (false === $ext = array_search(
        $finfo->file($_FILES['upload_file']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'doc' => 'application/msword',
	    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	    'xls' => 'application/vnd.ms-excel',
	    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	    'pdf' => 'application/pdf',
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
    $fileHash = sha1_file($_FILES["upload_file"]["tmp_name"]);
    $name = $_FILES['upload_file']['name'];
    $user = $_SESSION['islogin'];

    //prevent multiple uploads of the same file
    $filenames = scandir("./upload/");
    foreach($filenames as $fname) {
	if(strpos($fname,$fileHash)!==false)
       	    throw new RuntimeException("You've already uploaded that file.");   
    }

    create_self_signed($_SESSION['islogin']);
    sign($_FILES['upload_file']['tmp_name'],$fileHash,$user);

    if (!move_uploaded_file(
        $_FILES['upload_file']['tmp_name'],
        sprintf('./upload/%s.%s',
            $fileHash,$ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    //数据库操作
    $mysqli = new mysqli("localhost", "root", $_SERVER['MYSQL_PSW'],"login");
    if(!$mysqli)  throw new RuntimeException("数据库连接失败");
    else {
        $sql_insert = "insert into file (hashFile,user,fileName,ext) values('$fileHash','$user','$name', '$ext')";
	//echo $sql_insert;
	$res_insert = $mysqli->query($sql_insert);
	if(!$res_insert) throw new RuntimeException("文件信息存入数据库失败");
    }

    $cwd = dirname(__FILE__);
    verify("$cwd/upload/$fileHash.$ext",$fileHash,$user);


    echo 'File is uploaded successfully.';

} catch (RuntimeException $e) {

    echo $e->getMessage();

}

?>
