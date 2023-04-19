<?php 
$fileName = "testCronFile.txt";
touch($fileName);
$filePen = fopen($fileName,'w+');
fwrite($filePen,"Sample Text \n");
fclose($filePen);
?>