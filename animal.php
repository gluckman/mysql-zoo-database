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
		
		<h1>Animals</h1>
		<!-- animal table sorted by id and showing names for mother, father, and food -->
		<div>
			<table>
				<thead>
					<tr>
						<th>Animal ID</th>
						<th>Name</th>
						<th>Species</th>
						<th>Sex</th>
						<th>Birthday</th>
						<th>Death day</th>
						<th>Food</th>
						<th>Eating Schedule</th>
						<th>Pregnant</th>
						<th>Mother</th>
						<th>Father</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT a.animalId, a.name, a.species, a.sex, a.birth, a.death, animalFood.name AS foodName, a.eatingSchedule, REPLACE(REPLACE(a.pregnant,'0','No'),'1','Yes') AS pregBool, m.name AS mother, f.name AS father FROM animal AS a 
										LEFT JOIN animalFood ON a.foodId = animalFood.foodId 
										LEFT JOIN animal AS m ON a.motherId = m.animalId 
										LEFT JOIN animal as f ON a.fatherId = f.animalId
										ORDER BY a.animalId ASC"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($animalId, $name, $species, $sex, $birth, $death, $foodName, $eatingSchedule, $pregBool, $mother, $father)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $animalId . "\n</td>\n<td>\n" . $name . "\n</td>\n<td>\n" . $species . "\n</td>\n<td>\n" .  $sex
			. "\n</td>\n<td>\n" . $birth . "\n</td>\n<td>\n" . $death . "\n</td>\n<td>\n" . $foodName . "\n</td>\n<td>\n" . $eatingSchedule 
			. "\n</td>\n<td>\n" . $pregBool . "\n</td>\n<td>\n" . $mother . "\n</td>\n<td>\n" . $father . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>

		<h2>Add a new animal</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept new animal information -->
			<form method="post" action="addanimal.php"> 

				<fieldset>
					<legend>Animal Details</legend>
					<p>Name*: <input type="text" name="AnimalName" placeholder="Curious George" /></p>
					<p>Species*: <input type="text" name="AnimalSpecies" placeholder="Monkey"/></p>
					<p>Sex*: <input type="radio" name="AnimalSex" value="M" checked> Male 
					<input type="radio" name="AnimalSex" value="F"> Female</p>
					<p>Birthday: <input type="text" name="AnimalBirth" placeholder="YYYY-MM-DD" /></p>
					<p>Mother: <select name="AnimalMother">
									<option value=""> </option>
		<!-- retrieve female animals for mother dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalId, name FROM animal WHERE sex='F' OR sex='f'"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute query or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind query results or display error
		if(!$stmt->bind_result($animalId, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with results
		while($stmt->fetch()){
			echo '<option value=" '. $animalId . ' "> ' . $name . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
					<p>Father: <select name="AnimalFather">
									<option value=""> </option>
		<!-- retrieve male animals for father dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalId, name FROM animal WHERE sex='M' OR sex='m'"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute query or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind query results or display error
		if(!$stmt->bind_result($animalId, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with results
		while($stmt->fetch()){
			echo '<option value=" '. $animalId . ' "> ' . $name . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
					<p>Pregnant*: <input type="radio" name="AnimalPregnant" value="0" checked> No 
					<input type="radio" name="AnimalPregnant" value="1"> Yes</p>
					<p>Death day: <input type="text" name="AnimalDeath" placeholder="YYYY-MM-DD" /></p>
				</fieldset>

				<fieldset>
					<legend>Feeding Details</legend>
					<p>Food: <select name="AnimalFood">
									<option value=""> </option>
		<!-- retrieve food names and ids for food dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT foodId, name FROM animalFood"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute query or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind query results or display error
		if(!$stmt->bind_result($foodId, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with query results
		while($stmt->fetch()){
			echo '<option value=" '. $foodId . ' "> ' . $name . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
					<p>Eating Schedule*: <input type="text" name="AnimalEating" placeholder="3 times a day" /></p>
				</fieldset>
				<p><input type="submit" /></p>
			</form>
		</div>


		<h2>Update an animal's pregnant status</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to update pregnancy status of female animal -->
			<form method="post" action="upanimal.php"> 

				<fieldset>		
					<p>Animal*: <select name="UpAnimalID">
		<!-- retrieve female animals for dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalId, name FROM animal"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute query or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind query results or display error
		if(!$stmt->bind_result($animalId, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with query results
		while($stmt->fetch()){
			echo '<option value=" '. $animalId . ' "> ' . $animalId . " " . $name . '</option>\n';
		}
		$stmt->close();
		?>
					</select></p>
					<p>Pregnant*: <input type="radio" name="UpAnimalPregnant" value="0" checked> No 
					<input type="radio" name="UpAnimalPregnant" value="1"> Yes</p>
				</fieldset>
				<p><input type="submit" /></p>
			</form>
		</div>

		
		<h2>Delete an animal</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to delete an animal from the database -->
			<form method="post" action="delanimal.php"> 

				<fieldset>		
					<p>Animal to be deleted*: <select name="DelAnimalID">
		<!-- retrieve animal names and ids for dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT animalId, name FROM animal"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute query or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind query results or display error
		if(!$stmt->bind_result($animalId, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//populate dropdown with query results
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
	</body>
</html>