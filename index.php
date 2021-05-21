<?php
	session_start();
	require 'Config/config.php';
	


	if (!empty($_GET['pageno'])) 
	{
		$pageno = $_GET['pageno'];
	}
	else 
	{
		$pageno = 1;
	}

	$numOfrecs  = 3;
	$offset = ($pageno - 1) * $numOfrecs;

	if(isset($_POST['search']))
	{
		setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day
	}
	else
	{
		if(empty($_GET['pageno']))
		{
			unset($_COOKIE['search']);
			setcookie('search', null, -1, '/');
		}
	}

	if(isset($_GET['category_id']))
	{	
		$category_id = $_GET['category_id'];
		setcookie('category_id', $category_id, time() + (86400 * 30), "/");
	}
	else
	{
		if(empty($_GET['pageno']))
		{
			unset($_COOKIE['category_id']);
			setcookie('category_id', null, -1, '/');
		}
	}

	if(empty($_POST['search']) && empty($_COOKIE['search'])) 
	{
		if(isset($_GET['category_id']) || isset($_COOKIE['category_id']))
		{	
			$category_id =  isset($_GET['category_id']) ? $_GET['category_id'] : $_COOKIE['category_id'];
	
			$stmt = $pdo->prepare("SELECT * FROM products WHERE  quantity > 0 AND category_id ='$category_id'");
			$stmt->execute();
			$rawResult = $stmt->fetchAll();

			$total_pages = ceil(count($rawResult) / $numOfrecs);

			$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = '$category_id' AND quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfrecs ");
			$stmt->execute();
			$result = $stmt->fetchAll();
		}
		else
		{
			$stmt = $pdo->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC");
			$stmt->execute();
			$rawResult = $stmt->fetchAll();

			$total_pages = ceil(count($rawResult) / $numOfrecs);

			$stmt = $pdo->prepare("SELECT * FROM products WHERE quantity > 0  ORDER BY id DESC LIMIT $offset,$numOfrecs");
			$stmt->execute();
			$result = $stmt->fetchAll();
		}
	}	
	else 
	{
		$searchkey = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'] ;
		$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchkey%' AND quantity > 0 ORDER BY id DESC");
		$stmt->execute();
		$rawResult = $stmt->fetchAll();

		$total_pages = ceil(count($rawResult) / $numOfrecs);

		$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchkey%'  AND quantity > 0  ORDER BY id DESC LIMIT $offset,$numOfrecs ");
		$stmt->execute();
		$result = $stmt->fetchAll();
	}

	include('header.php');
?>
<!-- End Banner Area -->
<div class="container">
    <div class="row">

        <div class="col-xl-3 col-lg-4 col-md-5">
            <div class="sidebar-categories">
                <div class="head">Browse Categories</div>
                <?php 
						$catstmt = $pdo->prepare("SELECT * FROM categories order by id DESC");
						$catstmt->execute();
						$catresult = $catstmt -> fetchAll();						
					?>

                <ul class="main-categories">
                    <li class="main-nav-list">
                        <?php 
							if($catresult)
							{
								foreach ($catresult as $key => $value) {?>
                        <a href="index.php?category_id=<?php echo escape($value['id']);?> "><span
                                class="lnr lnr-arrow-right">
                            </span><?php echo escape($value['name']) ?>
                            <span class="number">
                                (
                                <?php 
										$category_id = $value['id'];
										$Countstmt = $pdo->prepare("SELECT COUNT(id) FROM products WHERE quantity > 0 AND category_id='$category_id'");
										$Countstmt->execute();
										$CountResult = $Countstmt -> fetch(PDO::FETCH_ASSOC);
																		
										echo escape($CountResult['COUNT(id)']);										
									?>
                                )
                            </span>
                        </a>

                        <?php 
										}
							 		}
						?>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8 col-md-7">
            <!-- Start Filter Bar -->
            <div class="filter-bar d-flex flex-wrap align-items-center">

                <ul class="pagination">
                    <li class="page-item <?php if($pageno <= 1) {echo "disabled";} ?>"> <a
                            href=" <?php if ($pageno <= 1) {echo '#';} else {echo "?pageno=" . ($pageno - 1);} ?> "
                            class="disabled"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a> </li>
                    <li class="page-item"> <a href="<?php echo "?pageno=".($pageno) ?>" class="active">
                            <?php echo $pageno; ?> </a> </li>
                    <li class="page-item <?php if(($pageno+1) > $total_pages) {echo 'disabled';} ?>"> <a
                            href="<?php echo "?pageno=".($pageno+1) ?>" class=""> <?php echo $pageno+1 ?> </a> </li>
                    <li class="page-item <?php if(($pageno+2) > $total_pages) {echo 'disabled';} ?>"> <a
                            href="<?php echo "?pageno=".($pageno+2) ?>" class=""> <?php echo $pageno+2 ?> </a> </li>
                    <li class="page-item <?php if(($pageno+3) > $total_pages) {echo 'disabled';} ?>"> <a
                            href="<?php echo "?pageno=".($pageno+3) ?>" class=""> <?php echo $pageno+3 ?> </a> </li>
                    <li class="page-item <?php if(($pageno+4) > $total_pages) {echo 'disabled';} ?>"> <a
                            href="<?php echo "?pageno=".($pageno+4) ?>" class=""> <?php echo $pageno+4 ?> </a> </li>
                    <li class="page-item <?php if ($pageno >= $total_pages) {echo 'disabled';} ?>"> <a
                            href=" <?php if ($pageno >= $total_pages) {echo '#';} else {echo "?pageno=" . ($pageno + 1);} ?> "
                            class=" disabled"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a> </li>
                </ul>

            </div>


            <!-- End Filter Bar -->
            <!-- Start Best Seller -->
            <section class="lattest-product-area pb-40 category-list">
                <div class="row">
                    <!-- single product -->
                    <?php
						if($result){
							foreach($result as $value){?>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-product">
                            <a href="product_detail.php?id=<?php echo escape($value['id']);?>">
                                <img class="img-fluid" src="admin/images/<?php echo escape($value['image']) ?>"
                                    alt="image" style="height: 220px;" >
                            </a>

                            <div class="product-details">
                                <h6><?php echo escape($value['name']) ?></h6>
                                <div class="price">
                                    <h6><?php echo escape(number_format($value['price'])) ?></h6>
                                    <!-- <h6 class="l-through">$51447</h6> -->
                                </div>
                                <div class="prd-bottom">
									<form action="addtocart.php" method="POST">
									<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
									<input type="hidden" name="id" value="<?php echo escape($value['id']) ?>">
									<input type="hidden" name="qty" value="1">
									
									<div class="social-info">
										<button type="submit" style="display: contents;">
										<span class="ti-bag"></span>
										<p class="hover-text" style="left: 20px;">add to bag</p>
										</button>
								
									</div>
							

                                    <a href="product_detail.php?id=<?php echo escape($value['id']);?>"
                                        class="social-info">
                                        <span class="lnr lnr-move"></span>
                                        <p class="hover-text"> view more </p>
                                    </a>
									</form>

                                </div>
                            </div>
							
                        </div>
                    </div>
                    <?php	}
						}
					?>
                </div>
        </div>
        </section>
        <!-- End Best Seller -->
        <?php include('footer.php');?>