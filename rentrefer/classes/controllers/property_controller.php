<?php
  require '../classes/models/property_model.php';
  require '../classes/db/db_connect.php';
  require '../classes/db/config.php';
  require '../classes/models/randomname_model.php';
  require '../classes/models/posts_model.php';

  class ManageReviews {

    private $_db;

    function __construct() {
      $this->_db = new MySQLDB(DB_HOST, DB_USER, DB_PSW, DB_NAME);
    }

    //Function that calls InsertAddress function in MySQLDB class
    function CreateAddress(PropertyData $prop) : int {

      if($prop->suite != null){
        $vals['suite'] = $prop->suite;
      }

      $vals['streetno'] = $prop->streetno;
      $vals['streetname'] = strtoupper($prop->streetname);
      $vals['city'] = strtoupper($prop->city);
      $vals['province'] = strtoupper($prop->province);
      $vals['country'] = strtoupper($prop->country);
      $vals['postalcode'] = strtoupper($prop->postalcode);

      $rtn = $this->_db->Insert('property', $vals);

      if($rtn > 0) {
        return $rtn;
      } else {
        return -1;
      }
    }

    //Function that calls SearchProperty function in MySQLDB class
    function ReadProperty(PropertyData $prop) {
      $arr[] = array();
      //$id= $prop->id;
      $arr = $this->_db->SearchProperty('property', $prop);
      return $arr;
    }

    //Function that calls SearchReviews function in MySQLDB class
    function GetReviews(PropertyData $prop) {
      $rarr[] = array();
      //$id= $prop->id;
      $rarr = $this->_db->SearchReviews($prop);
      return $rarr;
    }

    //Function that calls SearchDetails function in MySQLDB class
    function GetDetails(PropertyData $prop) {
      $darr[] = array();
      //$id= $prop->id;
      $darr = $this->_db->SearchDetails('posts', $prop);
      return $darr;
    }

    //Function that calls GenerateRandomName function in MySQLDB class
    function GetRandomName() {
      $rnd = new RandomNameData();
      $ran = array();
      $ran = $this->_db->GenerateRandomName('randomname');
      foreach ($ran as $res) {
        $rnd->id = $res['id'];
        $rnd->uname = $res['uname'];
      }
      return $rnd;
    }

    //Function that calls SearchRandomName function in MySQLDB class
    function VerifyRandomName(RandomNameData $rndname) {
      $rnd = $this->_db->SearchRandomName('randomname', $rndname);
      $rndname->id = $rnd;
      return $rndname;
    }

    //Function that calls InsertReview function in MySQLDB class
    function CreateReview(PostsData $posts) : int {
      $vals['user_id'] = $posts->userid;
      $vals['property_id'] = $posts->propertyid;
      $vals['uname_id'] = $posts->unameid;
      $vals['price'] = $posts->price;
      $vals['pricescore'] = $posts->pricescore;
      $vals['neighbourhood'] = $posts->neighbourhood;
      $vals['cleanliness'] = $posts->cleanliness;
      $vals['management'] = $posts->management;
      $vals['culture'] = $posts->culture;
      $vals['review'] = $posts->review;
      $vals['creationdate'] = $posts->creationdate;
      $res = $this->_db->Insert('posts', $vals);

      if($res > 0) {
        return $res;
      } else {
        return -1;
      }
    }

    //Function that calls SearchPost function in MySQLDB class
    function VerifyPost(PostsData $posts) : int {
      $count = $this->_db->SearchPost('posts', $posts);
      return $count;
    }

  }
?>
