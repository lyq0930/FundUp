<?php
/**
 * Created by PhpStorm.
 * User: emily
 * Date: 5/5/17
 * Time: 1:50 AM
 */
require_once ('include/header.html');
require_once ('include/dbconfig.php');
$username = $_SESSION['username'];
$aPerson = $_GET['username'];
$pdo = db_connect();
$stmt = $pdo -> prepare(
    "Select *
               From Users
               WHERE username = :username"
);
$stmt -> execute([':username' => $aPerson]);
$result = $stmt -> fetch();

$email = $result['email'];
$hometown = $result['hometown'];
$interest = $result['interests'];
$setupTime = $result['setupTime'];

//GET PLEDGED MONEY
$stmt = $pdo -> prepare(
        "SELECT SUM(fundAmount) as allMoney
                    FROM Fund
                    WHERE username = :username and moneyStatus = 'Released'"
);
$stmt -> execute([':username' => $aPerson]);
$moneyResult = $stmt -> fetch();

$money = $moneyResult['allMoney'];

//GET PROJECT
$stmt = $pdo -> prepare(
    "Select pid, pname, pdescription, pOwner, tags, Date(projectCreatedTime) createT, Date(endFundTime) endFT,
                          Date(completionDate) endPT, minFund, maxFund, fundSoFar, pstatus, cover
               From Project
               WHERE pOwner = :username"
);
$stmt -> execute([':username' => $aPerson]);
$project = $stmt -> fetchAll();

function showProject($project){
    $createTime = $project['createT'];
    $endTime = $project['endFT'];
    $completeTime = $project['endPT'];
    $pid = $project['pid'];
    $pname = $project['pname'];
    $pdescription = $project['pdescription'];
    $allTags = $project['tags'];

    echo "
        <article class='portfolio-item pf-illustrations alt clearfix'>
            <div class='portfolio-image'>
                <a href='project.php?pid=$pid'>
                    <img src='projectimage.php?pid=$pid' alt= '$pname'>
                </a>
            </div>
            <div class='portfolio-desc'>
                <h3><a href='project.php?pid=$pid'>$pname</a></h3>
                <p>'$pdescription'</p>
                <ul class='iconlist'>
                    <li><i class='icon-ok'></i> <strong>Created:</strong>$createTime</li>
                    <li><i class='icon-ok'></i> <strong>End Fund:</strong>$endTime</li>
                    <li><i class='icon-ok'></i> <strong>Complete:</strong>$completeTime</li>
                </ul>
                <a href='project.php?pid=$pid' class='button button-3d noleftmargin'>Have A Look</a>
            </div>
        </article>
    ";
}

//GET LIKED PROJECT
$stmt = $pdo -> prepare(
    "Select P.pid, pname, pdescription, pOwner, tags, Date(projectCreatedTime) createT, Date(endFundTime) endFT,
                                               Date(completionDate) endPT, minFund, maxFund, fundSoFar, pstatus, cover
                  From Project P JOIN UserLikes U using (pid)
                  WHERE U.username = :username
                  ORDER BY createT
                  LIMIT 3"
);
$stmt -> execute([':username' => $aPerson]);
$likedProject = $stmt -> fetchAll();

function showLikedProject($likedProject){
    $pid = $likedProject['pid'];
    $pname = $likedProject['pname'];
    $createT = $likedProject['createT'];

    echo "
        <div class='spost clearfix'>
            <div class='entry-image'>
                <a href='project.php?pid=$pid' class='nobg'><img src='projectimage.php?pid=$pid' alt=''></a>
            </div>
            <div class='entry-c'>
                <div class='entry-title'>
                    <h4><a href='project.php?pid=$pid'>$pname</a></h4>
                </div>
                <ul class='entry-meta'>
                    <li>$createT</li>
                </ul>
            </div>
        </div>
    ";
}

//GET COMMENTS
$stmt = $pdo -> prepare(
    "Select *
                  From Discussion
                  WHERE username = :username
                  ORDER BY commentPostedTime
                  LIMIT 3"
);
$stmt -> execute([':username' => $aPerson]);
$commented = $stmt -> fetchAll();

function showComments($commented){
    $comment = $commented['aComment'];
    $oldPostedT = $commented['commentPostedTime'];
    $postedT =date_format(date_create($oldPostedT), 'Y-m-d');

    echo "
        <div class='fslider testimonial noborder nopadding noshadow' data-animation='slide' data-arrows='false'>
            <div class='flexslider'>
                <div class='slider-wrap'>
                    <div class='slide'>
                        <div class='testi-content'>
                            <p>$comment</p>
                            <ul class='entry-meta'>
                                <li>$postedT</li>
                            </ul>
                        </div>
                    </div>
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
            <div class="postcontent nobottommargin">

                <div class="clear"></div>

                <!-- Portfolio Items
                ============================================= -->
                <div id="portfolio" class="portfolio portfolio-1 grid-container clearfix">

                    <article class="portfolio-item pf-media pf-icons clearfix">
                        <div class="portfolio-image">
                            <a>
                                <img src="images/default_user.jpg" alt="Open Imagination">
                            </a>
                        </div>
                        <div class="portfolio-desc">
                            <h3><a href="portfolio-single.html"><?php echo $aPerson; ?></a></h3>
                            <p><?php echo 'Email: '.$email.'</br>';
                                     echo 'Hometown: '.$hometown.'</br>';
                                     echo 'Interest: '.$interest;
                                ?></p>
                                <p><strong>Has Pledged: $ <?php if ($money == 0) {echo '0';} else {echo $money;} ?></strong></p>
                            <a href="#" class="social-icon inline-block si-small si-light si-rounded si-facebook">
                                <i class="icon-facebook"></i>
                                <i class="icon-facebook"></i>
                            </a>
                            <a href="#" class="social-icon inline-block si-small si-light si-rounded si-twitter">
                                <i class="icon-twitter"></i>
                                <i class="icon-twitter"></i>
                            </a>
                            <a href="#" class="social-icon inline-block si-small si-light si-rounded si-gplus">
                                <i class="icon-gplus"></i>
                                <i class="icon-gplus"></i>
                            </a>
                            <br>
                            <?php
                            if ($_SESSION['username'] != $aPerson) {
                                $stmt = $pdo -> query("select * from UserFollow where username='$username' and followee='$aPerson'");
                                $result = $stmt -> fetch();
                                if ($result!= null and sizeof($result) > 0) {
                                    echo "<a href='unfollow.php?followee=$aPerson' class='button button-3d btn-lg button-rounded button-brown'>Unfollow</a>";
                                } else {
                                    echo "<a href='follow.php?followee=$aPerson' class='button button-3d btn-lg button-rounded button-green'>Follow</a>";
                                }
                            }
                            ?>
                        </div>

                    </article>

                    <div class="container clearfix">

                        <div class="heading-block left">
                            <h3>Some of my Projects</h3>
                            <span>Awesome Works that I've contributed to. Proudly.!</span>
                        </div>

                    </div>

                    <?php
                    foreach($project as $row) {
                        showProject($row);
                    }
                    ?>

                </div><!-- #portfolio end -->

            </div><!-- .postcontent end -->

            <!-- Sidebar
            ============================================= -->
            <div class="sidebar nobottommargin col_last">
                <div class="sidebar-widgets-wrap">

                    <div class="widget clearfix">
                        <h4>Recent Liked Posts</h4>
                        <div id="post-list-footer">

                            <?php
                            foreach($likedProject as $row) {
                                showLikedProject($row);
                            }
                            ?>
                        </div>
                    </div>

                    <div class="widget clearfix">
                        <h4>Recent Comments</h4>
                        <?php
                        foreach($commented as $row) {
                            showComments($row);
                        }
                        ?>
                    </div>

                </div>
            </div><!-- .sidebar end -->

        </div>

    </div>

</section><!-- #content end -->


<?php
require_once ('include/footer.html');
