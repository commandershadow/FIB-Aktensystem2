<?php 
if (isset($_GET["f"])=="new"){
// Variablen aus dem POST nehmen	
	$vn = $_POST['vorname'];
	$nn = $_POST['nachname'];
	$sn = $_POST['spitzname'];
	$tel = $_POST['tel'];
	$sons = $_POST['sons'];
  
	$un = $_POST['unternehmen'];
	$gr = $_POST['gruppierung'];
	$fr = $_POST['fraktion'];
    $geb = $_POST['geb'];
    $ausid = $_POST['ausid'];
	   $gender = $_POST['geschlecht'];
	// Überprüft ob Bürger schon eingetragen ist.
	$buerger_rows = $db->countRow("Personen","WHERE Vorname = '".$vn."' AND Nachname = '".$nn."'");
				
	if($buerger_rows>0){
		echo "Der Bürger steht bereits in der Datenbank!";
	}else{
		
$upload_folder = 'buerger/'; //Das Upload-Verzeichnis
$filename = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));	
		
 
//Alles okay, verschiebe Datei an neuen Pfad
if ($filename != ""){
	//Überprüfung der Dateiendung
	$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
	if(!in_array($extension, $allowed_extensions)) {
		die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
	}

	//Überprüfung der Dateigröße
	$max_size = 11000000; //11MB
	if($_FILES['userfile']['size'] > $max_size) {
		die("Bitte keine Dateien größer 11 MB hochladen");
	}
		
	//Überprüfung dass das Bild keine Fehler enthält
	if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
		$allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		$detected_type = exif_imagetype($_FILES['userfile']['tmp_name']);
		if(!in_array($detected_type, $allowed_types)) {
			die("Nur der Upload von Bilddateien ist gestattet");
		}
	}
		
	//Pfad zum Upload
	$new_path = $upload_folder.$filename.'.'.$extension;
 
	//Neuer Dateiname falls die Datei bereits existiert
	if(file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
		$id = 1;
		do {
			$new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
			$id++;
		} while(file_exists($new_path));
	}
	
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $new_path) ) {
		$dbInsert = array(
			"Vorname" => $vn,
			"Nachname" => $nn,
			"Spitzname" => $sn,
			"Telefon" => $tel,
			"sons" => $sons,
			"passfoto" => $new_path,
            "geb" => $geb,
            "ausweisid" => $ausid,
			"Geschlecht" => $gender,
            "Unternehm_ID" => $un,
			"Grupp_ID" => $gr,
			"Frakt_ID" => $fr);
		$db->insert("Personen",$dbInsert);
		echo "Daten erfolgreich eingetragen! <a href=\"home.php?p=add_buerger\">Nächsten Bürger einfügen.</a><br>";
		echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';				
	} else {
		echo "Fehler beim Hochladen des Bildes";
	}
}else{
	$dbInsert = array(
		"Vorname" => $vn,
		"Nachname" => $nn,
		"Spitzname" => $sn,
		"Telefon" => $tel,
		"sons" => $sons,
        "ausweisid" => $ausid,
		"passfoto" => $new_path,
        "geb" => $geb,
        "ausweisid" => $ausid,
        "Geschlecht" => $gender,
		"Unternehm_ID" => $un,
		"Grupp_ID" => $gr,
		"Frakt_ID" => $fr);
	$db->insert("Personen",$dbInsert);
	echo "Daten erfolgreich eingetragen! <a href=\"home.php?p=add_buerger\">Nächsten Bürger einfügen.</a><br>";
	echo "Ohne Bild fortgefahren.";
}
	
	}
// Wenn nichts ansteht...
}else if(isset($_GET["f"])==''){
?>
           
<h3>Bürger hinzufügen</h3>
	 <link rel="stylesheet" type="text/css" href="tcal.css" />
<script type="text/javascript" src="tcal.js"></script> 
<!--- Formular --->
<div style="max-width: 500px; margin-top: 50px; margin-left: auto; margin-right: auto;">
<form action="home.php?p=add_buerger&f=new" method="post" enctype="multipart/form-data">             
	<p>
	 <label>Vorname</label>
	 <input type="text" name="vorname" placeholder="Max" />
	</p>
	<p>
    <label>Nachname</label>
    <input type="text" name="nachname" placeholder="Mustermann" />
    </p>
      <p>
    <label>Ausweis-ID</label>
    <input type="text" name="ausid" placeholder="12345678" />
    </p>  
	<p>
    <label>Spitzname</label>
    <input type="text" name="spitzname" placeholder="Terminator" />
	</p>
     <p>
    <label>Geschlecht</label>
     <select name="geschlecht"><br>
	 <option name="geschlecht" type="text" id="geschlecht" value="W" selected>Weiblich</option>
     <option name="geschlecht" type="text" id="geschlecht" value="M" selected>Männlich</option>
     </select>
    </p>   
	<p>
	<label>Geburtstag:</label>
		<input name="geb" type="text" id="geb" class="tcal">
	</p>
    <p>
    <label>Telefonnummer (Bitte <font color="red">OHNE</font> Bindestriche)</label>
    <input type="text" name="tel" placeholder="013371234567" />
    </p>   
    <p>
    <label>Sonstiges</label>
    <textarea name="sons" placeholder="Informationen" /></textarea>
    </p>
    <? if ($data->rang >= $BU_RECHT){?>
    <p>
	<label>Bild max. 10 MB</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="11000000">
	<input name="userfile" type="file">
	</p>
   <? } ?>
    <p>
     <label>Gruppierung:</label>
     <select name="gruppierung"><br>                
      <?
      echo "<option name=\"gruppierung\" type=\"text\" id=\"gruppierung\" value=\"0\" selected>Keine</option>";
	$foreach = $db->multiQuery("Grupp_ID,Name FROM Gruppierungen",true);
	foreach ($foreach as $key => $row) {
		echo "<option name=\"gruppierung\" type=\"text\" id=\"gruppierung\" value=". $row['Grupp_ID'] .">". $row['Name'] . "</option>";
	}
      ?>
     </select>
    </p>
    <p>
     <label>Unternehmen:</label>
     <select name="unternehmen"><br>                
<?
       echo "<option name=\"unternehmen\" type=\"text\" id=\"unternehmen\" value=\"0\" selected>Keine</option>";
	$foreach = $db->multiQuery("Unternehm_ID,Name FROM Unternehmen",true);
	foreach ($foreach as $key => $row) {
          echo "<option name=\"unternehmen\" type=\"text\" id=\"unternehmen\" value=". $row['Unternehm_ID'] .">". $row['Name'] . "</option>";
          }
?>
	</select>
    </p>
    <p>
     <label>Fraktionen:</label>
     <select name="fraktion"><br> 
     <?
     echo "<option name=\"fraktion\" type=\"text\" id=\"fraktion\" value=\"0\" selected>Keine</option>";
	$foreach = $db->multiQuery("Frakt_ID,Name from Fraktionen",true);
	foreach ($foreach as $key => $row) {
     echo "<option name=\"fraktion\" type=\"text\" id=\"fraktion\" value=". $row['Frakt_ID'] .">". $row['Frakt_ID'] ." ". $row['Name'] . "</option>";
      }
      ?>
	</select>
    </p>                                     

    <div class="reg_button">
    <input class="submit btn" type="submit" name="submit" value="Eintragen">
    </div>
                  
 </form> 
</div>                
</div>        
             
<?
}
?>
              
      