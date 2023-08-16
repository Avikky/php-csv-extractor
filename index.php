
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Extractor in PHP</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        max-width: 400px;
        margin: 2rem auto;
        background-color: #ccc;
        }
        

        main{
        border: 1px solid #333;
        padding: 1.5rem 2.5rem;
        background-color: rgb(244, 244, 244, 0.5);
        }

        h2 {
        margin-top: 0;
        }

        form {
        margin-bottom: 20px;
        }

        label, input {
        display: block;
        width: 100%;
        margin-bottom: 10px;
        }

        input[type="submit"] {
        width: auto;
        cursor: pointer;
        }
        button{
            margin: 0 auto;
            cursor: pointer;
            padding: 7px 15px;
        }
  </style>
</head>

<body>
<main>
    <?php if(isset($_GET['error'])): ?>
        <div style="border: 1px solid black; ">
            <? echo ($_GET['error'])  ?>
        </div>
    <?php endif; ?>
    <br><br>
    <h2>Submit CSV File</h2><br>
    <form action="extractor.php" method="post" enctype="multipart/form-data">
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