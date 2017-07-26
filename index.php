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
					$url = $row['url']; echo $url;

					echo "<h4><a href='";echo $url;echo "'>";
					echo $row['fileName'];
					echo "</a></h4>";

		      		}
			}else{			
				echo "<h4>无文件</h4>";echo "</br>";
			}
			echo "</br>";
		}

	}

	if (!isset ($_SESSION['islogin'])){
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="./bootstrap/js/bootstrap.min.js"></script>

  </body>
</html>
