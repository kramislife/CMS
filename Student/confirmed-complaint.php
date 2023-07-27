
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
  <title>CMS | Complaint History</title>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>

  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
</head>

<body>
  <?php include("../Student/sidebar.php"); ?>
  <?php include("../includes/header.php"); ?>

  <section id="container">
    <section id="main-content">
      <section class="wrapper">
        <h4 style="padding-bottom:10px; padding-top:10px; font-weight:bolder; font-family: 'Times New Roman', Times, serif;">IN PROCESS COMPLAINTS</h4>
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
                    <th>Complaint Type</th>
                    <th>Date Submitted</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  $userId = $_SESSION['UserID'];
                  $query = mysqli_query($conn, "SELECT * FROM complaints WHERE ComplaintID='$userId' AND isDeleted = '0' AND (Status = 'In Process') ORDER BY 
                   RegDate DESC");
       
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
                          $statusStyle = "background-color: #eb3e4f; font-size:13px;";
                          $statusText = "Pending";
                        } else if ($cStatus == "In Process") {
                          $statusClass = "badge";
                          $statusStyle = "background-color: #2eb44d; font-size:13px;";
                          $statusText = htmlentities($row['Status']);
                        } else if ($cStatus == "Closed") {
                          $statusClass = "badge";
                          $statusStyle = "background-color: #488eda; font-size:13px;";
                          $statusText = htmlentities($row['Status']);
                        } else {
                          $statusClass = "badge";
                          $statusStyle = "background-color: gray; color: white;";
                          $statusText = htmlentities($row['Status']);
                        }
                        ?>

                        <span class="<?php echo $statusClass; ?>" style="<?php echo $statusStyle; ?>"><?php echo $statusText; ?></span>
                      </td>

                      <td data-label="Complaint Type:"><?php echo htmlentities($row['ComplaintType']);?></td>
                      <td data-label="Registered Date:"><?php echo htmlentities($row['RegDate']);?></td>

                      <td data-label="Action:">
                        <a href="complaint-details?cid=<?php echo htmlentities($row['ComplaintNumber']);?>">
                          <button type="button" class="btn btn-primary fa fa-eye fa-lg edit_btn" name="editData"></button>
                        </a>
                       <!-- <button type="button" class="btn btn-danger fa fa-trash-o fa-lg delete_btn" data-toggle="modal" data-target="#deleteModal"></button> -->
                      </td>
                        </tr>
                      <?php
                      }
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
                  <form id="editForm" action="record_data.php" method="POST">
                    <div class="modal-body">
                      <input type="hidden" name="editID" id="editID">

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
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" name="updateBtn" class="btn btn-primary" id="updateBtn">Update</button>
                    </div>
                  </form>
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
              <form id="deleteForm" action="delete_data.php" method="POST">
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


<!-- DELETE MODAL SCRIPT -->
<script>
  $(document).ready(function () {
    $('.delete_btn').click(function(e) {
      e.preventDefault();

      var complaintNumber = $(this).closest('tr').find('.complaintNumber').text();
      var status = $(this).closest('tr').find('td:nth-child(2)').text().trim();
    
      if (status === "In Process") {
        $('#deleteModal .modal-body').html("<p style='color: black;'> Deletion for your complaints is not allowed because it is now <b>" + status +"</b></p>");
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

    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    searching: true,
    paging: true,
    ordering: true,
    info: true,
    order: true,
    columnDefs: [
      {
        targets: 0,
        orderable: false,
        className: 'select-checkbox',
      },
    ],
    select: {
      style: 'multi',
      selector: 'td:first-child',
    },
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






