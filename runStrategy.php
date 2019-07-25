<?php
    if(isset($_POST['session']) && !empty($_POST['session'])){
        $sessionId = $_POST['session'];
        $command = "/tmp/micromouse/php_root run ".$sessionId;
        exec($command, $output, $return_var);
        if ($return_var == 0) {
            echo "Running";
        } else {
            echo "Error ".$return_var;
        }
    }
?>