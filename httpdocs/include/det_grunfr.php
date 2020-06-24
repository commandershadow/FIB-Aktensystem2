<div style="max-width: 800px; margin-top: 50px; margin-left: auto; margin-right: auto;">
<?

	$typ = $_GET['typ'];
	$id = $_GET['id'];
 if ($typ=="Frakt"){
    
        $tabelle = "Fraktionen";
	    $bez = "Fraktion";
	 	$bid = "Frakt_ID";
        
}
if ($typ=="Unter"){
        
        $tabelle = "Unternehmen";
	 	$bez = "Unternehmen";    
	 	$bid = "Unternehm_ID";
        }
if ($typ=="Grupp"){
        $tabelle = "Gruppierungen";
	 	$bez = "Gruppierung";
	    $bid = "Grupp_ID";
        }
	

// Sicherheitsabfrage
if ($_GET["action"]=="remove"){
			echo "Sind Sie Sich wirklich Sicher, dass Sie die ".$bez." löschen möchten?<br>";

			echo "<a href=\"home.php?p=det_grunfr&action=removed&typ=".$typ."&id=".$_GET['id']."\">JA!</a>";
	
		}

	
// Löschen von Eintragungen von Bürgern
if ($_GET["action"]=="removed") {
			$db->delete($tabelle,array($bid => $_GET['id']));
			echo $bez." erfolgreich gelöscht.";
	
		}
	 
// Aktuallisieren von Eintragungen
if ($_POST['update']== "Save Changes"){
	$dbUpdate = array(
		"Name" => $_POST['Name'],
		"Standort" => $_POST['Standort'],
		"s" => $_POST['Status'],
		"Merkmale" => $_POST['Merkmale']);
	$dbWhere = array($bid => $_GET['id']);
	$db->update($tabelle,$dbUpdate,$dbWhere);
	@header('Location:home.php?p=det_grunfr&typ='.$_GET['typ'].'&id='.$id.'');	
}
	
//Löschen vom Bild
if ($_GET["f"]=="remove_pic"){	
	
	$bild = $_GET['bild'];
    $datei_name = $_GET['bild'];
    if (@file_exists($datei_name) == true) {
      
        if (@unlink($datei_name) == true) {
			$db->delete("grfrun_Bilder",array("Bild" => $bild));
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
	
//Bilder Hochladen	
if ($_POST['bilder']== "Bilder hochladen"){	
$upload_folder = 'grfrun/'; //Das Upload-Verzeichnis
$filename = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));	
		
 
//Alles okay, verschiebe Datei an neuen Pfad
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
	
	
$ID = $_POST['id'];
$beschr = $_POST['Beschr'];
	
	if ( move_uploaded_file($_FILES['userfile']['tmp_name'], $new_path) ) {
		$dbInsert = array(
			$bid => $ID,
			"Bild" => $new_path,
			"Beschreibung" => $beschr);
		$db->insert("grfrun_Bilder",$dbInsert);
		echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a> ';
				
			} else {
				echo "Fehler beim Hochladen des Bildes";
			} 
	}
			 
if ($_GET["f"]==""){ ?>

<h2><?=$bez;?> bearbeiten</h2>
    
<?
	// Auslesen von Details
	$u_detail = $db->multiQuery("* FROM ".$tabelle." WHERE ".$bid." = ".$_GET['id'],true);
	foreach ($u_detail as $key => $row) {
			 if($row>0)	// if user exists
			 {
				 
				 		if ($row['s'] == 1){
		$status = "AKTIV";
		$farbe ="#46981F";
		}else{
		$status = "INAKTIV";
		$farbe ="#841113";
		}
				 if ($data->rang >= $grunfr_bearb){?>
                
                     <div style="text-align: right">
                     
                        <? if ($_GET["f"]!=="change"){ ?>
                     
                            <a href="home.php?p=det_grunfr&f=change&typ=<?=$typ;?>&id=<? echo $_GET['id']; ?>" class="btn btn-info" >
                            Bearbeiten</a>
                        
                        <? } ?>
                        <? if ($data->rang >= $grunfr_loesch){?>
                        <a href="home.php?p=det_grunfr&action=remove&typ=<?=$typ;?>&id=<? echo $_GET['id']; ?>" class="btn btn-danger delet">
                        <font color="#8A0B0D">L&ouml;schen</font></a>
                         <? } ?> 
                        <a href="home.php?p=det_grunfr&f=form_bilder&typ=<?=$typ;?>&id=<? echo $_GET['id']; ?>" class="btn btn-info">
                            Bilder hochladen</a>
                     </div>
                 <? } ?>
<? if ($_GET["f"]!=="change"){ ?>
                     <table width="800px">
                      	<tr style="background: #6C6C6C; font-size: 15px;">
                            <td>Name </td><td> Standort</td>
                        </tr>
                        
                         <tr>
                            <td><? echo $row['Name']; ?> </td><td> <? echo $row['Standort']; ?></td>
                        </tr>
                        
                        <tr style="background: #6C6C6C; font-size: 15px;">
                            <td>Merkmale </td><td> Status</td>
                        </tr>
                        <tr>
                            <td><? echo $row['Merkmale']; ?> </td><td style="background: <?=$farbe;?>;text-align: center; font-size: 13px;"><?=$status;?></td>
                        </tr>                    
         
                      </table>
                      
  <h2>Mitgliederliste</h2>
<center>
<table style="width: 800px;" >
     <tr style="background: #6C6C6C;">
    <td>Vorname</td>
    <td>Nachname</td>
    <td>Spitzname</td>
    <td>Telefon</td>
    <td>Akte</td>
  </tr>
  <?
if ($typ=="Frakt"){
$sql = $db->multiQuery("Vorname, Nachname, Telefon, Pers_ID, Spitzname FROM Personen WHERE Frakt_ID = ".$id,true);
	//var_dump($sql);
$bild_detail = $db->multiQuery("id, Bild from grfrun_Bilder where id = ".$id,true);
foreach ($sql as $key => $row) {
	$tel = conv2Tel($row['Telefon']);
echo "<tr>
    <td>". $row['Vorname'] . "</td>
    <td>". $row['Nachname'] . "</td>
	<td>". $row['Spitzname'] . "</td>
	<td>". $tel . "</td>
	<td><a href=\"home.php?p=det_buer&f=&id=".$row['Pers_ID']."\">Bürgerakte aufrufen</a></td>	
  </tr>  ";

}
}

if ($typ=="Unter"){
$sql = $db->multiQuery("Vorname, Nachname, Telefon, Pers_ID, Spitzname FROM Personen WHERE Unternehm_ID = ".$id."",true);
//$bild_detail = $db->multiQuery("id, Bild from grfrun_Bilder where Unternehm_ID = ".$id,true);

foreach ($sql as $key => $row) {
			// Format Telephonnumber
    $tel = conv2Tel($row['Telefon']);
echo" <tr>
    <td>". $row['Vorname'] . "</td>
    <td>". $row['Nachname'] . "</td>
	<td>". $row['Spitzname'] . "</td>
	<td>". $tel . "</td>
	<td><a href=\"home.php?p=det_buer&f=&id=".$row['Pers_ID']."\">Bürgerakte aufrufen</a></td>		
  </tr>  ";
}
}

if ($typ=="Grupp"){
$sql = $db->multiQuery("Vorname, Nachname, Telefon, Pers_ID, Spitzname FROM Personen WHERE Grupp_ID = ".$id."",true);
//$bild_detail = $db->multiQuery("id, Bild from grfrun_Bilder where Grupp_ID = ".$id,true);
foreach ($sql as $key => $row) {
			// Format Telephonnumber
    $tel = conv2Tel($row['Telefon']);
echo  "<tr>
    <td>". $row['Vorname'] . "</td>
    <td>". $row['Nachname'] . "</td>
	<td>". $row['Spitzname'] . "</td>
	<td>". $tel . "</td>
	<td><a href=\"home.php?p=det_buer&f=&id=".$row['Pers_ID']."\">Bürgerakte aufrufen</a></td>	
  </tr>  ";
}
}
?>
   
</table>
</center>
<h2>Bilder </h2>
<?
foreach ($bild_detail as $key => $bild) {
$pfad = $bild['Bild']; 
	echo "<h6>".$bild['Beschreibung']."</h6>";	
	echo "<a href=\"home.php?p=det_grunfr&f=remove_pic&id=".$bild['id']."&bild=".$bild['Bild']."&typ=".$typ."\">Bild löschen</a> <br>";
	?>

		
<img src="<?=$pfad;?>" alt="<?=$pfad;?>" height="50%" width="50%">
    
<? } } } } }

                if ($_GET["f"]=="change"){ 
               
                    $id = $_GET['id']; 
                             
                        $sql_44 = $db->multiQuery("* from ".$tabelle." where ".$bid." = ".$id,true);
						foreach ($sql_44 as $key => $get_result) {
							
?>
<form id="update_form" name="update_form" method="post" action="">

	<p>
		<label>Name:</label>
		<input name="Name" type="text" id="Name" value="<? echo $get_result['Name']; ?>"/>
	</p>

	<p>
		<label>Standort:</label>
		<textarea name="Standort" id="Standort"><? echo $get_result['Standort']; ?></textarea>
	</p>
	<p>
		<label>Status:</label>
		<select name="Status"><br>
		<option name="Status" id="Status" value="1"<?= $get_result['s']=="1"?' selected="selected"':'';?>>AKTIV</option>
		<option name="Status" id="Status" value="0"<?= $get_result['s']=="0"?' selected="selected"':'';?>>INAKTIV</option>
		</select>
	</p>
	<p>
		<label>Merkmale:</label>
		<textarea name="Merkmale" id="Merkmale"><? echo $get_result['Merkmale']; ?></textarea>
	</p>
	<p>
		<input type="submit" name="update" id="update" class="btn" value="Save Changes"/>
	</p>

</form>        
<?	} } 
            
            
// Bilder hochladen
if ($_GET["f"]=="form_bilder"){ ?>
<form id="update_form" name="update_form" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="id" value="<? echo $_GET['id']; ?>"/>

    <p>
	<label>Bild max. 10 MB</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="11000000">
	<input name="userfile" type="file">
	</p>
	<textarea name="Beschr" type="text" id="Beschr" value=""></textarea>
	<p>
		<input type="submit" name="bilder" id="bilder" class="btn" value="Bilder hochladen"/>
	</p>
</form> 
<? } ?>
</div>
  
            
        
            