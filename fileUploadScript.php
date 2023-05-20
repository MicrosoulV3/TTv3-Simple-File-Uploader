<!-- Simple file uploader for TTv3 May 20 2023 MicroMonkey-->
<?php
require_once("backend/functions.php");
dbconn(false);
loggedinonly();

stdhead("File Uploader");

begin_frame("File Uploader");

$username = $CURUSER['username']; //Used to prepend the uploader name to the file.
$currentDirectory = getcwd();

if (isset($_POST['submit'])) {
    $fileName = $_FILES['the_file']['name'];
    $fileSize = $_FILES['the_file']['size'];
    $fileTmpName  = $_FILES['the_file']['tmp_name'];
    $uploadPath = $currentDirectory . $uploadDirectory . $username . '_' . basename($fileName); //Uploader name prepended to file

    // There is no "File too large" error message here or filetype check due to validateFile check on uploader.php. If it makes it this far, its uploading
if (move_uploaded_file($fileTmpName, $uploadPath)) {
        autolink ("uploader.php", "<b><font color='#ff0000'>File Uploaded....</font></b>");
    } else {
        echo "<b><font color='#ff0000'>Ooops! Something bad happened. Contact an Administrator.</font></b>";
    }
}

end_frame();
stdfoot();
?>
