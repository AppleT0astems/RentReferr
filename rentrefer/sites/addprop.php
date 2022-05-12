<?php
    require '../classes/controllers/property_controller.php';

    #Add a test comment
    session_start();

    if(isset($_SESSION["login"]) && $_SESSION["login"] === TRUE){
      $user = $_SESSION["username"];
    } else {
      header("location: login.php");
    }

    $id = null;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      //Set property data
      $prop = new PropertyData();
      $prop->streetno = trim($_POST["streetno"]);
      $prop->streetname = trim($_POST["streetname"]);
      $prop->city = trim($_POST["city"]);
      $prop->province = trim($_POST["province"]);
      $prop->country = trim($_POST["country"]);
      $prop->postalcode = trim($_POST["postalcode"]);

      //Validate fields
      if(empty(trim($_POST["streetno"]))){
          $serror= "Please enter street number.";
      } elseif(!preg_match('/^[0-9]+$/', trim($_POST["streetno"]))){
          $serror = "This field can only contain numbers.";
      } else{
          $prop->streetno = trim($_POST["streetno"]);
      }

      if(empty(trim($_POST["streetname"]))){
          $snerror = "Please enter street name.";
      } elseif(!preg_match('/^[a-zA-Z0-9\s]+$/', trim($_POST["streetname"]))){
          $snerror = "This field can only contain letters and numbers.";
      } else{
          $prop->streetname = trim($_POST["streetname"]);
      }

      if(empty(trim($_POST["suite"]))){
        $prop->suite = null;
      } elseif(!preg_match('/^[a-zA-Z0-9]+$/', trim($_POST["suite"]))){
          $sterror = "This field can only contain numbers and letters.";
      } else{
          $prop->suite = trim($_POST["suite"]);
      }

      if(empty(trim($_POST["city"]))){
          $cerror = "Please enter a city.";
      } elseif(!preg_match('/^[a-zA-Z\s]+$/', trim($_POST["city"]))){
          $cerror = "This field can only contain letters.";
      } else{
          $prop->city = trim($_POST["city"]);
      }

      if(empty(trim($_POST["province"]))){
          $perror = "Please enter a province.";
      } elseif(!preg_match('/^[a-zA-Z\s]+$/', trim($_POST["province"]))){
          $perror = "This field can only contain letters.";
      } else{
          $prop->province = trim($_POST["province"]);
      }

      if(empty(trim($_POST["country"]))){
          $cterror = "Please enter a country.";
      } elseif(!preg_match('/^[a-zA-Z\s]+$/', trim($_POST["country"]))){
          $cterror = "This field can only contain letters.";
      } else{
          $prop->country = trim($_POST["country"]);
      }

      if(empty(trim($_POST["postalcode"]))){
          $pcerror = "Please enter postal code.";
      } elseif(!preg_match('/^[a-zA-Z0-9]+$/', trim($_POST["postalcode"]))){
          $pcerror = "This field can only contain letters and numbers.";
      } else{
          $prop->postalcode = trim($_POST["postalcode"]);
      }

      //Verify errors
      if(empty($serror) && empty($snerror) && empty($sterror) && empty($cerror) && empty($perror) && empty($cterror) && empty($pcerror)){
        $reg = new ManageReviews();
        $id = $reg->CreateAddress($prop);
        //Redirect to property list after the creation if address was added if not, send and alert.
        if($id > 0){
          header("location: details.php?id=$id");
        } else {
          $error = "Something went wrong. Try again.";
        }
      } else {
        $error = "Please verify data entered.";
      }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>RentRefer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../static/css/forms.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://www.w3schools.com/lib/w3.js"></script>
</head>
  <body>
  <!--Header-->
    <?php if(!empty($user)) {
      echo '<div w3-include-html="../common/profileNav.php"></div>';
    } else {
      echo'<div w3-include-html="../common/header.html"></div>';
    }?>
    <div class="p-5 bg-secondary text-white text-center">
      <h1>Add a new address</h1>
    </div>
    <div id="main">
      <div class="container mt-5">
        <!--Create address form-->
        <div class="container reg-form" id="addForm">
          <!--Add alert if there's an error-->
          <?php
            if(!empty($error)) {
              echo '<div class="alert alert-danger alert-dismissible" id="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Error! </strong>' .  $error . '
                    </div>';
            }
          ?>
          <form class="mt-5" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="mb-3 mt-3 log-wrap">
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($serror)) ? 'is-invalid' : ''; ?>" id="streetno" placeholder="Street No" name="streetno"
                    value="<?php if(isset($_POST['streetno']) && empty($serror)) { echo $_POST['streetno']; } ?>" required />
                    <div class="invalid-feedback"><?php echo $serror; ?></div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($snerror)) ? 'is-invalid' : ''; ?>" id="streetname" placeholder="Street Name" name="streetname"
                    value="<?php if(isset($_POST['streetname']) && empty($snerror)) { echo $_POST['streetname']; } ?>" required />
                    <div class="invalid-feedback"><?php echo $snerror; ?></div>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($sterror)) ? 'is-invalid' : ''; ?>" id="suite" placeholder="Suite" name="suite"
                    value="<?php if(isset($_POST['suite']) && empty($sterror)) { echo $_POST['suite']; } ?>" />
                    <div class="invalid-feedback"><?php echo $sterror; ?></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($cerror)) ? 'is-invalid' : ''; ?>" id="city" placeholder="City" name="city"
                value="<?php if(isset($_POST['city']) && empty($cerror)) { echo $_POST['city']; } ?>" required />
                <div class="invalid-feedback"><?php echo $cerror; ?></div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($perror)) ? 'is-invalid' : ''; ?>" id="province" placeholder="Province" name="province"
                value="<?php if(isset($_POST['province']) && empty($perror)) { echo $_POST['province']; } ?>" required />
                <div class="invalid-feedback"><?php echo $perror; ?></div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($cterror)) ? 'is-invalid' : ''; ?>" id="country" placeholder="Country" name="country" value="CANADA" required />
                <div class="invalid-feedback"><?php echo $cterror; ?></div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($pcerror)) ? 'is-invalid' : ''; ?>" id="postalcode" placeholder="Postal Code" name="postalcode"
                value="<?php if(isset($_POST['postalcode']) && empty($pcerror)) { echo $_POST['postalcode']; } ?>" required />
                <div class="invalid-feedback"><?php echo $pcerror; ?></div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Add new address</button>
            <a href="property.php" type="submit" class="btn btn-danger">Cancel</a>
          </form>
        </div>
      </div>
    </div>
    <!--Footer-->
    <div w3-include-html="../common/footer.html"></div>
    <script>
      //Include header and footer layouts
      w3.includeHTML();
    </script>
  </body>
</html>
