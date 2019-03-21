<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Car Insert</title>
</head>
<body>
    <?php 
        // Add database connection
        require('dbconfig.php');
        
        #Capture the form values - no validation done
        $vin = $conn->real_escape_string($_POST['vin']);
        $make = $conn->real_escape_string($_POST['make']);
        $model = $conn->real_escape_string($_POST['model']);
        $price = $conn->real_escape_string($_POST['ask_price']);

        #Build the SQL query
        $query = "INSERT INTO INVENTORY
            (VIN, MAKE, MODEL, ASKING_PRICE)
            VALUES ('$vin', '$make', '$model', $price)";

        #Print the query
        echo $query . "<br/>";

        # Select the database to work with 
        // $conn->select_db("Cars");
        // echo "Selected the database Cars <br/>";

        if($result = $conn->query($query)) {
            echo "<p>Record added to database <br/>";
        } else {
            echo "Error inserting record to the database";
        }

        #Close the database 
        $conn->close(); 
    ?>
</body>
</html>