<?php // console/bootstrap.php (version2 updated May 2023)
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;

require __DIR__ . '/../vendor/autoload.php';

// Create a simple "default" Doctrine ORM configuration for Attributes
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__ . '/../src'),
    isDevMode: true
);

// database configuration parameters
$conn = \Doctrine\DBAL\DriverManager::getConnection([
    'driver'   => 'pdo_mysql',
     'user'     => $_ENV['MYSQL_USER'],
     'password' => $_ENV['MYSQL_PASSWORD'],
     'dbname'   => $_ENV['MYSQL_DATABASE'],
     'host'     => $_ENV['MYSQL_HOST']
], $config);

// obtaining the entity manager
$entityManager = new EntityManager($conn, $config);

// This should ideally be stored elsewhere...be sure not to push this file to a public repo
$bearerToken = 'Bearer '. $_ENV['X_API_KEY'];

$httpClient = new Client([
    'headers' => ['Authorization' => $bearerToken]
]);
