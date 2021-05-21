<?php 
require 'application/lib/dev.php';


use application\core\Router;


spl_autoload_register(function($class)
{
  $pach = str_replace('\\','/',$class.'.php');
  if (file_exists($pach)) {
   require $pach;
  }
});

session_start();

$router = new Router;
$router ->run();

?>