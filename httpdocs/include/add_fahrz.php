<?php
	if ( $_GET[ "f" ] == "new" ) {



		// Variablen aus dem POST nehmen	
		$name = $_POST[ 'name' ];
		$bild = $_POST[ 'bild' ];


		// Überprüft ob Unternehmen / Gruppierung oder Fraktion schon eingetragen ist


		$email_rows = $db->countRow("Fahrzeuge","WHERE Name = '" . $name . "'");

		if ( $email_rows > 0 OR $name == "" ) {
			echo "Fahrzeug ist schon vorhanden oder kein Name eingetragen.";
		} else {
				// inserting the user details into database.
			$sql_2 = $db->insert("Fahrzeuge",array("Name" => $name));
			echo "Fahrzeug erfolgreich eingetragen.";
		}


		// Wenn nichts ansteht...
	} else if ( $_GET[ "f" ] == '' ) {
		?>

		<h3>Fahrzeuge eintragen</h3><br/>
		<div style="max-width: 500px; margin-top: 50px; margin-left: auto; margin-right: auto;">
			<form action="home.php?p=add_fahrz&f=new" method="post">

				<!-- ======= FirstName ====== -->
				<p>
					<label>Name</label>
					<input type="text" name="name" placeholder="Name"/>
				</p>



				<!-- Submit button -->
				<div class="reg_button">
					<input class="submit btn" type="submit" name="submit" value="Eintragen" s><br/><br/>
				</div>

			</form>

		</div>
		<? }?>