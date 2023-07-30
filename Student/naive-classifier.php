<?php

// Sample training data
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
function preprocess($complaint)
{
    // Remove punctuation and convert to lowercase
    $complaint = preg_replace("/[^a-zA-ZÀ-ÖØ-öø-ÿĀ-ž ]+/", "", $complaint);
    $complaint = strtolower($complaint);
    
    return $complaint;
}

// Function to extract features from complaint data
function extractFeatures($complaint)
{
    // Split the complaint into individual words
    $words = explode(" ", $complaint);
    
    // Create a feature vector with word frequencies
    $features = array_count_values($words);
    
    return $features;
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
        $language = $data['language'];
        
        // Count the occurrences of each class
        if (!isset($classCounts[$type])) {
            $classCounts[$type] = 0;
        }
        $classCounts[$type]++;
        
        // Count the occurrences of features (words) for each class and language
        if (!isset($classFeatureCounts[$type][$language])) {
            $classFeatureCounts[$type][$language] = [];
        }
        $features = extractFeatures($complaint);
        foreach ($features as $feature => $count) {
            if (!isset($classFeatureCounts[$type][$language][$feature])) {
                $classFeatureCounts[$type][$language][$feature] = 0;
            }
            $classFeatureCounts[$type][$language][$feature] += $count;
            
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
    foreach ($classFeatureCounts as $type => $featuresByLanguage) {
        $conditionalProbabilities[$type] = [];
        foreach ($featuresByLanguage as $language => $features) {
            $totalFeatures = array_sum($features);
            foreach ($vocabulary as $word) {
                $count = isset($features[$word]) ? $features[$word] : 0;
                $probability = ($count + 1) / ($totalFeatures + $vocabularySize);
                $conditionalProbabilities[$type][$language][$word] = $probability;
            }
        }
    }
    
    return ['priorProbabilities' => $priorProbabilities, 'conditionalProbabilities' => $conditionalProbabilities];
}

// Function to classify a new complaint
function classifyComplaint($complaint, $priorProbabilities, $conditionalProbabilities)
{
    $complaint = preprocess($complaint);
    $features = extractFeatures($complaint);
    
    $classProbabilities = [];
    foreach ($priorProbabilities as $type => $priorProbability) {
        $classProbability = $priorProbability;
        foreach ($features as $feature => $count) {
            $conditionalProbability = isset($conditionalProbabilities[$type]['english'][$feature]) ? $conditionalProbabilities[$type]['english'][$feature] : (1 / (array_sum($conditionalProbabilities[$type]['english']) + count($conditionalProbabilities[$type]['english'])));
            $classProbability *= pow($conditionalProbability, $count);
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
$newComplaint = 'I experienced discrimination because of my race';
$classifiedType = classifyComplaint($newComplaint, $trainedModel['priorProbabilities'], $trainedModel['conditionalProbabilities']);

// Output the result
echo "Classified Type: " . $classifiedType;
?>





<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../includes/connection.php');
include("../includes/check_session.php");

$successmsg = '';
$errormsg = '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CMS | Complaint</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.css" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/style-responsive.css">
  <link rel="apple-touch-icon" sizes="180x180" href="../favicon_package_v0.16/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon_package_v0.16/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon_package_v0.16/favicon-16x16.png">
  <link rel="manifest" href="../favicon_package_v0.16/site.webmanifest">
  <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
   <link rel="stylesheet" href="//cdn.datatables.net/autofill/2.5.3/css/autoFill.bootstrap4.min.css"> 
   <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">

  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
</head>

<body>
  <?php include("../osas/sidebar.php"); ?>
  <?php include("../includes/header.php"); ?>

  <section id="container">
    <section id="main-content">
      <section class="wrapper">
        <h4 style="padding-bottom:10px; padding-top:10px; font-weight:bolder; font-family: 'Times New Roman', Times, serif;">COMPLAINT SUMMARY</h4>
        <div class="row mt">
          <div class="col-lg-12">
          
            <?php if (isset($_SESSION['successmsg'])) { ?>
              <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlentities($_SESSION['successmsg']); ?>
              </div>
            <?php unset($_SESSION['successmsg']);
            } ?>

            <?php if (isset($_SESSION['errormsg'])) { ?>
              <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo htmlentities($_SESSION['errormsg']); ?>
              </div>
            <?php unset($_SESSION['errormsg']);
            } ?>

            <div class="content-panel">
              <section id="unseen">
                <table id="records" class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th>Complaint Number</th>
                      <th>Status</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Complaint Type</th>
                      <th>Date and Time of Complaints</th>
                      <th>Complaint Update</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM complaints WHERE ComplaintType = 'discrimination'");

                    if (mysqli_num_rows($query) > 0) {
                      while ($row = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                          <td data-label="Complaint Number:" class="complaintNumber"><?php echo htmlentities($row['ComplaintNumber']); ?></td>

                          <td data-label="Status:">
                            <?php
                            $cStatus = $row['Status'];
                            $statusClass = "";
                            $statusText = "";

                            if ($cStatus == "" || $cStatus == NULL) {
                              $statusClass = "badge";
                              $statusStyle = "background-color: #dc3545; font-size:13px;";
                              $statusText = "Pending";
                            } else if ($cStatus == "In Process") {
                              $statusClass = "badge";
                              $statusStyle = "background-color: #28a745; font-size:13px;";
                              $statusText = htmlentities($row['Status']);
                            } else if ($cStatus == "Closed") {
                              $statusClass = "badge";
                              $statusStyle = "background-color: #007bff; font-size:13px;";
                              $statusText = htmlentities($row['Status']);
                            } else {
                              $statusClass = "badge";
                              $statusStyle = "background-color: gray; color: white;";
                              $statusText = htmlentities($row['Status']);
                            }
                            ?>

                            <span class="<?php echo $statusClass; ?>" style="<?php echo $statusStyle; ?>"><?php echo $statusText; ?></span>
                          </td>

                          <td data-label="Name:"><?php echo htmlentities($row['ComplainantName']); ?></td>
                          <td data-label="Email:"><?php echo htmlentities($row['Email']); ?></td>
                          <td data-label="Complaint Type:"><?php echo htmlentities($row['ComplaintType']); ?></td>

                          <td data-label="Registration Date:"><?php echo htmlentities($row['RegDate']); ?></td>
                          <td data-label="Complaint Update:"><?php echo htmlentities($row['Updated_Time']); ?></td>
                          <td data-label="Action:">
                            <button type="button" class="btn btn-primary fa fa-pencil-square-o fa-lg edit_btn" name="editData" data-toggle="modal" data-target="#editModal"></button>
                            <button type="button" class="btn btn-danger fa fa-trash-o fa-lg delete_btn" data-toggle="modal" data-target="#deleteModal"></button>
                          </td>

                        </tr>
                      <?php
                      }
                    } else {
                      ?>
                      <tr>
                        <td colspan="8" style="text-align: center;">No Data</td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </section>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div style="background-color: #8f0303;" class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update Complaint</h4>
                      </div>
                      <div class="modal-body">
                        <div class="edit_data">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Number:</strong></label>
                            <div class="col-sm-8">
                              <p id="editComplaintNumber"></p>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong style="color: black">Name:</strong></label>
                            <div class="col-sm-8">
                              <p id="editName"></p>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong style="color: black">Email:</strong></label>
                            <div class="col-sm-8">
                              <p id="editEmail"></p>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Type:</strong></label>
                            <div class="col-sm-8">
                              <p id="editComplaintType"></p>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong style="color: black">Others:</strong></label>
                            <div class="col-sm-8">
                              <p id="editOthers"></p>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Details:</strong></label>
                            <div class="col-sm-8" style=" text-align: justify;">
                              <p id="editComplaintDetails" style="word-wrap: break-word;"></p>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Related Docs:</strong></label>
                            <div class="col-sm-8">
                              <p id="editCompFile"></p>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong style="color: black">Status:</strong></label>
                            <div class="col-sm-3">
                              <select class="form-control" id="editStatus" name="status">
                                <option value="Pending">Pending</option>
                                <option value="In Process">In Process</option>
                                <option value="Closed">Closed</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
                      </div>
                    </div>
                  </div>
                </div>

            <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div style="background-color: #8f0303;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete Complaint</h4>
              </div>
              <form id="deleteForm" action="record_data.php" method="POST">
                <div class="modal-body">
                  <input type="hidden" name="deleteID" id="delete_id">
                  <p style="color:black">Are you sure you want to <strong>Delete</strong> this complaint?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="deleteData" class="btn btn-danger">Delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </section>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>


 <!-- EDIT MODAL SCRIPT -->
 <script>
$(document).ready(function () {
  var editModal = $('#editModal');
  var successMessage = $('#successMessage');

  $('.edit_btn').click(function (e) {
    e.preventDefault();
    var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();
    $.ajax({
      type: "POST",
      url: "record_data.php",
      data: {
        'checking_edit_btn': true,
        'complaintNumber': complaintNumber,
      },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.success) {
          updateEditModal(data);
          editModal.modal('show');
        } else {
          hideModal(editModal);
          alert('No data found!');
        }
      },
      error: function (xhr, status, error) {
        handleAjaxError(xhr.responseText);
      }
    });
  });

  function showResponse(response) {
    // Display the response in a specific element
    $('#responseElement').text(response);
  }

  function updateStatus(status) {
    var complaintNumber = $('#editComplaintNumber').text();

    $.ajax({
      url: 'record_data.php', // URL to the record_data.php file for updating the status in the database
      type: 'POST',
      data: {
        complaintNumber: complaintNumber,
        status: status
      },
      success: function(response) {
        // Show the response before updating the complaint status
        showResponse(response);

        // Update the status in the modal
        $('#editStatus').val(status);

        // Disable the dropdown menu and highlight it if the status is "Closed"
        if (status === 'Closed') {
          $('#editStatus').attr('disabled', true).css('background-color', '#f8d7da');
        }

        // Hide the modal after successful update
        hideModal(editModal);

        // Show success message in the table
        successMessage.text('Complaint status updated successfully!');
        successMessage.show();
      },
      error: function(xhr) {
        // Handle error if the status update fails
        showResponse('Status update failed. Please try again.');
        var response = JSON.parse(xhr.responseText);
        if (response.success === false) {
          $('#errorMessage').text('Failed to update complaint status.');
        }
      }
    });
  }

  
// Function to handle the update button click event
$('#updateBtn').click(function(e) {
  e.preventDefault();

  var newStatus = $('#editStatus').val();

  // Call the updateStatus function with the new status
  updateStatus(newStatus, function(success) {
    if (success) {
      // Show success message in the table
      successMessage.text('Complaint status updated successfully!');
      successMessage.show();

      // Clear the success message after 3 seconds
      setTimeout(function() {
        successMessage.hide();
      }, 3000);
    } else {
      // Show error message
      $('#errorMessage').text('Failed to update complaint status.');
    }
  });
});
  function updateEditModal(data) {
      var editModalBody = $('#editModal .modal-body');
      editModalBody.find('#editComplaintNumber').text(data.complaintNumber);
      editModalBody.find('#editName').text(data.name);
      editModalBody.find('#editEmail').text(data.email);
      editModalBody.find('#editComplaintType').text(data.complaintType);
      editModalBody.find('#editComplaintDetails').text(data.ComplaintDetails);
      if (data.complaintFile) {
        editModalBody.find('#editCompFile').html('<a href="../complaintDocs/' + encodeURIComponent(data.complaintFile) + '" target="_blank">View File</a>');
      } else {
        editModalBody.find('#editCompFile').text('None');
      }
      var status = data.status === "NULL" ? "Pending" : data.status;
      editModalBody.find('#editStatus').val(status);
      editModalBody.find('#editOthers').val(data.others || '').closest('.form-group').toggle(data.complaintType === "Others");
      $('#editStatusDisplay').text(status);
      updateEditStatusOptions(status);
    }

    function updateEditStatusOptions(status) {
      var editStatusOptions = editModal.find('#editStatus option');
      editStatusOptions.prop('disabled', false);
      if (status === "Pending" || status === "NULL") {
        editStatusOptions.filter('[value="Closed"]').prop('disabled', true);
      } else if (status === "In Process") {
        editStatusOptions.filter('[value="Pending"]').prop('disabled', true);
      } else if (status === "Closed") {
        editStatusOptions.filter('[value="Pending"], [value="In Process"]').prop('disabled', true);
      }
    }

    function hideModal(modal) {
      modal.modal('hide');
      $('.modal-backdrop').remove(); // Remove the black backdrop
      $('body').removeClass('modal-open'); // Remove the 'modal-open' class from the body
    }

    function handleAjaxError(errorMessage) {
      alert('Error: ' + errorMessage);
    }

});

</script>


<!-- DELETE MODAL SCRIPT -->
<script>
  $(document).ready(function () {
    // Set delete_id value when the delete button is clicked
    $('.delete_btn').click(function(e) {
      e.preventDefault();

      var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();
      var status = $(this).closest('tr').find('td:nth-child(2)').text().trim();
    
      if (status === "Pending" || status === "In Process") {
        // Show the message in the delete modal body
        $('#deleteModal .modal-body').html("<p style='color: black;'>Deletion is not allowed for complaints with <b>" + status + "</b> status.</p>");
        $('#deleteModal .modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>');
        $('#deleteModal').modal('show');
      } else {
        // Proceed with deletion
        $('#delete_id').val(complaintNumber);
        $('#deleteModal .modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="deleteData" class="btn btn-danger">Delete</button>');
        $('#deleteModal').modal('show');
      }
    });
  });
</script>

<!--DATA TABLES -->
<script>
  $(document).ready(function() {
    $('#records').DataTable({
      dom: 'lBfrtip',
      buttons: [
        {
          extend: 'copy',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'excel',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'pdf',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'csv',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
        {
          extend: 'print',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        },
      ],
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      searching: true,
      paging: true,
      ordering: true,
      info: true,
      order: true,
    });
  });
</script>

  <script src="../assets/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
  <script src="../assets/js/jquery.scrollTo.min.js"></script>
  <script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="../assets/js/common-scripts.js"></script>
</body> 
</html>




