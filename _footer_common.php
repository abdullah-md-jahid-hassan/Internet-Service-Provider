<?php
$home_link = "dash_";

if(isset($_SESSION['user'])){
    if($_SESSION['user']!='customer' && $_SESSION['user']!='employee'){
        $home_link .= 'admin';
    } else $home_link .= $_SESSION['user'];
    $home_link .= '.php';
} else $home_link .= '#';

// connect to the database
require '_database_connect.php';

//get footer data
$footer_info_sql = "SELECT * FROM `footer_data` WHERE 1";
$find_footer_info = mysqli_query($connect, $footer_info_sql);
$footer_info = mysqli_fetch_assoc($find_footer_info);

// Close the database connection
mysqli_close($connect);
?>

<!-- Footer start -->
<div class="footer text-white">
    <button class="footer-uper-btn btn"></button>
        <footer class="py-2">

        <!-- 1st row -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 py-2 my-5 px-2 my-2 justify-content-center">
             
            <!-- link -->
            <div class="col mb-3 px-lg-5 ">
                <h5 class="px-2"><u>Links</u></h5>
                <ul class="nav flex-column gap-2">
                    <li><a href="<?php echo $home_link;?>" target="_blank" class="footer-links">Home</a></li>
                    <li><a href="<?php echo $home_link;?>" target="_blank" class="footer-links">FaQ</a></li>
                    <li><a href="<?php echo $home_link;?>" target="_blank" class="footer-links">Give Suggestion</a></li>
            </div>

            <!-- Contract Info -->
            <div class="col mb-3">
                <h5 class="px-2"><u>Contract Info</u></h5>
                <ul class="nav flex-column gap-2">
                    <li><a href="<?php echo $footer_info['address_link'];?>" target="_blank" class="footer-links"><i al class="fa-solid fa-location-dot p-1"></i><?php echo $footer_info['address_text'];?></a></li>
                    
                    <li><a href="<?php echo $footer_info['phone_link'];?>" target="_blank" class="footer-links"><i class="fa-solid fa-phone p-1"></i><?php echo $footer_info['phone_text'];?></a></li>
                    
                    <li><a href="<?php echo $footer_info['mail_link'];?>" target="_blank" class="footer-links"><i class="fa-solid fa-envelope p-1"></i><?php echo $footer_info['mail_text'];?></a></li>
            </div>
        </div>

        <!-- 2nd row -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-2 my-2 justify-content-center">
            <div class="col-auto ">
                <ul class="list-unstyled d-flex">
                    <li class="ms-3"><a href="<?php echo $footer_info['fb_link'];?>" target="_blank" class="fa-brands fa-facebook mediaicons"></a></li>
                    
                    <li class="ms-3"><a href="<?php echo $footer_info['ms_link'];?>" target="_blank" class="fa-brands fa-facebook-messenger mediaicons"></a></li>
                    
                    <li class="ms-3"><a href="<?php echo $footer_info['wh_link'];?>" target="_blank" class="fa-brands fa-whatsapp mediaicons"></a></li>
                    
                    <li class="ms-3"><a href="<?php echo $footer_info['in_link'];?>" target="_blank" class="fa-brands fa-instagram mediaicons"></a></li>
                    
                    <li class="ms-3"><a href="<?php echo $footer_info['yt_link'];?>" target="_blank" class="fa-brands fa-youtube mediaicons"></a></li>
                </ul>
            </div>
        </div>

        <!-- 3rd row -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5 justify-content-center">
            <div class="col-auto ">
                <p class="text-muted">©CoppyRight 2024 | Designed By A.M.J. Hassan </p>
            </div>
        </div>
    </footer>
</div>
<!-- Footer End -->