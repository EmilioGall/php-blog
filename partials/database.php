<?php

require 'config.php';

try {

   // Define DSN (Data Source Name) string
   $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

   // Create a new PDO instance
   $connection = new PDO($dsn, $user, $password);

   // set the PDO error mode to exception
   $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // echo "Connected successfully";

   // -
} catch (PDOException $e) {
   // Exception handling for connection errors

   // Technical message for debugging
   echo '<pre style="background: #DEDEDE; color: #484848;">';
   var_dump($e->getMessage(), (int)$e->getCode());
   echo '</pre>';

   // Log the error details to server error log
   error_log("Database connection failed: " . $e->getMessage());

   // Generic user-friendly message
   echo "Connection to the database failed. Please try again later.";

   // Throw the exception if further handling is needed
   throw new PDOException("Database connection error.", (int)$e->getCode());
};
