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
    || empty($_POST['price']))
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
        if(is_numeric($_POST['quantity']) != 1)
        {
            $qtyError = "Quantity should be integer value";
        }
        if(is_numeric($_POST['price']) != 1)
        {
            $priceError = "Price should be integer value";
        }
        if($qtyError == null && $priceError == null)
        {
            if($_FILES['image']['name'] != null)  //check image upload
            {
                $file = 'images/'.($_FILES['image']['name']);
                $imageType = pathinfo($file, PATHINFO_EXTENSION);

                if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png')
                {
                    echo "<script> alert('Image should be jpg, jpeg, png'); </script>";
                }
                else  //image validation success;
                {
                    $id = $_POST['id'];
                    $name = $_POST['name'];            
                    $description = $_POST['description'];
                    $category = $_POST['category'];
                    $quantity = $_POST['quantity'];
                    $price = $_POST['price'];            
                    $image = $_FILES['image']['name'];

                    move_uploaded_file($_FILES['image']['tmp_name'], $file);
                
                    $stmt = $pdo -> prepare("UPDATE products SET name = :name, description = :description, category_id = :category, quantity = :quantity, price = :price, image = :image WHERE id ='$id'");             
              
                    $result = $stmt ->execute(
                        array(':name'=>$name,':description'=>$description,':category'=>$category,':quantity'=>$quantity,':price'=>$price, ':image'=> $image)
                    );

                    if ($result)
                    {                                
                        echo "<script> alert('Product is updated successful.'); window.location.href='index.php';</script>";
                    } 
                } 

            }
            else
            {
                $id = $_POST['id'];   
                $name = $_POST['name'];            
                $description = $_POST['description'];
                $category = $_POST['category'];
                $quantity = $_POST['quantity'];
                $price = $_POST['price'];                       
      
                $stmt = $pdo -> prepare("UPDATE products SET name = :name, description = :description, category_id = :category, quantity = :quantity, price = :price  WHERE id='$id'");

                $result = $stmt ->execute(
                    array(':name'=>$name,':description'=>$description,':category'=>$category,':quantity'=>$quantity,':price'=>$price)
                );

                if ($result)
                {                                
                    echo "<script> alert('Product is updated successful.'); window.location.href='index.php';</script>";
                } 
             
            }  
        }      
    }
}

$stmt = $pdo -> prepare("SELECT * FROM products WHERE id=".$_GET['id']);
$stmt -> execute();
$result= $stmt -> fetch(PDO::FETCH_ASSOC);

?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Product listing</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo escape($_SESSION['_token']); ?>">
                <input type="hidden" class="form-control" name="id" value="<?php echo escape($result['id']); ?>" >
                    <div class="form-group">
                        <label for="name" name="name"> name</label>
                        <p style="color:red;display:inline;"><?php echo empty($nameError) ? '' : '*'.$nameError ?></p>
                        <input type="text" class="form-control" name="name" value="<?php echo escape($result['name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="content" name="description"> Description</label>
                        <p style="color:red;display:inline;"><?php echo empty($descError) ? '' : '*'.$descError ?></p><br>
                        <textarea class="form-control" name="description" id="" cols="80" rows="8"><?php echo escape($result['description']); ?>
                        </textarea>    
                    </div>

                    <div class="form-group">
                    <?php 
                        $stmt = $pdo->prepare("SELECT * FROM categories");
                        $stmt->execute();
                        $catResult = $stmt->fetchAll();
                    ?>
                        <label for="content" name="Category" disable>Select Category</label>
                        <p style="color:red;display:inline;"><?php echo empty($descError) ? '' : '*'.$descError ?></p><br>
                        <select class="form-control" name="category" id="">
                        <option value="">SELECT CATEGORY</option>    
                        <?php 
                            foreach ($catResult as $value)
                            
                            { ?>                            
                            <?php if($value['id'] == $result['category_id']) : ?>                                
                                <option value = '<?php echo $value['id']; ?>' selected> <?php echo $value['name']; ?> </option>                            
                            <?php else : ?>
                                <option value = '<?php echo $value['id']; ?>' > <?php echo $value['name']; ?> </option>    
                            <?php endif; ?>
                            <?php            
                            }
                        ?>                                                      
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="content" name="quantity"> Quantity</label>
                        <p style="color:red;display:inline;"><?php echo empty($qtyError) ? '' : '*'.$qtyError ?></p><br>                        
                        <input type="number" class="form-control" name="quantity" value="<?php echo escape($result['quantity']); ?>" >
                    </div>

                    <div class="form-group">
                        <label for="content" name="price"> Price </label>
                        <p style="color:red;display:inline;"><?php echo empty($priceError) ? '' : '*'.$priceError ?></p><br>
                        <input type="number" class="form-control" name="price" value="<?php echo escape($result['price']); ?>" >
                    </div>

                    <div class="form-group">
                        <label for="content" name="image"> Image </label>
                        <p style="color:red;display:inline;"><?php echo empty($imageError) ? '' : '*'.$imageError ?></p><br>
                        <img src="images/<?php echo escape($result['image']) ;?>" width="150" height="150"><br>
                        <input type="file"  name="image" id="image" >
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