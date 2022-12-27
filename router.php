<?php

/* $matches=[];
if(preg_match('/\/([^\/]+)\/([^\/]+)/',$_SERVER["REQUEST_URI"],$matches))
{
    $_GET['resource_type']=$matches[1];    
    $_GET['resource_id']=$matches[2];
    error_log(print_r($matches,1));
    require 'server.php';
}else if(preg_match('/\/([^\/]+)\/?/',$_SERVER["REQUEST_URI"],$matches))
{
    $_GET['resource_type']=$matches[1];        
    error_log(print_r($matches,1));
    require 'server.php';
}else
{
    error_log('No matches');
    http_response_code(404);

} */

$libros=[4=>'hola',9=>'hola2',10=>'vamos'];
echo 'hola'."\n";

echo array_keys($libros)[count($libros)-1];
//echo count($d)-1 ."\n";
//print_r(array_keys($libros)) ;


?>