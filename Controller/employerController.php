<?php

require_once 'core/db.php';
require_once 'model/employer.php';
require_once 'vendor/autoload.php';



function defaultAction()
{
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: /');
        return;
    }
    
    $humeurs = getHumeurAll();
    $loader = new \Twig\Loader\FilesystemLoader('view');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
    ]);
    $template = $twig->load('employer.html.twig');
    echo $template->render([
        'humeurs' => $humeurs

    ]);
}



function  hasvotedAction()
{
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: /');
        return;
    }

    $loader = new \Twig\Loader\FilesystemLoader('view');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
    ]);
    $template = $twig->load('votefait.html.php');
    echo $template->render();
}


function voteAction(){
    session_start();
    global $uri;
    $exprReg = "#\/[0-9]+#";
    preg_match($exprReg, $uri, $matches);

    if (count($matches) === 0) {
        $humeurs = getHumeurAll();
        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);
        $template = $twig->load('employer.html.twig');
        echo $template->render([
            'humeurs' => $humeurs

        ]);
        return;
    }
    setlocale(LC_TIME, 'fra_fra');
    $date = strftime('%d/%m/%Y');
    $humeur_id = intval( substr( $matches[0], 1));
    $id_user = $_SESSION['user']['id'];
    header('Location: /employer/has_vote');
}

$action = 'default';

if (strpos($uri, '/', 1) !== false) {
    $action = (strpos($uri, '/', strlen($controller) + 1)  === false) ? substr($uri, strpos($uri, '/', strlen($controller)) + 1) : substr($uri,  strlen($controller) + 1, (strpos($uri, '/', strlen($controller) + 1) - 1) - (strlen($controller) - 1) - 1);
}


switch ($action) {

    case  'default':
    case  "";
        defaultAction();
        break;
    case  'vote':
        voteAction();
    break;
    case 'has_vote':
        hasvotedAction();
    default :
      require_once 'view/404.html.php';
}
