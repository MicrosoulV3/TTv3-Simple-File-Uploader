<?php
require_once("backend/functions.php");
dbconn(false);
loggedinonly();

stdhead("File Uploader");

begin_frame("File Uploader");
if ($site_config['UPLOADER']){
$maxFileSizeMB = $maxFileSize / (1024 * 1024); // Convert bytes to megabytes. DON'T TOUCH THIS!
?>   
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" http-equiv="refresh" content="100" border="none">
   <link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/themes.css" />
    <title>PHP File Upload</title>
    <script>
function validateFile() {
    const fileInput = document.getElementById("fileToUpload");
    
    if (!fileInput.files || !fileInput.files[0]) {
        alert("Please select a file to upload.");
        return false; // prevent form submission
    }
    
    const fileSize = fileInput.files[0].size; // file size in bytes
    const maxFileSizeMB = <?php echo $maxFileSizeMB; ?>;
    const maxFileSizeBytes = maxFileSizeMB * 1024 * 1024; // convert to bytes

    if (fileSize > maxFileSizeBytes) {
        const fileSizeMB = Math.round(fileSize / (1024 * 1024));
        alert("File is too large. Maximum file size is " + maxFileSizeMB + " MB. Your file size is " + fileSizeMB + " MB.");
        return false; // prevent form submission
    }

    return true; // allow form submission
}

    </script>
</head>
<body>
    <form action="fileUploadScript.php" method="post" enctype="multipart/form-data" onsubmit="return validateFile()">
        Upload a File:
        <input type="file" name="the_file" id="fileToUpload">
        <input type="submit" name="submit" value="Start Upload">
        <?php
            echo "<p>Maximum file size: " . $maxFileSizeMB . " MB</p><p>Allowed Types: " . implode(', ', $fileExtensionsAllowed) . "</p>";
        ?>
    </form>
</body>
</html>

<?php
function formatBytes($bytes, $decimals = 2) {
  $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'); // Overkill amounts of conversion, Yeee haaawww!
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
}

// Scan the upload directory for files
$files = scandir($_SERVER['DOCUMENT_ROOT'] . $uploadDirectory);

// Remove "." and ".." from the list of files
$files = array_diff($files, array('.', '..'));

//lots of fucking spaces, deal with it
echo "<br><br>";

// Display a list of all files found
?>
<h2 style="color: green; display: inline-block;">Uploaded Files:</h2>
<?php
// Page Layout
if (count($files) > 0): ?>
  <table class="its_a_table">
    <thead>
      <tr>
        <th>File Name</th>
        <th>Size</th>
        <th>Date</th>
        <?php if (get_user_class() >= 6): ?> <!--ADJUST FOR YOUR USER CLASS ACCESS TO THE DELETE BUTTON -->
          <th>Delete</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($files as $file): ?>
        <tr>
          <td>
            <a href="<?= $uploadDirectory . $file ?>" class="download-link1"><?= $file ?></a>
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

          <?php if (get_user_class() >= 6): ?> <!--ADJUST FOR YOUR USER CLASS ACCESS TO THE DELETE BUTTON -->
            <td>
              <form action="fileDeleteScript.php" method="post" onsubmit="return confirm('Are you sure you want to delete this file?');">
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

<!-- CSS Styling -->
<style>
  .its_a_table th {
  background-color: #696969; /* Sets the background color of the table header */
  font-weight: bold; /* Makes the text in the table header bold */
  text-align: left; /* Aligns the text in the table header to the left */
  padding: 12px 15px; /* Adds padding to the top and bottom (12px) and left and right (15px) of the table header */
  border-bottom: 1px solid #ddd; /* Adds a bottom border to the table header */
  color: #fffff0; /* Sets the font color of the table header */
}
  .download-link1 {
    display: inline-block;
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
    transition: background-color 0.3s ease;
  }    
  .download-link1:hover {
    background-color: #3e8e41;
  }
  td, th {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }
</style>

<?php
} else { show_error_msg("Sorry", "Site file uploader is unavailable currently",1); }
end_frame();
stdfoot();
?>
