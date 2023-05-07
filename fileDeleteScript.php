<!-- Simple file uploader for TTv3 may 7 2023 MicroMonkey-->
<?php
require_once("backend/functions.php");
dbconn(false);
loggedinonly();

stdhead("delete");

begin_frame("delete");

if (isset($_POST['delete']) && isset($_POST['fileToDelete'])) {
    $fileToDelete = $_POST['fileToDelete'];
    $filePath = realpath($_SERVER['DOCUMENT_ROOT']) . $uploadDirectory . $fileToDelete;

    if (file_exists($filePath)) {
        $logEntry = $CURUSER['username'] . " has deleted a file from the simple file uploader page: $fileToDelete";
        write_log($logEntry);
        unlink($filePath);
        autolink("uploader.php", "<b><font color='#ff0000'>File Deleted....</font></b>");
    } else {
        autolink("uploader.php", "<b><font color='#ff0000'>File Not Found....</font></b>");
    }

}

end_frame();
stdfoot();
?>
