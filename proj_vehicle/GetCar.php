<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Get Cars</title>
</head>
<body>
    <?php 
        require('dbconfig.php');
        $query = "SELECT * FROM INVENTORY";
        $result=$conn->query($query);
        if($result) {
            //Do nothing here
        } else {
            echo "Error getting cars from the database" . mysql_error() . "<br/>";
        }
        while($row = $result->fetch_assoc()) {
            echo "<h3>Vin:" . $row['VIN'] . "</h3>";
            echo "<h3>Make:" . $row['MAKE'] . "</h3>";
            echo "<h3>Model:" . $row['MODEL'] . "</h3>";
            echo "<h3>Price:" . $row['ASKING_PRICE'] . "</h3>";
            echo "<hr/>";
        }
        while($field=$result->fetch_field()) {
            echo "Name: " . $field->name . "<br/>";
            echo "Table: " . $field->table . "<br/>";
            echo "Max. Len: " . $field->max_length . "<br/>";
        }
    ?>
</body>
</html>