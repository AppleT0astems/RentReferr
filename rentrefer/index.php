<?php
  require 'classes/controllers/home_controller.php';

  session_start();

  //Check if there's and active session
  if(isset($_SESSION["login"]) && $_SESSION["login"] === TRUE){
    $user = $_SESSION["username"];
    $uid = $_SESSION["id"];
  }

  $id = null;

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = $_POST['searchBox'];

    //If an id is returned after the search, redirects to the details of the property entered, if not, redirects to the page to add a new user
    if(empty(trim($address))){
        $error= "Please enter a valid address.";
    } else{
      $search = new Search();
      $id = $search->VerifyReference($address);

      if($id > 0){
        header("location: sites/details.php?id=$id");
      } else {
        $errormsg = "Invalid address.";
      }
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>RentRefer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="static/css/main.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://www.w3schools.com/lib/w3.js"></script>
</head>
<body>

<!--Header-->
    <?php if(!empty($user)) {
      echo '<div w3-include-html="common/profileNav.php"></div>';
    } else {
      echo'<div w3-include-html="common/header.html"></div>';
    }?>
  <?php
    if(!empty($errormsg)) {
      echo '<div class="alert alert-danger alert-dismissible" id="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>Error! </strong>' .  $errormsg . '
            </div>';
    }
  ?>
  <?php if(!empty($user)) {
      echo '<p id="welcome">Welcome back <strong>' . $user . '!</strong></p>';
  }
  ?>

<!--Search button-->
    <form class="searchForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="d-flex" id="search">
        <!--If the input is not valid, it will add an error msg inside the box-->
        <input class="form-control me-2 <?php echo (!empty($error)) ? 'is-invalid' : '' ?>" id="searchBox" name="searchBox" type="text"
        value="<?php echo (!empty($error)) ?  $error : '' ?>" placeholder="Enter Address" data-bs-toggle="dropdown" onkeyup="javascript:searchAddress(this.value)" required/>
        <button class="btn btn-dark" role="button" style='color:teal; font-family: inherit; font-size: 20px; border: 1px' type="submit">Search</button>
        <span class="dropdown-menu" id="searchRes"></span>
      </div>
    </form>

<!--Footer-->
  <div w3-include-html="common/footer.html"></div>
    <script>
      //Include header and footer layouts
      w3.includeHTML();
      </script>
  <script type="text/javascript" src="/static/js/search.js"></script>
</body>
</html>
