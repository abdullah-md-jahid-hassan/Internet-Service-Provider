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

    //If Restore Button clicked
    if (isset($_POST['restore'])){
        try {
            // Path to your SQL backup file (make sure it's secure and not user-uploadable)
            $backupFile = 'path/to/your/backup.sql'; // Change this to your actual backup file path
            
            // Empty the database first
            $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            
            if (count($tables) > 0) {
                $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
                foreach ($tables as $table) {
                    $conn->exec("DROP TABLE IF EXISTS `$table`");
                }
                $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
            }
            
            // Import the backup file
            $command = "mysql -u ".DB_USER." -p".DB_PASS." ".DB_NAME." < ".$backupFile;
            system($command, $output);
            
            if ($output === 0) {
                $_SESSION['message'] = "Database restored successfully!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Error restoring database!";
                $_SESSION['msg_type'] = "danger";
            }
            
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
            
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error restoring database: ".$e->getMessage();
            $_SESSION['msg_type'] = "danger";
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    }
?>

<!-- Buttons -->
<div class="container my-2">
    <!-- Restore the taste initial data -->
    <div class="a_complit_modal m-4">
        <!-- Restore the taste initial data Button -->
        <button type="button" name="empty_db" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#restore_def">
            <i class="fa-solid fa-clock-rotate-left"></i> Restore initial data
        </button>

        <!--Restore the taste initial data Modal -->
        <div class="modal fade" id="restore_def" tabindex="-1" aria-labelledby="restore_defLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="restore_defLabel">Are You Sure?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Do you want to Restore the taste initial data?
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
