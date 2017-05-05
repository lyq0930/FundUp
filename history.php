<?php
/**
 * Created by PhpStorm.
 * User: emily
 * Date: 5/5/17
 * Time: 5:17 AM
 */
require_once('include/header.php');
require_once ('include/dbconfig.php');
$username = $_GET['username'];
$pdo = db_connect();

//GET ORDER HISTORY
$stmt = $pdo -> prepare(
    "Select F.fundPostedTime, F.fundAmount, P.pstatus, P.pid, P.pstatus, P.pname
               From Fund F JOIN Project P using (pid)
               WHERE username = :username and F.moneyStatus = 'Released'"
);
$stmt -> execute([':username' => $username]);
$result = $stmt -> fetchAll();

function showHistory($result)
{
    $oldFundPostedTime = $result['fundPostedTime'];
    $fundPostedTime = date_format(date_create($oldFundPostedTime), 'Y-m-d');
    $pid = $result['pid'];
    $amount = $result['fundAmount'];
    $projectStatus = $result['pstatus'];
    $pname = $result['pname'];
    
    echo "
        <tr class='cart_item'>
            <td class='cart-product-remove'>
            </td>
        
            <td class='cart-product-thumbnail'>
                <a href='project.php?pid=$pid'><img width='64' height='64' src='projectimage.php?pid=$pid' alt='$pid'></a>
            </td>
        
            <td class='cart-product-name'>
                <a href='project.php?pid=$pid'>$pname</a>
            </td>
        
            <td class='cart-product-name'>
                <span class='Date'>$fundPostedTime</span>
            </td>
        
            <td class='cart-product-name'>
                <span class='Date'>$projectStatus</span>
            </td>
        
            <td class='cart-product-price'>
                <span class='amount'>$amount</span>
            </td>
        </tr>
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

            <div class="table-responsive bottommargin">

                <table class="table cart">
                    <thead>
                    <tr>
                        <th class="cart-product-remove">&nbsp;</th>
                        <th class="cart-product-remove">&nbsp;</th>
                        <th class="cart-product-name">Project</th>
                        <th class="cart-product-name">Date</th>
                        <th class="cart-product-name">Project Status</th>
                        <th class="cart-product-price">Price</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($result as $row) {
                            showHistory($row);
                        }
                        ?>
                    </tbody>

                </table>

            </div>


        </div>


    </div>

</section><!-- #content end -->


<?php
require_once ('include/footer.html');
?>
