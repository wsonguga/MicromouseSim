<?php
    include "dbcon.php";

	if(isset($_POST['session']) && !empty($_POST['session'])){
        $sessionId = substr(file_get_contents('/tmp/micromouse/sessionId.txt'), 0, -2);
        $command = "/tmp/micromouse/php_root stop ".$_POST['session']." ".$sessionId;
        exec($command, $output, $return_var);
    	if ($return_var == 0) {
            $conn = getDBConnection();
            if (! $conn->query("UPDATE sessions SET status = 'dead' WHERE sessionId = \"".$_POST['session']."\";")) {
                die("Unable to change session status to active for session ".$_POST['session'].".");
            }
            $conn->close();
    		echo "Stopped";
    	} else {
    		echo "Error ".$return_var;
    	}
	}
?>