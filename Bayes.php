<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");
require '../vendor/autoload.php';

use Phpml\Classification\NaiveBayes;

$errormsg = '';

$trainingData = [

     // Academic issues complaints (English)
     ['keywords' => [
        'homework',
        'exams',
        'assignments',
        'study',
        'lecture',
        'note',
        'coursework',
        'time management',
        'attendance',
        'course',
        'requirements',
        'grading',
        'academic',
        'performance',
        'pressure',
        'workload',
        'research',
        'papers',
        'group',
        'projects',
        'learning',
        'online',
        'classes',
        'classroom',
        'e-learning',
        'educational',
        'education',
        'participation',
        'student',
        'noise',
        'tutoring',
        'academic',
        'reading',
        'mathematics',
        'test',
        'procrastination',
        'time-management',
        'note-taking',
        'problem-solving',
        'peer-pressure',
        'library',
        'academic',
        'plagiarism',
        'degree',
        'graduation',
        'deadlines',
    ], 'type' => 'academic issues', 'language' => 'english'],
        // Discrimination complaints (English)
        ['keywords' => [
            'bullied',
            'harassment',
            'prejudice',
            'inequality',
            'bias',
            'stereotyping',
            'microaggressions',
            'bullying',
            'intolerance',
            'marginalization',
            'inclusion',
            'hate',
            'crime',
            'stigma',
            'victimization',
            'oppression',
            'xenophobia',
            'heterosexism',
            'ableism',
            'ageism',
            'racism',
            'sexism',
            'homophobia',
            'transphobia',
            'islamophobia',
            'antisemitism',
            'discriminate',
            'discrimination',
            'race',
            'rights',
            'diversity',
            'inclusion',
            'intersectionality',
            'allyship',
            'anti-discrimination',
        ], 'type' => 'discrimination', 'language' => 'english'],

         // Medical report complaints (English)
    ['keywords' => [
        'nurse',
        'hospital',
        'emergency',
        'surgery',
        'prescription',
        'treatment',
        'diagnosis',
        'patient',
        'injury',
        'recovery',
        'pain',
        'medication',
        'lab', 
        'therapy',
        'patient',
        'medical',
        'sick',
        'health',
        'symptoms',
        'allergies',
        'chronic',
        'mental',
        'health',
        'psychiatrist',
        'psychologist',
        'counseling',
        'medical',
        'clinic',
    ], 'type' => 'medical report', 'language' => 'english'],
        
           
 
                // Facility and infrastructure complaints (English)
                ['keywords' => [
                    'classrooms',
                    'laboratories',
                    'halls',
                    'areas',
                    'campus',
                    'maintenance',
                    'broken',
                    'facilities',
                    'heating',
                    'cooling',
                    'plumbing',
                    'electrica',
                    'internet',
                    'Wi-Fi',
                    'furniture',
                    'equipment',
                    'services',
                    'parking',
                    'bicycle racks',
                    'racks',
                    'crowded',
                    'hallways',
                    'exits',
                    'fire',
                    'safety',
                    'building',
                    'security',
                    'cafeteria',
                    'cleaning',
                    'campus',
                    'lighting',
                    'library',
                    'rooms',             
                    'ventilation',
                    'indoor',
                    'patrols',
                    'escorts',
                    'surveillance',
                ], 'type' => 'facility and infrastructure', 'language' => 'english'],
            
                // Other complaint types and keywords can be added here as well...
            ];
            

// Singularize function to convert plural words to singular form
function singularize($word)
{
    // Implement singularization rules here if needed
    return $word;
}

// Function to preprocess the complaint data for the English language
function preprocess($complaint, $language)
{
    if ($language === 'filipino') {
        // Preprocess the Filipino complaint (e.g., remove diacritics and convert to lowercase)
        // ... (implement preprocessing for Filipino language)
    } else {
        // Preprocess the English complaint (e.g., remove punctuation and convert to lowercase)
        $complaint = preg_replace("/[^a-zA-Z ]+/", "", $complaint);
        $complaint = strtolower($complaint);

        // Remove stop words
        $stopWords = ['the', 'was', 'because', 'and', 'is', 'of', 'it', 'in', 'to', 'an', 'at', 'on', 'for', 'as', 'with', 'a', 'my', 'i', 'me'];
        $words = explode(" ", $complaint);
        $filteredWords = array_diff($words, $stopWords);

        // Handle word variations (e.g., singular/plural forms)
        $filteredWords = array_map('singularize', $filteredWords);

        // Remove duplicate words and rejoin to form the preprocessed complaint
        $complaint = implode(" ", array_unique($filteredWords));
    }

    return $complaint;
}

// Function to train the Naive Bayes classifier
function trainNaiveBayes($trainingData)
{
    $classCounts = [];
    $classFeatureCounts = [];
    $vocabulary = [];

    foreach ($trainingData as $data) {
        $complaint = preprocess($data['complaint'], $data['language']);
        $type = $data['type'];

        // Count the occurrences of each class
        if (!isset($classCounts[$type])) {
            $classCounts[$type] = 0;
        }
        $classCounts[$type]++;

        // Count the occurrences of features (words) for each class
        $features = explode(" ", $complaint);
        foreach ($features as $feature) {
            if (!isset($classFeatureCounts[$type][$feature])) {
                $classFeatureCounts[$type][$feature] = 0;
            }
            $classFeatureCounts[$type][$feature]++;

            // Collect the unique words in the vocabulary
            if (!in_array($feature, $vocabulary)) {
                $vocabulary[] = $feature;
            }
        }
    }

    // Calculate prior probabilities
    $priorProbabilities = [];
    $totalComplaints = count($trainingData);
    foreach ($classCounts as $type => $count) {
        $priorProbabilities[$type] = $count / $totalComplaints;
    }

    // Calculate conditional probabilities
    $conditionalProbabilities = [];
    $vocabularySize = count($vocabulary);
    foreach ($classFeatureCounts as $type => $features) {
        $totalFeatures = array_sum($features);
        foreach ($vocabulary as $word) {
            $count = isset($features[$word]) ? $features[$word] : 0;
            $probability = ($count + 1) / ($totalFeatures + $vocabularySize);
            $conditionalProbabilities[$type][$word] = $probability;
        }
    }

    return ['priorProbabilities' => $priorProbabilities, 'conditionalProbabilities' => $conditionalProbabilities];
}

// Function to classify a new complaint
function classifyComplaint($complaintDetails, $trainedModel, $language)
{
    $complaint = preprocess($complaintDetails, $language);
 
      // Iterate through each set of keywords and classify the complaint if a match is found
      foreach ($trainedModel as $data) {
        $keywords = $data['keywords'];
        $complaintType = $data['type'];

        // Check if any of the complaint keywords match the keywords in the current set
        $words = explode(" ", $complaint);
        $isMatch = false;

        foreach ($words as $word) {
            if (in_array($word, $keywords)) {
                $isMatch = true;
                break;
            }
        }

        if ($isMatch) {
            return $complaintType; // Return the complaint type if a match is found
        }
    }

    return 'not_classified'; // Return 'not_classified' if no match is found in any group
}

// Train the Naive Bayes classifier
$trainedModel = trainNaiveBayes($trainingData);

// Handle form submission
if (isset($_POST['submit'])) {
    $Username = $_SESSION['UserID'];

    $stmt = mysqli_prepare($conn, "SELECT UserID, FullName, Email FROM student_login WHERE UserID = ?");
    mysqli_stmt_bind_param($stmt, "s", $Username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $UserID = $row['UserID'];
        $complainantName = $row['FullName'];
        $complainantEmail = $row['Email'];

        // Classify the complaint type using the trained model
        $classifiedType = classifyComplaint($_POST['complainttype'], $trainedModel, $language);

        // Insert data into the database
        $newComplaint = mysqli_real_escape_string($conn, $_POST['complaintdetails']);
        $complaintFile = $_FILES["compfile"]["name"];
        $complaintFileTemp = $_FILES["compfile"]["tmp_name"];
        $complaintFilePath = "../complaintDocs/" . $complaintFile;

        move_uploaded_file($complaintFileTemp, $complaintFilePath);

        $stmt = mysqli_prepare($conn, "INSERT INTO complaints (ComplaintID, ComplainantName, Email, ComplaintType, Others, ComplaintDetails, ComplaintFile) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $Others = ''; // Assuming there's no 'Others' field in the form, set it to an empty string.
        mysqli_stmt_bind_param($stmt, "sssssss", $UserID, $complainantName, $complainantEmail, $classifiedType, $Others, $newComplaint, $complaintFile);
        $query = mysqli_stmt_execute($stmt);

        if ($query) {
            $_SESSION['complaintRegistered'] = true;
            header("Location: naive");
            exit();
        } else {
            $errormsg = "It looks like we have a problem in the Database. Please Try Again Later!";
        }
    } else {
        $errormsg = "Invalid UserID!";
    }
} else {
    if (isset($_SESSION['complaintRegistered']) && $_SESSION['complaintRegistered'] === true) {
        $successmsg = "Complaint Registered Successfully!";
        unset($_SESSION['complaintRegistered']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS | Create Complaint</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="../assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="../assets/lineicons/style.css">    
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/style-responsive.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_package_v0.16/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_package_v0.16/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_package_v0.16/favicon-16x16.png">
    <link rel="manifest" href="../favicon_package_v0.16/site.webmanifest">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script> 
    <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">  
  </head>
  <body>

<section id="container">
    <?php include("../Student/sidebar.php"); ?>
    <?php include("../includes/header.php"); ?>

    <section id="main-content">
        <section class="wrapper">
            <h4 style="padding-bottom:10px; padding-top:10px; font-weight:bolder; font-family: 'Times New Roman', Times, serif;">COMPLAINT REGISTRATION</h4>
            <div class="row mt">
                <div class="col-lg-12">
                    <div class="form-panel">
                           <?php if($successmsg)
            {?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <?php echo htmlentities($successmsg);?></div>
            <?php }?>

            <?php if($errormsg) { ?>
                  <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo htmlentities($errormsg); ?>
                  </div>
                <?php } ?>

                <form class="form-horizontal style-form" method="post" name="complaint" enctype="multipart/form-data">
                  <h4 class="mb"><i class="fa fa-paste"></i>&nbsp;&nbsp;Complaint Form</h4>

                  <?php
                      $query = mysqli_query($conn, "SELECT * FROM student_login WHERE UserID='".$_SESSION['UserID']."'");
                      while($row = mysqli_fetch_array($query)) {
                      ?>
                      <div class="form-group">
                        <label class="col-sm-2 col-form-label"><strong style="color: black">Name</strong></label>
                        <div class="col-sm-6">
                          <input type="text" name="Name" class="form-control" value="<?php echo htmlentities($row['FullName']); ?>" disabled>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2 col-form-label"><strong style="color: black">Email</strong></label>
                        <div class="col-sm-6">
                          <input type="email" name="email" class="form-control" required value="<?php echo htmlentities($row['Email']); ?>" disabled>
                        </div>
                      </div>
                      <?php
                      }
                      ?>


                  <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label"><strong style="color: black">Complaint Details (max 2000 words)</strong></label>
                    <div class="col-sm-6">
                      <textarea name="complaintdetails" required="required" cols="10" rows="10" class="form-control" maxlength="2000"></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label"> <strong style="color: black">Complaint Related Docs: <b>Supported formats: JPG, PDF, MP4, MP3 (Audio and Video Format must be under 60 seconds only)</b></strong></label>
                    <div class="col-sm-6">
                    <input type="file" name="compfile" class="form-control" value=""  accept=".jpg, .jpeg, .png, .pdf, .mp4, .mp3" multiple onchange="validateFiles(this)">
                      <p id="errorMsg" style="color: red;"></p>
                    </div>
                  </div>   
                  
                  <div class="form-group text-center">
                    <div class="col-sm-12 d-flex justify-content-center"  style="padding-top: 15px;">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reviewModal" onclick="reviewForm()">Review & Submit</button>
                </div>

              <!-- Modal -->
              <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div style="background-color: #8f0303;" class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Complaint Preview</h4>                 
                </div>
                <div class="modal-body">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><strong style="color: black">Name:</strong></label>
                    <div class="col-sm-8">
                      <p id="reviewName"></p>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><strong style="color: black">Email:</strong></label>
                    <div class="col-sm-8">
                      <p id="reviewEmail"></p>
                    </div>
                  </div>

                
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Details:</strong></label>
                    <div class="col-sm-8" style="text-align: justify;">
                      <p id="reviewComplaintDetails" style="word-wrap: break-word;"></p>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Related Docs:</strong></label>
                    <div class="col-sm-8">
                      <p id="reviewCompFile"></p>
                    </div>
                  </div>

                  <div class="form-group row">
                  <div class="col-sm-12">
                    <div class="form-check">
                    <label class="form-check-label" for="dataProtectionCheckbox" style="color: black;">
                      <input class="form-check-input" type="checkbox" id="dataProtectionCheckbox">
                        By submitting this complaint, you agree that your personal data will be used in accordance with our privacy policy.
                      </label>   
                    </div>
                  </div>
                </div>
                
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" name="submit" class="btn btn-danger" id="submitComplaintBtn" disabled>Submit</button>
                </div>
              </div>
            </div>
          </div>
        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
</section>
<script>
  
  function reviewForm() {
    var form = document.forms['complaint'];
    document.getElementById('reviewName').textContent = form.elements['Name'].value;
    document.getElementById('reviewEmail').textContent = form.elements['email'].value; 
    document.getElementById('reviewComplaintDetails').textContent = form.elements['complaintdetails'].value;

    var compFileInput = form.elements['compfile'];
    var reviewCompFile = document.getElementById('reviewCompFile');
    var compFile = compFileInput.files[0];

    if (compFile) {
      reviewCompFile.textContent = compFile.name;
    } else {
      reviewCompFile.textContent = "No file chosen";
    }
  }

  
  $(document).ready(function() {
  // Enable/disable submit button based on checkbox
  $('#dataProtectionCheckbox').change(function() {
    if ($(this).is(':checked')) {
      $('#submitComplaintBtn').prop('disabled', false);
    } else {
      $('#submitComplaintBtn').prop('disabled', true);
    }
  });
});


function validateFiles(input) {
  var files = input.files;

  for (var i = 0; i < files.length; i++) {
    var file = files[i];
    var extension = file.name.split('.').pop().toLowerCase();
    var video = document.createElement('video');
    var audio = document.createElement('audio');
    var supportedVideoTypes = ['video/mp4'];
    var supportedAudioTypes = ['audio/mpeg'];
    var supportedImageTypes = ['image/jpeg', 'image/jpg'];
    var supportedPdfTypes = ['application/pdf'];

    if (supportedVideoTypes.includes(file.type) && extension === 'mp4') {
      video.src = URL.createObjectURL(file);
      video.addEventListener('loadedmetadata', function () {
        URL.revokeObjectURL(video.src);
        var duration = video.duration;
        if (duration <= 60) {
          // Valid video
          console.log('Valid video: ' + file.name);
          document.getElementById('errorMsg').textContent = ''; // Clear the error message
        } else {
          // Invalid video
          input.value = '';
          document.getElementById('errorMsg').textContent =
            'Invalid video: ' +
            file.name +
            '. Video must be 60 seconds or less. Please try again!';
          console.log('Invalid video: ' + file.name);
        }
      });
    } else if (supportedAudioTypes.includes(file.type) && extension === 'mp3') {
      audio.src = URL.createObjectURL(file);
      audio.addEventListener('loadedmetadata', function () {
        URL.revokeObjectURL(audio.src);
        var duration = audio.duration;
        if (duration <= 60) {
          // Valid audio
          console.log('Valid audio: ' + file.name);
          document.getElementById('errorMsg').textContent = ''; // Clear the error message
        } else {
          // Invalid audio
          input.value = '';
          document.getElementById('errorMsg').textContent =
            'Invalid audio: ' +
            file.name +
            '. Audio must be 60 seconds or less. Please try again!';
          console.log('Invalid audio: ' + file.name);
        }
      });
    } else if (supportedImageTypes.includes(file.type) && (extension === 'jpg' || extension === 'jpeg')) {
      // Valid image (jpg/jpeg)
      console.log('Valid image: ' + file.name);
      document.getElementById('errorMsg').textContent = ''; // Clear the error message for a valid image
    } else if (supportedPdfTypes.includes(file.type) && extension === 'pdf') {
      // Valid PDF
      console.log('Valid PDF: ' + file.name);
      document.getElementById('errorMsg').textContent = ''; // Clear the error message for a valid PDF
    } else {
      // Invalid file
      input.value = '';
      document.getElementById('errorMsg').textContent =
        'Invalid file: ' +
        file.name +
        '. Only JPG, PDF, MP4, and MP3 (60 seconds or less) files are allowed. Please try again!';
      console.log('Invalid file: ' + file.name);
    }
  }
}

// Add an event listener to clear the error message when a valid file is selected
document.getElementById('fileInput').addEventListener('change', function () {
  document.getElementById('errorMsg').textContent = '';
});
</script>

  <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="../assets/js/jquery.scrollTo.min.js"></script>
    <script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="../assets/js/common-scripts.js"></script>
    <script src="../assets/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script src="../assets/js/bootstrap-switch.js"></script>
	<script src="../assets/js/jquery.tagsinput.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap-daterangepicker/date.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
	<script src="../assets/js/form-component.js"></script>
</body>
</html>



