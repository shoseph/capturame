<?php 

use Capturada\Entity\Capturada;

chdir(dirname(__DIR__));

// Setup autoloading
include 'init_autoloader.php';

// Run the variables of the application
include_once('config/constants.config.php');

// Starts all the methods in common
include_once('config/methods.config.php');

if(array_key_exists('get-php-info', $_GET)){
	phpinfo();
}

// Run the application!
try{
	$app = Zend\Mvc\Application::init(include 'config/application.config.php');
	
} catch(\Exception $e){
    \Captura::dumpt("[EXCEPT] " . $e->getMessage(),1);
}
