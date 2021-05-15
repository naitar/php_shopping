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

    $stmt = $pdo->prepare("SELECT * FROM sale_order_detail  ORDER BY id DESC LIMIT $offset,$numOfrecs ");
    $stmt->execute();
    $result = $stmt->fetchAll();

    $total_pages = ceil(count($result) / $numOfrecs);
?>

<!-- Header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sale Order Detail</h3>
            </div>


            <!-- /.card-header -->
            <div class="card-body">
                <a href="order_list.php" class="btn btn-secondary">Back</a><br /><br />

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 8%">#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Order Date</th>                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php                
                        if ($result) {
                            $i = 1;
                            foreach ($result as $value) {                                                                  

                            $SO_Stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE id=".$value['sale_order_id']);                            
                            $SO_Stmt->execute();
                            $SO_Result = $SO_Stmt->fetchAll();                                

                            $P_Stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);                            
                            $P_Stmt->execute();
                            $P_Result = $P_Stmt->fetchAll(); 
                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo escape($P_Result[0]['name']); ?></td>
                                    <td><?php echo escape($value['quantity']); ?></td>
                                    <td> <?php echo escape(date("d-m-Y",strtotime($value['order_date']))); ?></td>                                    
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