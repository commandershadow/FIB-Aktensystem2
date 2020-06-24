<?php
if ($_GET["f"]=="new"){
	
	
	
// Variablen aus dem POST nehmen	
	$n = $_POST['name'];
	$so = $_POST['standort'];
	$merk = $_POST['merk'];
	$typ = $_POST['typ'];

//Zuweisung von Tabelle
 if ($typ=="1"){
        
        $tabelle = "Fraktionen";
	    $bez = "Fraktion";
        
        }elseif ($typ=="2"){
        
        $tabelle = "Unternehmen";
	 	$bez = "Unternehmen";       
        }else{
        $tabelle = "Gruppierungen";
	 	$bez = "Gruppierung";
        }
	
// Überprüft ob Unternehmen / Gruppierung oder Fraktion schon eingetragen ist
				$grfrun_rows= $db->countRow($tabelle,"WHERE Name = '".$n."'");
				if($grfrun_rows>0)
				{
					echo $bez." ist schon vorhanden.";
				}				 				 
				else	
				{
					// inserting the user details into database.
					$dbInsert = array(
						"Name" => $n,
						"Standort" => $so,
						"Merkmale" => $merk);
					$db->insert($tabelle,$dbInsert);
					echo $bez." wurde erfolgreich eingetragen";
				}
	
// Wenn nichts ansteht...
}else if($_GET["f"]==''){
?>
<h3>Gruppierung / Fraktion / Unternehmen eintragen</h3><br/>
<div style="max-width: 500px; margin-top: 50px; margin-left: auto; margin-right: auto;">

<form action="home.php?p=add_grfrun&f=new" method="post"> 

		<p>
			<label>Bitte ausw&auml;hlen</label>
			<select name="typ">
				<option value="1">Fraktionen</option>
				<option value="2">Unternehmen</option>
				<option value="3">Gruppierungen</option>
			</select>
		</p>
		<p>
			<label>Name</label>
			<input type="text" name="name" placeholder="Gangster EV, Fire Department"/>
		</p>
		<p>
			<label>Standort</label>
			<input type="text" name="standort" placeholder="Los Santos Downtown"/>
		</p>
		<p>
			<label>Merkmale</label>
			<textarea name="merk" placeholder="grünes Hemd, graue Hose, Lila Sportwagen"></textarea>
		</p>

		<div class="reg_button">
			<input class="submit btn" type="submit" name="submit" value="Eintragen" s><br/><br/>
		</div>

	</form>
</div>
<? }
?>