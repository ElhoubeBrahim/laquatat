<?php

/**
 * This is The Config File, it is Very Important To Be Included in Every Page
 * on This Web App
 */

// Strat Session
ob_start();
session_start();

// Set Main Url
$main_url = 'http://localhost/laquatat/';

// Autoload Main Classes
spl_autoload_register(function ($class) {
 require $_SERVER['DOCUMENT_ROOT'] . '\\laquatat\\classes\\' . $class . '.class.php';
});

// Instantiate User Class
$user = new User;

// Instantiate Post Class
$post = new Post;
