<?php
session_start();
require_once ('include/header.php');
require_once ('include/dbconfig.php');
$keyword = "%" . $_GET['q'] . "%";
$pdo = db_connect();
$stmt = $pdo -> prepare(
        "select *
                   from Project
                   where pname like :keyword or pdescription like :keyword or tags like :keyword"
);
$stmt -> bindParam(":keyword", $keyword, $pdo::PARAM_STR);
$stmt -> execute();
$result = $stmt -> fetchAll();

function showAllProject($singleResult) {
    $createTime = $singleResult['createT'];
    $endTime = $singleResult['endFT'];
    $completeTime = $singleResult['endPT'];
    $fundSoFar = $singleResult['fundSoFar'];
    $author = $singleResult['pOwner'];
    $pid = $singleResult['pid'];
    $pname = $singleResult['pname'];
    $pdescription = $singleResult['pdescription'];
    $allTags = $singleResult['tags'];
    echo "
    <article class='portfolio-item pf-media pf-icons clearfix'>
        <div class='portfolio-image'>
            <a href='project.php?pid=$pid'>
                <img src='projectimage.php?pid=$pid' alt='$pname'>
            </a>
        </div>
        <div class='portfolio-desc'>
            <h3><a href='project.php?pid=$pid'>$pname</a></h3>
            <span>$allTags</span>
            <p>$pdescription</p>
            <ul class='iconlist'>
                <li><i class='icon-ok'></i> <strong>Created:</strong>$createTime</li>
                <li><i class='icon-ok'></i> <strong>End Fund:</strong>$endTime</li>
                <li><i class='icon-ok'></i> <strong>Complete:</strong>$completeTime</li>
                <li><i class='icon-ok'></i> <strong>Collected:</strong>$fundSoFar</li>
                <li><i class='icon-ok'></i> <strong>By:</strong>$author</li>
            </ul>
            <a href='project.php?pid=$pid' class='button button-3d noleftmargin'>Have A Look</a>
        </div>
    </article>
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

                <h2>Search Result about '<?php echo $_GET['q']; ?>'</h2>

                <div id="portfolio-shuffle" class="portfolio-shuffle" data-container="#portfolio">
                    <i class="icon-random"></i>
                </div>

                <div class="clear"></div>

                <!-- Portfolio Items
                ============================================= -->
                <div id="portfolio" class="portfolio grid-container portfolio-1 clearfix">

                    <?php
                    foreach($result as $row) {
                        showAllProject($row);
                    }
                    ?>

                </div><!-- #portfolio end -->

            </div>

        </div>

    </section><!-- #content end -->
<?php
require_once ('include/footer.html');
?>