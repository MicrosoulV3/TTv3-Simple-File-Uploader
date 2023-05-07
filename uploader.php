<!-- Simple file uploader for TTv3 may 5 2023 MicroMonkey-->
<!-- The 2 other files are fileUploadScript and fileDeleteScript -->
<?php
require_once("backend/functions.php");
dbconn(false);
loggedinonly();

stdhead("File Uploader");

begin_frame("Upload a file to share");
if ($site_config['UPLOADER']){
$maxFileSizeMB = $maxFileSize / (1024 * 1024); // Convert bytes to megabytes. DON'T TOUCH THIS!
?>   
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8" http-equiv="refresh" content="100" border="none">
   <link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/theme.css" />
    <title>PHP File Upload</title>
    <script>

function validateFile() {
    const fileInput = document.getElementById("fileToUpload");

    if (!fileInput.files || !fileInput.files[0]) {
        alert("Please select a file to upload.");
        return false;
    }
    const fileName = fileInput.files[0].name;
    const fileSize = fileInput.files[0].size; // file size in bytes
    const maxFileSizeMB = <?php echo $maxFileSizeMB; ?>;
    const maxFileSizeBytes = maxFileSizeMB * 1024 * 1024; // convert to bytes
    const fileExtensionsAllowed = <?php echo json_encode($fileExtensionsAllowed); ?>; // allowed file extensions. Defined in config.php
    const fileExtension = fileName.split('.').pop().toLowerCase();
    if (!fileExtensionsAllowed.includes(fileExtension)) {
    let message = "Sorry, you are trying to upload a ." + fileExtension + " file, which is not allowed. Please upload a file with the following extension";
    if (fileExtensionsAllowed.length === 1) {
        message += ": ." + fileExtensionsAllowed[0];
    } else if (fileExtensionsAllowed.length === 2) {
        message += "s: ." + fileExtensionsAllowed.join(" or .");
    } else {
        message += "s: ." + fileExtensionsAllowed.slice(0, -1).join(", .") + ", or ." + fileExtensionsAllowed.slice(-1);
    }
    alert(message);
    return false;
}
    if (fileSize > maxFileSizeBytes) {
        const fileSizeMB = Math.round(fileSize / (1024 * 1024));
        alert("File is too large. Maximum file size is " + maxFileSizeMB + " MB. Your file size is " + fileSizeMB + " MB.");
        return false;
    }
    return true;
}
    </script>
</head>

<body>
    <form action="fileUploadScript.php" method="post" enctype="multipart/form-data" onsubmit="return validateFile()">
        Upload a File:
        <input type="file" name="the_file" id="fileToUpload">
        <input type="submit" name="submit" value="Send it">
        <p>Maximum file size: <span style='color: green; font-weight:bold; font-size:10pt'><?php echo $maxFileSizeMB; ?> MB</span></p>
        <p>Allowed Types: <span style='color: green; font-weight:bold; font-size:10pt'><?php echo implode(', ', $fileExtensionsAllowed); ?></span></p>
    </form>
</body>
</html>

<?php
function formatBytes($bytes, $decimals = 2) {
  $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
}

// Scan the upload directory for files
$files = scandir($_SERVER['DOCUMENT_ROOT'] . $uploadDirectory);

// Remove "." and ".." from the list of files
$files = array_diff($files, array('.', '..'));

//A few breaks to shift the tables down a little bit
echo "<br><br>";

// Display a list of all files found
?>
<div class="table-container">
    <h2 style="color: red; text-align: center; display: inline-block;">By using this uploader, you acknowledge that you are using it. Your username will be added onto the front of your uploaded file.</h2>
        <br><br>
    <h2 style="color: green; display: inline-block;">Uploaded Files:</h2>

    <?php if (count($files) > 0): ?>
        <table class="its_a_table">
            <tbody>
                <tr class="header-row">
                    <td class="header-cell">File Name</td>
                    <td class="header-cell">Size</td>
                    <td class="header-cell">Upload Date</td>
                    <?php if (get_user_class() >= 6): ?> <!------------------------------------------------------------------------- change the delete class here --------------------------------------------------------------------------------------------->
                        <td class="header-cell">Delete</td>
                    <?php endif; ?>
                </tr>

                <?php foreach ($files as $file): ?>
                    <tr class="data-row">
                        <td>
                        <a href="<?= $uploadDirectory . $file ?>" class="download-link1" onclick="return confirm('Are you sure you want to download this file?')"><?= $file ?></a>
                        </td>
                        <td>
                            <?php if (is_file($_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $file)): ?>
                                <?= formatBytes(filesize($_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $file)) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (is_file($_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $file)): ?>
                                <?= date("M d, Y", filemtime($_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $file)) ?>
                            <?php endif; ?>
                        </td>

                        <?php if (get_user_class() >= 6): ?> <!------------------------------------------------------------------------- change the delete class here --------------------------------------------------------------------------------------------->
                            <td>
                                <form action="fileDeleteScript.php" method="post" onsubmit="return confirm('Are you sure you want to send this masterpiece to hades?');">
                                    <input type="hidden" name="fileToDelete" value="<?= $file ?>">
                                    <button type="submit" name="delete">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No files found.</p>
    <?php endif; ?>
</div>

<style>
    .table-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .its_a_table {
        border-collapse: collapse;
        width: 100%;
    }

    .header-row {
        background-color: #696969;
        color: #fffff0;
        font-weight: bold;
    }

    .header-cell,
    .data-row > td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    .download-link1 {
        display: inline-block;
        padding: 5px 10px;
        background-color: #ffffff;
        color: #000000;
        text-decoration: none;
        font-weight: bold;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .download-link1:hover {
        background-color: #3e8e41;
    }
</style>
<?php
} else { show_error_msg("Sorry", "Site file uploader is unavailable currently",1); }
end_frame();
stdfoot();
?>
