<?php
header('Content-Type:text/html;charset=utf-8');
?>
<!DCOTYPE html>
<html>
<head>
	<title>文件上传</title>
</head>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
上传文件：<input name="upload_file" type="file" /><br/>
<input type="submit" value="上传" />
</form>
</body>
</html>
