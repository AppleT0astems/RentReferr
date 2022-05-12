<?php
  require 'classes/db/db_connect.php';
  require 'classes/db/config.php';

  class Search {

    private $_db;

    function __construct() {
      $this->_db = new MySQLDB(DB_HOST, DB_USER, DB_PSW, DB_NAME);
    }

    //Function that calls SearchReference function in MySQLDB class
    function VerifyReference($address) : int {
      $id = $this->_db->SearchReference('property', $address);
      return $id;
    }
  }
?>
