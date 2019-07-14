<?php
	$db_hostname = '127.0.0.1';
	$db_database = 'test_db';
	$db_username = 'root';
	$db_password = 'luoeniac43';
	$robot_id = 1;

	// Create connection
	$conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT * from test_table WHERE robot_id = " . $robot_id . ";";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo $row["robot_x"]. "," . $row["robot_y"];
	    }
	}
	$conn->close();
?>