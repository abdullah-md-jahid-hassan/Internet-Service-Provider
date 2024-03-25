<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Index</title>
    
    <!-- Links Start -->

    <?php require '_link_common.php'; ?>
    
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="carousel.css">
    <link rel="stylesheet" href="footer.css">
    <!-- Links End -->


</head>
<body>
    <!-- Carousel -->
    <?php include '_carousel.php'; ?>

    <!-- Login And Registration button  -->
    <div class="container index-btn ">
        <!-- Login Button -->
        <a class="position-relative translate-middle top-50 start-50 mt-5" href="login.php">
            <span></span><span></span><span></span><span></span>
            LOGIN
        </a><br>
        
        <!-- Registration Button -->
        <a class="position-relative translate-middle top-50 start-50 mt-5 mb-5" href="registration.php">
            <span></span><span></span><span></span><span></span>
            REGISTRATION
        </a>
    </div>

    <!-- Footer -->
    <?php require '_footer_common.php';?>

</body>
</html>