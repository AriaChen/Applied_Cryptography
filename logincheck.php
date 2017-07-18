    <?php  
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
                $mysqli = new mysqli("localhost", "root", "songyawen", 				"login");  
               // mysql_query("set names 'gbk'");  
		//加密比对
		
                $sql = "select uname,upasswd from users where uname = '$_POST[username]' and upasswd = '$_POST[password]'";  
                $result = $mysqli->query($sql);  
                $num = mysqli_num_rows($result);  
                if($num)  
                {  
                    

		     $field_info_arr = $result->fetch_fields();
			//获取数据
			while($row = $result->fetch_assoc()){
    			echo "welcome!";
			echo $row['uname'];
			}
		    
		    
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
