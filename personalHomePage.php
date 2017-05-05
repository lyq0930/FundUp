<?php
session_start();
require_once ('include/helpfulFunctions.php');
require_once ('include/dbconfig.php');
require_once ('include/header.html');
$pdo = db_connect();
$youCreatedProject = $pdo -> prepare(
    "SELECT distinct pid, pname, powner, pdescription, fundSoFar, tags, pstatus, endFundTime
               FROM Project P
               WHERE pOwner = :username");
$youCreatedProject -> execute([':username' => $_SESSION['username']]) or die("Cannot get user's own projects");

$youFundedProject = $pdo -> prepare(
        "SELECT distinct pid, pname, powner, pdescription, fundSoFar, tags, pstatus, endFundTime
                   FROM Project P join Fund F using (pid)
                   WHERE F.username = :username");
$youFundedProject -> execute([':username' => $_SESSION['username']]) or die("Cannot get user's funded projects");

//$youLikeANewProject = $pdo -> prepare(
//	"INSERT INTO UserLikes(username,pid) values (:username,:pid)");
//$youLikeANewProject -> excute([':username' => $_SESSION['username']],[':pid' => $_GET['pid']]); //TODO: use $_GET

$youLikedProject = $pdo -> prepare(
    "SELECT distinct pid, pname, powner, pdescription, fundSoFar, tags, pstatus, endFundTime
               FROM UserLikes join Project using (pid)
               WHERE (username = :username and
                      pid not in (select F.pid from Fund F where F.username = :username))");
$youLikedProject -> execute([':username' => $_SESSION['username']]) or die("Cannot get user's funded projects");

$youFollowedPerson = $pdo -> prepare(
	"SELECT followee
		FROM UserFollow
		WHERE username = :username");
$youFollowedPerson -> execute([':username' => $_SESSION['username']]) or die("Cannot get user's followee"); 

$personFollowedYou = $pdo -> prepare(
	"SELECT username
		FROM UserFollow
		WHERE followee = :followee");
$personFollowedYou -> execute([':followee' => $_SESSION['followee']]) or die("Cannot get user's who follows you"); 

//$youFollowANewPerson = $pdo -> prepare(
//	"INSERT INTO UserFollow(username,followee) values (:username, :followee)");
//$youFollowANewPerson -> excute([':username' => $_SESSION['username']],[':followee' => $_GET['followee']]); //TODO: use $_GET

$followeeCreatedProject = $pdo -> prepare(
    "SELECT pid, pname, powner, pdescription, fundSoFar, tags, pstatus, endFundTime
               FROM Project P join UserFollow F on (followee = P.pOwner)
               WHERE F.username = :username");
$followeeCreatedProject -> execute([":username" => $_SESSION['username']]) or die("Cannot get user's followee's own projects");

$followeeFundedProject = $pdo -> prepare(
    "SELECT pid, pname, powner, pdescription, fundSoFar, tags, pstatus, endFundTime
               FROM Project P join Fund D using (pid),  UserFollow F
               WHERE F.username = :username and D.username = F.followee");
$followeeFundedProject -> execute([":username" => $_SESSION['username']]) or die("Cannot get user's followee's funded projects");

$followeeLikedProject = $pdo -> prepare(
    "SELECT distinct pid, pname, powner, pdescription, fundSoFar, tags, pstatus, endFundTime
               FROM UserLikes L join Project P using (pid), UserFollow U
               WHERE (U.username = :username and L.username = U.followee and 
                     P.pid not in (select F.pid from Fund F where F.username = U.followee))");
$followeeLikedProject -> execute([':username' => $_SESSION['username']]) or die("Cannot get user's funded projects");

$youCommentedProject = $pdo -> prepare(
	"SELECT distinct pid, aComment
		FROM Discussion
		WHERE username = :username");
$youCommentedProject -> execute([":username" => $_SESSION['username']]) or die("Cannot get your comments");

$followeeCommentedProject = $pdo -> prepare(
	"SELECT distinct pid, U.username, aComment,commentPostedTime
	FROM Discussion D join UserFollow U
	WHERE (U.username = :username) and D.username = U.followee
	ORDER BY commentPostedTime DESC
	LIMIT 5");
$followeeCommentedProject -> execute([":username" => $_SESSION['username']]);

//$tagOnOtherProject = $pdo -> prepare(
//	"SELECT pid, pname, powner, pdescription, fundSoFar, tags, pstatus, endFundTime
//		FROM Project
//		WHERE tags LIKE '%:tags%'");
//$tagOnOtherProject -> execute([':tags' => $_GET['tags']]); //USE $_GET

function projectPost($pid, $pname, $powner, $tags, $pdescription, $endFundTime, $fundSoFar, $pstatus, $marker = 'no marker') {
    $endFundTime = date_format(date_create($endFundTime), 'Y-m-d');
    echo "
        <div class='entry clearfix'>
        <div class='entry-image'>
            <a href='project.php?pid=$pid' data-lightbox='image'><img class='image_fade' src='projectimage.php?pid=$pid' alt=$pname></a>
        </div>
        <div class='entry-c'>
            <div class='entry-title'>
                <h2><a href='project.php?pid=$pid'>$pname</a></h2>
            </div>
            <ul class='entry-meta clearfix'>";
    if ($marker != 'no marker') {
        echo "<li>$marker</li>";
    }
    echo "
                <li><i class='icon-calendar3'></i>$endFundTime</li>
                <li><i class='icon-folder-open'></i>$tags</li>
                <li>$fundSoFar</li>
                <li>$pstatus</li>
            </ul>
            <div class='entry-content'>
                <p>$pdescription</p>
                <a href='project.php?pid=$pid'class='more-link'>Read More</a>
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
<!--            <h1>Your personal homepage</h1>-->
            <!--				<span>Showcase of Our Awesome Works in 2 Columns</span>-->
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

						<!-- Posts
						============================================= -->
						<div id="posts" class="small-thumbs">
                            <?php
                            echo "<h2>Your own activities</h2>";
                            $result = $youCreatedProject -> fetchAll();
                            foreach ($result as $row) {
                                projectPost($row['pid'], $row['pname'], $row['powner'], $row['tags'], $row['pdescription'], $row['endFundTime'], $row['fundSoFar'], $row['pstatus'], "You created");
                            }
                            $result = $youFundedProject -> fetchAll();
                            foreach ($result as $row) {
                                projectPost($row['pid'], $row['pname'], $row['powner'], $row['tags'], $row['pdescription'], $row['endFundTime'], $row['fundSoFar'], $row['pstatus'], "You funded");
                            }
                            $result = $youLikedProject -> fetchAll();
                            foreach ($result as $row) {
                                projectPost($row['pid'], $row['pname'], $row['powner'], $row['tags'], $row['pdescription'], $row['endFundTime'], $row['fundSoFar'], $row['pstatus'], "You liked");
                            }

                            echo "<h2>Your followees' activities</h2>";
                            $result = $followeeCreatedProject -> fetchAll();
                            foreach ($result as $row) {
                                projectPost($row['pid'], $row['pname'], $row['powner'], $row['tags'], $row['pdescription'], $row['endFundTime'], $row['fundSoFar'], $row['pstatus'], "Your followee created");
                            }
                            $result = $followeeFundedProject -> fetchAll();
                            foreach ($result as $row) {
                                projectPost($row['pid'], $row['pname'], $row['powner'], $row['tags'], $row['pdescription'], $row['endFundTime'], $row['fundSoFar'], $row['pstatus'], "Your followee funded");
                            }
                            $result = $followeeLikedProject -> fetchAll();
                            foreach ($result as $row) {
                                projectPost($row['pid'], $row['pname'], $row['powner'], $row['tags'], $row['pdescription'], $row['endFundTime'], $row['fundSoFar'], $row['pstatus'], "Your followee liked");
                            }
                            ?>

						</div><!-- #posts end -->

						<!-- Pagination
						============================================= -->
						<ul class="pager nomargin">
							<li class="previous"><a href="#">&larr; Older</a></li>
							<li class="next"><a href="#">Newer &rarr;</a></li>
						</ul><!-- .pager end -->

					</div><!-- .postcontent end -->

					<!-- Sidebar
					============================================= -->
					<div class="sidebar nobottommargin col_last clearfix">
						<div class="sidebar-widgets-wrap">

							<div class="widget widget-twitter-feed clearfix">
								<h4>Actions</h4>
								<ul class="iconlist twitter-feed" data-username="envato" data-count="2">
									<li></li>
								</ul>
                                <a href="#" class="button button-3d button-rounded button-teal">bo your profile</a>
                                <a href="newproject.php" class="button button-3d button-rounded button-teal">Create a new project</a>
							</div>

							<div class="widget clearfix">

								<h4>Flickr Photostream</h4>
								<div id="flickr-widget" class="flickr-feed masonry-thumbs" data-id="613394@N22" data-count="16" data-type="group" data-lightbox="gallery"></div>

							</div>

							<div class="widget clearfix">

								<div class="tabs nobottommargin clearfix" id="sidebar-tabs">

									<ul class="tab-nav clearfix">
										<li><a href="#tabs-1">Popular</a></li>
										<li><a href="#tabs-2">Recent</a></li>
										<li><a href="#tabs-3"><i class="icon-comments-alt norightmargin"></i></a></li>
									</ul>

									<div class="tab-container">

										<div class="tab-content clearfix" id="tabs-1">
											<div id="popular-post-list-sidebar">

												<div class="spost clearfix">
													<div class="entry-image">
														<a href="#" class="nobg"><img class="img-circle" src="images/magazine/small/3.jpg" alt=""></a>
													</div>
													<div class="entry-c">
														<div class="entry-title">
															<h4><a href="#">Debitis nihil placeat, illum est nisi</a></h4>
														</div>
														<ul class="entry-meta">
															<li><i class="icon-comments-alt"></i> 35 Comments</li>
														</ul>
													</div>
												</div>

												<div class="spost clearfix">
													<div class="entry-image">
														<a href="#" class="nobg"><img class="img-circle" src="images/magazine/small/2.jpg" alt=""></a>
													</div>
													<div class="entry-c">
														<div class="entry-title">
															<h4><a href="#">Elit Assumenda vel amet dolorum quasi</a></h4>
														</div>
														<ul class="entry-meta">
															<li><i class="icon-comments-alt"></i> 24 Comments</li>
														</ul>
													</div>
												</div>

												<div class="spost clearfix">
													<div class="entry-image">
														<a href="#" class="nobg"><img class="img-circle" src="images/magazine/small/1.jpg" alt=""></a>
													</div>
													<div class="entry-c">
														<div class="entry-title">
															<h4><a href="#">Lorem ipsum dolor sit amet, consectetur</a></h4>
														</div>
														<ul class="entry-meta">
															<li><i class="icon-comments-alt"></i> 19 Comments</li>
														</ul>
													</div>
												</div>

											</div>
										</div>
										<div class="tab-content clearfix" id="tabs-2">
											<div id="recent-post-list-sidebar">

												<div class="spost clearfix">
													<div class="entry-image">
														<a href="#" class="nobg"><img class="img-circle" src="images/magazine/small/1.jpg" alt=""></a>
													</div>
													<div class="entry-c">
														<div class="entry-title">
															<h4><a href="#">Lorem ipsum dolor sit amet, consectetur</a></h4>
														</div>
														<ul class="entry-meta">
															<li>10th July 2014</li>
														</ul>
													</div>
												</div>

												<div class="spost clearfix">
													<div class="entry-image">
														<a href="#" class="nobg"><img class="img-circle" src="images/magazine/small/2.jpg" alt=""></a>
													</div>
													<div class="entry-c">
														<div class="entry-title">
															<h4><a href="#">Elit Assumenda vel amet dolorum quasi</a></h4>
														</div>
														<ul class="entry-meta">
															<li>10th July 2014</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-content clearfix" id="tabs-3">
											<div id="recent-post-list-sidebar">

												<div class="spost clearfix">
													<div class="entry-image">
														<a href="#" class="nobg"><img class="img-circle" src="images/icons/avatar.jpg" alt=""></a>
													</div>
													<div class="entry-c">
														<strong>John Doe:</strong> Veritatis recusandae sunt repellat distinctio...
													</div>
												</div>

												<div class="spost clearfix">
													<div class="entry-image">
														<a href="#" class="nobg"><img class="img-circle" src="images/icons/avatar.jpg" alt=""></a>
													</div>
													<div class="entry-c">
														<strong>Mary Jane:</strong> Possimus libero, earum officia architecto maiores....
													</div>
												</div>

												<div class="spost clearfix">
													<div class="entry-image">
														<a href="#" class="nobg"><img class="img-circle" src="images/icons/avatar.jpg" alt=""></a>
													</div>
													<div class="entry-c">
														<strong>Site Admin:</strong> Deleniti magni labore laboriosam odio...
													</div>
												</div>

											</div>
										</div>

									</div>

								</div>

							</div>


							<div class="widget clearfix">

								<h4>Tag Cloud</h4>
								<div class="tagcloud">
									<a href="#">general</a>
									<a href="#">videos</a>
									<a href="#">music</a>
									<a href="#">media</a>
									<a href="#">photography</a>
									<a href="#">parallax</a>
									<a href="#">ecommerce</a>
									<a href="#">terms</a>
									<a href="#">coupons</a>
									<a href="#">modern</a>
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