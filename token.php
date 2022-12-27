<?php

// SERVIDOR DE AUTENTICACION

// El servidor crea un JSON WEB TOKEN 
$method = strtoupper($_SERVER['REQUEST_METHOD']);

// eSTE TOKEN DEBE SER ALMACENADO EN UNA BASE DATOS Y CON CADUCIDAD EN EL TIEMPO  
$token = sha1('Esto es secreto!!!'); // Token generado con funcion de encriptamiento sh1 mediante un codigo secreto

if($method === 'POST'){ // Para obtenener token
    // Verificar existencia de los headers
    if(!array_key_exists('HTTP_X_CLIENT_ID', $_SERVER) || !array_key_exists('HTTP_X_SECRET', $_SERVER)){
        http_response_code(400);
        die('Faltan parametros');
    }

    // Tomar los headers
    $clientId = $_SERVER['HTTP_X_CLIENT_ID'];
    $secret = $_SERVER['HTTP_X_SECRET'];

    // Verificar credenciales
    if($clientId !== '1' || $secret !== 'SuperSecreto!'){
        http_response_code(403); // Forbidden
        die('No autorizado');
    }

    echo "$token"; // Si las credenciales son validas devuelve el token 
} elseif ($method === 'GET'){ // Para validad token
    // Verificar existencia de token
    if(!array_key_exists('HTTP_X_TOKEN', $_SERVER)){
        http_response_code(400); // Bad Request
        die('Faltan patrametros');
    }
    
    if($_SERVER['HTTP_X_TOKEN'] == $token){
        echo 'true';
    } else {
        echo 'false';
    }
} else {
    echo 'false';    
}

?>