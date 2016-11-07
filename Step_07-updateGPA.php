<!DOCTYPE html>
<html>
<head>
	<title>Generate GPA</title>
</head>
<body>

<?php 

	$conn = mysqli_connect('localhost','root','mysqler','gpa');

	if (!$conn) {
		# code...
		echo "Databse not connect";
	}

 ?>

<form action="" method="POST">

	<label>
		SELECT Course and batch :
	</label>
	<select name="course_batch" id='course_batch'>
		<option>-- SELECT --</option>
<?php 
	$r = mysqli_query($conn,"SELECT tablename FROM tablelist");
	
	while ($row = mysqli_fetch_assoc($r)) {
		$val = $row['tablename'];
		$val_a = explode("_", $val);
		$year = $val_a[0];
		$co = $val_a[1];
?>

		<option value=<?php echo $val?> > <?php echo $year." - ".$co ?> </option>

<?php		
	}
?>
	</select>

	<input type="submit" name="submit" value="Update">
</form>

<?php 

	if(isset($_POST['submit'])){
		$course_batch = mysqli_escape_string($conn,$_POST['course_batch']);

		//SQL to get subjects 
		$qry = mysqli_query($conn,"SELECT * FROM $course_batch LIMIT 2");
		if($qry){
			$list = arraY();
			while($col_name = mysqli_fetch_field($qry)){
				$name = $col_name->name;
				if($name != 'reg_num' AND $name != 'index_num' AND $name != 'GPA' AND $name != 'rank'){
					$list[] = $name;
				}
			}

			//Get total credits
			$sql_to_get_total = "SELECT sum_of_credit FROM tablelist WHERE tablename = '$course_batch'";
			$q = mysqli_query($conn,$sql_to_get_total);
			$res = mysqli_fetch_assoc($q);

			$sql_to_update = "UPDATE $course_batch SET GPA = ROUND((";
			$sql_to_update .= implode("+", $list);
			$sql_to_update .= ")/".$res['sum_of_credit'].",4)";
			
			if(mysqli_query($conn, $sql_to_update)){
				echo "GPA Calculated";

				$sql_to_ranking = "UPDATE $course_batch JOIN 
								  (SELECT index_num, GPA, 
								 	(SELECT COUNT(*)+1 FROM $course_batch WHERE GPA>x.GPA) AS rank_upper 
							 	   FROM $course_batch x) a 
							 	   ON (a.index_num = $course_batch.index_num) 
							 	   SET $course_batch.rank = a.rank_upper";

				$res_rank = mysqli_query($conn,$sql_to_ranking);
				if($res_rank){
					echo " Ranking done";
				}else{
					echo mysqli_error($conn);
				}

			}else{
				echo mysqli_error($conn);
			}
			
		}


	}

 ?>



</body>
</html>