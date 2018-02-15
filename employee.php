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
		
		<h1>Employees</h1>
		<!-- emloyee table sorted by id and showing name for manager -->
		<div>
			<table>
				<thead>
					<tr>
						<th>Employee ID</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Birthday</th>
						<th>Street</th>
						<th>City</th>
						<th>State</th>
						<th>Zip Code</th>
						<th>Phone Number</th>
						<th>Sex</th>
						<th>Start Date</th>
						<th>Salary ($)</th>
						<th>Manager</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT e.employeeId, e.firstName, e.lastName, e.birth, e.street, e.city, e.state, e.zipcode, e.phone, e.sex, e.startDate, e.salary, m.firstName AS managerFirst, m.lastName AS managerLast FROM employee AS e LEFT JOIN employee AS m ON e.managerId = m.employeeId ORDER BY e.employeeId ASC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($employeeId, $firstName, $lastName, $birth, $street, $city, $state, $zipcode, $phone, $sex, $startDate, $salary, $managerFirst, $managerLast)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
		 echo "<tr>\n<td>\n" . $employeeId . "\n</td>\n<td>\n" . $firstName . "\n</td>\n<td>\n" . $lastName . "\n</td>\n<td>\n" .  $birth
			. "\n</td>\n<td>\n" . $street . "\n</td>\n<td>\n" . $city . "\n</td>\n<td>\n" . $state . "\n</td>\n<td>\n" . $zipcode 
			. "\n</td>\n<td>\n" . $phone . "\n</td>\n<td>\n" . $sex . "\n</td>\n<td>\n" . $startDate . "\n</td>\n<td>\n" . $salary
			. "\n</td>\n<td>\n" . $managerFirst . " " . $managerLast . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>

		<h2>Add a new employee</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept new employee information -->
			<form method="post" action="addemployee.php"> 

				<fieldset>
					<legend>Name</legend>
					<p>First Name*: <input type="text" name="FirstName" placeholder="Jane" /></p>
					<p>Last Name*: <input type="text" name="LastName" placeholder="Goodall" /></p>
				</fieldset>

				<fieldset>
					<legend>Personal Details</legend>
					<p>Birthday*: <input type="text" name="Birthday" placeholder="YYYY-MM-DD" /></p>
					<p>Gender*: <input type="radio" name="Sex" value="M" checked> Male 
					<input type="radio" name="Sex" value="F"> Female
				</fieldset>
		
				<fieldset>
					<legend>Contact Information</legend>
					<p>Street*: <input type="text" name="Street" placeholder="1600 Pennsylvania Ave." /></p>
					<p>City*: <input type="text" name="City" placeholder="Anytown" /></p>
					<p>State*: <input type="text" name="State" placeholder="MI" /></p>
					<p>Zip Code*: <input type="text" name="ZipCode" placeholder="48888" /></p>
					<p>Phone Number*: <input type="text" name="Phone" placeholder="333-444-5678" /></p>
				</fieldset>

				<fieldset>
					<legend>Job Details</legend>
					<p>Start Date*: <input type="text" name="StartDate" placeholder="YYYY-MM-DD" /></p>
					<p>Salary*: <input type="text" name="Salary" placeholder="55000" /></p>			
					<p>Manager: <select name="ManagerID">
						<option value=""> </option>
		<!-- retrieve all employees and their ids for manager dropdown -->
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
		//use results to populate dropdown
		while($stmt->fetch()){
			echo '<option value=" '. $employeeId . ' "> ' . $firstName . " " . $lastName . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
				</fieldset>
				<p><input type="submit" /></p>
			</form>
		</div>

		<h2>Delete an employee</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept deleted employee information -->
			<form method="post" action="delemployee.php"> 

				<fieldset>		
					<p>Employee to be deleted*: <select name="DelEmployeeID">
		<!-- retrieve all employees and their ids for delete dropdown -->
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
		//use results to populate dropdown
		while($stmt->fetch()){
			echo '<option value=" '. $employeeId . ' "> ' . $employeeId . " " . $firstName . " " . $lastName . '</option>\n';
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