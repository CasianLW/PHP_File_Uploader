<?php
include 'FileImageUploader.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $desiredName = $_POST['filename'];
    $imageUploader = new FileImageUploader($_FILES['image'], $desiredName);
    $result = $imageUploader->upload(); 
    $message = $result['message']; 
    $success = $result['success']; 
}


$imageUploader = new FileImageUploader(['name' => '', 'tmp_name' => '']);
$filesList = $imageUploader->scanDirectory(); 
?>

<html>
    <head>
        <style>
            body, html {
                font-family: Arial, Helvetica, sans-serif;
                height: 100%;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #f0f0f0; /* Light grey background */
            }
            body{flex-direction: column;}

            form {
                padding: 20px;
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
                display: flex;
                flex-direction: column;
                gap: 10px; /* Spacing between form elements */
            }
            input[type="file"], input[type="text"] {
                padding: 10px;
                border: 1px solid #ddd; /* Light grey border */
                border-radius: 5px; /* Rounded corners for inputs */
                margin-bottom: 10px; /* Space below each input */
            }
            input[type="submit"] {
                padding: 10px 20px;
                background-color: #007bff; /* Bootstrap primary color */
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #0056b3; /* Darker shade for hover state */
            }
            ul {
                padding: 0;
            }
            li{
                list-style: none;
            }
            .success {
            color: green;
            }
            .error {
                color: red;
            }

            
        </style>
    </head>
    <body>
        
        <h1>Upload d'image</h1>
        <div>
            <h3>Liste d'images existantes:</h3>
            <ul>
                <?php if (!empty($filesList)): ?>
                    <?php foreach ($filesList as $file): ?>
                        <li><?php echo htmlspecialchars($file); ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Aucune image n'a été uploadée.</li>
                <?php endif; ?>
            </ul>
                
        

        </div>
        <form action="" method="post" enctype="multipart/form-data" >
            <input type="file" name="image">
            <input type="text" name="filename">
            <input type="submit" value="Upload">
        </form>
        <?php if (!empty($message)): ?>
            <div class="<?php echo $success ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    </body>
</html>
