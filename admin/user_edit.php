<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('location:login.php');
}

if($_GET){
    $id = $_GET['id'];
    $stmt = $pdo->prepare("select * from users where id=$id");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

if($_POST) {
        if(empty($_POST['name']) || empty($_POST['email'])  ){
            if(empty($_POST['name'])){
                $nameError = 'Name  is required';
            }
            if(empty($_POST['email'])){
                $emailError = 'Email  is required';
            }
    }elseif(!empty($_POST['password']) && strlen($_POST['password']) < 4 ){
        $passwordError = 'Password should be 4 charater at least';
    }else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

        if(empty($_POST['role'])){
            $role = 0;
        }else{
            $role = 1;
        }
    
        $stmt2 = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id != :id");
        $stmt2->execute(array(':email'=>$email,':id'=>$id));
        $stmt2_res = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if($stmt2_res){
            echo "<script>alert('Email duplicated');</script>";        
        }else{
            if($password != null){
                $stmt3 =  $pdo->prepare("UPDATE users SET name='$name',email='$email',password='$password', role ='$role' WHERE id='$id'");
            }else{
                $stmt3 =  $pdo->prepare("UPDATE users SET name='$name',email='$email', role ='$role' WHERE id='$id'");
            }
            $res_stmt3 =  $stmt3->execute();
            if($res_stmt3){
                echo  "<script>alert('Successfully updated!!!'); window.location.href='userlist.php'</script>";
            }
        }
    }
}
     

    include('header.php'); 

?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Blog listing</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo escape($_SESSION['_token']); ?>">
                    <div class="form-group">

                        <label for="name" name="name"> Name</label><p style="color:red;display:inline;"><?php echo empty($nameError) ? '' : '*'.$nameError ?></p>                        
                        <input type="hidden" class="form-control" name="id" value="<?php echo escape($result['id']); ?>" >
                        <input type="name" class="form-control" name="name" value="<?php echo escape($result['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email" name="email"> Email</label><p style="color:red;display:inline;"><?php echo empty($emailError) ? '' : '*'.$emailError ?></p>
                        <input type="email" class="form-control" name="email" value="<?php echo escape($result['email']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password" name="password"> Password </label><p style="color:red;display:inline;"><?php echo empty($passwordError) ? '' : '*'.$passwordError ?></p>
                        <br>
                        <span>The user already has a password</span>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <div class="form-group">
                        <label for="admin" name="password"> Admin &nbsp;&nbsp; </label>
                        <input type="checkbox" name="role" <?php if( $result['role'] == 1){ echo escape("checked"); } ?> >                       
                    </div>
                  
                    <div class="form-goup">
                        <input type="submit" value="submit" class="btn btn-success">
                        <a href="userlist.php" class="btn btn-warning"> Back </a>
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