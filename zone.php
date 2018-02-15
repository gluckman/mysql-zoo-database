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
		
		<h1>Zones</h1>
		<!-- zone table sorted by id with indoor and seaosnal booleans replaced with 'Yes' or 'No' -->
		<div>
			<table>
				<thead>
					<tr>
					  <th>Zone ID</th>
					  <th>Name</th>
					  <th>Maintenance Hours</th>
					  <th>Indoor Zone</th>
					  <th>Seasonal Zone</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT zoneId, name, maintHours, REPLACE(REPLACE(indoor,'0','No'),'1','Yes') AS indoorBool, REPLACE(REPLACE(seasonal,'0','No'),'1','Yes') AS seasonalBool FROM zone ORDER BY zoneId ASC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($zoneId, $name, $maintHours, $indoor, $seasonal)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $zoneId . "\n</td>\n<td>\n" . $name . "\n</td>\n<td>\n" . $maintHours . "\n</td>\n<td>\n" .  $indoor
			. "\n</td>\n<td>\n" . $seasonal . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
		
			</table>
		</div>

		<h2>Add a new zone</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept new animal information -->
			<form method="post" action="addzone.php"> 

				<fieldset>
					<legend>Zone Details</legend>
					<p>Name*: <input type="text" name="ZoneName" placeholder="Zone Z" /></p>
					<p>Maintenance Hours*: <input type="text" name="ZoneMaint" placeholder="10pm-12am" /></p>
					<p>Indoor*: <input type="radio" name="ZoneIndoor" value="0" checked> No 
					<input type="radio" name="ZoneIndoor" value="1"> Yes</p>
					<p>Seasonal*: <input type="radio" name="ZoneSeasonal" value="0" checked> No 
					<input type="radio" name="ZoneSeasonal" value="1"> Yes</p>
				</fieldset>
				<p><input type="submit" /></p>
			</form>
		</div>

		<h2>Delete a zone</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept deleted animal information -->
			<form method="post" action="delzone.php"> 
				<fieldset>		
					<p>Zone to be deleted*: <select name="DelZoneID">
		<!-- retrieve zones for delete dropdown -->
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
	</body>
</html>