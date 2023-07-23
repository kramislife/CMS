<?php
session_start();

include('../includes/connection.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CMS | Complainant</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.css" />
  <link rel="stylesheet" type="text/css" href="../assets/css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="../assets/js/gritter/css/jquery.gritter.css" />
  <link rel="stylesheet" type="text/css" href="../assets/lineicons/style.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/style-responsive.css">
  <link rel="apple-touch-icon" sizes="180x180" href="../favicon_package_v0.16/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon_package_v0.16/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon_package_v0.16/favicon-16x16.png">
  <link rel="manifest" href="../favicon_package_v0.16/site.webmanifest">
  <link rel="mask-icon" href="../favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
</head>

<body>
  <section id="container">

    <?php include("../Admin/sidebar.php"); ?>
    <?php include("../includes/header.php"); ?>

    <section id="main-content">
      <section class="wrapper">
        <h3>List of Complaints</h3>
        <div class="row mt">
          <div class="col-lg-12">
            <div class="content-panel">
              <section id="unseen">
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
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
                    $query = mysqli_query($conn, "SELECT studentcomplaints.ComplainantName, studentcomplaints.ComplaintType, studentcomplaints.ComplaintDetails,
                              studentcomplaints.Status, student_login.Email, studentcomplaints.Updated_Time
                              FROM studentcomplaints
                              INNER JOIN student_login ON student_login.Email = student_login.Email
                              UNION
                              SELECT facultycomplaints.ComplainantName, facultycomplaints.ComplaintType, facultycomplaints.ComplaintDetails,
                              facultycomplaints.Status, faculty_login.Email, facultycomplaints.Updated_Time
                              FROM facultycomplaints
                              INNER JOIN faculty_login ON faculty_login.Email = faculty_login.Email
                              ORDER BY Updated_Time");

                    while ($row = mysqli_fetch_array($query)) {
                      ?>
                      <tr>
                        <td style="text-align:center;"><?php echo htmlentities($row['ComplainantName']); ?></td>
                        <td style="text-align:center;"><?php echo htmlentities($row['Email']); ?></td>
                        <td style="text-align:center;"><?php echo htmlentities($row['ComplaintType']); ?></td>
                        <td style="text-align:center;">
                          <?php $cStatus = $row['Status'];
                          if ($cStatus == "" || $cStatus == NULL) {
                          echo htmlentities("Pending");
                          } else {
                          echo htmlentities($row['Status']);
                          }?></td>
                        <td style="text-align:center;">
                          <button type="button" class="btn btn-primary fa fa-eye fa-lg" data-toggle="modal" data-target="#recordModal"></button>
                          <button type="button" class="btn btn-success fa fa-pencil fa-lg" data-toggle="modal" data-target="#editModal"></button>
                          <button type="button" class="btn btn-danger fa fa-trash-o fa-lg" data-toggle="modal" data-target="#deleteModal"></button>
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
        <!-- Modal -->
        <div id="recordModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
              <div style="background-color: #8f0303;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Complaint Details</h4>
              </div>
              <div class="modal-body">
                <?php
                $query = mysqli_query($conn, "SELECT studentcomplaints.ComplainantName, studentcomplaints.ComplaintNumber, studentcomplaints.ComplaintType, studentcomplaints.ComplaintDetails,
                              studentcomplaints.ComplaintFile, studentcomplaints.Status
                              FROM studentcomplaints
                              UNION
                              SELECT facultycomplaints.ComplainantName, facultycomplaints.ComplaintNumber, facultycomplaints.ComplaintType, facultycomplaints.ComplaintDetails,
                              facultycomplaints.ComplaintFile, facultycomplaints.Status
                              FROM facultycomplaints");

                while ($row = mysqli_fetch_array($query)) {
                  ?>
                  <div class="form-panel">
                  <form class="form-horizontal style-form">
                    <div class="form-group">
                       <label class="col-sm-2 control-label"><b>Name :</b></label>
                    <div class="col-sm-10">
                        <p><?php echo htmlentities($row['ComplainantName']); ?></p>
                    </div>
                    </div>
                      <div class="form-group">
                        <label class="col-sm-10 control-label"><b>Complaint Number : </b></label>
                        <div class="col-sm-4">
                          <p><?php echo htmlentities($row['ComplaintNumber']); ?></p>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2 control-label"><b>Complaint Type : </b></label>
                        <div class="col-sm-4 control-label">
                          <p><?php echo htmlentities($row['ComplaintType']); ?></p>
                        </div>
                      </div>

                      <?php if ($row['ComplaintType'] === 'Others') : ?>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><b>Others:</b></label>
                          <div class="col-sm-4 control-label">
                            <p><?php echo htmlentities($row['Others']); ?></p>
                          </div>
                        </div>
                      <?php endif; ?>

                      <div class="form-group">
                        <label class="col-sm-2 control-label"><b>Complaint Details:</b></label>
                        <div class="col-sm-4 control-label" style=" width:80%; text-align:justify; word-wrap: break-word;">
                          <p><?php echo htmlentities($row['ComplaintDetails']); ?></p>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2 control-label"><b>Complaint File : </b></label>
                        <div class="col-sm-4 control-label">
                          <p><?php $cfile = $row['ComplaintFile'];
                                                        if ($cfile == "" || $cfile == "NULL") {
                                                          echo htmlentities("None");
                                                        } else {
                                                          ?>
                              <a href="../complaintDocs/<?php echo htmlentities($row['ComplaintFile']); ?>">
                                <button type="button" name="submit" class="btn btn-info">View File</button>
                              </a>
                            <?php } ?></p>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-2 control-label"><b>Status: </b></label>
                        <div class="col-sm-2 control-label">
                          </p><?php $cStatus = $row['Status'];
                          if ($cStatus == "" || $cStatus == NULL) {
                          echo htmlentities("Pending");
                          } else {
                          ?><?php echo htmlentities($row['Status']); ?></p>
                          </div>
                      </div>
                    <?php } }?>
                    </form>
                  </div>
                </div>
                <div class="modal-body text-right">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
              </div>
            </div>
          </div>
        </div>
      </section>

       <!-- Delete Modal -->
       <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div style="background-color: #8f0303;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="deleteModalLabel">Delete Complaints</h5>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete the complaint?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" name="delete" class="btn btn-danger">Delete</button>
              </div>
            </div>
          </div>
        </div>
    </section>
  </section>

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
