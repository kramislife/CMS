<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");
require '../vendor/autoload.php';

use Phpml\Classification\NaiveBayes;

$errormsg = '';
$successmsg = '';


$trainingData = [
        // Discrimination
        ['complaint' => 'discrimination', 'type' => 'Discrimination'],
        ['complaint' => 'I experienced discrimination based on my gender', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination is prevalent in the workplace', 'type' => 'Discrimination'],
        ['complaint' => 'I faced discrimination due to my religious beliefs', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination based on age is an issue in our society', 'type' => 'Discrimination'],
        ['complaint' => 'I witnessed racial discrimination in a public place', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination is causing divisions in our community', 'type' => 'Discrimination'],
        ['complaint' => 'I felt discriminated against because of my disability', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination in educational institutions is a serious problem', 'type' => 'Discrimination'],
        ['complaint' => 'I observed gender-based discrimination at a social gathering', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination is affecting the mental health of individuals', 'type' => 'Discrimination'],
        ['complaint' => 'I encountered discrimination based on my nationality', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination is perpetuated through certain media channels', 'type' => 'Discrimination'],
        ['complaint' => 'I faced discrimination at a restaurant due to my appearance', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination is hindering social progress and inclusivity', 'type' => 'Discrimination'],
        ['complaint' => 'I witnessed discriminatory remarks against a particular ethnicity', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination based on economic status is a significant concern', 'type' => 'Discrimination'],
        ['complaint' => 'I experienced discrimination during a job interview', 'type' => 'Discrimination'],
        ['complaint' => 'Discrimination is prevalent in the online environment', 'type' => 'Discrimination'],
        ['complaint' => 'I faced discrimination in accessing public services', 'type' => 'Discrimination'],

        // Academic Issues
        ['complaint' => 'academics', 'type' => 'Academic Issue'],
        ['complaint' => 'I am struggling with my math assignments', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic pressure is affecting my mental health', 'type' => 'Academic Issue'],
        ['complaint' => 'I find it hard to concentrate during lectures', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic workload is overwhelming', 'type' => 'Academic Issue'],
        ['complaint' => 'I need help with improving my study habits', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic competition is creating a stressful environment', 'type' => 'Academic Issue'],
        ['complaint' => 'I feel anxious about taking exams', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic expectations are too high', 'type' => 'Academic Issue'],
        ['complaint' => 'I struggle to manage my time effectively for studies', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic feedback is inadequate', 'type' => 'Academic Issue'],
        ['complaint' => 'I fear failure and its impact on my future', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic resources at the library are insufficient', 'type' => 'Academic Issue'],
        ['complaint' => 'I have difficulty understanding certain subjects', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic advisors are not readily available', 'type' => 'Academic Issue'],
        ['complaint' => 'I feel demotivated in my academic journey', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic grading criteria are unclear', 'type' => 'Academic Issue'],
        ['complaint' => 'I struggle to balance academics with extracurricular activities', 'type' => 'Academic Issue'],
        ['complaint' => 'Academic lectures are monotonous and unengaging', 'type' => 'Academic Issue'],
        ['complaint' => 'I face challenges in adapting to the new learning environment', 'type' => 'Academic Issue'],

        // Medical Report
        ['complaint' => 'medical report', 'type' => 'Medical Report'],
        ['complaint' => 'A student had an accident in the chemistry lab', 'type' => 'Medical Report'],
        ['complaint' => 'I need to submit a medical certificate', 'type' => 'Medical Report'],
        ['complaint' => 'A student is unwell and unable to attend classes', 'type' => 'Medical Report'],
        ['complaint' => 'I had to visit the school nurse due to a health issue', 'type' => 'Medical Report'],
        ['complaint' => 'A medical emergency occurred during a school event', 'type' => 'Medical Report'],
        ['complaint' => 'I am suffering from a contagious illness and need to inform the school', 'type' => 'Medical Report'],
        ['complaint' => 'A student fainted and needs medical attention', 'type' => 'Medical Report'],
        ['complaint' => 'I have been advised bed rest by my doctor and cannot attend lectures', 'type' => 'Medical Report'],
        ['complaint' => 'A student is experiencing allergic reactions and needs medical care', 'type' => 'Medical Report'],
        ['complaint' => 'I injured myself during a physical education class', 'type' => 'Medical Report'],
        ['complaint' => 'A student needs medical accommodations for a chronic condition', 'type' => 'Medical Report'],
        ['complaint' => 'I lost my medical documents and require duplicates for school records', 'type' => 'Medical Report'],
        ['complaint' => 'A student is recovering from surgery and needs academic support', 'type' => 'Medical Report'],
        ['complaint' => 'I am facing side effects from medication prescribed by the school doctor', 'type' => 'Medical Report'],
        ['complaint' => 'A student has developed flu-like symptoms and should be monitored', 'type' => 'Medical Report'],
        ['complaint' => 'I need a medical clearance before participating in school sports', 'type' => 'Medical Report'],
        ['complaint' => 'A student with a medical condition requires special dietary arrangements', 'type' => 'Medical Report'],
        ['complaint' => 'I accidentally ingested something harmful in the school cafeteria', 'type' => 'Medical Report'],
        ['complaint' => 'A students pre-existing medical condition worsened during school hours', 'type' => 'Medical Report'],
 
        // Facilities and Infrastructure Problems
        ['complaint' => 'The school facilities are not wheelchair-accessible', 'type' => 'Facility Issue'],
        ['complaint' => 'There is a leakage in the school roof', 'type' => 'Facility Issue'],
        ['complaint' => 'The school restroom is in terrible condition', 'type' => 'Facility Issue'],
        ['complaint' => 'The classroom lights are flickering and need replacement', 'type' => 'Facility Issue'],
        ['complaint' => 'There are broken chairs in the school auditorium', 'type' => 'Facility Issue'],
        ['complaint' => 'The air conditioning system in the library is not functioning', 'type' => 'Facility Issue'],
        ['complaint' => 'The schools water supply is interrupted', 'type' => 'Facility Issue'],
        ['complaint' => 'There are cracks in the walls of the art room', 'type' => 'Facility Issue'],
        ['complaint' => 'The school playground equipment is damaged and needs repair', 'type' => 'Facility Issue'],
        ['complaint' => 'There is a pest infestation in the cafeteria', 'type' => 'Facility Issue'],
        ['complaint' => 'The music room floor is slippery and poses a safety risk', 'type' => 'Facility Issue'],
        ['complaint' => 'The schools internet connection is unreliable', 'type' => 'Facility Issue'],
        ['complaint' => 'There is a plumbing issue in the science laboratory', 'type' => 'Facility Issue'],
        ['complaint' => 'The schools fence is damaged and needs immediate repair', 'type' => 'Facility Issue'],
        ['complaint' => 'There is a shortage of desks in the classrooms', 'type' => 'Facility Issue'],
        ['complaint' => 'The school building has cracked windows that need fixing', 'type' => 'Facility Issue'],
        ['complaint' => 'There are no proper storage facilities for students to keep their belongings', 'type' => 'Facility Issue'],
        ['complaint' => 'The sports field has uneven ground, posing a risk of injury', 'type' => 'Facility Issue'],
        ['complaint' => 'There is no proper lighting in the school parking lot', 'type' => 'Facility Issue'],
        ['complaint' => 'The schools fire alarm system is faulty and needs inspection', 'type' => 'Facility Issue'],

        // Safety and Security Concerns
        ['complaint' => 'I witnessed a fight between students in the hallway', 'type' => 'Safety Concerns'],
        ['complaint' => 'There is a lack of security personnel on school premises', 'type' => 'Safety Concerns'],
        ['complaint' => 'The school gate is broken, posing a security risk', 'type' => 'Safety Concerns'],
        ['complaint' => 'I feel unsafe while walking home from school', 'type' => 'Safety Concerns'],
        ['complaint' => 'The schools CCTV cameras are not functioning', 'type' => 'Safety Concerns'],
        ['complaint' => 'There have been unauthorized individuals seen on school grounds', 'type' => 'Safety Concerns'],
        ['complaint' => 'The fire exits in the school are blocked', 'type' => 'Safety Concerns'],
        ['complaint' => 'There is a lack of proper lighting in the stairwells', 'type' => 'Safety Concerns'],
        ['complaint' => 'The schools emergency response plan is unclear', 'type' => 'Safety Concerns'],
        ['complaint' => 'I overheard students discussing potential violence in school', 'type' => 'Safety Concerns'],
        ['complaint' => 'The school does not have a visitor sign-in process', 'type' => 'Safety Concerns'],
        ['complaint' => 'I found a suspicious package on school premises', 'type' => 'Safety Concerns'],
        ['complaint' => 'There is a lack of proper fencing around the school', 'type' => 'Safety Concerns'],
        ['complaint' => 'I witnessed bullying incidents in the school bathroom', 'type' => 'Safety Concerns'],
        ['complaint' => 'The school does not conduct regular safety drills', 'type' => 'Safety Concerns'],
        ['complaint' => 'I feel uneasy due to the presence of unauthorized persons on campus', 'type' => 'Safety Concerns'],
        ['complaint' => 'The schools front door does not lock properly', 'type' => 'Safety Concerns'],
        ['complaint' => 'There is a lack of security measures during school events', 'type' => 'Safety Concerns'],
        ['complaint' => 'I heard rumors of a planned disruption during the upcoming school assembly', 'type' => 'Safety Concerns'],
        ['complaint' => 'The school does not have a clear protocol for handling emergencies', 'type' => 'Safety Concerns'],

];

function preprocess($complaint)
{
    // Preprocess the complaint (e.g., remove punctuation and convert to lowercase)
    $complaint = preg_replace("/[^a-zA-Z ]+/", "", $complaint);
    $complaint = strtolower($complaint);

    return $complaint;
}

// Function to train the Naive Bayes classifier
function trainNaiveBayes($trainingData)
{
    $classCounts = [];
    $classFeatureCounts = [];
    $vocabulary = [];

    foreach ($trainingData as $data) {
        $complaint = preprocess($data['complaint']);
        $type = $data['type'];

        if (!isset($classCounts[$type])) {
            $classCounts[$type] = 0;
        }
        $classCounts[$type]++;

        $features = explode(" ", $complaint);
        foreach ($features as $feature) {
            if (!isset($classFeatureCounts[$type][$feature])) {
                $classFeatureCounts[$type][$feature] = 0;
            }
            $classFeatureCounts[$type][$feature]++;

            // Collect all unique words in the vocabulary
            if (!in_array($feature, $vocabulary)) {
                $vocabulary[] = $feature;
            }
        }
        $types = []; 
          // Collect all complaint types
          if (!in_array($type, $types)) {
            $types[] = $type;
        }
    }

    $priorProbabilities = [];
    $totalComplaints = count($trainingData);
    foreach ($trainingData as $data) {
        $type = $data['type'];
    
        if (!isset($priorProbabilities[$type])) {
            $priorProbabilities[$type] = 0;
        }
        $priorProbabilities[$type]++;
    }
    
    // Calculate conditional probabilities for each word in the vocabulary
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

function classifyComplaint($complaintDetails, $trainedModel)
{
    global $types; // Access the global $types variable
    $complaint = preprocess($complaintDetails);
    $priorProbabilities = $trainedModel['priorProbabilities'];
    $conditionalProbabilities = $trainedModel['conditionalProbabilities'];

    $words = explode(" ", $complaint);

    $classProbabilities = [];
    foreach ($priorProbabilities as $type => $priorProbability) {
        $classProbability = $priorProbability;
        foreach ($words as $word) {
            if (isset($conditionalProbabilities[$type][$word])) {
                $classProbability *= $conditionalProbabilities[$type][$word];
            } else {
                // If the word is not in the vocabulary, use a default probability of 1/N
                $classProbability *= (1 / count($conditionalProbabilities[$type]));
            }
        }
        $classProbabilities[$type] = $classProbability;
    }

    // Find the class with the highest probability
    arsort($classProbabilities);
    $classifiedType = key($classProbabilities);

    // Check if the complaint matches any of the other types
    foreach ($types as $type) {
        if ($type != 'discrimination' && $type != $classifiedType) {
            if (preg_match('/' . $type . '/i', $complaint)) {
                $classifiedType = $type;
                break;
            }
        }
    }

    return $classifiedType;
}

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

        // Insert data into the database
        $newComplaint = mysqli_real_escape_string($conn, $_POST['complaintdetails']);

        // Classify the complaint type using the trained model
        $classifiedType = classifyComplaint($_POST['complaintdetails'], $trainedModel);
        $complaintFile = $_FILES["compfile"]["name"];
        $complaintFileTemp = $_FILES["compfile"]["tmp_name"];
        $complaintFilePath = "../complaintDocs/" . $complaintFile;

        move_uploaded_file($complaintFileTemp, $complaintFilePath);

        $stmt = mysqli_prepare($conn, "INSERT INTO complaints (ComplaintID, ComplainantName, Email, ComplaintType, Others, ComplaintDetails, ComplaintFile) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $Others = '';
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
            <?php if($successmsg) { ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <?php echo htmlentities($successmsg); ?>
            </div>
            <?php } ?>

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
                <?php } ?>


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
                    <div class="col-sm-12 d-flex justify-content-center" style="padding-top: 15px;">
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
            </form>
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




