    <?php
        session_start();
        //使用cookie传值到文件上传页面
      //  setcookie('mycookie','自灵');
        if(isset($_POST["submit"]) && $_POST["submit"] == "登陆")
        {
            $user = $_POST["username"];
            $psw = $_POST["password"];
            if($user == "" || $psw == "")
            {
                echo "<script>alert('请输入用户名或密码！'); history.go(-1);</script>";
            }

            else
            {

                $mysqli = new mysqli("localhost", "root",$_SERVER['MYSQL_PSW'], "login");
                // mysql_query("set names 'gbk'");

		            //找出对应用户名的加密后的密码
                $sql = "select upasswd from users where uname = '$_POST[username]'";

                $result = $mysqli->query($sql);

		$field_info_arr = $result->fetch_fields();
		while($row = $result->fetch_assoc())
		{
			$hash=$row['upasswd'];
			$res=password_verify($psw,$hash);

			//echo $res;
		}

              //  $num = mysqli_num_rows($result);


                if($res)
                {

    			  echo "welcome!";
            echo $user;
            //创建session变量标志登录成功
            $_SESSION['islogin'] = $user;
            echo $_SESSION['islogin'];
            //跳转至文件上传及文件列表页面
            Header("Location: index.php");
                }
                else
                {
                    echo "<script>alert('用户名或密码不正确！');history.go(-1);</script>";
                }
            }
        }
        else
        {
            echo "<script>alert('提交未成功！'); history.go(-1);</script>";
        }

    ?>
