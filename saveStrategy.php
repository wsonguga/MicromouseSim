<?php
    if(isset($_POST['maze']) && !empty($_POST['maze'])){
        $mazeName = $_POST['maze'];
        copy("mazes_text/".$mazeName.".maze", "/tmp/micromouse/maze.txt");
    } else {
        die("Unable to copy maze file.");
    }
    
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
            copy("framework/controller_core.py", "/tmp/micromouse/controller_core.py");
            copy("framework/demo_core.py", "/tmp/micromouse/demo_core.py");
            copy("framework/map.py", "/tmp/micromouse/map.py");
            copy("framework/mouse.py", "/tmp/micromouse/mouse.py");
            copy("framework/network.py", "/tmp/micromouse/network.py");
            copy("framework/strategy.py", "/tmp/micromouse/strategy.py");
            copy("framework/controller.py", "/tmp/micromouse/controller.py");
            copy("framework/maze.imn", "/tmp/micromouse/maze.imn");
            $command = "/tmp/micromouse/php_root save";
            exec($command, $output, $return_var);
            if ($return_var == 0) {
                $myfile = fopen("/tmp/micromouse/sessionId.txt", "r") or die("Unable to open file!");
                $sessionId = fgets($myfile);
                if (! empty($sessionId)) {
                    echo $sessionId;
                } else {
                    echo "No SessionId";
                }
            } else {
                echo "Error ".$return_var;
            }
        } else {
            die("Unable to create /tmp/micromouse/.");
        }
    } else {
        die("Unable to read your strategy.");
    }
    
?>