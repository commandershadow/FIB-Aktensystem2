<?php

if ($_GET["f"]=="new"){	

// Variablen aus dem POST nehmen	
	$perso = $_POST['perso'];
	$fahrz_ID = $_POST['fahrz_ID'];
	$kennz = strtoupper($_POST['kennz']);
	$alt = strtoupper($_POST['alt']);

	
// Überprüft ob Kennzeichen schon eingetragen ist
$vorhanden = $db->countRow("Kennzeichen","WHERE Kennzeichen = '".$kennz."'");
if($vorhanden > 0) {
	echo "Kennzeichen ".$kennz." ist bereits eingetragen. <a href=\"home.php?p=add_kennz\">Zurück</a>";
}			 				 
else	
{
						
// inserting the kennzeichen details into database.
	$dbInsert = array(
		"Pers_ID" => $perso,
		"Fahrz_ID" => $fahrz_ID,
		"Altes" => $alt,
		"Kennzeichen" => $kennz);
	$db->insert("Kennzeichen",$dbInsert);
	echo "Kennzeichen erfolgreich eingetragen.";
}
					
	
// Wenn nichts ansteht...
}else if($_GET["f"]==''){
?>

<h3>Kennzeichen eintragen</h3><br/>
<div style="max-width: 500px; margin-top: 50px; margin-left: auto; margin-right: auto;">
<form action="home.php?p=add_kennz&f=new" method="post"> 
	<p>
		<label>Bitte ausw&auml;hlen                    </label>
		<select name="fahrz_ID">
                <?
                //Kategorie Suche
                    $katData = $db->multiQuery("* FROM Fahrzeuge ORDER BY Name ASC",true);
                    foreach($katData as $key => $zeile)
    {
      echo "<option value=". $zeile['Fahrz_ID'] .">". $zeile['Name'] . "</option>";
    }
                
                ?>
                       </select>
	
	</p>

	<!-- ======= FirstName ====== -->
	<p>
		<label>Person</label>
		<select name="perso">
                <?
                //Kategorie Suche
				$pers_suche = $db->multiQuery("* FROM Personen ORDER BY Vorname ASC",true);
			    foreach($pers_suche as $key => $zeile)
    {
      echo "<option value=".$zeile['Pers_ID'].">".$zeile['Vorname']." ".$zeile['Nachname']."</option>";
    }
                
                ?>
                       </select>
	
	</p>

	<!-- ======= FirstName ====== -->
	<p>
		<label>Altes Kennzeichen:</label>
		<input type="text" name="alt" placeholder="PM0001"/>
	</p>
	<p>
		<label>Aktuelles Kennzeichen:</label>
		<input type="text" name="kennz" placeholder="PM0001"/>
	</p>



	<!-- Submit button -->
	<div class="reg_button">
		<input class="submit btn" type="submit" name="submit" value="Eintragen"><br/><br/>
	</div>

</form>
</div>

<?           } 
?>