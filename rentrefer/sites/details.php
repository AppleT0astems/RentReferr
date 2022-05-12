<?php
  require '../classes/controllers/property_controller.php';
  session_start();

  //Property id
  $id = $_GET["id"];

  if(isset($_SESSION["login"]) && $_SESSION["login"] === TRUE){
    $user = $_SESSION["username"];
    $uid = $_SESSION["id"];

    //Verify if the user already post a review of the property
    $posts = new PostsData();
    $posts->userid = $uid;
    $posts->propertyid = $id;
    $rev = new ManageReviews();
    $count = $rev->VerifyPost($posts);
  }

  //Retrieve property details and reviews
  $arr[] = array();
  $rarr[] = array();
  $darr[] = array();
  $prop = new PropertyData();
  $prop->id = $id;
  $adal = new ManageReviews();
  $arr = $adal->ReadProperty($prop);
  $rarr = $adal->GetReviews($prop);
  $darr = $adal->GetDetails($prop);

?>
<!DOCTYPE html>
<html>
<head>
  <title>RentRefer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../static/css/details.css" rel="stylesheet">
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
    <div id="main">
      <div id="alert"></div>
      <?php
        foreach($arr as $vals) {
          echo '<h1>' . $vals['address'] . '</h1>';
        }

        foreach($darr as $det) {
          echo '<div class="mb-4">
                  <p class="score"><img class="icon-star" src="../static/img/star.png" alt="Star" />&nbsp;<strong>' . $det['totalscore']. '
                  &emsp;$' . $det['price'] . '</strong></p>
                </div>';
          if($det['culture'] > 50) {
            echo '<div class="mb-4">
                    <p class="score"><strong>This is a cultural friendly place.</strong></p>
                  </div>';
          }
        }

        echo '<div class="mb-4">';
        if(empty($uid)) {
          echo '<div class="alert alert-info" id="alert">
                  <strong>Info! </strong> You must <a href="login.php">login</a> to add a review for this place.
                </div>';

        } elseif ($count > 0) {
          echo '<div class="alert alert-warning" id="alert">
                  <strong>Warning! </strong> You already added a review for this place.
                </div>';
        }else {
          echo '<a href="posts.php?id=' . $id . '" class="btn btn-primary">Add a review</a>';
        }
      ?>
      </div>
      <div class="container-fluid mt-5">
        <!--Reviews-->
        <div class="row">
          <div class="col-sm-6">
            <!--Comments-->
            <h2>Reviews</h2>
              <?php
                if($rarr === '' ) {
                  echo '<div class="alert alert-primary" id="alert">
                        <strong>Info! </strong> There are no reviews for this place. Be the first to add one!
                        </div>';
                } else {
                  foreach($rarr as $rev) {
                    echo '<div class="mt-3"> <h4>' . $rev['username'] . '</h4>';
                    echo '<p>' . $rev['cdate'] . '&emsp;<strong>Score</strong>&ensp;<img class="icon-score" src="../static/img/score.png" alt="Score">&ensp;<strong>' . $rev['score'] . '</strong></p>';
                    echo '<p>' . $rev['review'] . '</p></div>';
                  }
                }
              ?>
          </div>
          <!--Scores-->
          <div class="col-sm-6">
              <?php
                foreach($darr as $det) {
                  echo '<div class="mb-2">
                          <h4><img class="icon" src="../static/img/money.png" alt="Price"> Price</h4>
                        </div>
                        <div class="mx-2">';
                    for($score = 0; $score < $det['pricescore']; $score++) {
                      echo '<img class="icon-score" src="../static/img/star.png" alt="Star">';
                    }
                  echo '</div>
                        <div class="mt-3">
                          <h4><img class="icon" src="../static/img/home.png" alt="Home"> Neighbourhood</h4>
                        </div>
                        <div class="mx-2">';
                    for($score = 0; $score < $det['neighbourhood']; $score++) {
                      echo '<img class="icon-score" src="../static/img/star.png" alt="Star">';
                    }
                  echo '</div>
                        <div class="mt-3">
                          <h4><img class="icon" src="../static/img/broom.png" alt="Broom"> Cleanliness</h4>
                        </div>
                        <div class="mx-2">';
                    for($score = 0; $score < $det['cleanliness']; $score++) {
                      echo '<img class="icon-score" src="../static/img/star.png" alt="Star">';
                    }
                    echo '</div>
                          <div class="mt-3">
                            <h4><img class="icon" src="../static/img/house-owner.png" alt="Owner"> Management</h4>
                          </div>
                          <div class="mx-2">';
                      for($score = 0; $score < $det['management']; $score++) {
                        echo '<img class="icon-score" src="../static/img/star.png" alt="Star">';
                      }
                    echo '</div>';
                }
              ?>
          </div>
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
