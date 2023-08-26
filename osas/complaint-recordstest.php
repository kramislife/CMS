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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
</head>

<body>
  <section id="container">
  <?php include("../osas/sidebar.php"); ?>
<?php include("../includes/header.php"); ?>  

    <section id="main-content">
      <section class="wrapper">
        <h3>List of Complaints</h3>
        <div class="row mt">         
          <div class="col-lg-12">

              <?php if(isset($_SESSION['successmsg'])) { ?>
                <div class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo htmlentities($_SESSION['successmsg']); ?>
                </div>
              <?php unset($_SESSION['successmsg']); } ?>

              <?php if(isset($_SESSION['errormsg'])) { ?>
                <div class="alert alert-danger alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo htmlentities($_SESSION['errormsg']); ?>
                </div>
              <?php unset($_SESSION['errormsg']); } ?>

            <div class="content-panel row table-responsive">
              <section id="unseen">
                <table id="records" class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th style="text-align: center;">Complaint Number</th>
                      <th style="text-align: center;">Name</th>
                      <th style="text-align: center;">Email</th>
                      <th style="text-align: center;">Complaint Type</th>
                      <th style="text-align: center;">Status</th>
                      <th style="text-align: center;">Action</th>
                      <th style="text-align: center;">Date and Time of Complaints</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM complaints");

                    while ($row = mysqli_fetch_array($query)) {
                    ?>
                      <tr>
                      <td class="complaintNumber" style="text-align:center;"><?php echo htmlentities($row['ComplaintNumber']); ?></td> 
                        <td style="text-align:center;"><?php echo htmlentities($row['ComplainantName']); ?></td>
                        <td style="text-align:center;"><?php echo htmlentities($row['Email']); ?></td>
                        <td style="text-align:center;"><?php echo htmlentities($row['ComplaintType']); ?></td>
                        <td style="text-align:center;">
                          <?php
                          $cStatus = $row['Status'];
                          if ($cStatus == "" || $cStatus == NULL) {
                            echo htmlentities("Pending");
                          } else {
                            echo htmlentities($row['Status']);
                          }
                          ?>
                        </td>
                        <td style="text-align:center;">
                        <button type="button" class="btn btn-primary fa fa-eye fa-lg view_btn" name="viewData" data-toggle="modal" data-target="#viewModal"></button>
                          <button type="button" class="btn btn-success fa fa-pencil fa-lg edit_btn" name="editData" data-toggle="modal" data-target="#editModal"></button>
                          <button type="button" class="btn btn-danger fa fa-trash-o fa-lg delete_btn" data-toggle="modal" data-target="#deleteModal"></button>
                        </td>
                        <td style="text-align:center;"><?php echo htmlentities($row['Updated_Time']); ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </section> 
            </div>
          </div>
        </div>
                
        <!-- View Modal -->
          <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="recordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div style="background-color: #8f0303;" class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Complaint Record</h4>
                </div>
                <div class="modal-body">
                  <div class="view_data"> 

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Number:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewComplaintNumber"></p>
                      </div>
                    </div>

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
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Type:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewComplaintType"></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Others:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewOthers"></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Details:</strong></label>
                      <div class="col-sm-8" style=" text-align: justify;">
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
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Status:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewStatus"></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

           <!-- Edit Modal -->
           <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div style="background-color: #8f0303;" class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Complaint Record</h4>
                </div>
                <div class="modal-body">
                  <div class="edit_data"> 

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Number:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewComplaintNumber"></p>
                      </div>
                    </div>

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
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Type:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewComplaintType"></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Others:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewOthers"></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Details:</strong></label>
                      <div class="col-sm-8" style=" text-align: justify;">
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
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Status:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewStatus"></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                  <p><strong style="color: black">Are you sure you want to delete the complaint?</strong></p>
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
    

<!-- VIEW MODAL SCRIPT -->
<script>
 $(document).ready(function() {
  // Show the user's response in the recordModal
  $('.view_btn').click(function(e) {
    e.preventDefault();

    // Get the complaint number from the clicked button
    var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();

    $.ajax({
      type: "POST",
      url: "record_data.php",
      data: {
        'checking_view_btn': true,
        'complaintNumber': complaintNumber,
      },
      success: function(response) {
        var data = JSON.parse(response);

        if (data.success) {
          $('#reviewComplaintNumber').text(data.complaintNumber);
          $('#reviewName').text(data.name);
          $('#reviewEmail').text(data.email);
          $('#reviewComplaintType').text(data.complaintType);
          $('#reviewComplaintDetails').text(data.ComplaintDetails);

          if (data.complaintFile) {
            $('#reviewCompFile').html('<a href="../complaintDocs/' + encodeURIComponent(data.complaintFile) + '" target="_blank">View File</a>');
          } else {
            $('#reviewCompFile').text("No file Available");
          }

          if (data.status) {
            $('#reviewStatus').text(data.status);
          } else {
            $('#reviewStatus').text('Pending');
          }

          if (data.others) {
            $('#reviewOthers').text(data.others);
            $('#reviewOthers').closest('.form-group').show();
          } else {
            $('#reviewOthers').closest('.form-group').hide();
          }

          $('#viewModal').modal('show');
        } else {
          $('#viewModal').modal('hide');
          alert('No data found!');
        }
      }
    });
  });
});
</script>

<!-- VIEW MODAL SCRIPT -->
<script>
 $(document).ready(function() {
  // Show the user's response in the recordModal
  $('.view_btn').click(function(e) {
    e.preventDefault();

    // Get the complaint number from the clicked button
    var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();

    $.ajax({
      type: "POST",
      url: "record_data.php",
      data: {
        'checking_view_btn': true,
        'complaintNumber': complaintNumber,
      },
      success: function(response) {
        var data = JSON.parse(response);

        if (data.success) {
          $('#reviewComplaintNumber').text(data.complaintNumber);
          $('#reviewName').text(data.name);
          $('#reviewEmail').text(data.email);
          $('#reviewComplaintType').text(data.complaintType);
          $('#reviewComplaintDetails').text(data.ComplaintDetails);

          if (data.complaintFile) {
            $('#reviewCompFile').html('<a href="../complaintDocs/' + encodeURIComponent(data.complaintFile) + '" target="_blank">View File</a>');
          } else {
            $('#reviewCompFile').text("No file Available");
          }

          if (data.status) {
            $('#reviewStatus').text(data.status);
          } else {
            $('#reviewStatus').text('Pending');
          }

          if (data.others) {
            $('#reviewOthers').text(data.others);
            $('#reviewOthers').closest('.form-group').show();
          } else {
            $('#reviewOthers').closest('.form-group').hide();
          }

          $('#viewModal').modal('show');
        } else {
          $('#viewModal').modal('hide');
          alert('No data found!');
        }
      }
    });
  });
});
</script>


<!-- DELETE MODAL SCRIPT -->
<script>
  $(document).ready(function () {
    // Set delete_id value when the delete button is clicked
    $('.delete_btn').click(function(e) {
      e.preventDefault();

      var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();
    
      $('#delete_id').val(complaintNumber);
      $('#deleteModal').modal('show');

    });
  });
    
</script>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="../assets/js/jquery.scrollTo.min.js"></script>
    <script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="../assets/js/jquery.sparkline.js"></script>
    <script src="../assets/js/common-scripts.js"></script>
    <script type="text/javascript" src="../assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="../assets/js/gritter-conf.js"></script>
  </body>
</html>









$(document).ready(function() {
  var recordsTable = new DataTable('#records');
  var viewModal = $('#viewModal');
  var editModal = $('#editModal');
  var deleteModal = $('#deleteModal');

  $('.view_btn').click(function(e) {
    e.preventDefault();
    var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();
    $.ajax({
      type: "POST",
      url: "record_data.php",
      data: {
        'checking_view_btn': true,
        'complaintNumber': complaintNumber,
      },
      success: function(response) {
        var data = JSON.parse(response);
        if (data.success) {
          updateReviewModal(data);
          viewModal.modal('show');
        } else {
          hideModal(viewModal);
          alert('No data found!');
        }
      }
    });
  });

  $('.edit_btn').click(function(e) {
    e.preventDefault();
    var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();
    $.ajax({
      type: "POST",
      url: "record_data.php",
      data: {
        'checking_edit_btn': true,
        'complaintNumber': complaintNumber,
      },
      success: function(response) {
        var data = JSON.parse(response);
        if (data.success) {
          updateEditModal(data);
          editModal.modal('show');
        } else {
          hideModal(editModal);
          alert('No data found!');
        }
      },
      error: function(xhr, status, error) {
        handleAjaxError(xhr.responseText);
      }
    });
  });

  $('.delete_btn').click(function(e) {
    e.preventDefault();
    var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();
    var status = $(this).closest('tr').find('td:nth-child(5)').text().trim();
    if (status === "Pending" || status === "In Process") {
      showNotAllowedDeletionModal(status);
    } else {
      setDeleteId(complaintNumber);
      showDeleteModal();
    }
  });

  $('#updateBtn').click(function(e) {
    e.preventDefault();
    var complaintNumber = $('#editComplaintNumber').text();
    var status = $('#editStatus').val();
    if (status === "") {
      alert("Please select a status to update.");
      return;
    }
    if (!isStatusUpdateAllowed(status)) {
      alert("Cannot update to the selected status.");
      return;
    }
    $.ajax({
      type: "POST",
      url: "record_data.php",
      data: {
        'complaintNumber': complaintNumber,
        'status': status
      },
      success: function(response) {
        var data = JSON.parse(response);
        if (data.success) {
          location.reload();
        } else {
          alert('Failed to update complaint.');
        }
      },
      error: function(xhr, status, error) {
        handleAjaxError(xhr.responseText);
      }
    });
  });

  function updateReviewModal(data) {
    var reviewModal = viewModal.find('.view_data');
    reviewModal.find('#reviewComplaintNumber').text(data.complaintNumber);
    reviewModal.find('#reviewName').text(data.name);
    reviewModal.find('#reviewEmail').text(data.email);
    reviewModal.find('#reviewComplaintType').text(data.complaintType);
    reviewModal.find('#reviewComplaintDetails').text(data.ComplaintDetails);
    if (data.complaintFile) {
      reviewModal.find('#reviewCompFile').html('<a href="../complaintDocs/' + encodeURIComponent(data.complaintFile) + '" target="_blank">View File</a>');
    } else {
      reviewModal.find('#reviewCompFile').text("No file Available");
    }
    reviewModal.find('#reviewStatus').text(data.status || 'Pending');
    reviewModal.find('#reviewOthers').text(data.others || '').closest('.form-group').toggle(!!data.others);
  }

  function updateEditModal(data) {
    var editModalBody = editModal.find('.edit_data');
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
    editModalBody.find('#editStatus').val(data.status);
    editModalBody.find('#editOthers').val(data.others || '').closest('.form-group').toggle(data.complaintType === "Others");
    updateEditStatusOptions(data.status);
  }

  function updateEditStatusOptions(status) {
    var disabledStatuses = ["Pending", "In Process", "Closed"];
    var editStatusOptions = editModal.find('#editStatus option');
    editStatusOptions.prop('disabled', false);
    disabledStatuses.forEach(function(disabledStatus) {
      editStatusOptions.filter('[value="' + disabledStatus + '"]').prop('disabled', true);
    });
  }

  function isStatusUpdateAllowed(status) {
    var allowedStatuses = {
      "Pending": true,
      "In Process": $('#editStatus option[value="Closed"]').is(':selected'),
      "Closed": false
    };
    return allowedStatuses[status];
  }

  function setDeleteId(complaintNumber) {
    deleteModal.find('#delete_id').val(complaintNumber);
  }

  function showNotAllowedDeletionModal(status) {
    var message = "<p style='color: black;'>Deletion is not allowed for complaints with <b>" + status + "</b> status.</p>";
    deleteModal.find('.modal-body').html(message);
    deleteModal.find('.modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>');
    showDeleteModal();
  }

  function showDeleteModal() {
    deleteModal.modal('show');
  }

  function hideModal(modal) {
    modal.modal('hide');
  }

  function handleAjaxError(errorMessage) {
    alert('Error: ' + errorMessage);
  }
});




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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">  
</head>
<body>

<?php include("../osas/sidebar.php"); ?>
<?php include("../includes/header.php"); ?>  

  <section id="container">
    <section id="main-content">
      <section class="wrapper">
      <h4 style=" padding-bottom:10px; padding-top:10px; font-weight:bolder; font-family: 'Times New Roman', Times, serif;">COMPLAINT RECORDS</h4>
        <div class="row mt">         
          <div class="col-lg-12">

              <?php if(isset($_SESSION['successmsg'])) { ?>
                <div class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo htmlentities($_SESSION['successmsg']); ?>
                </div>
              <?php unset($_SESSION['successmsg']); } ?>

              <?php if(isset($_SESSION['errormsg'])) { ?>
                <div class="alert alert-danger alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo htmlentities($_SESSION['errormsg']); ?>
                </div>
              <?php unset($_SESSION['errormsg']); } ?>

            <div class="content-panel">
              
              <section id="unseen">
              <table id="records" class="table table-bordered table-striped table-condensed">
                
                  <thead>
                    <tr>
                      <th>Complaint Number</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Complaint Type</th>
                      <th>Status</th>
                      <th>Action</th>
                      <th>Date and Time of Complaints</th>
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
                          <td data-label="Name:"><?php echo htmlentities($row['ComplainantName']); ?></td>
                          <td data-label="Email:"><?php echo htmlentities($row['Email']); ?></td>
                          <td  data-label="Complaint Type:"><?php echo htmlentities($row['ComplaintType']); ?></td>
                          <td  data-label="Status:">
                            <?php
                            $cStatus = $row['Status'];
                            if ($cStatus == "" || $cStatus == NULL) {
                              echo htmlentities("Pending");
                            } else {
                              echo htmlentities($row['Status']);
                            }
                            ?>
                          </td>
                          <td  data-label="Buttons:">
                            <button type="button" class="btn btn-primary fa fa-eye fa-lg view_btn" name="viewData" data-toggle="modal" data-target="#viewModal"></button>
                            <button type="button" class="btn btn-success fa fa-pencil fa-lg edit_btn" name="editData" data-toggle="modal" data-target="#editModal"></button>
                            <button type="button" class="btn btn-danger fa fa-trash-o fa-lg delete_btn" data-toggle="modal" data-target="#deleteModal"></button>
                          </td>
                          <td  data-label="Time:"><?php echo htmlentities($row['Updated_Time']); ?></td>
                        </tr>
                        <?php
                      }
                    } else {
                      ?>
                      <tr>
                        <td colspan="7" style="text-align: center;">No Data</td>
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
                
        <!-- View Modal -->
          <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="recordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div style="background-color: #8f0303;" class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Complaint Record</h4>
                </div>
                <div class="modal-body">
                  <div class="view_data"> 

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Number:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewComplaintNumber"></p>
                      </div>
                    </div>

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
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Type:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewComplaintType"></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Others:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewOthers"></p>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Complaint Details:</strong></label>
                      <div class="col-sm-8" style=" text-align: justify;">
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
                      <label class="col-sm-4 col-form-label"><strong style="color: black">Status:</strong></label>
                      <div class="col-sm-8">
                        <p id="reviewStatus"></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

       <!-- Edit Modal -->
       <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div style="background-color: #8f0303;" class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Complaint Record</h4>
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
                  <button type="submit" class="btn btn-primary" id="updateBtn">Update</button>

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
                  <p><strong style="color: black">Are you sure you want to delete the complaint?</strong></p>
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

    <script src="../js/script.js" defer></script>

  <script src="../assets/js/jquery.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="../assets/js/jquery.dcjqaccordion.2.7.js"></script>
  <script src="../assets/js/jquery.scrollTo.min.js"></script>
  <script src="../assets/js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="../assets/js/common-scripts.js"></script>
   
  </body>
</html>






// VIEW FUNCTION IN MODAL
if (isset($_POST['checking_view_btn'])) {
  $complaintNumber = $_POST['complaintNumber'];

  $query = "SELECT * FROM complaints WHERE ComplaintNumber = '$complaintNumber'";
  $query_run = mysqli_query($conn, $query);

  if (mysqli_num_rows($query_run) > 0) {
    $row = mysqli_fetch_assoc($query_run);

    $data = array(
      'success' => true,
      'complaintNumber' => $row['ComplaintNumber'],
      'name' => $row['ComplainantName'],
      'email' => $row['Email'],
      'complaintType' => $row['ComplaintType'],
      'others' => $row['Others'],
      'ComplaintDetails' => $row['ComplaintDetails'],
      'complaintFile' => $row['ComplaintFile'],
      'status' => $row['Status']
    );

    echo json_encode($data);
  } else {
    $data = array('success' => false);
    echo json_encode($data);
  }
}
