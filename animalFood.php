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
		
		<h1>Animal Foods</h1>
		<div>
		<!-- food table sorted by id and showing 'Yes' or 'No' for perishable boolean -->
			<table>
				<thead>
					<tr>
					  <th>Food ID</th>
					  <th>Name</th>
					  <th>Price ($)</th>
					  <th>Unit Of Measure</th>
					  <th>Supplier</th>
					  <th>Stock</th>
					  <th>Perishable</th>
					  <th>Amount On Order</th>
					  <th>Next Ship Date</th>
					</tr>
				</thead>
				<tbody>
		<!-- retrieve table values -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT foodId, name, price, unitOfMeasure, supplier, stock, REPLACE(REPLACE(perishable,'0','No'),'1','Yes') AS perishableBool, amountOnOrder, nextShipDate FROM animalFood ORDER BY foodId ASC;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($foodId, $name, $price, $unitOfMeasure, $supplier, $stock, $perishableBool, $amountOnOrder, $nextShipDate)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate table
		while($stmt->fetch()){
			 echo "<tr>\n<td>\n" . $foodId . "\n</td>\n<td>\n" . $name . "\n</td>\n<td>\n" . $price . "\n</td>\n<td>\n" .  $unitOfMeasure
			. "\n</td>\n<td>\n" . $supplier . "\n</td>\n<td>\n" . $stock . "\n</td>\n<td>\n" . $perishableBool . "\n</td>\n<td>\n" . $amountOnOrder 
			. "\n</td>\n<td>\n" . $nextShipDate . "\n</td>\n</tr>";
		}

		$stmt->close();
		?>
			</table>
		</div>


		<h2>Add a new animal food</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to accept new animal food information -->
			<form method="post" action="addanimalFood.php"> 
				<fieldset>
					<legend>Food Details</legend>
					<p>Name*: <input type="text" name="FoodName" placeholder="Bananas" /></p>
					<p>Price*: <input type="text" name="FoodPrice" placeholder="3.99" /></p>
					<p>Unit Of Measure*: <input type="text" name="FoodUnit" placeholder="10lb bushels" /></p>
					<p>Supplier: <input type="text" name="FoodSupplier" placeholder="Yellow Hat Produce" /></p>
					<p>Stock*: <input type="text" name="FoodStock" placeholder="12" /></p>
					<p>Perishable*: <input type="radio" name="FoodPerishable" value="0" checked> No 
					<input type="radio" name="FoodPerishable" value="1"> Yes</p>
					<p>Amount On Order: <input type="text" name="FoodOrder" placeholder="6" /></p>
					<p>Next Ship Date: <input type="text" name="FoodShip" placeholder="YYYY-MM-DD" /></p>
				</fieldset>
				<p><input type="submit" /></p>
			</form>
		</div>


		<h2>Delete an animal food</h2>
		<p><em>*required field</em></p>
		<div>
			<!-- form to delete an animal from the database -->
			<form method="post" action="delanimalFood.php"> 

				<fieldset>		
					<p>Animal food to be deleted*: <select name="DelAnimalFoodID">
		<!-- retrieve animal food names and ids for dropdown -->
		<?php
		//prepare query or display error
		if(!($stmt = $mysqli->prepare("SELECT foodId, name FROM animalFood"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		//execute or display error
		if(!$stmt->execute()){
			echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//bind results or display error
		if(!$stmt->bind_result($foodId, $name)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//use results to populate dropdown
		while($stmt->fetch()){
			echo '<option value=" '. $foodId . ' "> ' . $foodId . " " . $name . '</option>\n';
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