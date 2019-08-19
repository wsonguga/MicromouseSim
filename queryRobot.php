<?php
	ini_set('display_errors', 1);

	include "dbcon.php";

	$result = "";
	if(isset($_GET['session_id']) && !empty($_GET['session_id'])){
		$conn = getDBConnection();
		$session_id = $_GET['session_id'];
		for ($robot_id = 1; $robot_id <= 4; ++$robot_id) {
			$sql = "SELECT * from robots WHERE session_id = ".$session_id." AND robot_id = " . $robot_id . ";";
			$temp = $conn->query($sql);

			if ($temp->num_rows > 0) {
			    // output data of each row
			    while($row = $temp->fetch_assoc()) {
			        $result = $result . $row["robot_x"] . "," . $row["robot_y"] . "," . $row['direction'] . ",";
			    }
			}
		}
	}
	
	echo $result;
	$conn->close();
?>