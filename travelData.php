<?php

# referenced official documentation + previous php calls done for it202

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmtable(__DIR__);
$dotenv->load();

$clientId = $_ENV['API_KEY'];
$clientSecret = $_ENV['API_SECRET'];


?>
