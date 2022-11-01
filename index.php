<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Favicon Generator</title>
    <link  rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="center">
        <div class="form-input">
            <form method="post" action="upload.php" enctype="multipart/form-data" >
                <div class="preview">
                    <img id="file-ip-1-preview" />
                </div>    
                <div class="input-group">
                    <label>Select Image File to Upload</label>
                    <input type="file" name="file" onchange="showPreview(event)">
                </div>
                <div class="input-group">
                    <label>Input Description about Image</label>
                    <input type="text" name="description">
                </div>
                <div class="input-group">
                    <button class="btn" type="submit" name="submit" style="background: #556B2F;" >Upload</button>
                </div>
            </form>
        </div>
    </div>
	
</body>

<?php if (isset($_SESSION['message'])): ?>
	<div class="msg">
		<?php 
			echo $_SESSION['message']; 
			unset($_SESSION['message']);
		?>
	</div>
<?php endif ?>

<?php
// Include the database configuration file
include 'dbConfig.php';

// Get images from the database
$query = $db->query("SELECT * FROM images ORDER BY uploaded_on DESC");

if($query->num_rows > 0){
    while($row = $query->fetch_assoc()){
        $imageURL = 'upload/'.$row["file_name"];
?>
    <img src="<?php echo $imageURL; ?>" alt="" style="width: 200px; height: 200px;" />
<?php }
}else{ ?>
    <p>No image(s) found...</p>
<?php } ?>

<script>
      function showPreview(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            preview.style.display = "block";
        }
        }
</script>
</html>