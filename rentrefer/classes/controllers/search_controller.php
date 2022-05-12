<?php
  require '../db/db_connect.php';
  require '../db/config.php';

   $_db = new MySQLDB(DB_HOST, DB_USER, DB_PSW, DB_NAME);

   if(isset($_POST["key"])) {
   	$data = array();
   	$addr = preg_replace('/[^A-Za-z0-9\- ]/', '', $_POST["key"]);
    $data = $_db->SearchAddress('property', $addr);
   	echo json_encode($data);
   }
?>
