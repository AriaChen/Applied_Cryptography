<!DCOTYPE html>
<html>
<head>
	<title>搜索</title>
</head>
<body>
</br>
</br>

<form action="search.php" method="post" enctype="multipart/form-data">
搜索：<input name="search" type="text" /><br/>

<input type="submit" value="搜索" />

</br>

</form>

</body>
</html>


<?php

    $search = $_POST["search"];

    if(empty($search)){
	echo "Must type a word to search.";
    }else{
	    //数据库操作
	    session_start ();
	    $mysqli = new mysqli("localhost", "root", $_SERVER['MYSQL_PSW'],"login");
	    if(!$mysqli)  echo "数据库连接失败";
	    else {
		$sql_select = "SELECT fileName, hashFile, ext FROM file WHERE fileName LIKE '%$search%'";
		//echo $sql_select;
		$res_select = $mysqli->query($sql_select);
		if(!$res_select) echo "搜索失败";
		if($num = mysqli_num_rows($res_select) > 0){
			$field_info_arr = $res_select->fetch_fields();
			
			while($row = $res_select->fetch_assoc())
                	{
				$hash = $row['hashFile'];$ext = $row['ext'];

				echo "<a href='/upload/$hash";echo ".";echo $ext;echo "'>";

				echo $row['fileName'];
				echo "</a>";
				echo "</br>";
              		}
		}else{			
			echo "No match found.";
		}
	    }
    }

?>
