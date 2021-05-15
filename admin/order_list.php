<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('location:login.php');
}

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 0) {
        echo  "<script>alert('You are not admin');window.location.href='login.php'</script>";
    }
}

require('header.php');

if (!empty($_GET['pageno'])) {

    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$numOfrecs  = 3;
$offset = ($pageno - 1) * $numOfrecs;

// if (empty($_POST['search'])) {
//     $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
//     $stmt->execute();
//     $rawResult = $stmt->fetchAll();

//     $total_pages = ceil(count($rawResult) / $numOfrecs);

//     $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$numOfrecs ");
//     $stmt->execute();
//     $result = $stmt->fetchAll();
// } else {
//     $searchkey = $_POST['search'];
//     $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchkey%' ORDER BY id DESC");
//     $stmt->execute();
//     $rawResult = $stmt->fetchAll();

    // $total_pages = ceil(count($rawResult) / $numOfrecs);

    $stmt = $pdo->prepare("SELECT * FROM sale_orders  ORDER BY id DESC LIMIT $offset,$numOfrecs ");
    $stmt->execute();
    $result = $stmt->fetchAll();

    $total_pages = ceil(count($result) / $numOfrecs);
// }

?>

<!-- Header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sale Order listing</h3>
            </div>


            <!-- /.card-header -->
            <div class="card-body">
                <!-- <a href="cat_add.php" class="btn btn-success">New Category</a><br /><br /> -->

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 8%">#</th>
                            <th>User</th>
                            <th>Total Price</th>
                            <th>Order Date</th>
                            <th style="width: 150px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php                
                        if ($result) {
                            $i = 1;
                            foreach ($result as $value) {                                                                  
                            $UserStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);                            
                            $UserStmt->execute();
                            $UserResult = $UserStmt->fetchAll();                                
                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo escape($UserResult[0]['name']); ?></td>
                                    <td style="height: 20px;"><?php echo escape(substr($value['total_price'],0,50)); ?></td>
                                    <td> <?php echo escape(date("d-m-Y",strtotime($value['order_date']))); ?></td>
                                    <td><div class="container"> <a href="order_detail.php?id=<?php echo escape($value['id']);  ?>" class="btn btn-primary">View</a> </div></td>
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