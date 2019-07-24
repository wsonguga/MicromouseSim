<?php
	if(isset($_POST['session']) && !empty($_POST['session'])){
        $sessionId = $_POST['session'];
		$command = "core-gui --closebatch ".$sessionId.">/dev/null 2>&1 &";
    	exec($command, $output, $return_var);
    	if ($return_var == 0) {
    		echo "Stop";
    	} else {
    		echo $return_var;
    	}
	}
?>