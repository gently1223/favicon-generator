<?php 
	session_start();
	include 'dbConfig.php';

	$targetDir = "upload/";
	$fileName = basename($_FILES["file"]["name"]);
	$targetFilePath = $targetDir.$fileName;
	$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
	
	if(!empty($_FILES["file"]["name"])) {
		$file_info = @getimagesize($_FILES["file"]["tmp_name"]);
	}

	
		if(isset($_POST['submit']) && !empty($_FILES["file"]["name"]) && strlen($fileName) <= 32){
			if($_FILES['file']['size'] >= 1 || $_FILES['file']['size'] < 16000000) {
				
				if($file_info[0] >= 64) {
		
					// Allow certain file formats
					$allowTypes = array('jpg','png','jpeg','gif','pdf');
					if(in_array($fileType, $allowTypes)){
						// Upload file to server
						if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
							// Insert image file name into database
							$insert = $db->query("INSERT INTO images (file_name, uploaded_on, description) VALUES ('".$fileName."', NOW(), '".$_POST["description"]."')");
							if($insert){
								$_SESSION['message'] = "The file ".$fileName. " has been uploaded successfully.";

								// Generate Favicon
								require(dirname( __FILE__ ) . '/class-php-ico.php' );
								
								$source = dirname( __FILE__ ) . "/upload/" . $fileName;
								$destination = dirname( __FILE__ ) . "/favicon/" . substr($fileName, 0, -4) . '.ico';
								
								$ico_lib = new PHP_ICO( $source, array( array( 32, 32 ), array( 64, 64 ) ) );

								// $ico_lib->add_image( dirname( __FILE__ ) . "/upload" . $fileName, array( array( 16, 16 ), array( 24, 24 ), array( 32, 32 ) ) );
								// $ico_lib->add_image( dirname( __FILE__ ) . "/upload" . $fileName, array( array( 48, 48 ), array( 96, 96 ) ) );
								// $ico_lib->add_image( dirname( __FILE__ ) . "/upload" . $fileName, array( array( 128, 128 ) ) );
								
								$ico_lib->save_ico( $destination);
								
								// echo $destination;
								// exit();
							}else {
								$_SESSION['message'] = "File upload failed, please try again.";
							}
						} else {
							$_SESSION['message'] = "Sorry, there was an error uploading your file.";
						}
					} else {
						$_SESSION['message'] = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
					}
				} else {
					$_SESSION['message'] = 'Sorry, this file\'s width is less than 64pixels.';
				}
			}else {
				$_SESSION['message'] ='File size may be less than 1 byte or more that 16 MB.';
			}
		} else {
			$_SESSION['message'] = 'Please select a file to upload or image\'s name is over 32 letters..';
		}

	

	header('location: index.php');
?>