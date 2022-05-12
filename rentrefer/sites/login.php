<?php
  require '../classes/controllers/login_controller.php';

  session_start();

  if(isset($_SESSION["login"]) && $_SESSION["login"] === TRUE){
    header("location: ../index.php");
  }

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Set user data
    $item = new UserData();
    $item->username = $_POST["username"];
    $item->password = $_POST["password"];

    //Validate empty fields
    if(empty(trim($_POST["username"]))){
        $userror = "Please enter username.";
    } else{
        $item->username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $pserror = "Please enter password.";
    } else{
        $item->password = trim($_POST["password"]);
    }

    //Validate if there are no errors and verify login data
    if(empty($userror) && empty($pserror)) {
      $reg = new Register();
      $item = $reg->VerifyLogin($item);
      //If data is correct start session
      if((!empty($item->username)) && (!empty($item->password))) {
        $_SESSION["login"] = TRUE;
        $_SESSION["id"] = $item->id;
        $_SESSION["username"] = $item->username;
        header("location: ../index.php");
      } else {
        $error = "Invalid user or password.";
      }
    } else {
      $error = "Something went wrong. Try again.";
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
    <!--Login form-->
    <div class="container log-form">
      <h1>Login</h1>
      <form class="mt-5" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="mb-3 mt-3 log-wrap">
          <div class="form-group">
            <input type="text" class="form-control mb-3 mt-3 <?php echo (!empty($userror)) ? 'is-invalid' : ''; ?>" id="username" placeholder="Username" name="username"
            value="<?php if(isset($_POST['username']) && empty($userror)) { echo $_POST['username']; } ?>" required />
            <div class="invalid-feedback"><?php echo $userror; ?></div>
          </div>
          <div class="form-group">
            <input type="password" class="form-control mb-3 mt-3 <?php echo (!empty($pserror)) ? 'is-invalid' : ''; ?>" id="pass" placeholder="Password" name="password"
            value="<?php if(isset($_POST['password']) && empty($pserror)) { echo $_POST['password']; } ?>" required />
            <div class="invalid-feedback"><?php echo $pserror; ?></div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Login</button>
        <a href="../index.php" class="btn btn-danger mt-3">Cancel</a>
        <div class="mx-auto mt-3">
          Don't have and account? <a href="signup.php">Sign up here</a>
        </div>
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
