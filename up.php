<?php
// for permission deny: https://stackoverflow.com/questions/32017161/xampp-on-windows-8-1-cant-edit-files-in-htdocs/32040660
$target_dir = "uploads/";
//$target_dir = "/";
$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
$uploadOk = 1;
  } else {
    echo "File is not an image.";
$uploadOk = 0;
  }
}

// Check if file already exists
if(file_exists($target_file)) 
	unlink("documenti/$fileName");

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
$uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
$uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  $dt = date(time());
  $imgName = "uploads/".$dt.".jpg";
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $imgName)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
	//header('Location: '.'http://localhost/AI/TFOOD2.php?imgName='.$imgName);
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; //get full url
  // echo "acc link : $actual_link<br>"; // can show server link here
  $actual_link_removeTail = substr($actual_link,0,-7);
  // echo "acc link : $actual_link_removeTail "; // remove '/.up.php' then replace with 'TFOOD2.php?imgName=xxx.jpg'
  header('Location: '.$actual_link_removeTail.'/AI-Fianl.php?imgName='.$imgName); // redirect to previous page with image name
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>