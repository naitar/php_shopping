<?php
  session_start();
  require '../Config/config.php';
  require '../Config/common.php';

  if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in']))
  {
    header('location:login.php');
  }

  if(isset($_SESSION['role']))
  {
    if($_SESSION['role'] == 0)
    {    
      echo  "<script>alert('You are not admin');window.location.href='login.php'</script>";
    }    
  }

  require('header.php');

  $currentDate = date('Y-m-d');
  $fromDate = date('Y-m-d',strtotime($currentDate. '+1 day'));
  $toDate = date('Y-m-d',strtotime($currentDate. '-7 day'));
// $fromDate = date("Y-m-d", time() + 86400);

  $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date <:fromDate AND order_date >= :toDate ORDER BY id DESC");
  $stmt->execute([':fromDate'=>$fromDate, ':toDate'=>$toDate]);
  $result = $stmt->fetchAll();

//   echo "<pre>";
//   print_r($result);
//   echo "</pre>";
//   exit();
?>

<!-- Header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Weekly Report</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">

                <table class="table table-bordered" id="d-table">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>UserID</th>
                            <th>TotalAmount</th>
                            <th>Order Date</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if ($result)
                    {
                        $i = 1; 
                        foreach ($result as $value) 
                        { 
                            $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                            $userStmt->execute();
                            $userResult = $userStmt->fetchAll();
                            // echo "<pre>";
                            // print_r($userResult);
                            // echo "</pre>";
                    
                    ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo escape($userResult[0]['name']); ?></td>
                            <td> <?php echo escape($value['total_price']); ?></td>
                            <td><?php echo escape(date("Y-m-d",strtotime($value['order_date']))); ?> </td>                            
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

        </div>
        <!-- /.card -->
        <!-- table -->

    </div>
</div>

</div>
<!-- /.content-wrapper -->



<!-- Footer -->
<?php include('footer.html'); ?>
<!-- DataTable -->
<script>
    $(document).ready(function() {
        $('#d-table').DataTable();
    } );
</script>