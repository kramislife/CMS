<?php
include("../includes/connection.php");

if (!isset($_SESSION['userImage'])) {
    $_SESSION['userImage'] = 'img/user.png';
}

// fetch image data from the session variable or database if available
if ($_SESSION['userImage'] !== null) {
    $userImage = $_SESSION['userImage'];
} else {
    $userImage = 'img/user.png';
}

$query = mysqli_query($conn, "SELECT UserImage FROM student_login WHERE UserID='" . $_SESSION['UserID'] . "'");
while ($row = mysqli_fetch_array($query)) {
    if ($row['UserImage'] !== null) {
        $userImage = 'data:image/jpeg;base64,' . base64_encode($row['UserImage']);
        $_SESSION['userImage'] = $userImage;
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
        $query = mysqli_query($conn, "SELECT FullName FROM student_login WHERE UserID='" . $_SESSION['UserID'] . "'");
        while ($row = mysqli_fetch_array($query)) {
        ?>
            <h5 class="centered"><?php echo htmlentities($row['FullName']); ?></h5>
        <?php } ?>


      <li class="mt">
        <a href="dashboard">
          <i class="fa fa-dashboard"></i>
          <span>Dashboard</span>
        </a>                
      </li>

      <li class="sub-menu">
        <a href="javascript:;">
          <i class="fa fa-cogs"></i>    
          <span>Manage Account</span>
        </a>
        <ul class="sub">
          <li><a href="profile">My Profile</a></li>
          <li><a href="change-password">Change Password</a></li>
        </ul>
      </li>

      <li class="sub-menu">
        <a href="naive">
          <i class="fa fa-paperclip"></i>
          <span>Create Complaint</span>
        </a>
      </li>

      <li class="sub-menu">
        <a href="monitor-complaint">
          <i class="fa fa-history"></i>
          <span>Monitor Complaint</span>
        </a>
      </li>
        
      <li class="mt">
      <a href="log-out">
        <i class="fa fa-sign-out"></i>
        <span><b>Sign-out</b></span>
      </a>
      </li> 

    </ul>
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
      xhr.open("POST", "../Student/update-pic.php");
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
      xhr2.open("GET", "../Student/update-pic.php");
      xhr2.send();
    };
    reader.readAsDataURL(file);
  });

  // simulate a click on the file input element to open the file explorer dialog
  input.click();
}

</script>


</aside>




