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
		
		<h1>Mixed Data</h1>

		<h2>Animals ordered by name listed with their species and trainer(s) (if any)</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Animal Name</th>
						<th>Animal Species</th>
						<th>Responsible Employee</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT a.name, a.species, e.firstName, e.lastName
										FROM animal AS a LEFT JOIN animalEmployee ON a.animalId = animalEmployee.animalId
										LEFT JOIN employee AS e ON animalEmployee.employeeId = e.employeeId
										ORDER BY a.name ASC"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($name, $species, $firstName, $lastName)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $name . "\n</td>\n<td>\n" . $species . "\n</td>\n<td>\n" . $firstName . " " . $lastName . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Employees ordered by last name listed with their manager (if any)</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Employee Name</th>
						<th>Manager Name</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT e.firstName AS employeeFirst, e.lastName AS employeeLast, m.firstName AS managerFirst, m.lastName AS managerLast
										FROM employee AS e
										LEFT JOIN employee AS m
										ON e.managerId = m.employeeId
										ORDER BY employeeLast ASC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($employeeFirst, $employeeLast, $managerFirst, $managerLast)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $employeeFirst . " " . $employeeLast . "\n</td>\n<td>\n" . $managerFirst . " " . $managerLast . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Animals ordered by species and then birthday (if known)</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Animal Name</th>
						<th>Animal Species</th>
						<th>Animal Birthday</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT name, species, birth FROM animal
										ORDER BY species, birth;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($name, $species, $birth)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $name . "\n</td>\n<td>\n" . $species . "\n</td>\n<td>\n" . $birth . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Indoor zones listed with their maintenance hours</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Zone Name</th>
						<th>Maintenance Hours</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT name, maintHours FROM zone 
										WHERE indoor = 'true';"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($name, $maintHours)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $name . "\n</td>\n<td>\n" . $maintHours . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Foods ordered by price with their supplier</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Food Name</th>
						<th>Price ($)</th>
						<th>Supplier</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT name, price, supplier FROM animalFood ORDER BY price;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($name, $price, $supplier)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $name . "\n</td>\n<td>\n" . $price . "\n</td>\n<td>\n" . $supplier . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
		<br>
		<br>
		<h2>Employees with their salary and any assigned zones, ordered by greatest to least salary</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th>Employee Name</th>
						<th>Employee Salary ($)</th>
						<th>Zone Responsibility</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT e.firstName, e.lastName, e.salary, z.name AS zoneName FROM employee e 
										LEFT JOIN zoneEmployee ze ON e.employeeId = ze.employeeId 
										LEFT JOIN zone z ON ze.zoneId = z.zoneId
										ORDER BY salary DESC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($firstName, $lastName, $salary, $zoneName)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $firstName . " " . $lastName . "\n</td>\n<td>\n" . $salary . "\n</td>\n<td>\n" . $zoneName . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>
	</body>
</html>