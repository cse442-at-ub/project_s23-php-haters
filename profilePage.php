<?php
session_start();
$user_id = $_SESSION["username"];
//$user_id = "ben";
//$user_id = "hGilmore9";
$image_path = "uploads/$user_id/";
$files = glob($image_path . '*.{jpg,jpeg,png,gif}', GLOB_BRACE); // check if an image is already uploaded
if (count($files) > 0 && !isset($_FILES["image"])) { // only display the image if a new photo has not been uploaded
    $image_filename = basename($files[0]);
    echo "<img src='$image_path$image_filename' class='profile-image' alt='Profile Image'>";
}
echo $user_id;
?>

<html>
<head>
    <link rel="stylesheet" href="profilePage.css">
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK){
        // get the file details
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];

        // Delete all files in the directory
        $target_dir = 'uploads/' . $user_id . '/';
        $files = glob($target_dir . '*');
        foreach ($files as $file) {
            if (is_file($file)) { // Check if it's a file and not a directory
                unlink($file); // Delete the file
            }
        }
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // create the directory if it doesn't exist
        }

        $target_file = $target_dir . $user_id . '_' . basename($_FILES["image"]["name"]); // get the full path of the uploaded file with the username preceeding the image name
        $target_file = $target_dir . basename($_FILES["image"]["name"]); // get the full path of the uploaded file
        move_uploaded_file($file_tmp, $target_file);
//        echo "<img src='$target_file' class='profile-image' alt='Uploaded image' class='profile-image'>";

    }
//    else {
//        echo "Image file or size invalid. Select a different or smaller image";
//    }
}

?>
<form action="" method="POST" enctype="multipart/form-data" name="aForm" id="aForm">
    <label for="image">Select image:</label>
    <input type="file" id="image" name="image">
    <br><br>
    <input type="submit" value="Upload" id="submitButton">
</form>
<script>
    const form = document.getElementById('aForm');
    const submitButton = document.getElementById('submitButton');
    submitButton.addEventListener('click', function (event) {
        if (form.checkValidity()) {
            console.log("VALID FORM")
            location.reload();
            form.submit();
        } else {
            console.log("ERROR ERROR")
        }
    });
</script>
</body>
</html>