

<?php

header('Content-Type: text/plain; charset=utf-8');

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
    if (!move_uploaded_file(
        $_FILES['upload_file']['tmp_name'],
        sprintf('./upload/%s.%s',
            sha1_file($_FILES['upload_file']['tmp_name']),
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    echo 'File is uploaded successfully.';

} catch (RuntimeException $e) {

    echo $e->getMessage();

}

?>
