<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
use User\Auth\CapturaAuth;

chdir(dirname(__DIR__));

// Run the variables of the application
include_once('config/constants.config.php');

// Setup autoloading
include 'init_autoloader.php';


// Starts all the methods in common
include_once('config/methods.config.php');

// inclue o tratamento para os fatais erros
//include_once('config/erro.config.php');

if(array_key_exists('get-php-info', $_GET)){
    phpinfo();
}

// Run the application!
try{
    $app = Zend\Mvc\Application::init(include 'config/application.config.php');
    $app->run();
} catch(\Exception $e){

    if($_SERVER['HTTP_HOST'] == 'zf.captura' || $_SERVER['HTTP_HOST'] == 'captura.br' ){
        \Captura::dump($e->getMessage(),1);
    }
    if(CapturaAuth::getInstance() && CapturaAuth::getInstance()->getUser()->login == 'root'){
        dump($e->getMessage(),1);
    }
    header('Location: /error/sem permissao');
}