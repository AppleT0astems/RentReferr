<?php
  require '../classes/models/user_model.php';
  require '../classes/db/db_connect.php';
  require '../classes/db/config.php';

  class Register {

    private $_db;

    function __construct() {
      $this->_db = new MySQLDB(DB_HOST, DB_USER, DB_PSW, DB_NAME);
    }

    //Function that calls SearchUser function in MySQLDB class
    function VerifyUser(UserData $item) : int {
      $vals['username'] = $item->username;
      $res = $this->_db->SearchUser('users', $vals['username']);
      return $res;
    }

    //Function that calls SearchEmail function in MySQLDB class
    function VerifyEmail(UserData $item) : int {
      $vals['email'] = $item->email;
      $res = $this->_db->SearchEmail('users', $vals['email']);
      return $res;
    }

    //Function that calls InsertUser function in MySQLDB class
    function CreateUser(UserData $item) : int {
      $vals['lastname'] = $item->lastname;
      $vals['firstname'] = $item->firstname;
      $vals['username'] = $item->username;
      $vals['email'] = $item->email;
      $vals['password'] = md5($item->password);

      $rtn = $this->_db->Insert('users', $vals);

      if($rtn > 0) {
        return $rtn;
      } else {
        return -1;
      }
    }

    //Function that calls LoginAccount function in MySQLDB class
    function VerifyLogin(UserData $item) {
      $vals['username'] = $item->username;
      $vals['password'] = md5($item->password);
      $item = $this->_db->LoginAccount('users', $vals['username'], $vals['password']);
      return $item;
    }
  }
?>
