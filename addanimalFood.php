<?php
//Turn on error reporting
ini_set('display_errors', 'On');

//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","amadh-db","jR53TKihWjPIHSd4","amadh-db");

//Check for connection error and display
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<title>Home - Zoo Database</title>
		<link rel="stylesheet" href="stylesheet.css" type="text/css">
	</head>
	<body>
		<!-- nav bar -->
		<div id="header"><a href="home.html">HOME</a> | 
		<a href="animal.php">ANIMALS</a> | 
		<a href="animalFood.php">ANIMAL FOODS</a> | 
		<a href="employee.php">EMPLOYEES</a> | 
		<a href="zone.php">ZONES</a> | 
		<a href="animalEmployee.php">ANIMAL-EMPLOYEE</a> | 
		<a href="zoneEmployee.php">ZONE-EMPLOYEE</a> | 
		<a href="mixed.php">MIXED DATA</a></div>

<?php

//Prepare query and display error
if(!($stmt = $mysqli->prepare("INSERT INTO animalFood(name, price, unitOfMeasure, supplier, stock, perishable, amountOnOrder, nextShipDate) VALUES (?,?,?,?,?,?,?,?)"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

//Check for allowed empty fields and set them to NULL
if ($_POST['FoodSupplier'] == '') {
    $_POST['FoodSupplier'] = NULL;
}

if ($_POST['FoodOrder'] == '') {
    $_POST['FoodOrder'] = NULL;
}

if ($_POST['FoodShip'] == '') {
    $_POST['FoodShip'] = NULL;
}

//Assume data is okay before checking
$dataOkay = true;

//Check for disallowed empty fields and display error
if ($_POST['FoodName'] == '') {
    echo "Name cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['FoodPrice'] == '') {
    echo "Price cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['FoodUnit'] == '') {
    echo "Unit Of Measure cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['FoodStock'] == '') {
    echo "Stock cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['FoodPerishable'] == '') {
    echo "Perishable cannot be blank.<br>";
    $dataOkay = false;
}


//If any NOT NULL parameters are NULL, do not execute and display error
if(!$dataOkay) {
	echo "<br>Please go back and re-enter the data.<br>";
}

//Else the data is okay, so execute the query
else {
	//Bind parameters and display error
	if(!($stmt->bind_param("sdssdsds",$_POST['FoodName'],$_POST['FoodPrice'],$_POST['FoodUnit'],$_POST['FoodSupplier'],$_POST['FoodStock'],$_POST['FoodPerishable'],$_POST['FoodOrder'],$_POST['FoodShip']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	//Execute query and display error
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}

	//If successful, display how many employees were added.
	else {
		echo "Added " . $stmt->affected_rows . " row(s) to animalFood.";
	}
}
?>

		<p>Return to the <a href="animalFood.php">Animal Foods</a> page</p>
	</body>
</html>