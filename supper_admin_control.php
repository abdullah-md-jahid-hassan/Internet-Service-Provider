<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <!-- Link End -->

</head>
<body>

<?php
    // Defining Page Type
    $page_type = $page_name = "Supper Panel";
        
    //Navbar
    require '_nav_admin.php';

    //If Buttons clicked
    //Empty_DB
    if (isset($_POST['empty_db'])){
        
    } elseif (isset($_POST['restore'])){

    }
?>

<!-- Buttons -->
<div class="container my-2">
    <!-- Empty Database -->
    <div class="a_complit_modal m-4">
        <!-- Empty database Button -->
        <button type="button" name="empty_db" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#empty_dataBase">
            <i class="fa-solid fa-trash"></i> Empty Database
        </button>

        <!--Empty database Modal -->
        <div class="modal fade" id="empty_dataBase" tabindex="-1" aria-labelledby="empty_dataBaseLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="empty_dataBaseLabel">Are You Sure?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Do you Realy want to Clear the DataBase?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <form method="post">
                            <button type="submit" class="btn btn-danger" name="empty_db">I'm Sure</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    
    <!-- Restore the tast initial data -->
    <div class="a_complit_modal m-4">
        <!-- Restore the tast initial data Button -->
        <button type="button" name="empty_db" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#restore_def">
            <i class="fa-solid fa-clock-rotate-left"></i> Restore initial data
        </button>

        <!--Restore the tast initial data Modal -->
        <div class="modal fade" id="restore_def" tabindex="-1" aria-labelledby="restore_defLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="restore_defLabel">Are You Sure?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Do you want to Restore the tast initial data?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <form method="post">
                            <button type="submit" class="btn btn-success" name="restore">I'm Sure</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>

</div>



</body>
</html>
