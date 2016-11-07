<!DOCTYPE html>
<html>
<head>
	<title>Generate GPA</title>
	<script
			  src="https://code.jquery.com/jquery-3.1.1.min.js"
			  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
			  crossorigin="anonymous">
  	</script>
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

	<div id="checkBox">
		<!-- Subjects are list down in here -->
	</div>

	<input type="submit" name="submit" value="Generate">
</form>

<?php 

	if(isset($_POST['submit'])){
		$course_batch = mysqli_escape_string($conn,$_POST['course_batch']);

		$subjects = $_POST['subjects'];
		foreach ($subjects as $subject) {
			
			$sql_to_alter_gpa_table = "ALTER TABLE $course_batch ADD $subject FLOAT(10)";
			$qu = mysqli_query($conn,$sql_to_alter_gpa_table);
			if($qu){
				echo "New column added ";

				$sql_to_insert_gpv_val =  "	UPDATE $course_batch
						    INNER join
					         $subject
					         on $course_batch.index_num = $subject.index_num
						    set $subject = $subject.gpv
						         ";

				$res = mysqli_query($conn,$sql_to_insert_gpv_val);
				if($res){
					echo "data passed";
					$cr = mysqli_fetch_assoc(mysqli_query($conn,"SELECT credit FROM subject WHERE subject_code = '$subject'"));
					$final_q = mysqli_query($conn,"UPDATE tablelist SET sum_of_credit = sum_of_credit +".$cr['credit']." WHERE tablename = '$course_batch'");
					if ($final_q) {
						echo " tablelist updated";
					}else{
						echo mysqli_error($conn);
					}
				}else{
					echo mysqli_error($conn);
				}

			}elseif(mysqli_errno($conn) == 1060){
				echo "Subject $subject is already added to GPA calculation.";
			}else{
				echo mysqli_errno($conn);
			}

				
		}

		


	}

 ?>



</body>
<script >
	
		$('#course_batch').change(function(){
			var valw = this.value;
			
			$.ajax({
				url:"getMethods.php",
				type:'POST',
				data:{'sub':valw},
				success:function(res){
					
					$('#checkBox').html(res);
					
				}
			});

		});


</script>

</html>