<?php
    require '../classes/controllers/property_controller.php';

    session_start();

    if(isset($_SESSION["login"]) && $_SESSION["login"] === TRUE){
      $user = $_SESSION["username"];
      $uid = $_SESSION["id"];
    } else {
      header("location: login.php");
    }

    $id = $_GET["id"];
    $revid = null;

    function getName() {
      $rnd = new RandomNameData();
      $rev = new ManageReviews();
      $rnd = $rev->GetRandomName();
      echo $rnd->uname;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      date_default_timezone_set("America/New_York");
      $cdate = date("Y-m-d");
      //Set post data
      $posts = new PostsData();
      $posts->userid = $uid;
      $posts->propertyid = $_POST["propid"];
      $posts->unameid = null;
      $posts->review = $_POST["review"];
      $posts->price = $_POST["avgprice"];
      $posts->pricescore = isset($_POST["priceRadio"]);
      $posts->neighbourhood = isset($_POST["nghRadio"]);
      $posts->cleanliness = isset($_POST["cleanRadio"]);
      $posts->management = isset($_POST["mngtRadio"]);
      $posts->culture = isset($_POST["culture"]);
      $posts->creationdate = $cdate;

      //Validate fields
      if(empty(trim($_POST["rndname"]))){
        $rnerror= "Please generate random username.";
      } else{
          $rnd = new RandomNameData();
          $rnd->uname = $_POST["rndname"];
          //Call ManageReviews to verify the random username exists
          $rev = new ManageReviews();
          $rnd = $rev->VerifyRandomName($rnd);
          if($rnd->id < 0){
            $rnerror = "Invalid username, generate a random username.";
          } else {
            $posts->unameid = $rnd->id;
          }
      }

      if(empty(trim($_POST["review"]))){
        $rverror = "Please write a review.";
      } else{
        $posts->review = trim($_POST["review"]);
      }

      if(empty(trim($_POST["avgprice"]))){
        $perror = "Please enter price.";
      } elseif(!preg_match('/^[0-9]+$/', trim($_POST["avgprice"]))){
        $perror = "This field can only contain numbers.";
      } else{
        $posts->price = trim($_POST["avgprice"]);
      }

      if(isset($_POST["priceRadio"])){
        $posts->pricescore = trim($_POST["priceRadio"]);
      } else{
        $pserror = "Please select a price score.";
      }

      if(isset($_POST["nghRadio"])){
        $posts->neighbourhood = trim($_POST["nghRadio"]);
      } else{
        $nserror = "Please select a neighbourhood score.";
      }

      if(isset($_POST["cleanRadio"])){
        $posts->cleanliness = trim($_POST["cleanRadio"]);
      } else{
        $cserror = "Please select a cleanliness score.";
      }

      if(isset($_POST["mngtRadio"])){
        $posts->management = trim($_POST["mngtRadio"]);
      } else{
        $mserror = "Please select a management score.";
      }

      if(isset($_POST["culture"])){
        $posts->culture = trim($_POST["culture"]);
      } else{
        $posts->culture = 0;
      }

      //Verify errors
      if(empty($rnerror) && empty($rverror) && empty($perror) && empty($pserror) && empty($nserror) && empty($cserror) && empty($mserror)){
        $rev = new ManageReviews();
        $revid = $rev->CreateReview($posts);
        //Redirect to login after registration if user was added if not, send and alert.
        if($revid > 0){
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
  <div id="main">
    <!--Add alert if there's an error-->
    <?php if(!empty($error)) {
      echo '<div class="alert alert-danger alert-dismissible" id="alert">
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              <strong>Error! </strong>' .  $error . '
           </div>';
    } ?>
    <!--Form-->
    <div class="container rate-form">
      <h1>Add a review</h1>
      <form class="mt-5" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?id=". $id);?>">
        <div class="mb-3 mt-3 rate-wrap">
          <input type="hidden" name="propid" value="<?php echo $id; ?>" />
          <div class="form-group mb-3 mt-5">
            <span>To keep your anonymity, we have added a random name generator.</span>
            <input type="text" class="form-control <?php echo (!empty($rnerror)) ? 'is-invalid' : ''; ?>" id="rndname" placeholder="Random Username" name="rndname"
            value="<?php if(isset($_POST['rndname']) && empty($rnerror)) { echo $_POST['rndname']; } ?>" />
            <div class="invalid-feedback"><?php echo $rnerror; ?></div>
            <a class="btn btn-dark mt-3" onclick="getRndUsername()">Generate Name</a>
          </div>
          <div class="form-group mb-3 mt-5">
            <textarea class="form-control <?php echo (!empty($rverror)) ? 'is-invalid' : ''; ?>" rows="15" maxlength="200" id="review" placeholder="Write a review (max 200 characters)"
              name="review" value="<?php if(isset($_POST['review']) && empty($rverror)) { echo $_POST['review']; } ?>" required /></textarea>
            <div class="invalid-feedback"><?php echo $rverror; ?></div>
          </div>
          <div class="input-group mb-3 mt-5">
            <span class="input-group-text"> $ </span>
            <input type="text" id="avgprice" class="form-control <?php echo (!empty($perror)) ? 'is-invalid' : ''; ?>" placeholder="Enter price" name="avgprice"
            value="<?php if(isset($_POST['avgprice']) && empty($perror)) { echo $_POST['avgprice']; } ?>" required />
            <div class="invalid-feedback"><?php echo $perror; ?></div>
          </div>
          <h4>Rate your experience on the next categories</h4>
          <div class="mt-3">
              <h4>Price</h4>
              <input type="radio" class="btn-check <?php echo (!empty($pserror)) ? 'is-invalid' : ''; ?>" id="price1" value="1" name="priceRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="price1">1</label>
              <input type="radio" class="btn-check <?php echo (!empty($pserror)) ? 'is-invalid' : ''; ?>" id="price2" value="2" name="priceRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="price2">2</label>
              <input type="radio" class="btn-check <?php echo (!empty($pserror)) ? 'is-invalid' : ''; ?>" id="price3" value="3"name="priceRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="price3">3</label>
              <input type="radio" class="btn-check <?php echo (!empty($pserror)) ? 'is-invalid' : ''; ?>" id="price4" value="4" name="priceRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="price4">4</label>
              <input type="radio" class="btn-check <?php echo (!empty($pserror)) ? 'is-invalid' : ''; ?>" id="price5" value="5" name="priceRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="price5">5</label>
              <div class="invalid-feedback"><?php echo $pserror; ?></div>
            </div>
            <div class="mt-3">
              <h4>Neighbourhood</h4>
              <input type="radio" class="btn-check <?php echo (!empty($nserror)) ? 'is-invalid' : ''; ?>" id="ngh1" value="1"name="nghRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="ngh1">1</label>
              <input type="radio" class="btn-check <?php echo (!empty($nserror)) ? 'is-invalid' : ''; ?>" id="ngh2" value="2" name="nghRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="ngh2">2</label>
              <input type="radio" class="btn-check <?php echo (!empty($nserror)) ? 'is-invalid' : ''; ?>" id="ngh3" value="3" name="nghRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="ngh3">3</label>
              <input type="radio" class="btn-check <?php echo (!empty($nserror)) ? 'is-invalid' : ''; ?>" id="ngh4" value="4" name="nghRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="ngh4">4</label>
              <input type="radio" class="btn-check <?php echo (!empty($nserror)) ? 'is-invalid' : ''; ?>" id="ngh5" value="5" name="nghRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="ngh5">5</label>
              <div class="invalid-feedback"><?php echo $nserror; ?></div>
            </div>
            <div class="mt-3">
              <h4>Cleanliness</h4>
              <input type="radio" class="btn-check <?php echo (!empty($cserror)) ? 'is-invalid' : ''; ?>" id="clean1" value="1" name="cleanRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="clean1">1</label>
              <input type="radio" class="btn-check <?php echo (!empty($cserror)) ? 'is-invalid' : ''; ?>" id="clean2" value="2" name="cleanRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="clean2">2</label>
              <input type="radio" class="btn-check <?php echo (!empty($cserror)) ? 'is-invalid' : ''; ?>" id="clean3" value="3" name="cleanRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="clean3">3</label>
              <input type="radio" class="btn-check <?php echo (!empty($cserror)) ? 'is-invalid' : ''; ?>" id="clean4" value="4" name="cleanRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="clean4">4</label>
              <input type="radio" class="btn-check <?php echo (!empty($cserror)) ? 'is-invalid' : ''; ?>" id="clean5" value="5" name="cleanRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="clean5">5</label>
              <div class="invalid-feedback"><?php echo $cserror; ?></div>
            </div>
            <div class="mt-3">
              <h4>Management</h4>
              <input type="radio" class="btn-check <?php echo (!empty($mserror)) ? 'is-invalid' : ''; ?>" id="mngt1" value="1" name="mngtRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="mngt1">1</label>
              <input type="radio" class="btn-check <?php echo (!empty($mserror)) ? 'is-invalid' : ''; ?>" id="mngt2" value="2" name="mngtRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="mngt2">2</label>
              <input type="radio" class="btn-check <?php echo (!empty($mserror)) ? 'is-invalid' : ''; ?>" id="mngt3" value="3" name="mngtRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="mngt3">3</label>
              <input type="radio" class="btn-check <?php echo (!empty($mserror)) ? 'is-invalid' : ''; ?>" id="mngt4" value="4" name="mngtRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="mngt4">4</label>
              <input type="radio" class="btn-check <?php echo (!empty($mserror)) ? 'is-invalid' : ''; ?>" id="mngt5" value="5" name="mngtRadio" autocomplete="off" />
              <label class="btn btn-outline-warning" for="mngt5">5</label>
              <div class="invalid-feedback"><?php echo $mserror; ?></div>
            </div>
          </div>
          <div class="mt-5">
            <input type="checkbox" id="culture" name="culture" value="1" autocomplete="off">
            <label for="culture">Is this place cultural friendly?</label>
          </div>
          <div class="mt-5">
            <button type="submit" class="btn btn-primary">Submit Review</button>
            <a href="<?php echo 'details.php?id=' . $id; ?>" class="btn btn-danger">Cancel</a>
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
  <script>
    //Function to execute random name generator
    function getRndUsername() {
      var rndName = "<?php getName(); ?>"
      document.getElementById('rndname').value = rndName;
    }
  </script>
  </body>
</html>
