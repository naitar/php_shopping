<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('location:login.php');
}

include('header.php'); 

    if($_POST){
        
        if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
            if(empty($_POST['name'])){
                $nameError = 'Name  is required';
            }
            if(empty($_POST['email'])){
                $emailError = 'Email  is required';
            }

            if(strlen($_POST['password']) < 4 ){
                $passwordError = 'Password should be 4 charater at least';
            }

            if(empty($_POST['password'])){
                $passwordError = 'Password  is required';
            }

        }else{
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password =password_hash($_POST['password'],PASSWORD_DEFAULT);
            
            if(empty($_POST['role'])){
            $role = 0;
            }else{
            $role = 1;
            }

            $stmt = $pdo -> prepare("SELECT * FROM users where email=:email");
            $stmt -> bindValue(':email',$email);
            $stmt -> execute();
            $user = $stmt -> fetch(PDO::FETCH_ASSOC);
            
            if($user){
            echo "<script>alert('Email Duplicated')</script>";
            
            }else{
            $stmt =  $pdo -> prepare("INSERT INTO users (name,email,password,role) VALUES (:name,:email,:password,:role)");
            $result =  $stmt-> execute(
                array(':name'=>$name,':email'=>$email,':password'=> $password,':role' => $role)
            );
            if($result){
                echo  "<script>alert('Successfully added new user');window.location.href='userlist.php'</script>";
            }

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
                <h3 class="card-title">New User</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <form action="user_add.php" method="POST" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <div class="form-group">
                        <label for="name" name="name"> Name</label><p style="color:red;display:inline;"><?php echo empty($nameError) ? '' : '*'.$nameError ?></p>
                        <input type="name" class="form-control" name="name" >
                    </div>

                    <div class="form-group">
                        <label for="email" name="email"> Email</label><p style="color:red;display:inline;"><?php echo empty($emailError) ? '' : '*'.$emailError ?></p>
                        <input type="email" class="form-control" name="email" >
                    </div>

                    <div class="form-group">
                        <label for="password" name="password"> Password </label><p style="color:red;display:inline;"><?php echo empty($passwordError) ? '' : '*'.$passwordError ?></p>
                        <input type="password" class="form-control" name="password" >
                    </div>

                    <div class="form-group">
                        <label for="admin" name="password"> Admin &nbsp;&nbsp; </label>
                        <input type="checkbox" id="admin" name="admin" value="1">
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