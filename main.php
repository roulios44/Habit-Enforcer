<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <?php
    include 'request.php';
    $datetime = date("Y-m-d H:i:s");
    $con = openDB();
    addUserDB("oui", "oui@hotmail.com", "non");
    $query = "SELECT username FROM user";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
    ?>
</body>

</html>