<?php
include_once "nps/widgets/dash.php";
$_SESSION["user_role"] = $user_role = getRole($link,$_SESSION["username"]);
if($user_role != "admin"){
  header('location: /');
}
// Check if the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded without errors
    if (isset($_FILES["docUpload"]) && $_FILES["docUpload"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["docUpload"]["name"];
        $filetype = $_FILES["docUpload"]["type"];
        $filesize = $_FILES["docUpload"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            if (file_exists("UserUploads/" . $filename)) {
                echo $filename . " is already exists.,";
            } else {
                move_uploaded_file($_FILES["docUpload"]["tmp_name"], "UserUploads/" . $filename);
                echo "Your file was uploaded successfully.";
            }
        } else {
            echo "Error: There was a problem uploading your file. Please try again.";
        }
    } else {
        echo "Error: " . $_FILES["docUpload"]["error"];
    }
    $contentid = $_POST["contentid"];
    $lessonid = $_POST["lessonid"];
    $uploadlink = "/UserUploads/" . $filename;
    $sql = "INSERT INTO skymake_useruploads (upload_id,uploadlink) VALUES ('" . $contentid . "','" . $uploadlink . "')";
    if ($result = mysqli_query($link, $sql)) {
        echo "SQL Query successfully executed.";
        if ($_POST["aasc"] == "yes") {
            $sql = "INSERT INTO skymake_lessoncontent (lessonid,`content-type`,`content-id`,`content-link`) VALUES ('" . $lessonid . "','Document','" . $contentid . "','" . "/" . $uploadlink . "')";
            if ($result = mysqli_query($link, $sql)) {
                echo "SQL Query successfully executed.";

            } else {
                die("There was an SQL Error. SQL Server (" . dbHost . ") Returned: " . mysqli_error($link) . " for SQL Query " . $sql);
            }
        }
    } else {
        die("There was an SQL Error. SQL Server (" . dbHost . ") Returned: " . mysqli_error($link) . " for SQL Query " . $sql);
    }
}
?>
    <form action="/upload" method="post" enctype="multipart/form-data">
        <h2>Upload File</h2>
        <label for="fileSelect">Filename:</label>
        <input type="file" name="docUpload" id="fileSelect">
        <p>Add as lessoncontent<p>
        <input type="radio" name="aasc" value="yes"><p>Yes</p>
        <input type="radio" name="aasc" value="no"><p>No</p>
        <input type="text" name="lessonid" placeholder="Lesson ID to assign if you said yes."><br>
        <input type="text" name="contentid" placeholder="Content ID to assign."><br>
        <input type="submit" name="submit" value="Upload">
        <p><strong>Note:</strong> Only .jpg, .jpeg, .gif, .png .zip .pdf .docx formats allowed to a max size of 5 MB.</p>
    </form>
</body>
</html>
