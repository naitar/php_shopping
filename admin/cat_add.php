<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('location:login.php');
}

?>
<?php include('header.php'); ?>
<!-- Header -->

<?php

if($_POST){
    if(empty($_POST['name']) || empty($_POST['description'])){
        if(empty($_POST['name'])){
            $nameError = "Category name is required";
        }
        if(empty($_POST['description'])){
            $descError = "Category description is required";
        }
    }else{
        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo -> prepare("INSERT INTO categories (name,description) VALUES (:name,:description)");
        $result = $stmt -> execute(
            array(':name'=>$name,':description'=>$description)
        );

        if($result){
            echo "<script> alert('Categories Add');window.location.href='category.php'; </script>";
        }

    }

}

?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Categories listing</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <form action="cat_add.php" method="POST" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo escape($_SESSION['_token']); ?>">
                    <div class="form-group">
                        <label for="name" name="name"> name</label><p style="color:red;display:inline;"><?php echo empty($nameError) ? '' : '*'.$nameError ?></p>
                        <input type="text" class="form-control" name="name" >
                    </div>

                    <div class="form-group">
                        <label for="content" name="description"> Description</label><p style="color:red;display:inline;"><?php echo empty($descError) ? '' : '*'.$descError ?></p><br>
                        <textarea class="form-control" name="description" id="" cols="80" rows="8" ></textarea>    
                    </div>

                    <div class="form-goup">
                        <input type="submit" value="submit" class="btn btn-success">
                        <a href="category.php" class="btn btn-warning"> Back </a>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->

            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </div>
        </div>
        <!-- /.card -->
        <!-- table -->

    </div><!-- /.container-fluid -->
</div>

</div>
<!-- /.content-wrapper -->



<!-- Footer -->
<?php include('footer.html'); ?>