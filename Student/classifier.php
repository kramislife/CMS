<?php

// Sample training data (complaints and their corresponding types)
$trainingData = [
    ['complaint' => 'I experienced discrimination because of my race', 'type' => 'discrimination', 'language' => 'english'],
    ['complaint' => 'Naranasan ko ang diskriminasyon dahil sa aking lahi', 'type' => 'discrimination', 'language' => 'filipino'],
    ['complaint' => 'I am struggling with my academics', 'type' => 'academic issues', 'language' => 'english'],
    ['complaint' => 'Nahihirapan ako sa aking pag-aaral', 'type' => 'academic issues', 'language' => 'filipino'],
    ['complaint' => 'I witnessed teacher misconduct in the classroom', 'type' => 'teacher misconduct', 'language' => 'english'],
    ['complaint' => 'Nakakita ako ng di-maayos na pag-uugali ng guro sa silid-aralan', 'type' => 'teacher misconduct', 'language' => 'filipino'],
    ['complaint' => 'The school facilities are in poor condition', 'type' => 'facilities and infrastructure problems', 'language' => 'english'],
    ['complaint' => 'Ang mga pasilidad ng paaralan ay nasa masamang kalagayan', 'type' => 'facilities and infrastructure problems', 'language' => 'filipino'],
    ['complaint' => 'I feel unsafe and insecure in the school premises', 'type' => 'safety and security concerns', 'language' => 'english'],
    ['complaint' => 'Nararamdaman ko ang kawalan ng kaligtasan at katiyakan sa loob ng paaralan', 'type' => 'safety and security concerns', 'language' => 'filipino'],
    // Add more training data...
];

// Function to preprocess the complaint data
function preprocess($complaint, $language)
{
    if ($language === 'filipino') {
        // Preprocess the Filipino complaint (e.g., remove diacritics and convert to lowercase)
        // ... (implement preprocessing for Filipino language)
    } else {
        // Preprocess the English complaint (e.g., remove punctuation and convert to lowercase)
        $complaint = preg_replace("/[^a-zA-Z ]+/", "", $complaint);
        $complaint = strtolower($complaint);
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
                // If the word is not present in the training data, use a small default probability
                $classProbability *= (1 / count($conditionalProbabilities[$type]));
            }
        }
        $classProbabilities[$type] = $classProbability;
    }

    // Find the class with the highest probability
    arsort($classProbabilities);
    $classifiedType = key($classProbabilities);

    return $classifiedType;
}

// Train the Naive Bayes classifier
$trainedModel = trainNaiveBayes($trainingData);

// Example usage - classify a new complaint
$newComplaint = 'academic';
$language = 'english';
$classifiedType = classifyComplaint($newComplaint, $trainedModel, $language);

 // Output the result
echo "Classified Type: " . $classifiedType . PHP_EOL;

?>



<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../includes/connection.php');
include("../includes/check_session.php");
require '../vendor/autoload.php';

use Phpml\Classification\NaiveBayes;

$errormsg = '';

$trainingData = [
    ['complaint' => 'I experienced discrimination because of my race', 'type' => 'discrimination', 'language' => 'english'],
    ['complaint' => 'Naranasan ko ang diskriminasyon dahil sa aking lahi', 'type' => 'discrimination', 'language' => 'filipino'],
    ['complaint' => 'I am struggling with my academics', 'type' => 'academic issues', 'language' => 'english'],
    ['complaint' => 'Nahihirapan ako sa aking pag-aaral', 'type' => 'academic issues', 'language' => 'filipino'],
    ['complaint' => 'I witnessed teacher misconduct in the classroom', 'type' => 'teacher misconduct', 'language' => 'english'],
    ['complaint' => 'Nakakita ako ng di-maayos na pag-uugali ng guro sa silid-aralan', 'type' => 'teacher misconduct', 'language' => 'filipino'],
    ['complaint' => 'The school facilities are in poor condition', 'type' => 'facilities and infrastructure problems', 'language' => 'english'],
    ['complaint' => 'Ang mga pasilidad ng paaralan ay nasa masamang kalagayan', 'type' => 'facilities and infrastructure problems', 'language' => 'filipino'],
    ['complaint' => 'I feel unsafe and insecure in the school premises', 'type' => 'safety and security concerns', 'language' => 'english'],
    ['complaint' => 'Nararamdaman ko ang kawalan ng kaligtasan at katiyakan sa loob ng paaralan', 'type' => 'safety and security concerns', 'language' => 'filipino'],
];

function preprocess($complaint, $language)
{
    if ($language === 'filipino') {
        // Remove diacritics (accented characters) from the Filipino complaint
        $complaint = iconv('UTF-8', 'ASCII//TRANSLIT', $complaint);
        // Convert the Filipino complaint to lowercase
        $complaint = strtolower($complaint);
    } else {
        // Preprocess the English complaint (e.g., remove punctuation and convert to lowercase)
        $complaint = preg_replace("/[^a-zA-Z ]+/", "", $complaint);
        $complaint = strtolower($complaint);
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

function classifyComplaint($complaintDetails, $trainedModel, $language)
{
    $complaint = preprocess($complaintDetails, $language);
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
                $classProbability *= (1 / count($conditionalProbabilities[$type]));
            }
        }
        $classProbabilities[$type] = $classProbability;
    }

    // Find the class with the highest probability
    arsort($classProbabilities);
    $classifiedType = key($classProbabilities);

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
        $classifiedType = classifyComplaint($_POST['complainttype'], $trainedModel, $language);
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
