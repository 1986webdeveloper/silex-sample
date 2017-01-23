<?php 

// my_bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;

$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/models"), $isDevMode);

// database configuration parameters
$conn = array(
     'driver'   => 'pdo_mysql',
    'user'     => 'YOUR_DB_USER_NAME',
    'password' => 'YOUR_DB_USER_PASSWORD',
    'dbname'   => 'YOUR_DB_NAME'
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);