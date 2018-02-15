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
if(!($stmt = $mysqli->prepare("INSERT INTO employee(firstName, lastName, birth, street, city, state, zipcode, phone, sex, startDate, salary, managerId) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

//Check for allowed empty fields and set them to NULL
if ($_POST['ManagerID'] == '') {
    $_POST['ManagerID'] = NULL;
}

$dataOkay = true;

//Check for disallowed empty fields and display error
if ($_POST['FirstName'] == '') {
    echo "First Name cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['LastName'] == '') {
    echo "Last Name cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['Birthday'] == '') {
    echo "Birthday cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['Street'] == '') {
    echo "Street cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['City'] == '') {
    echo "City cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['State'] == '') {
    echo "State cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['ZipCode'] == '') {
    echo "Zip Code cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['Phone'] == '') {
    echo "Phone Number cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['Sex'] == '') {
    echo "Gender cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['StartDate'] == '') {
    echo "Start Date cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['Salary'] == '') {
    echo "Salary cannot be blank.<br>";
    $dataOkay = false;
}


//If any NOT NULL parameters are NULL, do not execute and display error
if(!$dataOkay) {
	echo "<br>Please go back and re-enter the data.<br>";
}

//Else the dats is okay, so execute the query
else {
	//Bind parameters and display error
	if(!($stmt->bind_param("sssssssssssi",$_POST['FirstName'],$_POST['LastName'],$_POST['Birthday'],$_POST['Street'],$_POST['City'],$_POST['State'],$_POST['ZipCode'],$_POST['Phone'],$_POST['Sex'],$_POST['StartDate'],$_POST['Salary'],$_POST['ManagerID']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	//Execute query and display error
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}

	//If successful, display how many employees were added.
	else {
		echo "Added " . $stmt->affected_rows . " rows to employee.";
	}
}
?>

		<p>Return to the <a href="employee.php">Employees</a> page</p>
	</body>
</html>