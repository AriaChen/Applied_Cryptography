<?php

function display(){
	session_start ();

	if (isset ($_SESSION['islogin'])){
		echo "</br>";
		echo "<h2>您的文件：</h2>";

		$user = $_SESSION['islogin'];
		$mysqli = new mysqli("localhost", "root", $_SERVER['MYSQL_PSW'],"login");
		if(!$mysqli)  echo "数据库连接失败";
		else {
			$sql_select = "SELECT fileName, hashFile, ext, url FROM file WHERE user LIKE '%$user%'";
			$res_select = $mysqli->query($sql_select);
			if(!$res_select) echo "查询文件失败";
			if($num = mysqli_num_rows($res_select) > 0){
				$field_info_arr = $res_select->fetch_fields();
				echo "</br>";
			
				while($row = $res_select->fetch_assoc())
		        	{
					$hash = $row['hashFile'];$ext = $row['ext'];
					$url = $row['url'];

					echo "<h4><a href='";echo $url;echo "'>";
					echo $row['fileName'];
					echo "</a></h4>";

					echo "<h4><a href='./upload/$hash.txt'>";
					echo "文件散列值";
					echo "</a></h4>";


					echo "</br>";

		      		}
			}else{			
				echo "<h4>无文件</h4>";echo "</br>";
			}
			echo "</br>";
		}

	}

	if (!isset ($_SESSION['islogin'])){

		//列出所有文件
		//1、先打开要操作的目录，并用一个变量指向它
		//打开当前目录下的目录pic下的子目录common。
		$handler = opendir('./upload');
		//2、循环的读取目录下的所有文件
		//其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
		while( ($filename = readdir($handler)) !== false ) {
      		//3、目录下都会有两个文件，名字为’.'和‘..’，不要对他们进行操作
      			if($filename != "." && $filename != ".."){
          		//4、进行处理
				echo "<a href='./upload/";
				echo $filename;
				echo "'>";
				echo $filename;
				echo "</a>";
				echo "</br>";
      			}
		}
		//5、关闭目录
		closedir($handler);
		echo "</br>";
		echo "</br>";
		echo "请开始探索 -> <a href='login.php'>登录</a>" ;
		exit () ;
	}
}

?>



<!DOCTYPE html>
<html lang="zh">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rachel & Aria</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./bootstrap/css/cover.css" rel="stylesheet">
    <link href="./bootstrap/css/theme.css" rel="stylesheet">


  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
          </div>

          <div class="inner cover">
            <br><br><h1 class="cover-heading">Welcome!</h1>
            <p class="lead">Why not get started and enjoy applied cryptography?</p>
            <p class="lead">
		<?php display(); ?>
		<form action="upload.php" method="post" enctype="multipart/form-data">
            </p>
		<font size = "4">
		<p>
		   <label style = "float:left">选择上传文件: <input type="file" name="upload_file" style = "float:right"/></label>
		  <!-- <label style = "float:left">文件分享密码: <input type="password" name="baseKey"/></label>-->
		</p>
		   <input type="submit" value="上传" class="btn btn-lg btn-default"/><br><br><br>
		
		<a href='https://rachelaria.com/logout.php'>退出登录</a>
		</font>
          </div>

        </div>

      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./bootstrap/js/bootstrap.min.js"></script>

  </body>
</html>
