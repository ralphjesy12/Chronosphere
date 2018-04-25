<?php

if (! function_exists('osProcessIsRunning')) {
    function osProcessIsRunning($needle)
    {

        exec('ps aux -ww', $process_status);
        print_r($process_status);
        
        $result = array_filter($process_status,function($var) use ($needle){
            return strpos($var, $needle);
        });

        if (!empty($result)) {
            return true;
        }

        return false;
    }
}
