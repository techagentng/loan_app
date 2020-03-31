<?php

// Load the classes and create the new objects
require_once('includes/core_class.php');
require_once('includes/database_class.php');

$core = new Core();
$database = new Database();

$data['hostname'] = 'localhost:3307';
$data['username'] = 'root';
$data['password'] = '';
$data['database'] = 'kloans';

$database->create_tables($data);

?>