<!DOCTYPE html>
<html>
<head>
	<title>Add GPA</title>
</head>
<body>

	<form action="" method="post" enctype="multipart/form-data">
 	<label> Batch Year : 
 		<input type="text" name="batch">		
 	</label>
 	<label> Cource : 
 		<input type="text" name="course">		
 	</label>
 	<input type="file" name="readFile">
 	<input type="submit" name="submit" value="Upload">

 	</form>


<?php 

	if (isset($_POST['submit'])) {
		# code...
		if ($_FILES['readFile']['size']) {
			# code...
			$files = $_FILES['readFile']['tmp_name'];
			$jsonf = file_get_contents($files);
			$jsondata = (array)json_decode($jsonf,true);
			
?>

			<table >
				<tr>
					<th>Registration Number</th>
					<th>Index Number</th>
				</tr>
			
<?php foreach ($jsondata as $row) { ?>
				<tr>
					<td>
						<?php echo $row['Reg_num']; ?>
					</td>
					<td>
						<?php echo $row['Index_num']; ?>		
					</td>
				
				</tr>
<?php }?>
				
			</table>

<?php		
		
		$conn = mysqli_connect('localhost','root','mysqler','gpa');

		if(!$conn){
			echo "server not connected";
		}

		# code...
		$batch = mysqli_escape_string($conn,$_POST['batch']);
		$course = mysqli_escape_string($conn,$_POST['course']);
		$tableName = $batch."_".$course;
		$sql_to_table = "CREATE TABLE IF NOT EXISTS $tableName (
				    reg_num VARCHAR(10) NOT NULL,
				    index_num VARCHAR(10) NOT NULL,
				    GPA FLOAT(10) NOT NULL DEFAULT '0.00',
				    rank int(10) NOT NULL DEFAULT 0,
				    PRIMARY KEY (reg_num,index_num)
				)";
		mysqli_query($conn,"CREATE TABLE IF NOT EXISTS tablelist(tablename VARCHAR(10) NOT NULL PRIMARY KEY,sum_of_credit INT(10) NOT NULL DEFAULT 0)");
		mysqli_query($conn,"INSERT INTO tablelist VALUES('$tableName','0')");
		$response = mysqli_query($conn,$sql_to_table);

		if ($response) {

			# code...
			$sql_pre = "INSERT INTO $tableName(reg_num,index_num) VALUES";
			$stringlist = array();
			foreach ($jsondata as $row) {
				# code...
				$individual_reg = $row['Reg_num']; 
				$individual_index = $row['Index_num'];
				$stringlist[] = "('$individual_reg','$individual_index')";

			}

			$sql_pre .= implode(",", $stringlist);
			
		}

		$final_res = mysqli_query($conn,$sql_pre);
			if ($final_res) {
				# code...
				echo "data dammoooo";

			}else{
				if(mysqli_errno($conn) == 1062){
					echo "You already have entered these data.";
				}elseif (mysqli_errno($conn) == 1064) {
					echo "Batch name should not be number";
				}else{
					echo "Error! , Contact administration";
				}
			}

		}

	}
?>


</body>
</html>