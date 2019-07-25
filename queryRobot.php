<?php
	ini_set('display_errors', 1);

	$db_hostname = '127.0.0.1';
	$db_database = 'test_db';
	$db_username = 'root';
	$db_password = 'luoeniac43';

	// Create connection
	$conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$result = "";
	for ($robot_id = 1; $robot_id <= 4; ++$robot_id) {
		$sql = "SELECT * from test_table WHERE robot_id = " . $robot_id . ";";
		$temp = $conn->query($sql);

		if ($temp->num_rows > 0) {
		    // output data of each row
		    while($row = $temp->fetch_assoc()) {
		        $result = $result . $row["robot_x"] . "," . $row["robot_y"] . "," . $row['direction'] . ",";
		    }
		}
	}
	
	echo $result;
	$conn->close();
?>