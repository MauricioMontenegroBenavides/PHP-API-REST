<?php

// AUTENTICACION MEDIANTES HTTP//////////
/* 
// obtener el usuario y las password q viene en los encabezados http
$user= array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '';// Este es el modo en el q codifica PHP
$pwd= array_key_exists('PHP_AUTH_PW', $_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';
// user:password@URL
// http://mau:123@localhost:8000?tipos_data=libros // aqui se envia el usuario y la contraseña
// El problema de la autenticacion http es poca segura porque viaja por la URL
// Otro problema es q en cada pedido se debe volver a realizar la autenticacion es valida 
//
if ($user!='mau'&& $pwd!='123'){

die();
} */



// AUTENTICACION MEDIANTES HMAC//

// Un hash() es una funcion q transforma el texto en algo bastante dificil de leer 

// Se compone de tres pasos:
// 1)-.Una funcion de hash es dificil de leer (conocida por el clinete y por el servidor)
// 2)-.Palabra Secreta (Solo por el cliente o el servidor)
// 3)-.Datos q viajan en forma publica

// El cliente con la funcion de hash y el secreto, lo cancatena y lo envia al servidor, entonces el servidor toma el hash y la funcion publica y vuelve a general el hash, compara el recibido y el generado por le servidor, si coinciden se realiza la autenticacin 

// Encabezados q se denota con letra X para decir q son encabezados no estandar
$hash= array_key_exists('HTTP_X_HASH', $_SERVER); // encabezado hash
$time= array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) ; // encabezado marca de tiempo
$uid= array_key_exists('HTTP_X_UID', $_SERVER); // id de usuario 
 
 if(!$user||!$time||!$uid){// Se verifica si ciertos encabezados esta en el arreglo de encabezados q recibimos 
// Si cualquiera de estos encabezadosp no estan el pedido se termino el tema
   die();
}


list($hash,$uid,$time)=[

$_SERVER['HTTP_X_HASH'],// encabezado hash
$_SERVER['HTTP_X_UID'], // id de usuario 
$_SERVER['HTTP_X_TIMESTAMP'] , // encabezado marca de tiempo

]; // Cpontrucion de php q me permite usar varias variables en una sola instruccion 

$secret='Sh!! No se lo cuentes a nadie!';// calve secreta q solo conoce el servidor y el cliente
$newhash=sha1($hash.$uid.$time.$secre); //sha1() es una funcion de criptografia, concateno las cosas q me paso el usuario mas la clave secreta


if ($newhash!==$hash){// Ahora hay q generar el hash q yo genere con el del usuario
die();

}


// TOKEN//

// Hay dos servidores, uno el q hace la autenticacion , otro el q hace las consultas 
// Cuaundo el clinete se autentica obtine un id y una clave secreta
// Si las credenciales on validas el servidor responde con un token 
// 














// Mucha de la tarea ya  esta hecha por el web server
// La arquitectura res a bajo nivel ya esta hecha 
// TODA LA CO,MIUNICACION SE REALIZa por json, pero como http es protocolo de texto, asi q cualquier formato de texto puede utilizarse
// El servidor res debe ser capaz de responder a los cuatro verbos http(delete, put, get,post)
// Las apis es para conectar dos aplicaciones 
// rest se apoya en http 
// http es un conjunto de reglas de determina las reglas de comunicacion entre dos computadoras 
// Una peticion RES tiene una url + verbo http
// res no es la unica forma de interactuar con aplicaciones 

// Se debe validar para ver si los tipos de recursos estan disponibles

// DEFINIMOS LOS RECURSOS
$tipos=[// tipos de recursos q se pueden interactuar desde afuera 

    'libros',
    'autores',
    'generos'
];
// VALIDAMOS Q EL RECURSO ESTE DISPONIBLE 
//  EL GET es un array

$recursos=$_GET['tipos_data'];
// PARA LEVANTAR EL SERVIDOR 
// php -S localhost:8000 server.php
// ABRIR OTRO CMD
// curl http://localhost:8000?tipos_data=libros -v  // Aqui hacemos la consulta 

if(!in_array($recursos,$tipos)){// valida sin lo q me vino en la url no pertenece al array
die;
}

$libros=[

    1=>['titulo'=>'lo q el viento se llevo',
    'id_autor'=>2,
    'id_genero'=>2,
    ],
    2=>['titulo'=>'La iliada',
    'id_autor'=>2,
    'id_genero'=>2,
    ]
];


header('Content-Type:application/json');// Un aviso q le dice al cliente se le envia la respuesta en formato json
// GENERAMOS EL RECUROS ASUMINEDO QUE EL RECURSO ES CORRECTO
// GENERAMOS LA RSPUESTA EN LA LINEA 60

$resourceId=array_key_exists('resource_id',$_GET) ? $_GET['resource_id'] : '';// asumo q la resource_id viene del GET
// EL resource_id PUEDA Q venga o no venga en la url

switch(strtoupper($_SERVER['REQUEST_METHOD'])){// Aqui se mira q metodo se utiliza con http

    //$_SERVER['REQUEST_METHOD' con este metodo ya puedo saber q metodo se utulizo para la app
    case  'GET':
       //if (empty($var)) determina si una variable esta vacia 
       if(empty($resourceId)){

            //echo json_encode($libros[$resourceId]);
            echo json_encode($libros);
           
        }else{
         
            if(array_key_exists($resourceId,$libros)){
                    echo json_encode($libros[$resourceId]);
            }
        }
       
        breaK;

    case  'POST':

        // ASUMIMOS Q LOS DATOS ESTAN EN FORMATO JSON
        $json=file_get_contents('php://input');// LEE UN ARCHIVO POR COMPLETO Y DEVULEVE SU CONTENIDO 
        // 'php://input' es el post en crudo y no en formulario 
        // Agregamos un nuevo libro y decodificamos lo q recibimos
        // true es para decir q esto se devuelva en foram de arrreglo
        // decodificamos el texto json

        $libros[]=json_decode($json,true);

        // devolver el id q se genero para el nuevo objeto
        //echo array_keys($libros)[count($libros)-1];
        
        echo json_encode($libros);
        breaK;

    case  'PUT':
        // PUT ES UNA COMBINACION DEL METODO GET Y EL METODO POST 
           if(!empty($resourceId) && array_key_exists($resourceId,$libros)){
              
            $json=file_get_contents('php://input');
            $libros[$resourceId]=json_decode($json,true);// reemplaza el recurso
            echo json_encode($libros);
           }
         breaK; 

    case  'DELETE':
         
        // VALIDAMOS QUE EL RECUROS EXISTA Y SI EXISTE SE ELIMINA
        if(!empty($resourceId) && array_key_exists($resourceId,$libros)){

            unset($libros[$resourceId]);
        }
     
        echo json_encode($libros);

        breaK;

}


// Se puede restringir el acceso a la api, ya sea a toda la api o algunos rescursos en particular, o retringir a algunas accines en particular
// 
//



?>