<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('location:login.php');
}

if(isset($_SESSION['role'])){
  if($_SESSION['role'] == 0){    
    echo  "<script>alert('You are not admin');window.location.href='login.php'</script>";
  }
}

include('header.php');
if (!empty($_GET['pageno'])) {

  $pageno = $_GET['pageno'];
} else {
  $pageno = 1;
}

$numOfrecs  =10;
$offset = ($pageno - 1) * $numOfrecs;

if (empty($_POST['search'])) {
  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
  $stmt->execute();
  $rawResult = $stmt->fetchAll();

  $total_pages = ceil(count($rawResult) / $numOfrecs);

  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfrecs ");
  $stmt->execute();
  $result = $stmt->fetchAll();
} else {
  $searchkey = $_POST['search'];
  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchkey%' ORDER BY id DESC");
  $stmt->execute();
  $rawResult = $stmt->fetchAll();

  $total_pages = ceil(count($rawResult) / $numOfrecs);

  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchkey%' ORDER BY id DESC LIMIT $offset,$numOfrecs ");
  $stmt->execute();
  $result = $stmt->fetchAll();
}

?>

<!-- Header -->
<!-- Main content -->
<div class="content">
  <div class="container-fluid">

  
    <!-- table -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">User listing</h3>
      </div>


      <!-- /.card-header -->
      <div class="card-body">
        <a href="user_add.php" class="btn btn-success">New User</a><br /><br />

        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">ID</th>
              <th style="width: 250px">Name</th>
              <th>Email</th>              
              <th>Role</th>
              <th style="width: 150px">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php

            if ($result) {
              $i = 1;
              foreach ($result as $value) {
            ?>
                <tr>
                  <td><?php echo $i; ?></td>
                  <td><?php echo escape($value['name']); ?></td>
                  <td><?php echo escape($value['email']); ?></td>                  
                  <td><?php if($value['role'] == 1 ){ echo "Admin";} else {echo "User"; } ?></td>

                  <td>
                    <div class="btn-group">
                      <div class="container"> <a href="user_edit.php?id=<?php echo escape($value['id']);  ?>" class="btn btn-primary">Edit</a> </div>
                      <div class="container"> <a href="user_delete.php?id=<?php echo escape($value['id']);  ?>" 
                      onclick="return confirm('Are you sure you want to delete this item')"
                      class="btn btn-danger">Delete</a></div>
                    </div>
                  </td>
                </tr>
            <?php
                $i++;
              }
            }
            ?>

          </tbody>
        </table>

      </div>
      <!-- /.card-body -->
      <div class="card-footer clearfix">
        <ul class="pagination" style="float:right;">
          <li class="page-item">
            <a class="page-link" href="?pageno=1">First</a>
          </li>
          <li class="page-item  <?php if ($pageno <= 1) {
                                  echo 'disabled';
                                } ?>">
            <a class="page-link" href="<?php if ($pageno <= 1) {
                                          echo '#';
                                        } else {
                                          echo "?pageno=" . ($pageno - 1);
                                        } ?>">Previous</a>
          </li>
          <li class="page-item disabled">
            <a class="page-link" href="#"><?php echo $pageno; ?> </a>
          </li>
          <li class="page-item <?php if ($pageno >= $total_pages) {
                                  echo 'disabled';
                                } ?> ">
            <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                          echo '#';
                                        } else {
                                          echo "?pageno=" . ($pageno + 1);
                                        } ?>">Next</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- /.card -->
    <!-- table -->

  </div>
</div>

</div>
<!-- /.content-wrapper -->



<!-- Footer -->
<?php include('footer.html'); ?>