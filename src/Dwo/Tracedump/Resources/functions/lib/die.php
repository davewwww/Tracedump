<?php

function tds()
{
    $args = func_get_args();
    syslog(LOG_ALERT, json_encode($args));
}

if (isset($_SERVER["SHELL"]) OR isset($_SERVER["HTTP_BEHAT"]))
    include("die_console.php");
else
    include("die_html.php");
