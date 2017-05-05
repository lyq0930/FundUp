<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/28/17
 * Time: 10:28 AM
 */
session_start();
require_once ('include/header.html');
$_SESSION['pid'] = $_GET['pid'];
?>

    <!-- Page Title
    ============================================= -->
    <section id="page-title">

        <div class="container clearfix">
            <h1>Create a new project</h1>
            <span>Please type the basic info for the project you would like to be created. The below info are required, and cannot be empty.</span>
            <ol class="breadcrumb">
                <li><a href="index">Back</a></li>
            </ol>
        </div>

    </section><!-- #page-title end -->

    <script src="../bower_components/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="../bower_components/bootstrap-fileinput/css/fileinput.min.css">

    <!-- Content
    ============================================= -->
    <section id="CreateProject">

        <div class="content-wrap">
            <form id="project-basic" name="project-basic" class="nobottommargin" action="process_updateproject.php" method="post" enctype="multipart/form-data">
                <div class="container clearfix">
                    <div class="col-md-12">
                        <label for="project-image">Upload multimedia file to your project</label>
                        <input type="file" name="file" id="file"><br>

                        <div class="col_full">
                            <label for="project-description">Project Description <small>*</small></label>
                            <textarea class="sm-form-control" id="project-description" name="project-description" rows="6" cols="30"></textarea>
                        </div>
                        <button type="submit" class="button button-3d fright">Update</button>
                    </div>
                </div>
            </form>
        </div>

    </section><!-- #content end -->

<?php
require_once ('include/footer.html');
?>