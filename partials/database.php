<?php

// Database configuration

use function PHPSTORM_META\type;

$host = "localhost"; // your database host
$db = "php_blog"; // your database name
$user = "root"; // your database username
$password = "root"; // your database password

try {

   $connection = new PDO("mysql:host=$host;dbname=$db;", $user, $password);

   // set the PDO error mode to exception
   $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // echo "Connected successfully";

   // -
} catch (PDOException $e) {

   echo "Connection failed: " . $e->getMessage();

   exit();

   // -
};
