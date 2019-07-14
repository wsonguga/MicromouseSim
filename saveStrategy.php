<?php
    if(isset($_POST['strategy']) && !empty($_POST['strategy'])){
        $content = $_POST['strategy'];
        if (! file_exists('/tmp/micromouse/')) {
        	mkdir('/tmp/micromouse/');
        }
        if (file_exists('/tmp/micromouse/')) {
        	$file = "/tmp/micromouse/strategy_test.py";
			$Saved_File = fopen($file, 'w');
			fwrite($Saved_File, $content);
			fclose($Saved_File);
        }
		
    }
    $command = escapeshellcmd("/usr/local/bin/python3 write_db.py");
	exec($command, $output, $return_var);
?>