<?php session_start(); ?>
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
        
    // Navbar
    require '_nav_admin.php';


    // If Restore Button clicked
    if (isset($_POST['restore'])){
        // Database Connection
        require '_database_connect.php';

        // Disable foreign key checks
        $connect->query("SET FOREIGN_KEY_CHECKS = 0");

        // 2. Drop all views
        $views = $connect->query("SELECT table_name FROM information_schema.views WHERE table_schema = '$database'");
        while ($row = $views->fetch_assoc()) {
            $connect->query("DROP VIEW IF EXISTS `{$row['table_name']}`");
        }

        // 3. Drop all triggers
        $triggers = $connect->query("SELECT TRIGGER_NAME FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA = '$database'");
        if ($triggers) {
            while ($row = $triggers->fetch_assoc()) {
                if (!empty($row['TRIGGER_NAME'])) {
                    $triggerName = $connect->real_escape_string($row['TRIGGER_NAME']);
                    $connect->query("DROP TRIGGER IF EXISTS `$triggerName`");
                }
            }
        }
        

        // 4. Drop all events
        $events = $connect->query("SELECT EVENT_NAME FROM information_schema.EVENTS WHERE EVENT_SCHEMA = '$database'");
        if ($events) {
            while ($row = $events->fetch_assoc()) {
                if (!empty($row['EVENT_NAME'])) {
                    $eventName = $connect->real_escape_string($row['EVENT_NAME']);
                    $connect->query("DROP EVENT IF EXISTS `$eventName`");
                }
            }
        }

        // 5. Drop all tables
        $tables = $connect->query("SHOW TABLES");
        while ($row = $tables->fetch_row()) {
            $connect->query("DROP TABLE IF EXISTS `{$row[0]}`");
        }

        // Re-enable foreign key checks
        $connect->query("SET FOREIGN_KEY_CHECKS = 1");

        // 6. Import SQL file
        $mysqlPath = "C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\\mysql.exe"; // adjust if needed
        $sqlFile = realpath("initial_data/isp_initial.sql");
        $command = "cmd /c \"$mysqlPath -h $servername -u $username -p$password $database < \"$sqlFile\"\" 2>&1";
        $output = shell_exec($command);

        // 7. Empty the 'files' folder
        function deleteFolderContents($folder) {
            foreach (glob($folder . '/*') as $file) {
                if (is_dir($file)) {
                    deleteFolderContents($file);
                    rmdir($file);
                } else {
                    unlink($file);
                }
            }
        }
        deleteFolderContents('files');

        // 8. Copy from initial_data/files to files/
        function copyFolder($src, $dst) {
            if (!file_exists($dst)) {
                mkdir($dst, 0777, true);
            }
            $dir = opendir($src);
            while (false !== ($file = readdir($dir))) {
                if ($file != '.' && $file != '..') {
                    $srcPath = "$src/$file";
                    $dstPath = "$dst/$file";
                    if (is_dir($srcPath)) {
                        copyFolder($srcPath, $dstPath);
                    } else {
                        copy($srcPath, $dstPath);
                    }
                }
            }
            closedir($dir);
        }
        copyFolder('initial_data/files', 'files');

        echo "<script>window.location.href = 'dash_admin.php';</script>";
        exit();
        
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
