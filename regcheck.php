
 <?php
	header("Content-Type:text/html;charset=utf-8");
        function checkUser($user){
          //if(preg_match('/^[0-9a-zA-Z".chr(0xa1)."-".chr(0xff)."]+$/',$user))//GB2312 汉字字母数字正则表达式
          if(!preg_match('/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]+$/u',$user))//UTF-8 汉字字母数字正则表达式
            echo"<script>alert('含有违法字符'); history.go(-1);</script>";
          else {
            return true;
          }

        }

        function checkPassword($psw){
          if(strlen($psw)>36){
            echo"<script>alert('密码长度必须小于36个字符'); history.go(-1);</script>";
          }
          else {
	         //flag用来标记密码是否为弱口令,flag＝１表明为弱口令
           //存在于弱口令字典中或是打分不超过三分均属于弱口令
           $flag=0;
           $passwd=file("password.txt");//打开弱密码文件
           //遍历
           for($i=0;$i<count($passwd);$i++)
           {
              $pass=$passwd[$i];//取出每一个文件
              if($pass==$psw){
	              $flag=1;
		            break;
	            }
            }

             //打分制
            $score = 0;
            if(preg_match("/[0-9]+/",$psw))
             $score ++;
            if(preg_match("/[0-9]{3,}/",$psw))
             $score ++;
            if(preg_match("/[a-z]+/",$psw))
             $score ++;
            if(preg_match("/[a-z]{3,}/",$psw))
             $score ++;
            if(preg_match("/[A-Z]+/",$psw))
             $score ++;
            if(preg_match("/[A-Z]{3,}/",$psw))
             $score ++;
            if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/",$psw))
             $score += 2;
            if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]{3,}/",$psw))
             $score ++;
            if(strlen($psw) >= 10)
             $score ++;
            if($score>=0&&$score<=3)
              $flag=1;

            if($flag==1)
              echo"<script>alert('弱密码！'); history.go(-1);</script>";
            if(!$flag)
              return true;
          }
        }

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
              if(checkUser($user)&&checkPassword($psw))
              {

                  if($psw == $psw_confirm)
                  {

  		            $mysqli = new mysqli("localhost", "root", $_SERVER['MYSQL_PSW'],"login");
			mysqli_query("set names 'utf8'");

      		            if(!$mysqli)  {
                          echo"数据库连接失败";
      	   	          }
                      else{
         		              echo"数据库连接成功";
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
  			                   //Message to be hashed.password.
                           $data = $_POST["password"];
                           $enpsw= password_hash ($data, PASSWORD_DEFAULT);

                           $sql_insert =
                          "insert into users (uname,upasswd) values('$_POST[username]','$enpsw')";

                          $res_insert = $mysqli->query($sql_insert);
                          $num_insert = mysqli_num_rows($res_insert);

  			                  if($res_insert)
                          {
                            //提示注册成功并返回至登录界面
                             echo "<script>alert('注册成功！');window.location.href='https://rachelaria.com/login.php'</script>";
                            
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
        }
        else
        {
            echo "<script>alert('提交未成功！'); history.go(-1);</script>";
        }

    ?>
