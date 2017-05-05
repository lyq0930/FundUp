<?php
session_start();
require_once('include/header.php');
require_once ('include/dbconfig.php');
require_once ('include/helpfulFunctions.php');
$pid = $_GET['pid'];
$username = $_SESSION['username'];
$pdo = db_connect();

$log = $pdo -> prepare(
        "insert into log(username, operation, target)
                   values (:username, :operation, :target)"
);
$operation = "look project";
$log -> bindParam(":username", $username, $pdo::PARAM_STR);
$log -> bindParam(":operation", $operation, $pdo::PARAM_STR);
$log -> bindParam(":target", $pid, $pdo::PARAM_INT);
$log -> execute();


$stmt = $pdo -> prepare(
        "Select pid, pname, pdescription, pOwner, tags, Date(projectCreatedTime) createT, Date(endFundTime) endFT,
                          Date(completionDate) endPT, minFund, maxFund, fundSoFar, pstatus, cover
                   From Project
                   WHERE pid = :pid"
);
$stmt -> execute([':pid' => $pid]);
$project = $stmt -> fetch();

$stmt = $pdo -> prepare (
        "select *
                   from ProjectDetails
                   where pid = :pid
                   order by updateTime desc"
);
$stmt -> execute([':pid' => $pid]);
$projectUpdates = $stmt -> fetchAll();

// Made Changes
$stmt = $pdo -> prepare(
        "Select tags
                   From Project
                   WHERE pid = :pid"
);
$stmt -> execute([':pid' => $pid]);
$result = $stmt -> fetch();

function displaytags($record){
    $tags = $record['tags'];
    $tagsArray = explode(",", $tags);
    echo "
    <!-- Tag Cloud -->
    
    <div class='tagcloud clearfix bottommargin'>";
        foreach($tagsArray as $row) {
            echo "<a href='projectbucket.php?tag=$row'>$row</a>";//TODO
        }
    echo "
    </div><!-- .tagcloud end --> 
    ";
}

$stmt = $pdo -> prepare(
        "select *
                   from Discussion
                   where pid = :pid"
);
$stmt -> execute([':pid' => $pid]);
$projectDiscussion = $stmt -> fetchAll();

function displayComments($username, $aComment, $commentPostedTime) {
//    $commentPostedTime = date_format(date_create($commentPostedTime), 'Y-m-d H:m:s');
    echo "
    <div id='comment-1' class='comment-wrap clearfix'>

        <div class='comment-meta'>

            <div class='comment-author vcard'>

                <span class='comment-avatar clearfix'>
                <img alt='' src='http://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=60' class='avatar avatar-60 photo avatar-default' height='60' width='60' /></span>

            </div>

        </div>

        <div class='comment-content clearfix'>

            <div class='comment-author'>$username<span><a href='#' title='Permalink to this comment'>$commentPostedTime</a></span></div>

            <p>$aComment</p>

            <a class='comment-reply-link' href='#'><i class='icon-reply'></i></a>

        </div>

        <div class='clear'></div>

    </div>
    ";
}

function relatedPost($record,$pdo){
    $tags = $record['tags'];
    $tagsArray = explode(",", $tags);

    $stmt1 = $pdo -> prepare(
            "SELECT pid, pname, pdescription, pOwner, tags, Date(projectCreatedTime) createT, Date(endFundTime) endFT,
                      Date(completionDate) endPT, minFund, maxFund, fundSoFar, pstatus, cover
               FROM Project
               WHERE tags LIKE :tag1
               ORDER BY createT DESC
               LIMIT 2"
    );

    $stmt2 = $pdo -> prepare(
        "SELECT pid, pname, pdescription, pOwner, tags, Date(projectCreatedTime) createT, Date(endFundTime) endFT,
                      Date(completionDate) endPT, minFund, maxFund, fundSoFar, pstatus, cover
               FROM Project
               WHERE tags LIKE :tag2
               ORDER BY createT DESC
               LIMIT 2"
    );
    $tag1 = "%".$tagsArray[0]."%";
    $tag2 = "%".$tagsArray[1]."%";
    $stmt1 -> execute([':tag1' => $tag1]);
    $result1 = $stmt1 -> fetchAll();


    $title1 = $result1[0]['pname'];
    $createdTime1 = $result1[0]['createT'];
    $description1 = $result1[0]['pdescription'];
    $pid1 = $result1[0]['pid'];
    $pname1 = $result1[0]['pname'];

    $stmt2 -> execute([':tag2' => $tag2]);
    $result2 = $stmt2 -> fetchAll();

    $title2 = $result2[0]['pname'];
    $createdTime2 = $result2[0]['createT'];
    $description2 = $result2[0]['pdescription'];
    $pid2 = $result2[0]['pid'];
    $pname2 = $result2[0]['pname'];

    echo "
    <h4>Related Posts:</h4>

							<div class='related-posts clearfix'>

								<div class='col_half nobottommargin'>
								    

									<div class='mpost clearfix'>
										<div class='entry-image'>
											<a href='#'><img src='projectimage.php?pid=$pid1' alt=$pname1></a>
										</div>
										<div class='entry-c'>
											<div class='entry-title'>
												<h4><a href='project.php?pid=$pid1'>$title1</a></h4>
											</div>
											<ul class='entry-meta clearfix'>
												<li><i class='icon-calendar3'></i> $createdTime1</li>
											</ul>
											<div class='entry-content'>$description1</div>
										</div>
									</div>

								</div>
								
								<div class='col_half nobottommargin col_last'>

									<div class='mpost clearfix'>
										<div class='entry-image'>
											<a href='#'><img src='projectimage.php?pid=$pid2' alt=$pname2></a>
										</div>
										<div class='entry-c'>
											<div class='entry-title'>
												<h4><a href='project.php?pid=$pid2'>$title2</a></h4>
											</div>
											<ul class='entry-meta clearfix'>
												<li><i class='icon-calendar3'></i>$createdTime2</li>
											</ul>
											<div class='entry-content'>$description2</div>
										</div>
									</div>

								</div>
							
    ";
}
?>
		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

					<!-- Post Content
					============================================= -->
					<div class="postcontent nobottommargin clearfix">

						<div class="single-post nobottommargin">

							<!-- Single Post
							============================================= -->
							<div class="entry clearfix">

								<!-- Entry Title
								============================================= -->
								<div class="entry-title">
									<h2><?php echo $project['pname']?></h2>
								</div><!-- .entry-title end -->

								<!-- Entry Image
								============================================= -->
								<div class="entry-image">
									<a href="#"><img src='projectimage.php?pid=<?php echo $pid; ?>' alt="Blog Single"></a>
								</div><!-- .entry-image end -->

								<!-- Entry Content
								============================================= -->
								<div class="entry-content notopmargin">
                                    <h4>Description</h4>
                                    <p><?php echo $project['pdescription'];?></p>
                                    <?php
                                    if (isset($projectUpdates)) {
                                        echo "<div class='line'></div>";
                                        echo "<h4>Update</h4>";
                                        foreach ($projectUpdates as $row) {
                                            $updateTime = $row['updateTime'];
                                            $updateDescription = $row['updateDescription'];
                                            $updateTimeDisplay = $updateTime;
                                            echo "<i class='icon-calendar3'></i>Update at $updateTimeDisplay";
                                            echo "<img src='projectimage.php?pid=$pid&updateTime=$updateTime' alt='Blog Single''>";
                                            echo "<p>$updateDescription</p>";
                                            echo "<div class='divider divider-border'><i class='icon-refresh'></i></div>";
                                        }
                                    }
                                    ?>
									<!-- Post Single - Content End -->

									<div class="clear"></div>

									<!-- Post Single - Share
									============================================= -->
									<div class="si-share noborder clearfix">
										<span>Share this Post:</span>
										<div>
											<a href="#" class="social-icon si-borderless si-facebook">
												<i class="icon-facebook"></i>
												<i class="icon-facebook"></i>
											</a>
											<a href="#" class="social-icon si-borderless si-twitter">
												<i class="icon-twitter"></i>
												<i class="icon-twitter"></i>
											</a>
											<a href="#" class="social-icon si-borderless si-pinterest">
												<i class="icon-pinterest"></i>
												<i class="icon-pinterest"></i>
											</a>
											<a href="#" class="social-icon si-borderless si-gplus">
												<i class="icon-gplus"></i>
												<i class="icon-gplus"></i>
											</a>
											<a href="#" class="social-icon si-borderless si-rss">
												<i class="icon-rss"></i>
												<i class="icon-rss"></i>
											</a>
											<a href="#" class="social-icon si-borderless si-email3">
												<i class="icon-email3"></i>
												<i class="icon-email3"></i>
											</a>
										</div>
									</div><!-- Post Single - Share End -->

								</div>
							</div><!-- .entry end -->

							<div class="line"></div>

                            <?php
                            relatedPost($result,$pdo);
                            ?>



							</div>

                            <!-- Comments
							============================================= -->
							<div id="comments" class="clearfix">

								<h3 id="comments-title">Comments</h3>

								<!-- Comments List
								============================================= -->
								<ol class="commentlist clearfix">

									<li class="comment even thread-even depth-1" id="li-comment-1">
                                        <?php
                                        foreach ($projectDiscussion as $row) {
                                            displayComments($row['username'], $row['aComment'], $row['commentPostedTime']);
                                        }
                                        ?>
									</li>

								</ol><!-- .commentlist end -->

								<div class="clear"></div>

								<!-- Comment Form
								============================================= -->
								<div id="respond" class="clearfix">

									<h3>Leave a <span>Comment</span></h3>

									<form class="clearfix" action="process_postComment.php" method="post" id="commentform">
                                        <div class="col_full">
                                            <input type='hidden' id="pid" name="pid" value='<?php echo $pid;?>'/>
                                            <label for="acomment">Comment</label>
											<textarea name="aComment" cols="58" rows="7" tabindex="4" class="sm-form-control"></textarea>
										</div>

										<div class="col_full nobottommargin">
											<button name="submit" type="submit" id="submit-button" tabindex="5" value="Submit" class="button button-3d nomargin">Submit Comment</button>
										</div>

                                    </form>

								</div><!-- #respond end -->

							</div><!-- #comments end -->

						</div>

					</div><!-- .postcontent end -->

					<!-- Sidebar
					============================================= -->
					<div class="sidebar col_last clearfix">
						<div class="sidebar-widgets-wrap">

                            <div class="widget clearfix">

                                <h4>Project bio</h4>
                                <div class="portfolio-desc">
                                    <ul class="iconlist">
                                        <li><i class="icon-ok"></i> <strong>Created:</strong> <?php echo $project['createT']?></li>
                                        <li><i class="icon-ok"></i> <strong>Fund end at:</strong> <?php echo $project['endFT']?></li>
                                        <li><i class="icon-ok"></i> <strong>Min Fund:</strong> <?php echo $project['minFund']?></li>
                                        <li><i class="icon-ok"></i> <strong>Max Fund:</strong> <?php echo $project['maxFund']?></li>
                                        <li><i class="icon-ok"></i> <strong>Fund SoFar:</strong> <?php echo $project['fundSoFar']?></li>
                                        <li><i class="icon-ok"></i> <strong>Complete:</strong> <?php echo $project['endPT']?></li>
                                        <li><i class="icon-ok"></i> <strong>Collected:</strong> <?php echo '$'.$project['fundSoFar']?></li>
                                        <li><i class="icon-ok"></i> <strong>Status:</strong> <?php echo $project['pstatus']?></li>
                                        <li><i class="icon-ok"></i> <strong>Owner:</strong> <?php $pOname = $project['pOwner']; echo "<a href='user.php?username=$pOname'>$pOname</a>" ?></li>
                                    </ul>

                                    <?php
                                    displaytags($result);
                                    ?>

                                    <div class="text-center">
                                    <?php
                                    if ($_SESSION['username'] == $project['pOwner']) {
                                        echo "<a href='updateProject.php?pid=$pid' class='button button-3d btn-block button-rounded button-teal'>Update your project</a>";
                                        if($project['pstatus'] == "Funded, in progessing") {
                                            echo "<a href='process_completeProject.php?pid=$pid' class='button button-3d btn-block button-rounded button-teal'>Mark it complete</a>";
                                        }
                                    }
                                    try {
                                        $stmt = $pdo->query("select * from UserLikes where username='$username' and pid=$pid");
                                    } catch (Exception $e) {
                                        warningMessage($e -> getMessage());
                                    }
                                    $hasLike = $stmt->fetch();
                                    if ($hasLike == true) {
                                        echo "<button class=\"button btn-block button-3d\" disabled>Liked</button>";
                                    } else {
                                        echo "<a href='likeProject.php?pid=$pid&username=$username' class='button button-3d btn-block button-rounded button-green'>Like the project</a>";
                                    }
                                    if ($project['pstatus'] == "Funding") {
                                        echo "<a href='pledgeProject.php?pid=$pid&username=$username' class='button button-3d btn-block button-rounded button-green'>Pledge the project</a>";
                                    }

                                    if($project['pstatus'] == "Completed") {
                                        echo "<form id='project-basic' name='project-basic' class='nobottommargin' action='rateProject.php' method='post' enctype='multipart/form-data'>";
//                                        echo "<lable>Rate the project</lable>";
                                        echo "<input type='hidden' name='pid' value='$pid' />";
                                        echo "<input type='text' name='stars' placeholder='1-5 stars' class='form-control'/>";
                                        echo "<button type=\"submit\" class=\"button button-3d\">Rate</button>";
                                        echo "</form>";
                                        //echo "<a href='process_completeProject.php?pid=$pid' class='button button-3d btn-block button-rounded button-teal'>Mark it complete</a>";
                                    }
                                    ?>
                                    </div>

                                </div>
                            </div>
						</div>

					</div><!-- .sidebar end -->

				</div>

			</div>

		</section><!-- #content end -->

<?php
require_once ('include/footer.html');
?>