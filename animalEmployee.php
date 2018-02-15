<?php
//Turn on error reporting
ini_set('display_errors', 'On');

//Connect to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","amadh-db","jR53TKihWjPIHSd4","amadh-db");

//Check connection and report error if exists
if($mysqli->connect_errno){
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
		
		<h1>Animal-Employee</h1>
		<!-- animal-employee table sorted by id and showing names for employee and animal -->
		<div>
			<table>
				<thead>
					<tr>
					  <th>Animal-Employee ID</th>
					  <th>Employee Name</th>
					  <th>Animal Name</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalEmployeeId, firstName, lastName, name FROM animal JOIN animalEmployee ON animal.animalId = animalEmployee.animalId JOIN employee ON animalEmployee.employeeId = employee.employeeId ORDER BY animalEmployeeId ASC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($animalEmployeeId, $firstName, $lastName, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $animalEmployeeId . "\n</td>\n<td>\n" . $firstName . " " . $lastName . "\n</td>\n<td>\n" . $name . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>

		<h2>Add a new animal-employee responsibility</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept new animal-employee information -->
			<form method="post" action="addanimalEmployee.php"> 
				<fieldset>
					<legend>Choose the animal and employee</legend>
					<p>Employee*: <select name="EmployeeId">
		<!-- retrieve employees and ids for employee dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM employee"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($employeeId, $firstName, $lastName)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with results
		while($stmt->fetch()){
			echo '<option value=" '. $employeeId . ' "> ' . $employeeId . " " . $firstName . " " . $lastName . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
					<p>Animal*: <select name="AnimalId">
		<!-- retrieve animals and ids for animal dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalId, name FROM animal"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($animalId, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with results
		while($stmt->fetch()){
			echo '<option value=" '. $animalId . ' "> ' . $animalId . " " . $name . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
				</fieldset>
				<p><input type="submit" /></p>
			</form>
		</div>

		<h2>Delete an animal-employee responsibility</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept deleted animal-employee information -->
			<form method="post" action="delanimalEmployee.php"> 
				<fieldset>		
					<p>Responsibility to be deleted*: <select name="DelAEID">
		<!-- retrieve animal-employee responsibilities and ids for delete dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalEmployeeId, firstName, lastName, name FROM employee JOIN animalEmployee ON animalEmployee.employeeId = employee.employeeId JOIN animal ON animalEmployee.animalId = animal.animalId"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($animalEmployeeId, $firstName, $lastName, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with results
		while($stmt->fetch()){
			echo '<option value=" '. $animalEmployeeId . ' "> ' . $animalEmployeeId . " " . $firstName . " " . $lastName . " - " . $name . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
				</fieldset>
				<p><input type="submit" /></p>
			</form>
		</div>
	</body>
</html>