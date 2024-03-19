<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Index</title>
    
    <!-- Links Start -->

    <?php require '_common_link.php'; ?>
    
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="carousel.css">
    <link rel="stylesheet" href="footer.css">
    <!-- Links End -->


</head>
<body>
    <!-- Carousel -->
    <?php include '_carousel.php'; ?>

    <!-- Login And Registation button  -->
    <div class="container index-btn ">
        <a class="position-relative translate-middle top-50 start-50 mt-5" href="login.php">
            <span></span><span></span><span></span><span></span>
            LOGIN
        </a><br>
        
        <a class="position-relative translate-middle top-50 start-50 mt-5 mb-5" href="registration.php">
            <span></span><span></span><span></span><span></span>
            REGISTATION
        </a>
    </div>

    <!-- Footer -->
    <?php require '_common_footer.php';?>

</body>
</html>