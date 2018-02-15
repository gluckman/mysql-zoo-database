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
//Prepare query or display error
if(!($stmt = $mysqli->prepare("INSERT INTO animal(name, species, sex, birth, death, foodId, eatingSchedule, pregnant, motherId, fatherId) VALUES (?,?,?,?,?,?,?,?,?,?)"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

//Check for allowed empty fields and set them to NULL
if ($_POST['AnimalBirth'] == '') {
    $_POST['AnimalBirth'] = NULL;
}

if ($_POST['AnimalDeath'] == '') {
    $_POST['AnimalDeath'] = NULL;
}

if ($_POST['AnimalMother'] == '') {
    $_POST['AnimalMother'] = NULL;
}

if ($_POST['AnimalFather'] == '') {
    $_POST['AnimalFather'] = NULL;
}

if ($_POST['AnimalFood'] == '') {
    $_POST['AnimalFood'] = NULL;
}

$dataOkay = true;

//Check for disallowed empty fields and display error
if ($_POST['AnimalName'] == '') {
    echo "Name cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['AnimalSpecies'] == '') {
    echo "Species cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['AnimalSex'] == '') {
    echo "Sex cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['AnimalEating'] == '') {
    echo "Eating Schedule cannot be blank.<br>";
    $dataOkay = false;
}

if ($_POST['AnimalPregnant'] == '') {
    echo "Pregnant cannot be blank.<br>";
    $dataOkay = false;
}


//If any NOT NULL parameters are NULL, do not execute and display error
if(!$dataOkay) {
	echo "<br>Please go back and re-enter the data.<br>";
}

//Else the dats is okay, so execute the query
else {
	//Bind parameters and display error
	if(!($stmt->bind_param("sssssisiii",$_POST['AnimalName'],$_POST['AnimalSpecies'],$_POST['AnimalSex'],$_POST['AnimalBirth'],$_POST['AnimalDeath'],$_POST['AnimalFood'],$_POST['AnimalEating'],$_POST['AnimalPregnant'],$_POST['AnimalMother'],$_POST['AnimalFather']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	//Execute query and display error
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}

	//If successful, display how many employees were added.
	else {
		echo "Added " . $stmt->affected_rows . " row(s) to animal.";
	}
}
?>

		<p>Return to the <a href="animal.php">Animals</a> page</p>
	</body>
</html>