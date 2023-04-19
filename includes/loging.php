<?php
$logFileName = 'whatsup.log';
function addLog( $fileName, $message )
{
    error_log( "\n" . date('Y-m-d H:i:s ') . $message,3 ,$fileName);
}