<div style="max-width: 800px; margin-top: 50px; margin-left: auto; margin-right: auto;">
<?
    
switch($_GET["action"]){

    #Sicherheitsabfrage
    case "remove":
        echo "Wirklich Sicher, dass du den Buerger löschen möchtest?<br>";
        echo "<a href=\"home.php?p=det_buer&action=removed&id=".$_GET['id']."\">JA!</a>";   
    break;
    
    #Löschung eines Bürgers und Bestätigung
    case "removed":
        $db->delete("Personen",array("Pers_ID" => $_GET['id']));
        echo "Bürger erfolgreich gelöscht.";
    break;
        
}
		
	
//Neuhochladen von Bildern -> Speichern in der DB
if ($_POST["bilder"]=="Hochladen"){		
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
$max_size = 11000000; //500 KB
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
	
	
	if ( move_uploaded_file($_FILES['userfile']['tmp_name'], $new_path) ) {
		$db->update("Personen",array("passfoto" => $new_path),array("Pers_ID" => $_GET['id']));
		echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';		
	} else {
		echo "Fehler beim Hochladen des Bildes";
	}
} }
	
//Speichern in der DB
	if ($_GET['f'] == "update_pic"){?>
	<form action="" method="post" enctype="multipart/form-data">     
	<p>
	<label>Bild max. 10 MB</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="11000000">
	<input name="userfile" type="file">
	</p>
	<div class="reg_button">
    <input class="submit btn" type="submit" id="bilder" name="bilder" value="Hochladen">
    </div>
	</form>
	<?}?>
<?
//Löschen vom Bild
if ($_GET["f"]=="remove_pic"){	
	
	$datei_name = $_GET['bild'];
      
    if (@file_exists($datei_name) == true) {
      
        if (@unlink($datei_name) == true) {
			$db->update("Personen",array("passfoto" => ''),array("Pers_ID" => $_GET['id']));		
            echo 'Das Foto: '.$datei_name.' wurde
                erfolgreich aus der Akte vernichtet.';
        } else {
            echo 'Die Datei: '.$datei_name.' konnte
                nicht gelöscht werden!';
        }
    } else {
        echo 'Die Datei: '.$datei_name.' ist nicht
            vorhanden!';
    }
}

	 
// Aktuallisieren von Eintragungen
if ($_POST['update']== "Save Changes"){
 $geb = $_POST['geb'];
    $ausid = $_POST['ausweis'];
	   $gender = $_POST['geschlecht'];
	$dbUpdate = array(
		"status" => $_POST['Status'],
		"Vorname" => $_POST['vorname'],
		"Nachname" => $_POST['nachname'],
		"Spitzname" => $_POST['spitzname'],
		"Telefon" => $_POST['telefon'],
		"geb" => $geb,
        "ausweisid" => $ausid,
		"Geschlecht" => $gender,
		"Unternehm_ID" => $_POST['unternehmen'],
		"Grupp_ID" => $_POST['gruppierung'],
		"Frakt_ID" => $_POST['fraktion'],
		"sons" => addslashes(nl2br(htmlspecialchars($_POST['sons']))));
	$dbWhere = array("Pers_ID" => $_POST['id']);
	$db->update("Personen",$dbUpdate,$dbWhere);
	echo "<h1>Erfolgreich geändert</h1>";
}

// Auslesen von Details
	$u_detail = $db->multiQuery("Personen.*, Gruppierungen.Name AS Grupp_name, Unternehmen.Name AS unternehm_name, Fraktionen.Name AS Frakt_name from Personen left 
      join Gruppierungen on Personen.Grupp_ID = Gruppierungen.Grupp_ID left
      join Unternehmen on Personen.Unternehm_ID = Unternehmen.Unternehm_ID left
      join Fraktionen on Personen.Frakt_ID = Fraktionen.Frakt_ID where Personen.Pers_ID = ".$_GET['id'],true);
	foreach ($u_detail as $key => $row) {
			
			 if(sizeof($row) >0)	// if user exists
			 {
?>
 <nav class="cf">
		<ul id="nav" class="sf-menu">
			<li>
				<a href="#">Hauptmenü</a>

				<ul class="sub-menu">
					<li> <a href="home.php?p=det_buer&f=&id=<?=$row[Pers_ID];?>" class="btn btn-info">
                            Bürgerakte</a>
					</li>
					<li> <a href="home.php?p=det_buer&f=akte&id=<? echo $row['Pers_ID']; ?>" class="btn btn-danger delet">
                        DOJ Verurteilungen / Beschlüsse</a>
					</li>
					<li> <a href="home.php?p=det_buer&f=kennz&id=<? echo $row['Pers_ID']; ?>" class="btn btn-danger delet">
                        Kennzeichen eintragen</a>
					</li>
					</ul>
			</li>
			<li>
				<a href="#">Einstellungen</a>

				<ul class="sub-menu">
					<lI>
                            <?if ($data->rang >= $BUE_Bearb){?>
                            <a href="home.php?p=det_buer&f=change&id=<? echo $row['Pers_ID']; ?>" class="btn btn-info" >
                            Bearbeiten</a>
                        
                        <? } ?>
					</lI>

					<lI> <? if ($data->rang >= $BUE_Loesch){?>
                        <a href="home.php?p=det_buer&action=remove&id=<? echo $row['Pers_ID']; ?>" class="btn btn-danger delet">
                        <font color="#8A0B0D">Bürger L&ouml;schen</font></a>
                         <? } ?>
					</lI>
				</ul>
			</li>

		</ul>
	</nav>

<?
	} }
			 
if ($_GET["f"]==""){ 
// Auslesen von Details
	$u_detail = $db->multiQuery("Personen.*, Gruppierungen.Name AS Grupp_name, Unternehmen.Name AS unternehm_name, Fraktionen.Name AS Frakt_name from Personen left 
      join Gruppierungen on Personen.Grupp_ID = Gruppierungen.Grupp_ID left
      join Unternehmen on Personen.Unternehm_ID = Unternehmen.Unternehm_ID left
      join Fraktionen on Personen.Frakt_ID = Fraktionen.Frakt_ID where Personen.Pers_ID = ".$_GET['id'],true);
	foreach ($u_detail as $key => $row) {
			
			 if(sizeof($row) >0)	// if user exists
			 {?>
			
			
			
			
			
			<style>
*{
padding : 0;
margin : 0;
border : 0;
}
.blended_grid{
display : block;
width : 900px;
overflow : auto;
margin : 20px auto 0 auto;
}
.pageLeftMenu{
float : left;
clear : none;
height : auto;
width : 300px;
}
.pageContent{
float : left;
clear : none;
height : auto;
width : 600px;
}
</style>
 <h2><font size="5">Akte von</font> <font color="#020187"><? echo $row['Vorname']; ?> <? echo $row['Nachname']; ?> </font></h2>
<div class="blended_grid">
<div class="pageLeftMenu">
<?if ($row['passfoto'] != ""){
			$bild   = $row['passfoto'];
			$meldung = "<a href=\"home.php?p=det_buer&f=remove_pic&id=".$row['Pers_ID']."&bild=".$row['passfoto']."\">Bild löschen</a>";
		}else{
			$bild   = "pl_bild.png";
			$meldung = "<a href=\"home.php?p=det_buer&f=update_pic&id=".$row['Pers_ID']."\">Bild hochladen</a>";
		}
		if ($row['status'] == 0){
			$status = "TOT";
			$farbe ="#841113";
		}else if ($row['status'] == 2){
			$status = "VERMISST";
			$farbe ="#D7BB03";
		}else{
			$status = "LEBEND";
			$farbe ="#46981F";
		}
echo "<center> Status:	<font style=\"background: ".$farbe."; font-size: 15px; text-align: center;\">". $status . "</font></center>";?>
		<br>
		 <img src="<?=$bild;?>" style="width:200px; height:300px;" />
		 <?if ($data->rang >= $BUE_BU){?>
		 <br><?=$meldung;?>
		 <? } ?>
</div>
<div class="pageContent">
 
			 
 
                 <? } ?>
                     <table width="500px">
 
						 <tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Vorname </td><td> Nachname</td>
                        </tr>
 
                         <tr>
                           <td><? echo $row['Vorname']; ?> </td><td> <? echo $row['Nachname']; ?></td
                        </tr>
                        
                        <tr style="background: #6C6C6C; font-size: 15px;">
                            <td>Spitzname </td><td> Telefon </td>
                        </tr>
                        <tr>
                           <td><? echo $row['Spitzname']; ?> </td><td> <?=conv2Tel($row['Telefon']);?></td>
                        </tr>
                         <tr style="background: #6C6C6C; font-size: 15px;">
                            <td>Ausweis ID </td><td> Geschlecht </td>
                        </tr>
                        <tr>
                           <td><? echo $row['ausweisid']; ?> </td><td> <?=$row['Geschlecht'];?></td>
                        </tr>
                        <tr style="background: #6C6C6C; font-size: 15px;">
                            <td>Geburtstag </td><td> </td>
                        </tr>
                        <tr>
                           <td><? echo $row['geb']; ?> ( Alter: <?echo  alter($row['geb']);?> )</td><td> </td>
                        </tr>
   					
                      <? if ($row['Unternehm_ID'] !== "0" || $row['Grupp_ID'] !== "0"){ ?>
                       <tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Unternehmen </td><td> Gruppierung</td>
                        </tr>
                        <tr>
                           <td><? echo $row['unternehm_name']; ?> </td><td> <? echo $row['Grupp_name']; ?></td>
                        </tr>
                           <? } ?>
                           <? if ($row['Frakt_name'] !== "" || $row['sons'] !== ""){ ?>
                        <tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Fraktionen </td><td>Sonstiges</td>
                        </tr>
                          <tr>
                           <td> <? echo $row['Frakt_name']; ?></td><td><? echo $row['sons']; ?></td>
                        </tr>
                        <? } ?>
	</table>
                      <br>
                       <h3>Beteiligte Fälle</h3>
                                 <table width="500px">
                        <tr>
                           <td>&nbsp;</td><td> </td>
                        </tr>
                        
                        <tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Fallakte</td><td> Status</td>
                        </tr>
 <?
    
$fallakten= $db->multiQuery("* from Fallakten_Personen where Pers_ID = ".$row['Pers_ID'],true);
foreach ($fallakten as $key => $row2) {
		$Fall_d = $db->singleQuery("* FROM Fallakten WHERE Fall_ID = ".$row2['Fall_ID']);
?>
      <tr>
      <td><a href="home.php?p=det_fallakten&Fall=<?=$Fall_d->Fall_ID;?>">#<?=$Fall_d->Fall_ID;?> - <?=$Fall_d->Bezeichnung;?></a></td>
      <? if ($Fall_d->Status =="0"){
			$status = "OFFEN";
			$farbe ="#841113";		
		}else if ($Fall_d->Status =="1"){
			$status = "IN BEARBEITUNG";
			$farbe ="#D7BB03";
		}else{
			$status = "ABGESCHLOSSEN";
			$farbe ="#46981F";
		}
							?>
      <td style="background: <?=$farbe;?>;">                   
        <?=$status;?>               
	  </td>
      </tr> 
 
                         <tr>
                            <td>&nbsp;</td><td> </td>
                        </tr>
       
                         <? }?>
                         <tr>
                            <td>&nbsp;</td><td> </td>
                        </tr>
                        	</table>
                      <br>
      <h3>DOJ Verurteilungen / Beschlüsse</h3>
                                 <table width="500px">
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
                        
                        <tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Dokumente</td>
                        </tr>
                     
                      <?
    
$fallakten= $db->multiQuery("* from PD_Akten where pid = ".$row['Pers_ID'],true);
foreach ($fallakten as $key => $row2) {
?>
                      
                       <tr>
      <td><a href="<?=$row2['url'];?>" target="_blank"><?=$row2['Titel'];?></a></td>
      </tr> 
    <? }?>
                         <tr>
                            <td>&nbsp;</td>
                        </tr>
                        	</table>
                      <br>
                      
                      
                       <h3>Kennzeichen</h3>
                            <table width="500px">
                         <tr style="background: #6C6C6C; font-size: 15px;">
                            <td>Fahrzeug </td><td> Kennzeichen</td><td>
        </td>
                        </tr>
                        <?
    
$kennz_suche= $db->multiQuery("* from Kennzeichen left join Fahrzeuge on Kennzeichen.Fahrz_ID = Fahrzeuge.Fahrz_ID where Pers_ID = ".$_GET['id'],true);
foreach ($kennz_suche as $key => $row) {
if (sizeof($row) >0){
?>
                       
<?
	// Alte Kennzeichen zu dem Fahrzeug vorhanden?
	$oldKennz = $db->multiQuery("logParam FROM Log WHERE logTable = 'Kennzeichen' AND logTableID = '".$row['Kennz_ID']."' AND logType = 'UPDATE'",true);
	$oldKennzArr = array();
	foreach($oldKennz as $key => $oldK) {
		$oldK = unserialize($oldK['logParam']);
		$oldKennzArr[] = $oldK['Kennzeichen'][0];
		#print_r($oldKennzArr);
	}
	if(sizeof($oldKennzArr) > 0) {
		$oldKennzText = implode(', ',$oldKennzArr);
		$oldKennzText = '<span style="opacity:0.5"> ('.$oldKennzText.')</span>';
	} else $oldKennzText = '';
      echo "
      <tr>
      <td>". $row['Name']."</td>
      <td>". $row['Kennzeichen'] . $oldKennzText . "</td>
	  <td align=\"right\"><a href=\"?p=det_buer&f=bear_kennz&id=".$row['Kennz_ID']."\">Bearbeiten</a> | <a href=\"?p=det_buer&f=del_kennz&id=".$row['Kennz_ID']."\">(X)Löschen</a></td>
      </tr>";      
} else {
echo "<tr>
      <td>Keine Fahrzeuge vorhanden...</td>
      <td></td>
      </tr>";
}
}
                
?>   
                       
                      </table>
                    
                    
</div>
</div>



<? }  
}
	 if ($_GET["f"]=="del_kennz"){
		 $db->delete("Kennzeichen",array("Kennz_ID" => $_GET['id']));
			echo "Kennzeichen erfolgreich entfernt";
		 
	 }
	 if ($_POST['update']== "Kennzeichen bearbeiten"){
		 $db->update("Kennzeichen", array("Kennzeichen" => strtoupper($_POST['kennz']), "Altes" => strtoupper($_POST['alt'])), array("Kennz_ID" => $_GET['id']));		
            echo 'Kennzeichen erfolgreich geändert.';
	 }
	
	 if ($_GET["f"]=="bear_kennz"){
		 $kennz = $db->singleQuery("Altes, Kennzeichen FROM Kennzeichen WHERE Kennz_ID = ".$_GET["id"]);
		?>
		
<form id="update_form" name="update_form" method="post" action="">

	<p>
		<label>Altes Kennzeichen:</label>
		<input type="text" name="alt" value="<? echo $kennz->Altes; ?>"/>
	</p>
	<p>
		<label>Neues Kennzeichen:</label>
		<input name="kennz" type="text" id="kennz" value="<? echo $kennz->Kennzeichen; ?>"/>
	</p>
		<p>
		<input type="submit" name="update" id="update" class="btn" value="Kennzeichen bearbeiten"/>
	</p>

</form>   
		<? 
		 
	 }
	 // Akte anheften
	 if ($_POST["submit"]=="Akte anheften"){	

// Variablen aus dem POST nehmen	
	$id = $_POST['id'];
	$url = $_POST['url'];
	$titel = $_POST['titel'];
	
	
				
// inserting the kennzeichen details into database.
	$dbInsert = array(
		"url" => $url,
		"Titel" => $titel,
		"pid" => $id);
	$db->insert("PD_Akten",$dbInsert);
	echo "PD Akte erfolgreich angeheftet.";
}

	 if ($_GET["f"]=="akte"){
	 ?>
<h3>Akte anheften</h3><br/>
<div style="max-width: 500px; margin-top: 50px; margin-left: auto; margin-right: auto;">
<form action="" method="post"> 	 

<input type="hidden" name="id" value="<? echo $_GET['id']; ?>"/>
	<p>
		<label>Straftat</label>
		<input type="text" name="titel" placeholder="StVO §6 Abs. 4 - Gesetzbeschreibung"/>
	</p>
	<p>
		<label>URL</label>
		<input type="text" name="url" placeholder="http://www.proj5.net/akten/...."/>
	</p>

	<!-- Submit button -->
	<div class="reg_button">
		<input class="submit btn" type="submit" name="submit" value="Akte anheften"><br/><br/>
	</div>

</form>
	</div>
	<?
	}

	 
	 if ($_POST["submit"]=="Eintragen"){	

// Variablen aus dem POST nehmen	
	$perso = $_POST['id'];
	$fahrz_ID = $_POST['fahrz_ID'];
	$kennz = strtoupper($_POST['kennz']);
	$alt = strtoupper($_POST['alt']);

	
// Überprüft ob Kennzeichen schon eingetragen ist
$vorhanden = $db->countRow("Kennzeichen","WHERE Kennzeichen = '".$kennz."'");
if($vorhanden > 0) {
	echo "Kennzeichen ".$kennz." ist bereits eingetragen. <a href=\"home.php?p=det_buer\">Zurück</a>";
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
}

	 if ($_GET["f"]=="kennz"){
	 ?>
<h3>Kennzeichen eintragen</h3><br/>
<div style="max-width: 500px; margin-top: 50px; margin-left: auto; margin-right: auto;">
<form action="" method="post"> 	 

<input type="hidden" name="id" value="<? echo $_GET['id']; ?>"/>

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
	<?
	}
     if ($_GET["f"]=="change"){ 
               
                    $id = $_GET['id']; 
						$sql_44 = $db->multiQuery("Personen.*, Gruppierungen.Name AS Grupp_name, Unternehmen.Name AS unternehm_name, Fraktionen.Name AS Frakt_name from Personen left 
      join Gruppierungen on Personen.Grupp_ID = Gruppierungen.Grupp_ID left
      join Unternehmen on Personen.Unternehm_ID = Unternehmen.Unternehm_ID left
      join Fraktionen on Personen.Frakt_ID = Fraktionen.Frakt_ID where Personen.Pers_ID = ".$id,true);
                               foreach ($sql_44 as $key => $get_result) {
?>
	 <link rel="stylesheet" type="text/css" href="tcal.css" />
<script type="text/javascript" src="tcal.js"></script> 
<form id="update_form" name="update_form" method="post" action="home.php?p=det_buer&f=&id=<? echo $get_result['Pers_ID']; ?>">

	<input type="hidden" name="id" value="<? echo $get_result['Pers_ID']; ?>"/>
	<p>
		<label>Status:</label>
		<select name="Status"><br>
			<?
                //Kategorie Suche
                             if   ($get_result['status']==0){
                             echo "<option name=\"Status\" type=\"text\" id=\"Status\" value=\"0\" selected>TOT</option>";
                             }else if ($get_result['status']==1){
							echo "<option name=\"Status\" type=\"text\" id=\"Status\" value=\"1\" selected>LEBEND</option>";
							 } else {
							echo "<option name=\"Status\" type=\"text\" id=\"Status\" value=\"2\" selected>VERMISST</option>";	 
							 }
         ?>
		<option name="Status" id="Status" value="0">TOT</option>
		<option name="Status" id="Status" value="1">LEBEND</option>
		<option name="Status" id="Status" value="2">VERMISST</option>
		</select>
			</p>

	<p>
		<label>Vorname</label>
		<input name="vorname" type="text" id="vorname" value="<? echo $get_result['Vorname']; ?>"/>
	</p>

	<p>
		<label>Nachname:</label>
		<input name="nachname" type="text" id="nachname" value="<? echo $get_result['Nachname']; ?>"/>
	</p>

	<p>
		<label>Spitzname:</label>
		<input name="spitzname" type="text" id="spitzname" value="<? echo $get_result['Spitzname']; ?>"/>
	</p>

	<p>
		<label>Telefon:</label>
		<input name="telefon" type="text" id="telefon" value="<? echo $get_result['Telefon']; ?>"/>
	</p>
    	<p>
		<label>Ausweis ID:</label>
		<input name="ausweis" type="text" id="ausweis" value="<? echo $get_result['ausweisid']; ?>"/>
	</p>
     <p>
    <label>Geschlecht</label>
     <select name="geschlecht"><br>
     		<?
                //Kategorie Suche
                             if   ($get_result['Geschlecht']=="M"){
                             echo "<option name=\"geschlecht\" type=\"text\" id=\"geschlecht\" value=\"M\" selected>Männlich</option>
                             <option name=\"geschlecht\" type=\"text\" id=\"geschlecht\" value=\"W\">Weiblich</option>";
                             }else if ($get_result['Geschlecht']=="W"){
							echo "<option name=\"geschlecht\" type=\"text\" id=\"geschlecht\" value=\"W\" selected>Weiblich</option>
                            <option name=\"geschlecht\" type=\"text\" id=\"geschlecht\" value=\"M\">Männlich</option>";
							 } ?>	 
     </select>
    </p>   
    	<p>
	<label>Geburtstag:</label>
		<input name="geb" type="text" id="geb" class="tcal" value=<?=$get_result['geb'];?>
	</p>

	<p>
		<label>Sonstiges:</label>
		<textarea name="sons" type="text" id="sons" value="<? echo $get_result['sons']; ?>"/><? echo str_replace('<br />','',$get_result['sons']); ?></textarea>
	</p>
	<p>
		<label>Gruppierung:</label>
		<select name="gruppierung"><br>                
	  <?
		echo "<option name=\"gruppierung\" type=\"text\" id=\"gruppierung\" value=\"0\">Keine</option>";
		$grupp_suche= $db->multiQuery("* from Gruppierungen",true);
		foreach ($grupp_suche as $key => $zeile) {
		   echo "<option name=\"gruppierung\" type=\"text\" id=\"gruppierung\" value=". $zeile['Grupp_ID'] .($get_result['Grupp_ID']==$zeile['Grupp_ID']?' selected="selected"':'').">". $zeile['Name'] . "</option>";
		}
		?>
                      
                                </select>
	</p>
	<p>
		<label>Unternehmen:</label>
		<select name="unternehmen"><br>                
	  <?
//Kategorie Suche
		echo "<option name=\"unternehmen\" type=\"text\" id=\"unternehmen\" value=\"0\">Keine</option>";
		$unternehm_suche= $db->multiQuery("* from Unternehmen",true);
		foreach ($unternehm_suche as $key => $unternehm) {
			echo "<option name=\"unternehmen\" type=\"text\" id=\"unternehmen\" value=". $unternehm['Unternehm_ID'] .($get_result['Unternehm_ID']==$zeile['Unternehm_ID']?' selected="selected"':'').">". $unternehm['Name'] . "</option>";
		}
		?>
                      
                                </select>
	</p>
	<p>
		<label>Fraktionen:</label>
		<select name="fraktion"><br> 
              
                              <?
                //Kategorie Suche
		echo "<option name=\"fraktion\" type=\"text\" id=\"fraktion\" value=\"0\">Keine</option>";
		$frakt_suche= $db->multiQuery("* from Fraktionen",true);
		foreach ($frakt_suche as $key => $frakt) {
			echo "<option name=\"fraktion\" type=\"text\" id=\"fraktion\" value=". $frakt['Frakt_ID'] .($get_result['Frakt_ID']==$zeile['Frakt_ID']?' selected="selected"':'').">". $frakt['Frakt_ID'] ." ". $frakt['Name'] . "</option>";
		}
                
                                ?>
                      
                                </select>
	</p>
	<p>
		<input type="submit" name="update" id="update" class="btn" value="Save Changes"/>
	</p>

</form>        
            <?	} }  ?>
            </div>
            