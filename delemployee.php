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
if(!($stmt = $mysqli->prepare("DELETE FROM employee WHERE employeeId=?"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

//Bind parameters and display error
if(!($stmt->bind_param("i",$_POST['DelEmployeeID']))){
	echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}

//Execute query and display error
if(!$stmt->execute()){
	echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
}

//If successful, display how many employees were added.
else {
	echo "Deleted " . $stmt->affected_rows . " row(s) from employee.";
}
?>

		<p>Return to the <a href="employee.php">Employees</a> page</p>
	</body>
</html>