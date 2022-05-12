<?php
  session_start();

  if(isset($_SESSION["login"]) && $_SESSION["login"] === TRUE){
    $user = $_SESSION["username"];
  }
?>

<!DOCTYPE html>
<html>
<head>
  <link href="static/css/main.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<!--Navigation bar fixed to top of the page, colour black that can be changed with css-->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php" class="navbar-left"><img src="../static/img/verify.png">Your Best Reference</a> <!--Logo or brand-->
    <!--If the screen resizes, a button of 3 lines will appear on the right side of the bar, when is clicked, the options appear under the logo-->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLinks">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="collapseLinks">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="../sites/property.php">Properties</a>
        </li>
        <div class="btn-group">
          <button id="logBtn" type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?php if(!empty($user)) { echo $user; }?></button>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-start">
            <li><a class="dropdown-item" href="../classes/controllers/logout.php">Logout</a></li>
          </ul>
        </div>
      </ul>
    </div>
  </div>
</nav>
</body>
</html>
