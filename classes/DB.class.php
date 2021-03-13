<?php

/**
 * This Class Provide Some Static Functions To Deal With Database 'CRUD'
 */
class DB {

 // Define Server Constants
 private CONST SERVER = 'localhost';
 private CONST USERNAME = 'root';
 private CONST PASSWORD = '';
 private CONST DATABASE = 'laquatat';

 // Define The Website Main URL For Quick Access
 public CONST MAIN_URL = 'http://localhost/laquatat/';

 /**
  * Make Connection With Database Using Mysqli OOP
  *
  * @return connection
  */
 public static function connect() {
  // Start New Mysqli Connection
  $connection = new mysqli(self::SERVER, self::USERNAME, self::PASSWORD, self::DATABASE);

  // If There is Errors
  if ($connection->connect_error) {
   echo '<h1>';
   echo 'We could not connect to database : ';
   echo $connection->connect_error;
   echo '</h1>';
   return false;
  }

  mysqli_set_charset($connection, 'utf8'); // Set the Encoding to utf-8
  return $connection; // If There is No Error
 }

 /**
  * This Function Returns Some Data in The $table Where Some
  * $column equals to Some $value
  *
  * @param [string] $table
  * @param [string] $column
  * @param [any] $value
  * @return data
  */
 public static function select($table, $column, $value) {
  // Make Connection To The Database
  $connection = DB::connect();
  // Write SQL Statement
  $stmt = "SELECT * FROM $table WHERE $column = '$value'";

  // Execute The Statement
  $result = $connection->query($stmt);

  // If There Some Error
  if (!$result) {
   echo '<h1>';
   echo 'Oops, We Could not Select Any Data from "' . $table . ' table"';
   echo '</h1>';
   echo $connection->error;
   return null;
  }

  // If There is No Error
  return $result;
 }

 /**
  * This Function Returns All Data Form a $table
  *
  * @param [string] $table
  * @return data
  */
 public static function select_all($table, $limit = null) {
  // Make Connection
  $connection = DB::connect();
  // Write SQL Statement
  $stmt = $limit != null ? "SELECT * FROM $table LIMIT $limit" : "SELECT * FROM $table";

  // Execute The Statement
  $result = $connection->query($stmt);

  // If There is Some Error in The Result
  if (!$result) {
   echo '<h1>';
   echo 'Oops, We Could not Select Any Data from "' . $table . ' table"';
   echo '</h1>';
   return null;
  }

  // If There is No Error
  return $result;
 }

}
