<?php 

	$conn = mysqli_connect('localhost','root','mysqler','gpa');

	if(!$conn){
		echo "server not connected";
	}

	if (isset($_POST['submit'])) {
		# code...
		$title = mysqli_escape_string($conn,$_POST['title']);
		$course = mysqli_escape_string($conn,$_POST['course']);
		$code = mysqli_escape_string($conn,$_POST['code']);
		$credit = mysqli_escape_string($conn,$_POST['credit']);

		$ccode = $course.$code;

		mysqli_query($conn,"CREATE TABLE IF NOT EXISTS subject(
									subject_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
									title VARCHAR(250) NOT NULL ,
									subject_code VARCHAR(10) NOT NULL,
									course VARCHAR(10) NOT NULL,
									credit INT(11) NOT NULL
									)");

		$sql = "INSERT INTO subject(title,subject_code,course,credit) VALUES ('$title','$ccode','$course','$credit')";
		
		$res = mysqli_query($conn ,$sql);
		if ($res) {
			echo 'wade goda';
		}else{
			echo 'monwada bn me..';
			echo mysqli_error($conn);
		}
	}
 ?>

<!DOCTYPE html>
<html>
<head>	
	<title>Add subject</title>
</head>
<body>

	<form action="" method="POST">
		<label>Subject :
			<input type="text" name="title"> 
		</label><br>
		<label> Select course :</label>
			<select name="course">
				<option value="IS">IS</option>
				<option value="CS">CS</option>
			</select>
			<br>
		<label>Course Code:
		<input type="text" name="code">
		</label>
		<br>
		<label>Credits:
			<input type="number" name="credit" min="1" max="4">
		</label>

		<br>
		<input type="submit" name="submit">

	</form>

</body>
</html>