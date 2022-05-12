<?php
    require '../classes/controllers/login_controller.php';

    session_start();

    if(isset($_SESSION["login"]) && $_SESSION["login"] === TRUE){
      header("location: ../index.php");
    }

    $id = null;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      //Set user data
      $item = new UserData();
      $item->firstname = $_POST["firstname"];
      $item->lastname = $_POST["lastname"];
      $item->username = $_POST["username"];
      $item->email = $_POST["email"];
      $item->password = $_POST["password"];

      //Validate fields
      if(empty(trim($_POST["firstname"]))){
          $fnerror= "Please enter first name.";
      } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["firstname"]))){
          $fnerror = "This field can only contain letters.";
      } else{
          $item->firstname = trim($_POST["firstname"]);
      }

      if(empty(trim($_POST["lastname"]))){
          $lnerror = "Please enter last name.";
      } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["lastname"]))){
          $lnerror = "This field can only contain letters.";
      } else{
          $item->lastname = trim($_POST["lastname"]);
      }

      if(empty(trim($_POST["username"]))){
          $userror = "Please enter username.";
      } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
          $userror = "This field can only contain letters, numbers, or underscores.";
      } else{
        //Call Register to verify username exists
        $reg = new Register();
        $user = $reg->VerifyUser($item);
        if($user > 0){
          $userror = "Username already in use. Please try with another.";
        } else {
          $item->username = trim($_POST["username"]);
        }
      }

      if(empty(trim($_POST["email"]))){
          $emerror = "Please enter email.";
      } else{
        //Call Register to verify email exists
          $reg = new Register();
          $email = $reg->VerifyEmail($item);
          if($email > 0){
            $emerror = "Email already registered.";
          } else {
            $item->email = trim($_POST["email"]);
          }
      }

      if(empty(trim($_POST["password"]))){
          $pserror = "Please enter password.";
      } elseif(strlen(trim($_POST["password"])) < 8){
          $pserror = "Password must contain at least 8 characters.";
      } else{
          $item->password = trim($_POST["password"]);
      }

      if(empty(trim($_POST["conpass"]))){
          $cperror = "Please confirm password.";
      } elseif($_POST["password"] != $_POST["conpass"]){
          $cperror = "Passwords do not match.";
      } else{
          trim($_POST["conpass"]);
      }

      //Verify errors
      if(empty($fnerror) && empty($lnerror) && empty($userror) && empty($emerror) && empty($pserror) && empty($cperror)){
        $reg = new Register();
        $id = $reg->CreateUser($item);
        //Redirect to login after registration if user was added if not, send and alert.
        if($id > 0){
          header("location: ./login.php");
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
  <div w3-include-html="../common/header.html"></div>
  <div id="main">
    <!--Add alert if there's an error-->
    <?php if(!empty($error)) {
      echo '<div class="alert alert-danger alert-dismissible" id="alert">
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              <strong>Error! </strong>' .  $error . '
           </div>';
    } ?>
    <!--Register form-->
    <div class="container reg-form">
      <h1>Sign Up</h1>
      <form class="mt-5" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="mb-3 mt-3 log-wrap">
          <div class="form-group">
            <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($fnerror)) ? 'is-invalid' : ''; ?>" id="firstname" placeholder="First Name" name="firstname"
            value="<?php if(isset($_POST['firstname']) && empty($fnerror)) { echo $_POST['firstname']; } ?>" required />
            <div class="invalid-feedback"><?php echo $fnerror; ?></div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($lnerror)) ? 'is-invalid' : ''; ?>" id="lastname" placeholder="Last Name" name="lastname"
            value="<?php if(isset($_POST['lastname']) && empty($lnerror)) { echo $_POST['lastname']; } ?>" required />
            <div class="invalid-feedback"><?php echo $lnerror; ?></div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($userror)) ? 'is-invalid' : ''; ?>" id="username" placeholder="Username" name="username"
            value="<?php if(isset($_POST['username']) && empty($userror)) { echo $_POST['username']; } ?>" required />
            <div class="invalid-feedback"><?php echo $userror; ?></div>
          </div>
          <div class="form-group">
            <input type="email" class="form-control mb-3 mt-3 <?php echo (!empty($emerror)) ? 'is-invalid' : ''; ?>" id="email" placeholder="Email Address" name="email"
            value="<?php if(isset($_POST['email']) && empty($emerror)) { echo $_POST['email']; } ?>" required />
            <div class="invalid-feedback"><?php echo $emerror; ?></div>
          </div>
          <div class="form-group">
            <input type="password" class="form-control mb-3 mt-3 <?php echo (!empty($pserror)) ? 'is-invalid' : ''; ?>" id="password" placeholder="Password" name="password"
            value="<?php if(isset($_POST['password']) && empty($pserror)) { echo $_POST['password']; } ?>" required />
            <div class="invalid-feedback"><?php echo $pserror; ?></div>
          </div>
          <div class="form-group">
            <input type="password" class="form-control mb-3 mt-3 <?php echo (!empty($cperror)) ? 'is-invalid' : ''; ?>" id="conpass" placeholder="Confirm password" name="conpass"
            value="<?php if(isset($_POST['conpass']) && empty($cperror)) { echo $_POST['conpass']; } ?>" required />
            <div class="invalid-feedback"><?php echo $cperror; ?></div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Sign up</button>
        <a href="login.php" class="btn btn-danger">Cancel</a>
      </form>
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
