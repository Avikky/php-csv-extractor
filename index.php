
<?php 
    session_start();  
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Extractor in PHP</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body id="index-main">
<main>
    <?php if(isset($_GET['error'])): ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div style="border: 1px solid red; padding: 10px;">
                <?php echo $_SESSION['error'];  ?>
            </div>
        <?php endif ?>
    <?php endif; ?>
    <br><br>
    <h2>Submit CSV File</h2><br>
    <form action="core/extractor.php" method="post" enctype="multipart/form-data">
        <div style="padding: 10px; border: 1px solid #000;">
            <div>
                <label for="csvFile">CSV File:</label>
                <input type="file" id="csvFile" name="csfile", accept=".csv" required>
            </div>
            <hr>
            <div>
                <label for="csvFile">What to extact</label>
                <select name="type" id="extractType">
                    <option disabled selected>Choose what to extract</option>
                    <option value="3">Extract Header and Content</option>
                    <option value="1">Extract Header</option>
                    <option value="2">Extract Content</option>
                </select>
            </div>
        </div>
        <br>
        <button type="submit">Submit</button>
    </form>
  </main>
</body>
</html>