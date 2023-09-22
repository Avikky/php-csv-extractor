<?php 
    include('core/extractor.php')
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extracted Data Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body id="resultpage">

<?php  $data = json_decode($_SESSION['success'], true) ?>

<main>
   
    <h2>Results</h2><br>
    <table>
        <thead>
            <?php if($data['type'] == 0 || $data['type'] == 3 || $data['type'] == 1 ): ?>
                <?php foreach($data['headers'] as $key => $value): ?>
                    <?= '<th>'. $value  .'</th>'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </thead>
        <tbody>
            <?php if(isset($data['content'])): ?>
                <?php foreach($data['content'] as $content): ?>
                    <tr>
                        <td><?= $content['Index'] ?></td>
                        <td><?= $content['User Id'] ?></td>
                        <td><?= $content['First Name'] ?></td>
                        <td><?= $content['Last Name'] ?></td>
                        <td><?= $content['Sex'] ?></td>
                        <td><?= $content['Email'] ?></td>
                        <td><?= $content['Phone'] ?></td>
                        <td width='100'><?= $content['Date of birth'] ?></td>
                        <td><?= $content['Job Title'] ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endif; ?>
        </tbody>
    </table>
 
  </main>
</body>
</html>