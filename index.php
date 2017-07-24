<?php
session_start ();
//文件下载函数
	//列出所有文件
	/**
	 * 【php获取目录中的所有文件名】
	 */
	//1、先打开要操作的目录，并用一个变量指向它
	//打开当前目录下的目录pic下的子目录common。
$handler = opendir('./upload');
//2、循环的读取目录下的所有文件
//其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
echo "<h1>所有文件</h1>";
while( ($filename = readdir($handler)) !== false ) {
      //3、目录下都会有两个文件，名字为’.'和‘..’，不要对他们进行操作
      if($filename != "." && $filename != ".."){
          //4、进行处理
          //这里简单的用echo来输出文件名
					/*echo "<a href='./upload/";
					echo $filename;
					echo "'>";
					echo $filename;
					echo "</a>";
					echo "<br/>";*/
					//文件下载
					echo "<a href='/upload/";
					echo $filename;
					echo "' download='";
					echo $filename;
					echo ".jpg'>";
					echo $filename;
					echo "</a>";
					echo "</br>";
      }
}
//5、关闭目录
closedir($handler);
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
/*if($_SESSION['ISLOGIN'] == 1)
{
	echo "请登录！";
	exit();
}*/
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
