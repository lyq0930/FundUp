<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/28/17
 * Time: 10:28 AM
 */
session_start();
require_once('include/header.php');
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
            <form id="project-basic" name="project-basic" class="nobottommargin" action="process_newproject.php" method="post" enctype="multipart/form-data">
                <div class="container clearfix">

                    <div class="col-md-6">
                        <div class="col_full">
                            <label for="project-name">Project Name:</label>
                            <input type="text" id="project-name" name="project-name" value="" class="sm-form-control" />
                        </div>

                        <div class="input-daterange travel-date-group nobottommargin">
                            <div class="col-full bottommargin-sm">
                                <label for="">Fund Expiration Date</label>
                                <input type="text" id="project-endfund-date" name="project-endfund-date"
                                       value="" class="sm-form-control tleft format" placeholder="YYYY-MM-DD">
                            </div>
                            <div class="col-full nobottommargin">
                                <label for="">Project Intended Completion Date</label>
                                <input type="text" id="project-complete-date" name="project-complete-date"
                                       value="" class="sm-form-control tleft format" placeholder="YYYY-MM-DD">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col_full">
                            <label for="project-tags">Tags:</label>
                            <input type="text" id="project-tags" name="project-tags" value="" class="sm-form-control" />
                        </div>

                        <div class="col_full">
                            <label for="project-minfund">Minimum Fund Amount</label>
                            <input type="text" id="project-minfund" name="project-minfund" value="" class="sm-form-control" />
                        </div>

                        <div class="col_full">
                            <label for="project-maxfund">Maximum Fund Amount</label>
                            <input type="text" id="project-maxfund" name="project-maxfund" value="" class="sm-form-control" />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <!--<div class="col-full">-->
                        <!--<label>Add Multi-media related to your project</label><br>-->
                        <!--<input id="input-1" name="media" type="file" class="file" data-show-upload="false">-->
                        <!--</div>-->


                        <!--<input id="file" name = "file" type="file" class="file" data-preview-file-type="text" data-show-upload="true">-->
                        <label for="project-maxfund">Upload multimedia file to your project</label>
                        <input type="file" name="file" id="file"><br>

                        <div class="col_full">
                            <label for="project-description">Project Description <small>*</small></label>
                            <textarea class="sm-form-control" id="project-description" name="project-description" rows="6" cols="30"></textarea>
                        </div>
                        <button type="submit" class="button button-3d fright">Submit</button>
                    </div>
                </div>
        </div>
        </form>
        </div>

    </section><!-- #content end -->

<?php
require_once ('include/footer.html');
?>