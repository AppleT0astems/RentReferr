<?php

  class MySQLDB {
    public $servername;
    public $username;
    public $password;
    public $database;
    public $conn;

    function __construct($server, $user, $pass, $db) {
      $this->servername = $server;
      $this->username = $user;
      $this->password = $pass;
      $this->database = $db;

      $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

      if($this->conn->connect_error){
        die('Connection failed: ' . $this->conn->connect_error);
      }
    }

    //Funtion to insert values to db from different forms
    function Insert($table, $vals) : int {
      $cols_str = '(';
      $vals_str = '(';

      foreach($vals as $key => $value) {
        $cols_str .= $key . ', ';
        $vals_str .= "'" . $value . "', ";
      }

      $cols_str = chop($cols_str, ', ');
      $vals_str = chop($vals_str, ', ');

      $cols_str .= ')';
      $vals_str .= ')';

      $sql = 'INSERT INTO ' . $table . ' ' . $cols_str . 'VALUES ' . $vals_str . ";";

      if($this->conn->query($sql) === TRUE) {
        return $this->conn->insert_id;
      } else {
        return -1;
      }
      $this->conn->close();
    }

    //Function to verify user exists in db
    function SearchUser($table, $username) : int {
      $sql = 'SELECT COUNT(*) AS user FROM ' . $table . ' WHERE username = ' . "'" . $username . "';";
      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          return $row["user"];
        }
      }
      $this->conn->close();
    }

    //Function to verify email exists in db
    function SearchEmail($table, $email) : int {
      $sql = 'SELECT COUNT(*) AS email FROM ' . $table . ' WHERE email = ' . "'" . $email . "';";
      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          return $row["email"];
        }
      }
      $this->conn->close();
    }

    //Function to verify login data
    function LoginAccount($table, $user, $pass) {
      $sql = 'SELECT id, username, password FROM ' . $table . ' WHERE username = ' . "'" . $user . "' AND password = " . "'" . $pass . "';";
      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        $item = new UserData();
        while($row = $result->fetch_assoc()) {
           $item->id = $row["id"];
           $item->username = $row["username"];
           $item->password = $row["password"];
        }
        return $item;
      }
      $this->conn->close();
    }

    //Function to verify concatenated address
    function SearchAddress($table, $addr) {
      $data = array();
      $sql = 'SELECT CONCAT(streetno, " ", streetname, " ", (CASE WHEN suite IS NULL THEN "" ELSE suite END), ", ", city, ", ", province, " ", postalcode) AS name
     		     FROM ' . $table . ' WHERE CONCAT(streetno, " ", streetname, " ", (CASE WHEN suite IS NULL THEN "" ELSE suite END), ", ", city, ", ", province, " ", postalcode) LIKE "%'. $addr .'%"';

     	$result = $this->conn->query($sql);
     	$str = '<strong>' . $addr . '</strong>';

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $data[] = array('name' => str_ireplace($addr, $str, $row['name']));
        }
        return $data;
      }
      $this->conn->close();
    }

    //Function to verify the address
    function SearchReference($table, $address) : int {
      $sql = 'SELECT id FROM ' . $table . ' WHERE CONCAT(streetno, " ", streetname, " ", (CASE WHEN suite IS NULL THEN "" ELSE suite END), ", ", city, ", ", province, " ", postalcode) = "' . $address . '";';
     	$result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          return $row["id"];
        }
      } else {
        return -1;
      }
      $this->conn->close();
    }

    //Function to verify concatenated address
    function SearchProperty($table, $prop) {
      $sql = "";
      $data = array();
      if($prop->id === null){
        $sql = 'SELECT id, CONCAT(streetno, " ", streetname, " ", (CASE WHEN suite IS NULL THEN "" ELSE suite END), ", ", city, ", ", province, " ", postalcode) AS address
        FROM ' . $table . ";";
      } else {
        $sql = $sql = 'SELECT id, CONCAT(streetno, " ", streetname, " ", (CASE WHEN suite IS NULL THEN "" ELSE suite END), ", ", city, ", ", province, " ", postalcode) AS address
        FROM ' . $table . ' WHERE id = ' .  $prop->id . ";";
      }

      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $data[] = $row;
        }
        return $data;
      }
      $this->conn->close();
    }

    //Function to search the reviews of a property
    function SearchReviews($prop) {
      $rarr = array();
        $sql = 'SELECT r.uname as username, p.review as review,
        ROUND(((p.pricescore + p.neighbourhood + p.cleanliness + p.management)/4), 1) AS score, p.creationdate as cdate
        FROM posts p, randomname r WHERE p.uname_id = r.id AND p.property_id =' . $prop->id . ";";

      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
           $rarr[] = $row;
        }
        return $rarr;
      } else {
        return $rarr[] = '';
      }

      $this->conn->close();
    }

    //Function to search the details of a property
    function SearchDetails($table, $prop) {
      $darr = array();
      $frag = 'ROUND((((SELECT COUNT(culture) FROM posts WHERE culture = 1 AND property_id =' . $prop->id .')*100)/(SELECT COUNT(culture) FROM posts WHERE property_id =' . $prop->id . ')), 0)';
      $sql = 'SELECT ROUND(AVG((pricescore + neighbourhood + cleanliness + management)/4), 1) AS totalscore, ROUND(AVG(price), 0) AS price, ROUND(AVG(pricescore), 0) AS pricescore,
      ROUND(AVG(neighbourhood), 0) AS neighbourhood, ROUND(AVG(cleanliness), 0) AS cleanliness, ROUND(AVG(management), 0) AS management, ' . $frag . ' AS culture
      FROM ' . $table . ' WHERE property_id =' . $prop->id . ";";
      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
           $darr[] = $row;
        }
        return $darr;
      } else {
        return $darr[] = '';
      }
      $this->conn->close();
    }

    //Function to get a random username
    function GenerateRandomName($table){
      $ran = array();
      $sql = 'SELECT * FROM ' . $table . ' ORDER BY RAND() LIMIT 1;';
      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ran[] = $row;
        }
        return $ran;
      }
      $this->conn->close();
    }

    //Function to verify the random username exists in db
    function SearchRandomName($table, $rnd) : int {
      $sql = 'SELECT id FROM ' . $table . ' WHERE uname = ' . "'" . $rnd->uname . "';";
      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          return $row["id"];
        }
      } else {
        return -1;
      }
      $this->conn->close();
    }

    //Function to verify the user already posted a review for a property
    function SearchPost($table, $posts) : int {
      $sql = 'SELECT COUNT(*) AS count FROM ' . $table . ' WHERE user_id = ' . $posts->userid . " AND property_id =" . $posts->propertyid . ";";

      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          return $row["count"];
        }
      }
      $this->conn->close();
    }

  }
?>
