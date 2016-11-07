 <!DOCTYPE html>
 <html>
 <head>
 	<title>GPA</title>
 </head>
 <body>
 	<form action="" method="post" enctype="multipart/form-data">
 	<label> Subject : 
 		<input type="text" name="subject">		
 	</label>
 	<label> Batch : 
 		<input type="text" name="batch">		
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
					<th>Index number</th>
					<th>Result</th>
				</tr>
			
<?php foreach ($jsondata as $row) { ?>
				<tr>
					<td>
						<?php echo $row['Index_num']; ?>
					</td>
					<td>
						<?php echo $row['Programming and Problem solving (IS 1001)']; ?>		
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
		$tableName = mysqli_escape_string($conn,$_POST['subject']);
		$sql_to_table = "CREATE TABLE IF NOT EXISTS $tableName (
				    batch INT (6) NOT NULL,
				    index_num VARCHAR (10) NOT NULL,
				    result VARCHAR(5) NOT NULL,
				    cpv FLOAT NOT NULL,
				    gpv FLOAT NOT NULL,
				    PRIMARY KEY (batch,index_num)
				)";

		$response = mysqli_query($conn,$sql_to_table);

		if ($response) {
	
			$sql_to_get_credits = "SELECT credit FROM subject WHERE subject_code='$tableName'";
			$q_credit = mysqli_query($conn,$sql_to_get_credits);
			$res_credit = mysqli_fetch_assoc($q_credit);
			$credit = $res_credit['credit'];

			# code...
			$sql_pre = "INSERT INTO $tableName(batch,index_num,result,cpv,gpv) VALUES";
			$stringlist = array();
			//$stringlist[] = "('sdfs','asd','asd')";
			foreach ($jsondata as $row) {
				# code...
				$cpv = 0;
				$individual_index = $row['Index_num'];
				$individual_res = $row['Programming and Problem solving (IS 1001)']; 
				switch ($individual_res) {
					case 'A+':
						$cpv = 4;
						break;
					case 'A':
						$cpv = 4;
						break;
					case 'A-':
						$cpv = 3.75;
						break;
					case 'B+':
						$cpv = 3.25;
						break;
					case 'B':
						$cpv = 3;
						break;
					case 'B-':
						$cpv = 2.75;
						break;
					case 'C+':
						$cpv = 2.25;
						break;
					case 'C':
						$cpv = 2;
						break;
					case 'C-':
						$cpv = 1.75;
						break;
					case 'D+':
						$cpv = 1.25;
						break;
					case 'D':
						$cpv = 1;
						break;
					case 'D-':
						$cpv = 0.75;
						break;
					case 'E':
						$cpv = 0;
						break;
					default:
						$cpv = 0;
						break;
				}

				$gpv = $cpv * $credit;
				$stringlist[] = "('$batch','$individual_index','$individual_res','$cpv','$gpv')";

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
				}else{
					echo "Error! , Contact administration";
				}

			}

		}

	}
?>

 </body>
 </html>