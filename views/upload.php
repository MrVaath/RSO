<?php
if (isset($_POST['submitPh'])) {

	$file = $_FILES['file'];

	$fileName = $file['name'];
	$fileTmpName = $file['tmp_name'];
	$fileSize = $file['size'];
	$fileError = $file['error'];
	$fileType = $file['type'];

	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array('jpg');

	if (in_array($fileActualExt, $allowed)) {
		if ($fileError === 0) {
			if ($fileSize < 1000000) {
				$fileNameNew = strtolower($user['username']).".".$fileActualExt;
				$fileDestination = 'uploads/'.$fileNameNew;
				move_uploaded_file($fileTmpName, $fileDestination);
        alert('Added image!');
			} else {
				alert("Your file is too big!");
			}
		} else {
			alert("There was an error uploading your file!");
		}
	} else {
		alert("You cannot upload files of this type!");
	}
}

redirectJS('profile');

?>
