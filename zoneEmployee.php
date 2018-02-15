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
		
		<h1>Zone-Employee</h1>
		<!-- zoo-employee table sorted by id and showing names for employee and zoo -->
		<div>
			<table>
				<thead>
					<tr>
					  <th>Zone-Employee ID</th>
					  <th>Employee Name</th>
					  <th>Zone Name</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT zoneEmployeeId, firstName, lastName, name FROM zone JOIN zoneEmployee ON zone.zoneId = zoneEmployee.zoneId JOIN employee ON zoneEmployee.employeeId = employee.employeeId ORDER BY zoneEmployeeId ASC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($zoneEmployeeId, $firstName, $lastName, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $zoneEmployeeId . "\n</td>\n<td>\n" . $firstName . " " . $lastName . "\n</td>\n<td>\n" . $name . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>

		<h2>Add a new zone-employee responsibility</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept new zone-employee information -->
			<form method="post" action="addzoneEmployee.php"> 
				<fieldset>
					<legend>Choose the zone and employee</legend>
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
					<p>Zone*: <select name="ZoneId">
		<!-- retrieve zones and ids for zone dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT zoneId, name FROM zone"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($zoneId, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with results
		while($stmt->fetch()){
			echo '<option value=" '. $zoneId . ' "> ' . $zoneId . " " . $name . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
				</fieldset>
				<p><input type="submit" /></p>
			</form>
		</div>
		
		<h2>Delete a zone-employee responsibility</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept deleted zone-employee information -->
			<form method="post" action="delzoneEmployee.php"> 

				<fieldset>		
					<p>Responsibility to be deleted*: <select name="DelZEID">
		<!-- retrieve zone-employee responsibilities and ids for delete dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT zoneEmployeeId, firstName, lastName, name FROM employee JOIN zoneEmployee ON zoneEmployee.employeeId = employee.employeeId JOIN zone ON zoneEmployee.zoneId = zone.zoneId"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($zoneEmployeeId, $firstName, $lastName, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with results
		while($stmt->fetch()){
			echo '<option value=" '. $zoneEmployeeId . ' "> ' . $zoneEmployeeId . " " . $firstName . " " . $lastName . " - " . $name . '</option>\n';
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