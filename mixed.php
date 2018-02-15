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
		<title>Mixed Data - Zoo Database</title>
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
		
		<h1>Mixed Data</h1>

		<h2>Animals that do not have an employee assigned to them (if any), ordered by ID ascending</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Animal ID</th>
						<th>Animal Name</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalId, name FROM animal WHERE animal.animalId NOT IN (SELECT animalId FROM animalEmployee)
										ORDER BY animalId ASC"))){
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
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $animalId . "\n</td>\n<td>\n" . $name . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Employees that do not have zone responsibilities (if any), ordered by ID ascending</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Employee ID</th>
						<th>Employee Name</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM employee 
										WHERE employee.employeeId NOT IN (SELECT employeeId FROM zoneEmployee) 
										ORDER BY employeeId ASC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($employeeId, $employeeFirst, $employeeLast)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $employeeId . "\n</td>\n<td>\n" . $employeeFirst . " " . $employeeLast . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Managers and the number of people they manage, ordered by ID ascending</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Employee ID</th>
						<th>Manager Name</th>
						<th>Number of Employees Managed</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName, managees FROM employee 
										JOIN (SELECT managerId, COUNT(*)  as managees FROM employee 
										GROUP BY managerId) AS managers ON employee.employeeId = managers.managerId
										ORDER BY employeeId ASC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($employeeId, $firstName, $lastName, $managees)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $employeeId . "\n</td>\n<td>\n" . $firstName . " " . $lastName . "\n</td>\n<td>\n" . $managees . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Animals and the number of other animals they are the father or mother of (if any)</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Animal ID</th>
						<th>Animal Name</th>
						<th>Animals Fathered</th>
						<th>Animals Mothered</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalId, name, IFNULL(fatherees, 0) AS dads, IFNULL(motherees, 0) AS moms FROM animal 
										LEFT JOIN (SELECT fatherId, COUNT( * )  as fatherees FROM animal 
										GROUP BY fatherId) AS fathers ON animal.animalId = fathers.fatherId 
										LEFT JOIN (SELECT motherId, COUNT( * )  as motherees FROM animal
										GROUP BY motherId) AS mothers ON animal.animalId = mothers.motherId;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($animalId, $name, $dads, $moms)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $animalId . "\n</td>\n<td>\n" . $name . "\n</td>\n<td>\n" . $dads . "\n</td>\n<td>\n" . $moms . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Zones and the number of employees assigned to each (if any)</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Zone ID</th>
						<th>Zone Name</th>
						<th>Employees Assigned</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT zone.zoneId, name, IFNULL(assignees, 0) FROM zone 
										LEFT JOIN (SELECT zoneId, COUNT(*)  as assignees FROM zoneEmployee 
										GROUP BY zoneId) AS zonepeople ON zone.zoneId = zonepeople.zoneId;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($zoneId, $name, $assignees)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $zoneId . "\n</td>\n<td>\n" . $name . "\n</td>\n<td>\n" . $assignees . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Foods and the number of animals that eat them (if any)</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Food ID</th>
						<th>Food Name</th>
						<th>Animals Eating</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalFood.foodId, name, IFNULL(eaters, 0) FROM animalFood 
										LEFT JOIN (SELECT foodId, COUNT(*)  as eaters FROM animal 
										GROUP BY foodId) AS foods ON animalFood.foodId = foods.foodId;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($foodId, $name, $eaters)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $foodId . "\n</td>\n<td>\n" . $name . "\n</td>\n<td>\n" . $eaters . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
	</body>
</html>