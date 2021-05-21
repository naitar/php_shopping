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

if($_POST)
{
    if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) || empty($_POST['quantity'])
    || empty($_POST['price']) || empty($_FILES['image']['name']))
    {
        if(empty($_POST['name']))
        {
            $nameError = "Category name is required";
        }
        if(empty($_POST['description']))
        {
            $descError = "Category description is required";
        }
        if(empty($_POST['category']))
        {
            $categoryError = "Category is required";
        }
        if(empty($_FILES['image']['name']))
        {            
            $imageError = "Image is required";            
        }
        if(empty($_POST['quantity']))
        {
            $qtyError = "Quantity is required";
        }
        elseif(is_numeric($_POST['quantity']) != 1)
        {
            $qtyError = "Quantity should be integer value";
        }
        if(empty($_POST['price']))
        {
            $priceError = "Price is required";
        }
        elseif(is_numeric($_POST['price']) != 1)
        {
            $priceError = "Price should be integer value";
        }

    }
    else
    {
        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file, PATHINFO_EXTENSION);

        if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png')
        {
            echo "<script> alert('Image should be jpg, jpeg, png'); </script>";
        }
        else  //image validation success;
        {
            $name = $_POST['name'];            
            $description = $_POST['description'];
            $category = $_POST['category'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];            
            $image = $_FILES['image']['name'];

            move_uploaded_file($_FILES['image']['tmp_name'], $file);


            $stmt = $pdo -> prepare("INSERT INTO products (name, description, category_id, quantity, price, image)
                VALUES (:name,:description,:category,:quantity,:price,:image)");
            $result = $stmt ->execute(
                array(':name'=>$name,':description'=>$description,':category'=>$category,':quantity'=>$quantity,':price'=>$price,':image'=>$image)                
            );

            // print_r($result);

            if ($result)
            {                                
                echo "<script> alert('Product is add successful.'); window.location.href='index.php';</script>";
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
                <h3 class="card-title">Categories listing</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <form action="product_add.php" method="POST" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="<?php echo escape($_SESSION['_token']); ?>">
                    <div class="form-group">
                        <label for="name" name="name"> name</label>
                        <p style="color:red;display:inline;"><?php echo empty($nameError) ? '' : '*'.$nameError ?></p>
                        <input type="text" class="form-control" name="name">
                    </div>

                    <div class="form-group">
                        <label for="content" name="description"> Description</label>
                        <p style="color:red;display:inline;"><?php echo empty($descError) ? '' : '*'.$descError ?></p>
                        <br>
                        <textarea class="form-control" name="description" id="" cols="80" rows="8"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="content" name="Category">Select Category</label>
                        <p style="color:red;display:inline;"><?php echo empty($descError) ? '' : '*'.$descError ?></p>
                        <br>
                        <select class="form-control" name="category" id="">
                            <option value="">SELECT</option>
                            <?php 
                              $stmt = $pdo->prepare("SELECT * FROM categories");
                              $stmt->execute();
                              $catResult = $stmt->fetchAll();

                              foreach ($catResult as $value) 
                              {  ?>
                            <option value='<?php echo $value['id']; ?>'> <?php echo $value['name']; ?> </option>
                            <?php
                              }                                                                                                                  
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="content" name="quantity"> Quantity</label>
                        <p style="color:red;display:inline;"><?php echo empty($qtyError) ? '' : '*'.$qtyError ?></p><br>
                        <input type="number" class="form-control" name="quantity" value="">
                    </div>

                    <div class="form-group">
                        <label for="content" name="price"> Price </label>
                        <p style="color:red;display:inline;"><?php echo empty($priceError) ? '' : '*'.$priceError ?></p>
                        <br>
                        <input type="number" class="form-control" name="price" value="">
                    </div>

                    <div class="form-group">
                        <label for="content" name="image"> Image </label>
                        <p style="color:red;display:inline;"><?php echo empty($imageError) ? '' : '*'.$imageError ?></p>
                        <br>
                        <input type="file" name="image" id="image">
                    </div>

                    <div class="form-goup">
                        <input type="submit" value="submit" class="btn btn-success">
                        <a href="index.php" class="btn btn-warning"> Back </a>
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