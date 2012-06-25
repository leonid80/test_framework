<?php

function debug($msg)
{
    global $config;
    // Use call_user_func_array() to save caller context.
    if($config['debug']['hacker_console'])
        call_user_func(array('Debug_HackerConsole_Main', 'out'), $msg);
}

/*
* Checks whether a file "config.ini" exists and if it exists and forms an array $config
* @param $config_file: name of config file
*/
function getConfig($config_file)
{
    if(!file_exists($config_file))
    {
        die("Could't open the required config file.");
    }
    else
    {
        return parse_ini_file($config_file, true);
    }
}
