    <?php
        if(isset($_POST["Submit"]) && $_POST["Submit"] == "注册")
        {
            $user = $_POST["username"];
            $psw = $_POST["password"];
            $psw_confirm = $_POST["confirm"];
            if($user == "" || $psw == "" || $psw_confirm == "")
            {
                echo "<script>alert('请确认信息完整性！'); history.go(-1);</script>";
            }
            else
            {
                if($psw == $psw_confirm)
                {

		                $mysqli = new mysqli("localhost", "root", "songyawen","login");
    		              if(!$mysqli)  {
                        echo"database error";
    	   	             }
                       else{
       		                echo"php env successful";
  		                    }
		                $sql = "select uname from users where uname = '$_POST[username]'"; //SQL语句
                    $result = $mysqli->query($sql);    //执行SQL语句
                    $num = mysqli_num_rows($result); 
                    //统计执行结果影响的行数
                    
		     
		    if($num)    //如果已经存在该用户
                    {
                        echo "<script>alert('用户名已存在'); history.go(-1);</script>";
                    }
                    else    //不存在当前注册用户名称
                    {
			//插入的是加密数据
			
                        $sql_insert =
                        "insert into users (uname,upasswd) values('$_POST[username]','$_POST[password]')";

                        $res_insert = $mysqli->query($sql_insert);
                        $num_insert = mysqli_num_rows($res_insert);

			if($res_insert)
                        {
                            echo "<script>alert('注册成功！'); history.go(-1);</script>";
                        }
                        else
                        {
                            echo "<script>alert('系统繁忙，请稍候！'); history.go(-1);</script>";
                        }
                    }
                }
                else
                {
                    echo "<script>alert('密码不一致！'); history.go(-1);</script>";
                }
            }
        }
        else
        {
            echo "<script>alert('提交未成功！'); history.go(-1);</script>";
        }

    ?>
