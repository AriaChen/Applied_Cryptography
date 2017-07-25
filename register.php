<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>Register</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./bootstrap/css/signin.css" rel="stylesheet">


  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="regcheck.php" method="post">
        <h2 class="form-signin-heading">Sign up now!</h2><br>
        <!--<label for="inputUsername" class="sr-only">用户名</label>-->
        <h4>用户名：</h4>
	<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        <!--<label for="inputPassword" class="sr-only">密码</label>-->
        <h4>密码：</h4>
	<input type="password" name="password" class="form-control" placeholder="Password" required>
	<!--<label for="inputPassword" class="sr-only">确认密码</label>-->
        <h4>确认密码：</h4>
	<input type="password" name="confirm" class="form-control" placeholder="Password" required>

        <button class="btn btn-lg btn-primary btn-block" type="Submit" name="Submit" value="注册">注册</button>
	<br>

      </form>

    </div> <!-- /container -->


  </body>
</html>

