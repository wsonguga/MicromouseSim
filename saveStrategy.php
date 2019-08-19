<?php
    include "dbcon.php";

    function checkUserExist($userId) {
        $conn = getDBConnection();
        $temp = $conn->query("SELECT userId from sessions WHERE userId = \"".$userId."\";");

        if ($temp->num_rows > 0) {
            $conn->close();
            return true;
        } else {
            $conn->close();
            return false;
        }
        
    }

    function addUser($userId) {
        $conn = getDBConnection();
        if (! $conn->query("INSERT INTO sessions(userId) VALUES(\"".$userId."\");")) {
            die("Unable to add new user ".$userId.".");
        }
        $temp = $conn->query("SELECT max(sessionId) sid FROM sessions;");
        if ($temp->num_rows > 0) { 
            $row = $temp->fetch_assoc();
            $sessionId = $row["sid"] + 1;
        }
        if (! $conn->query("UPDATE sessions SET sessionId = ".$sessionId." WHERE userId = \"".$userId."\";")) {
            die("Unable to create new sessionId for ".$userId.".");
        }
        $temp = $conn->query("SELECT sessionId FROM sessions WHERE userId = \"".$userId."\";");
        if ($temp->num_rows > 0) { 
            $row = $temp->fetch_assoc();
            $sessionId = $row["sessionId"];
        } else {
            die("Unable to find the sessionId for ".$userId.".");
        }
        if (! $conn->query("INSERT INTO robots(session_id,robot_id) VALUES(".$sessionId.",1);")) 
            die("Unable to add robots initial coordinates for ".$userId.".");
        if (! $conn->query("INSERT INTO robots(session_id,robot_id) VALUES(".$sessionId.",2);")) 
            die("Unable to add robots initial coordinates for ".$userId.".");
        if (! $conn->query("INSERT INTO robots(session_id,robot_id) VALUES(".$sessionId.",3);")) 
            die("Unable to add robots initial coordinates for ".$userId.".");
        if (! $conn->query("INSERT INTO robots(session_id,robot_id) VALUES(".$sessionId.",4);")) 
            die("Unable to add robots initial coordinates for ".$userId.".");
        $conn->close();
    }

    function checkSessionRunning($userId) {
        $conn = getDBConnection();
        $temp = $conn->query("SELECT status FROM sessions WHERE userId = \"".$userId."\";");
        if ($temp->num_rows > 0) { 
            $row = $temp->fetch_assoc();
            $status = $row["status"];
            if (strcmp($status, "alive") == 0) {
                return true;
                $conn->close();
            } else {
                return false;
                $conn->close();
            }
        } else {
            die("Unable to find the status of session for ".$userId.".");
        }
    }

    // Copy the code to the user's temp folder marked by daemon port.
    function prepareSession($userId) {
        $conn = getDBConnection();
        $temp = $conn->query("SELECT sessionId FROM sessions WHERE userId = \"".$userId."\";");
        if ($temp->num_rows > 0) { 
            $row = $temp->fetch_assoc();
            $sessionId = $row["sessionId"];
        } else {
            die("User not exist.");
        }
        $conn->close();

        $rootPath = '/tmp/micromouse/'.$sessionId.'/';
        if (file_exists('/tmp/micromouse/')) {
            if (! file_exists($rootPath)) {
                if(! mkdir($rootPath))
                    die("Unable to create a folder ".$rootPath);
            } 
        } else {
            die("Root path /tmp/micromouse/ does not exist. Contact instructor to run prepare.sh.");
        }

        if(isset($_POST['maze']) && !empty($_POST['maze'])){
            $mazeName = $_POST['maze'];
            copy("mazes_text/".$mazeName.".maze", $rootPath."maze.txt");
        } else {
            die("Unable to copy maze file.");
        }
        
        if(isset($_POST['strategy']) && !empty($_POST['strategy'])){
            $content = $_POST['strategy'];
            $file = $rootPath."strategy_test.py";
            $Saved_File = fopen($file, 'w');
            fwrite($Saved_File, $content);
            fclose($Saved_File);
            copy("framework/controller_core.py", $rootPath."controller_core.py");
            copy("framework/demo_core.py", $rootPath."demo_core.py");
            copy("framework/map.py", $rootPath."map.py");
            copy("framework/mouse.py", $rootPath."mouse.py");
            copy("framework/network.py", $rootPath."network.py");
            copy("framework/strategy.py", $rootPath."strategy.py");
            copy("framework/controller.py", $rootPath."controller.py");
            $command = "/tmp/micromouse/php_root session ".$sessionId;
            exec($command, $output, $return_var);
            echo $sessionId;
        } else {
            die("Unable to read your strategy.");
        }
    }

    if(isset($_POST['email'])) {
        $userId = $_POST['email'];
    } else {
        die("Unable to read user's information.");
    }

    if (! checkUserExist($userId)) {
        addUser($userId);
    } 

    if (! checkSessionRunning($userId)) {
        prepareSession($userId);
    } else {
        echo "Session is not shutdown properly last time.";
    }



?>
