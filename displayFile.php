<?php

$user = $_GET['user'];
function display($user){

		//列出所有文件
		//1、先打开要操作的目录，并用一个变量指向它
		$dir = "./upload/$user";
		//2、循环的读取目录下的所有文件
		if($handler = opendir($dir)){
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
		}
		echo "</br>";
		echo "</br>";
		echo "请开始探索 -> <a href='login.php'>登录</a>" ;
		exit () ;
	
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
		<?php display($user); ?>
            </p>

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
