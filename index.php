<?php
session_start ();

if (isset ($_SESSION['islogin'])){

	echo "<h1>您的文件</h1>";

	$user = $_SESSION['islogin'];
	$mysqli = new mysqli("localhost", "root", $_SERVER['MYSQL_PSW'],"login");
	if(!$mysqli)  echo "数据库连接失败";
	else {
		$sql_select = "SELECT fileName, hashFile, ext FROM file WHERE user LIKE '%$user%'";
		$res_select = $mysqli->query($sql_select);
		if(!$res_select) echo "查询文件失败";
		if($num = mysqli_num_rows($res_select) > 0){
			$field_info_arr = $res_select->fetch_fields();
			
			while($row = $res_select->fetch_assoc())
                	{
				$hash = $row['hashFile'];$ext = $row['ext'];

				echo "<a href='/upload/$hash";echo ".";echo $ext;echo "'>";
				echo $row['fileName'];
				echo "</a>";
				echo "</br>";

				/*echo "<a href='/upload/$hash";echo ".";echo "dat";echo "'>";
				echo $row['fileName'];echo "数字签名";
				echo "</a>";
				echo "</br>";*/
              		}
		}else{			
			echo "无文件";
		}
	}

}

?>

<?php
header('Content-Type:text/html;charset=utf-8');
//echo $_SESSION['islogin'];
if (!isset ($_SESSION['islogin'])){
echo "</br>";
echo "</br>";
echo "您还没有登录,<a href='login.php'>登录</a>才可上传文件" ;
exit () ;
}

?>
<!DCOTYPE html>
<html>
<head>
	<title>文件</title>
</head>
<body>
</br>
</br>


<form action="upload.php" method="post" enctype="multipart/form-data">
上传文件：<input name="upload_file" type="file" /><br/>
文件分享码:<input type="password" name="baseKey"/><br/>
<input type="submit" value="上传" />
<a href='https://rachelaria.com/logout.php'>退出登录</a>
</br>

</form>

</body>
</html>
