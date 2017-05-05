    <?php
    /**
     * Created by PhpStorm.
     * User: louis
     * Date: 4/28/17
     * Time: 10:28 AM
     */
    session_start();
    require_once('include/header.php');
    require_once ('include/dbconfig.php');
    $pdo = db_connect();
    $pid = $_GET['pid'];
    $username = $_SESSION['username'];

    $stmt = $pdo -> prepare(
            "select *
                       from Payment
                       where username = :username"
    );
    $stmt -> execute([':username' => $username]);
    $existingPayments = $stmt -> fetchAll();
    ?>

        <!-- Bootstrap Select CSS -->
        <link rel="stylesheet" href="css/components/bs-select.css" type="text/css" />

        <link rel="stylesheet" href="css/responsive.css" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

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

        <!-- Page Title
        ============================================= -->
        <section id="page-title">
        </section><!-- #page-title end -->

        <script src="../bower_components/bootstrap-fileinput/js/fileinput.min.js"></script>
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <link rel="stylesheet" href="../bower_components/bootstrap-fileinput/css/fileinput.min.css">

        <!-- Content
        ============================================= -->
        <section id="CreateProject">

            <div class="content-wrap">

                <form id="project-basic" name="project-basic" class="nobottommargin" action="process_pledge.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="pid" value='<?php echo $pid;?>'/>
                    <div class="container clearfix">
                        <div class="col_full">
                            <div class="white-section">
                                <label>How much you would like to pledge</label>
                                <input type="text" id="pledgeAmount" name="pledgeAmount" value="" class="sm-form-control" />
                                <label>Select your exsiting payments</label>
                                <select class="selectpicker" id="card-number" name="card-number" data-width="75%" data-style="btn-primary">
                                    <?php
                                    if (sizeof($existingPayments) > 0) {
                                        foreach ($existingPayments as $row) {
                                            $num = $row['cardNum'];
                                            echo "<option value='$num'>$num</option>";
                                        }
                                    } else {
                                        echo "<option> No existing payment </option>";
                                    }
                                    ?>
                                </select>
                                <?php
                                if (sizeof($existingPayments) > 0) {
                                    echo "<button type='submit' class='button button-3d'>Pay with this card</button>";
                                } else {
                                    echo "<button type='submit' class='button  button-3d' disabled>Pay with this card</button>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="project-basic" name="project-basic" class="nobottommargin" action="process_pledge.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="pid" value='<?php echo $pid;?>'/>
                    <div class="container clearfix">
                        <div class="col-md-6">
                            <div class="col_full">
                                <label for="name-on-card">Name on card:</label>
                                <input type="text" id="name-on-card" name="name-on-card" value="" class="sm-form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col_full">
                                <label>How much you would like to pledge</label>
                                <input type="text" id="pledgeAmount" name="pledgeAmount" value="" class="sm-form-control" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col_three_fifth">
                                <label for="card-number">Card Number:</label>
                                <input type="text" id="card-number" name="card-number" value="" class="sm-form-control" />
                            </div>
                        </div>
                        <div class="col-mid-12">
                            <div class="col-md-6">
                                <div class="col_full">
                                    <label for="expiration-date">Expiration date:</label>
                                    <input type="text" id="expiration-date" name="expiration-date" placeholder="MM/YY" class="sm-form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col_full">
                                    <label for="cvv">CVV number:</label>
                                    <input type="text" id="cvv" name="cvv" value="" class="sm-form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col_full">
                                <label for="billingAdd">Billing Address</label>
                                <input type="text" id="billingAdd" name="billingAdd" value="" class="sm-form-control" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col_one_third">
                                <div class="col_full">
                                    <label for="city">City:</label>
                                    <input type="text" id="city" name="city" value="" class="sm-form-control" />
                                </div>
                            </div>
                            <div class="col_one_third">
                                <div class="col_full">
                                    <label for="state">State:</label>
                                    <input type="text" id="state" name="state" value="" class="sm-form-control" />
                                </div>
                            </div>
                            <div class="col_one_fourth">
                                <div class="col_full">
                                    <label for="zip">Zip:</label>
                                    <input type="text" id="zip" name="zip" value="" class="sm-form-control" />
                                </div>
                            </div>
                            <button type="submit" class="button button-3d fright">Pay with new card</button>
                        </div>
                    </div>
                </form>
            </div>

        </section><!-- #content end -->

    <?php
    require_once ('include/footer.html');
    ?>