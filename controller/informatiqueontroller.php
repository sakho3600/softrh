<?php

require_once 'core/db.php';
require_once 'model/informatiques.php';

function defaultAction(){
    $loader = new \Twig\Loader\FilesystemLoader('view');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);
    $informatiques = getinformatiquesAll();
    require_once 'view/informatiques.html.php';
}

function detailAction(){
    $loader = new \Twig\Loader\FilesystemLoader('view');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);
    global $uri;
    // RÉCUPÉRER L'ID
    $exprReg = "#/[0-9]+#";
    preg_match($exprReg, $uri, $matches);
    var_dump($matches);

    if( count($matches) === 0 ){
        require_once 'view/votefait.html.php';
        return;
    }

    $id = intval( substr($matches[0], 1) );
    $informatique = getinformatiquesByid($id);
    var_dump($informatique);

    if ( $informatique === false ){
        require_once 'view/votefait.html.php';
        return;
    }

    require_once 'view/detailinformatique.html.php';
}


$action = 'default';

if( strpos( $uri, '/', 1) !== false){
    $action = ( strpos( $uri, '/', strlen( $controller ) + 1 )  === false )? substr( $uri, strpos( $uri, '/', strlen( $controller ))+1) : substr( $uri,  strlen( $controller ) + 1, ( strpos( $uri, '/', strlen( $controller ) + 1 ) -1 ) - ( strlen( $controller ) - 1 ) -1    );

    
}


switch($action){

    case  'default' :
    case  "" ;    
        defaultAction();
    break;
    case  'detail' :
        detailAction();
    break;
    default :
      require_once 'view/404.html.php';
}