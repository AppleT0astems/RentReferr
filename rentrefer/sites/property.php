<?php
    require '../classes/controllers/property_controller.php';

    session_start();

    if(isset($_SESSION["login"]) && $_SESSION["login"] === TRUE){
      $user = $_SESSION["username"];
      $uid = $_SESSION["id"];
    }

    $arr[] = array();
    $prop = new PropertyData();
    $prop->id = null;
    $adal = new ManageReviews();
    $arr = $adal->ReadProperty($prop);
?>
<!DOCTYPE html>
<html>
<head>
  <title>RentRefer</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="../static/css/property.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://www.w3schools.com/lib/w3.js"></script>
</head>
  <body>
    <!--Header-->
    <?php
      if(!empty($user)) {
        echo '<div w3-include-html="../common/profileNav.php"></div>';
      } else {
        echo'<div w3-include-html="../common/header.html"></div>';
      }
    ?>
    <div id="main">
      <div class="container mb-5">
        <div class="mt-2 mb-5">
          <?php
            if(!empty($uid)) {
              echo '<a href="addprop.php" class="btn btn-primary">Add new address</a>';
            } else {
              echo '<div class="alert alert-info" id="alert">
                    <strong>Info! </strong> You must <a href="login.php">login</a> to add a new reference.
                    </div>';
            }
          ?>
        </div>
        <table class="table table-striped" id="propTable">
          <thead>
            <tr>
              <th>Address</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
            foreach($arr as $vals) {
              echo"<tr>";
              echo "<td>" . $vals['address'] . "</td>";
              echo "<td>";
              echo "<div>";
              echo '<a href="details.php?id=' . $vals['id'] . '"class="btn btn-primary">Add a review for this place</a>';
              echo "</td>
                    </div>
                    </tr>";
             }
          ?>
          </tbody>
        </table>
      </div>
    </div>
    <!--Footer-->
    <div w3-include-html="../common/footer.html"></div>
    <script>
      //Include header and footer layouts
      w3.includeHTML();

      //Pagination function for table
      $(document).ready(function () {
        $('#propTable').DataTable();
      });
    </script>
  </body>
</html>
