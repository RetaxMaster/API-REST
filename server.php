<?php

// PHP_AUTH_USER es la manera de PHP de codificarlo
$user = array_key_exists( "PHP_AUTH_USER", $_SERVER ) ? $_SERVER["PHP_AUTH_USER"] : "";
$pwd = array_key_exists( "PHP_AUTH_PW", $_SERVER ) ? $_SERVER["PHP_AUTH_PW"] : "";

if( $user !== "carlos" || $pwd !== "1234" ) die;

// Definimos los recursos disponibles
$allowedResourceTypes = [
    "books",
    "authors",
    "genres"
];

// Validamos que el recurso esté disponible
$resourceType = $_GET["resource_type"];

if( !in_array($resourceType, $allowedResourceTypes) ) die;

// Defino los recursos
$books = [

    1 => [
        "titulo" => "Lo que el viento se llevo",
        "id_autor" => 2,
        "id_genero" => 2,
    ],

    2 => [
        "titulo" => "La Iliada",
        "id_autor" => 1,
        "id_genero" => 1,
    ],
    
    3 => [
        "titulo" => "La Odisea",
        "id_autor" => 1,
        "id_genero" => 1,
    ],

];

header("Content-Type: application/json");

// Levantamos del id del recurso buscado
$resourceId = array_key_exists("resource_id", $_GET) ? $_GET["resource_id"] : "";

// Generamos la respuesta asumiento que el pedido es correcto
switch ( strtoupper($_SERVER["REQUEST_METHOD"]) ) {

    case 'GET':

        if( empty( $resourceId ) ) {
            echo json_encode( $books );
        }
        else {

            if( array_key_exists($resourceId, $books) ) {
                echo json_encode( $books[$resourceId] );
            }

        }

        break;

    case 'POST':

        $json = file_get_contents("php://input");
        $books[] = json_decode($json, true);

        //echo array_keys( $books )[ count( $books - 1 ) ];
        echo json_encode( $books );

        break;

    case 'PUT':

        // Validamos que el recurso buscado exista
        if( !empty($resourceId) && array_key_exists( $resourceId, $books ) ) {

            // Tomamos la entrada cruda
            $json = file_get_contents("php://input");

            // Transformamos el jsn recibido a un nuevo elemento del arreglo
            $books[ $resourceId ] = json_decode($json, true);

            // Retornamos la colección modificada en formato json
            echo json_encode( $books );


        }

        break;

    case 'DELETE':

        // Validamos que el recurso exista
        if( !empty($resourceId) && array_key_exists( $resourceId, $books ) ) {

            unset( $books[$resourceId] );

            echo json_encode( $books );

        }

        break;

}

?>