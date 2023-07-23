<?php
include ("../includes/connection.php");

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['userImage'])) {
  $imageData = file_get_contents($_FILES['userImage']['tmp_name']);
  $username = $_SESSION['login'];

  // update database with new image
  $query = "UPDATE faculty_login SET UserImage=? WHERE Username=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $imageData, $username);
  $stmt->execute();
  $stmt->close();
}

$userImage = '/img/user.png';

// fetch image data from database if available
$query = mysqli_query($conn, "SELECT UserImage FROM faculty_login WHERE Username='" . $_SESSION['login'] . "'");
while ($row = mysqli_fetch_array($query)) {
  if ($row['UserImage'] !== null) {
    $userImage = 'data:image/jpeg;base64,' . base64_encode($row['UserImage']);
  }
}
?>

<aside>
  <div id="sidebar" class="nav-collapse">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">

      <p class="centered">
        <img src="<?php echo $userImage ?>" class="img-circle" width="120" height="120" id="profile-pic" onclick="changeProfilePic()">            
      </p>
      <?php
        $query = mysqli_query($conn, "SELECT FullName FROM faculty_login where Username='" . $_SESSION['login'] . "'");
        while ($row = mysqli_fetch_array($query)) {
      ?>
        <h5 class="centered"><?php echo htmlentities($row['FullName']); ?></h5>
      <?php } ?>

      <li class="mt">
        <a href="dashboard.php">
          <i class="fa fa-dashboard"></i>
          <span>Dashboard</span>
        </a>                
      </li>

      <li class="sub-menu">
        <a href="javascript:;">
          <i class="fa fa-cogs"></i>    
          <span>Account Setting</span>
        </a>
        <ul class="sub">
          <li><a href="profile.php">Profile</a></li>
          <li><a href="change-password.php">Change Password</a></li>
        </ul>
      </li>

      <li class="sub-menu">
        <a href="register-complaint.php">
          <i class="fa fa-paperclip"></i>
          <span>Create Complaint</span>
        </a>
      </li>

      <li class="sub-menu">
        <a href="complaint-history.php">
          <i class="fa fa-history"></i>
          <span>Complaint History</span>
        </a>
      </li>
      <li class="sub-menu">
      <a href="../Student/Portal.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
        <i class="fas fa-power-off me-2"></i>
        <span>Logout</span>
      </a>
      </li>

    </ul>
    <!-- sidebar menu end-->
  </div>

  <script>
  function changeProfilePic() {
  // create a file input element
  var input = document.createElement("input");
  input.type = "file";
  input.accept = "image/*";

  // add an event listener to handle file selection
  input.addEventListener("change", function(event) {
    // get the selected file
    var file = event.target.files[0];

    // create a file reader to read the file as a data URL
    var reader = new FileReader();
    reader.onload = function(event) {
      // set the new image as the profile picture
      var profilePic = document.getElementById("profile-pic");
      profilePic.src = event.target.result;

      // send the new image data to the server and save it to the database
      var formData = new FormData();
      formData.append("userImage", file);
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "../Faculty/fetchpic.php");
      xhr.send(formData);

      // fetch the updated image data from the server and update the image on the page
      var xhr2 = new XMLHttpRequest();
      xhr2.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var data = JSON.parse(this.responseText);
          if (data.userImage !== null) {
            profilePic.src = 'data:' + data.imageType + ';base64,' + data.userImage;
          }
        }
      };
      xhr2.open("GET", "../Faculty/fetchpic.php");
      xhr2.send();
    };
    reader.readAsDataURL(file);
  });

  // simulate a click on the file input element to open the file explorer dialog
  input.click();
}


  
</script>

</aside>







