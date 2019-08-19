<?php
    ini_set('display_errors', 1);
    if(isset($_POST['session']) && !empty($_POST['session'])){
        $sessionId = substr(file_get_contents('/tmp/micromouse/sessionId.txt'), 0, -2);
        $nodeId = intval($_POST['session']) * 5 - 4;
        $logStr = file_get_contents('/tmp/pycore.'.$sessionId.'/n'.$nodeId.'.log');
        $logArray = explode("\n", $logStr);
        for ($i = count($logArray)-1; $i >= 0; --$i) {
            if (strpos($logArray[$i], "Traceback") !== FALSE)
                break;
        }
        if ($i !== -1) {
            echo "<pre>";
            $posBegin = $i;
            echo "<b>Last Error occurred at ".explode(" ", $logArray[$i-1])[1]."</b><br><br>";
            for ( ; $i < count($logArray); ++$i) {
                if (strpos($logArray[$i], "vnode") !== FALSE) {
                    $posEnd = $i;
                    break;
                }
                echo $logArray[$i]."<br>";
            }
            echo "</pre>";
        }
    }
?>