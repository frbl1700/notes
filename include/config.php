<?php
//Tidszon
date_default_timezone_set('Europe/Stockholm');

//Session på
session_start();

const DB_HOST = 'localhost:3306';
const DB_NAME = 'notes_db';
const DB_USER = 'localuser';
const DB_PASS = 'localuser';

//Registrera klasser
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.class.php';
});
?>