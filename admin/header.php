

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title> Sophia Store Admin Panel </title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- for DataTable -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"> 


</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white  navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

      </ul>

      <?php
      $link = $_SERVER['PHP_SELF'];
      $link_array = explode('/',$link);
      $page = end($link_array);

      ?>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3" method="POST" 
      <?php	if($page == 'index.php'):?>
        action="index.php"
      <?php elseif($page == 'category.php'):?>
        action="category.php"
      <?php elseif($page == 'userlist.php'):?>
        action="userlist.php"
      <?php endif; ?>
      >

      <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
      <?php
        // check search box show or hide;
        if($page == 'order_list.php' || $page == 'order_detail.php' || $page == 'weekly_report.php' ||
            $page == 'monthly_report.php' || $page == 'best_seller_item.php' || $page == 'royal_user.php'){

        }
        else{ ?>

          <div class="input-group input-group-sm">
            <input name="search" class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
      <?php  
        }
      ?>

      </form>


    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="#" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"> Sophia Store Panel </span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="dist/img/naitar.jpg" class="img-circle elevation-2" style="width: 35px; height: 35px;" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"> <?php echo $_SESSION['name']; ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-p ills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p> Product </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="category.php" class="nav-link">
                <i class="nav-icon fa fa-list"></i>
                <p>Category</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="userlist.php" class="nav-link">
                <i class="nav-icon fa fa-user"></i>
                <p>Users</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="order_list.php" class="nav-link">
                <i class="nav-icon fa fa-table"></i>
                <p>Order</p>
              </a>
            </li>
            <li class="nav-item has-treeview menu">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Reports
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="weekly_report.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Weekly Report</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="monthly_report.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Monthly Report</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="royal_user.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Royal Customers</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="best_seller_item.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Best Seller Items</p>
                  </a>
                </li>
              </ul>
            </li>

          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">

        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->