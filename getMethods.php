<?php 


	
		$conn = mysqli_connect('localhost','root','mysqler','gpa');

		if(!$conn){
			echo "server not connected";
		}

		$get = mysqli_escape_string($conn,$_POST['sub']);
		$mod = explode("_", $get);
		$course = $mod[1];
		$batch = $mod[0];
		$sql = "SELECT subject_code,title FROM subject WHERE course='$course'";
		$query = mysqli_query($conn,$sql);

		while ($row = mysqli_fetch_assoc($query)) {
			
			echo "<input type='checkbox' name='subjects[]' value=".$row['subject_code'].">".$row['subject_code']." - ".$row['title']."<br>";

		}

 ?>