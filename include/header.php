<?php
session_start();
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

    <!-- Bootstrap CSS v3 -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />

    <!-- Stylesheets
    ============================================= -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="css/swiper.css" type="text/css" />
    <link rel="stylesheet" href="css/dark.css" type="text/css" />
    <link rel="stylesheet" href="css/font-icons.css" type="text/css" />
    <link rel="stylesheet" href="css/animate.css" type="text/css" />
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css" />

    <link rel="stylesheet" href="css/responsive.css" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Date & Time Picker CSS -->
    <link rel="stylesheet" href="css/datepicker.css" type="text/css" />
    <link rel="stylesheet" href="css/components/timepicker.css" type="text/css" />
    <link rel="stylesheet" href="css/components/daterangepicker.css" type="text/css" />

    <!-- Bootstrap File Upload CSS -->
    <link rel="stylesheet" href="css/components/bs-filestyle.css" type="text/css" />


    <!-- Document Title
    ============================================= -->
    <title>FundUp - Fund your favorite projects up to success!</title>

</head>

<body class="stretched">

<!-- Document Wrapper
============================================= -->
<div id="wrapper" class="clearfix">

    <!-- Header
    ============================================= -->
    <header id="header">

        <div id="header-wrap">

            <div class="container clearfix">

                <div id="primary-menu-trigger"><i class="icon-reorder"></i></div>

                <!-- Logo
                ============================================= -->
                <div id="logo">
                    <a href="index.php" class="standard-logo" data-dark-logo="images/fundup_logo2.png"><img src="images/fundup_logo2.png" alt="Canvas Logo"></a>
                    <a href="index.php" class="retina-logo" data-dark-logo="images/fundup_logo2.png"><img src="images/fundup_logo2.png" alt="Canvas Logo"></a>
                </div><!-- #logo end -->

                <!-- Primary Navigation
                ============================================= -->
                <nav id="primary-menu" class="sub-title">

                    <ul>
                        <li class="current"><a href="index.php"><div>Home</div><span>Let's Start</span></a></li>
                        <li class="mega-menu"><a href="./user.php?username=<?php echo $username;?>"><div>Portfolio</div><span>Personal page</span></a></li>
                        <li class="mega-menu"><a href="./projectAll.php"><div>Discover</div><span>Let's discover</span></a></li>
                        <li class="mega-menu"><a href="./history.php?username=<?php echo $username;?>"><div>History</div><span>Pledge History</span></a>
                        </li>

                    </ul>

                    <!-- Top Search
                    ============================================= -->
                    <div id="top-search">
                        <a href="#" id="top-search-trigger"><i class="icon-search3"></i><i class="icon-line-cross"></i></a>
                        <form action="search.php" method="get">
                            <input type="text" name="q" class="form-control" value="" placeholder="Type &amp; Hit Enter..">
                        </form>
                    </div><!-- #top-search end -->

                    <!-- Top Account
============================================= -->
                    <div id="top-account" class="dropdown">
                        <a href="" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="icon-user"></i><i class="icon-angle-down"></i></a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                            <li><a href="logout.php">Logout <i class="icon-signout"></i></a></li>
                        </ul>
                    </div>
                </nav><!-- #primary-menu end -->

            </div>

        </div>

        <style>

            .white-section {
                background-color: #FFF;
                padding: 25px 20px;
                -webkit-box-shadow: 0px 1px 1px 0px #dfdfdf;
                box-shadow: 0px 1px 1px 0px #dfdfdf;
                border-radius: 3px;
            }

            .white-section label {
                display: block;
                margin-bottom: 15px;
            }

            .white-section pre { margin-top: 15px; }

            .dark .white-section {
                background-color: #111;
                -webkit-box-shadow: 0px 1px 1px 0px #444;
                box-shadow: 0px 1px 1px 0px #444;
            }

        </style>

    </header><!-- #header end -->