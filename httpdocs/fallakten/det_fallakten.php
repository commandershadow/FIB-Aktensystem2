<div style="max-width: 800px; margin: auto;">
<style type="text/css">
.sff-menu ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

.sff-menu li {
    float: left;
}

.sff-menu li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

/* Change the link color to #111 (black) on hover */
.sff-menu li a:hover {
    background-color: #111;
}
	
.active {
    background-color: #10156F;
}
    </style>
<?
//Verbindung herstellen
$db = getDB();

//Rangabfrage
$rang_abfr= $db->query("SELECT uid,rang FROM users WHERE uid=".$_SESSION['uid']."");
$rang_abfr->execute();
$data = $rang_abfr->fetch(PDO::FETCH_OBJ);

//ID Holen 
$id = $_GET['Fall'];
	
if ($_POST['Aussage']== "Aussage bearbeiten"){
			$id = $_POST['Person'];
			$Fall = $_POST['fall'];
			$aussage = addslashes(htmlspecialchars($_POST['ZAussage']));
			$quey = "UPDATE 
								Fallakten_Aussagen
							SET
								Aussagen = '".$aussage."',
								Pers_ID = '".$id."'
								
							WHERE
								Fall_ID = " .$Fall." AND id = ".$_POST['auto_ID']; 
								
		$db->query($quey);
		echo "<h1>Erfolgreich geändert</h1>";
}
if ($_POST['Perso_Aussage']== "Aussage bearbeiten"){
			$id = $_POST['Person'];
			$Fall = $_POST['fall'];
			$aussage = addslashes(htmlspecialchars($_POST['ZAussage']));
			$quey = "UPDATE 
								Fallakten_Aussagen
							SET
								Aussagen = '".$aussage."',
								Fall_UID = '".$id."'
								
							WHERE
								Fall_ID = " .$Fall." AND id = ".$_POST['auto_ID']; 
								
		$db->query($quey);
		echo "<h1>Erfolgreich geändert</h1>";
}
	
	
// Aktuallisieren von Eintragungen
if ($_POST['update']== "Save Changes"){
			$id = $_POST['id'];
			$bez = $_POST['bez'];
			$bearbeiter = $_POST['bear'];
			$kurz = addslashes(htmlspecialchars($_POST['kurz']));
			$detail = addslashes(htmlspecialchars($_POST['detail']));
			$status = $_POST['Status'];
			$staat = $_POST['staasi'];
			$date = $_POST['datum'];
			$quey = "UPDATE 
								Fallakten
							SET
								Anwalt = '".$staat."',
								datum = '".$date."',
								Bezeichnung = '" .$bez. "',
								k_Beschreibung = '" .$kurz. "',
								d_Beschreibung = '" .$detail. "',
                Status = '" .$status. "'
							WHERE
								Fall_ID = " .$id; 
								
		$db->query($quey);
		echo "<h1>Erfolgreich geändert</h1>";

		}
	
//neue Personal Aussage aufnehmen
if ($_POST['Aussage_perso']== "Aussage aufnehmen"){
	
	// Variablen aus dem POST nehmen	
	$id = $_POST['id'];
	$Person = $_POST['Person'];
	$Aussage = addslashes(htmlspecialchars($_POST['ZAussage']));
	
	// Wenn es sich bei den Aussagen um Personal handelt
		$sql_2="insert into Fallakten_Aussagen(Fall_ID,Fall_UID,Aussagen)
		values('$id','$Person','$Aussage')";  
		$db->query($sql_2);
	echo "Personal Zeugenaussage erfolgreich aufgenommen!<br>";
}
	
//Agent den Fall zuweisen
if ($_POST['Bearb']== "Agent an den Fall setzen"){
	
	// Variablen aus dem POST nehmen	
	$id = $_POST['id'];
	$Person = $_POST['Person'];
	$Aussage = addslashes(htmlspecialchars($_POST['aufg']));
	
	// Wenn es sich bei den Aussagen um Personal handelt
		$sql_2="insert into Fallakten_Agents(Fall_ID,Fall_UID,Tätigkeit)
		values('$id','$Person','$Aussage')";  
		$db->query($sql_2);
	echo "Agent erfolgreich an den Fall gesetzt!<br>";
}
	
	
//neue Aussage aufnehmen
if ($_POST['Aussage']== "Aussage aufnehmen"){
	
	// Variablen aus dem POST nehmen	
	$id = $_POST['id'];
	$Person = $_POST['Person'];
	$Aussage = addslashes(htmlspecialchars($_POST['ZAussage']));
	das
	// Wenn es sich bei den Aussagen nicht um Personal handelt

	$sql_2="insert into Fallakten_Aussagen(Fall_ID,Pers_ID,Aussagen)
		values('$id','$Person','$Aussage')";  
		$db->query($sql_2);
	echo "Zeugenaussage erfolgreich aufgenommen!<br>";
		
}
	
	//neue Videobeweise aufnehmen
if ($_POST['beweis']== "Videobeweis sichern"){
	
	// Variablen aus dem POST nehmen	
	$id = $_POST['id'];
	$url = "https://www.youtube.com/embed/".$_POST['url'];
	$beschr = $_POST['beschr'];
	
	// Wenn es sich bei den Aussagen nicht um Personal handelt

	$sql_2="insert into Fallakten_Video(Fall_ID,url,Beschreibung)
		values('$id','$url','$beschr')";  
		$db->query($sql_2);
	echo "Videobeweise erfolgreich gesichert!<br>";
		
}

// Opfer und Täter hinzufügen
if ($_POST['opftat']== "Aussage aufnehmen"){
	// Variablen aus dem POST nehmen	
	$fall_ID= $_POST['id'];
	$tatopf = $_POST['Status'];
	$Person = $_POST['Person'];
	$uid = $_SESSION['uid'];
	
	
	
		$sql_2="insert into Fallakten_Personen(Fall_ID,P_Status,PERS_ID, uid)
		values('$fall_ID','$tatopf','$Person', '$uid')";  
		$db->query($sql_2);
	echo "Täter / Opfer erfolgreich aufgenommen! <a href=\"home.php?p=det_fallakten&id=".$fall_ID."\">Zurück zum Fall</a><br>";
	}
	
// Opfer oder Täter wieder löschen	
if ($_GET["f"]=="del_opfer"){
	// Variablen aus dem POST nehmen	
	$fall_ID= $_GET['id'];
	$Person = $_GET['sid'];

	

			$delete="DELETE FROM Fallakten_Personen WHERE Fall_ID = ".$fall_ID." AND PERS_ID = ".$Person;
			$db->query($delete);
			echo "Opfer / Täter erfolgreich gelöscht. <a href=\"home.php?p=det_fallakten&id=".$fall_ID."\">Zurück zum Fall</a>";
	}

//Bilder löschen
	
	//Löschen vom Bild
if ($_GET["f"]=="remove_pic"){	
	
	$bild = $_GET['bild'];
    $datei_name = './'.$bild;
  
    if (@file_exists($datei_name) == true) {
      
        if (@unlink($datei_name) == true) {
			$delete="DELETE FROM Fallakten_Bilder WHERE Bild = '".$bild."' AND Fall_ID = '".$_GET['Fall']."'";
			$db->query($delete);

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
$upload_folder = 'fallakten/'; //Das Upload-Verzeichnis
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
	
	
$fall_ID = $_POST['id'];
$beschr = $_POST['Beschr'];
	
	if ( move_uploaded_file($_FILES['userfile']['tmp_name'], $new_path) ) {
				
		$sql_2="insert into Fallakten_Bilder(Fall_ID,Bild,Beschreibung)
		values('$fall_ID','$new_path','$beschr')";  
		$db->query($sql_2);
	
		echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a> ';
				
			} else {
				echo "Fehler beim Hochladen des Bildes";
			} 
	}
			 
if ($_GET["f"]==""){ ?>

<h2>Fallakte FA<? echo str_pad($id, 5, 0, STR_PAD_LEFT); ?></h2>
    
<?
	// Auslesen von Fallakten
	$FA = "select * from Fallakten where Fall_ID = ".$_GET['Fall']."";
	foreach ($db->query($FA) as $FA_D) {
			// Überprüfung ob Akten vorhanden sind
			 if($FA_D>0)	
			 {
				 $agent = new agent;
				$allow = $agent->AC_Fallakte_Zugriff($data->rang, $FA_D['Fall_ID']);
				 // Rangabfrage
				 if ($allow == 1){ ?>

                
                     <div style="text-align: center">
                     
                        <?  
							if ($_GET["f"]!=="change"){ ?>
                    
    <div>
        <ul class="sff-menu">
            <li> <a href="home.php?p=det_fallakten&f=change&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info" >
                            Fallakte Bearbeiten</a></li>
            <li><a href="home.php?p=det_fallakten&f=form_zeuge&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Zeugenaussage aufnehmen</a>
                            <li>
                            <a href="home.php?p=det_fallakten&f=form_perso_zeuge&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Zeugenaussage von Personal aufnehmen</a>  </li>
                            <li> <a href="home.php?p=det_fallakten&f=form_opf&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Opfer oder Täter eintragen</a></li>
							<lI><a href="home.php?p=det_fallakten&f=bearbeiter_hinzu&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Agent beauftragen</a></lI>
                            <li><a href="home.php?p=det_fallakten&f=form_bilder&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Beweisbilder hochladen</a></li>
                            <lI><a href="home.php?p=det_fallakten&f=form_url&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Videobeweis hinzufügen</a></lI>
                            <lI><a href="http://fib-aktensystem.de/include/fallakte.php?id=<? echo $FA_D['Fall_ID']; ?>" target="_blank" class="btn btn-info">
                            PDF Herunterladen</a></lI>
			
		        </ul>
    </div>
<!-- #access -->
                           
                        
                        <?  } ?>
                     </div>
                 <? } ?>
                 
<? if ($_GET["f"]!=="change"){ ?>
<table width="800px">
	<? if ($FA_D['Status'] == "0"){
							$status = "OFFEN";
					 		$farbe ="#841113";
				        	
					        
				 			}else if ($FA_D['Status'] =="1"){
					 		$status = "IN BEARBEITUNG";
					 		$farbe ="#D7BB03";
				 			}else{
					 		$status = "ABGESCHLOSSEN";
					 		$farbe ="#46981F";
				 			}
							 
							?>
	<tr  style="background: <?=$farbe;?>; font-size: 13px;">
		<td>Tat-Datum: <?=date("d.m.Y", strtotime($FA_D['datum']));?> / Bearbeitung von: <? $FA_T = "select Fallakten_Agents.*, users.PA_Deck AS deck from Fallakten_Agents left 
      join users on users.uid = Fallakten_Agents.Fall_UID where Fallakten_Agents.Fall_ID = ".$_GET['Fall'];
								foreach ($db->query($FA_T) as $FA_M) { echo "Agent ".$FA_M['deck']." "; } ?>  / Zugewiesener Staatsanwalt: <?=$FA_D['Anwalt'];?>  / Status: </td>
		<td width="20%">
			<?=$status;?>
		</td>
	</tr>
	<tr style="background: #6C6C6C; font-size: 15px;">
		<td>Fallbezeichnung: </td>
		<td>Fallaktennummer: </td>
	</tr>

	<tr>
		<td>
			<? echo $FA_D['Bezeichnung']; ?>
		</td>
		<td> FA<? echo str_pad($FA_D['Fall_ID'], 5, 0, STR_PAD_LEFT); ?>
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td> </td>
	</tr>

	<tr style="background: #6C6C6C; font-size: 15px;">
		<td>Kurzbeschreibung </td>
		<td> </td>
	</tr>

	<tr>
		<td>
			<? echo $FA_D['k_Beschreibung']; ?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td> </td>
	</tr>
	<? if ($FA_D['d_Beschreibung'] !== ""){ ?>
	<tr style="background: #6C6C6C; font-size: 15px;">
		<td>Detaillierte Beschreibung </td>
		<td></td>
	</tr>
	<tr>
		<td>
			<? echo nl2br($FA_D['d_Beschreibung']); ?>
		</td>
		<td></td>
	</tr>

	<?}?>
	<tr>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<?  
							  $pers_details = $db->query("select * from Fallakten_Personen where Fall_ID = ".$_GET['Fall']."");
							  $pers_details->execute();
							  $count = $pers_details->rowCount();
						 if($count > 0) { ?>
	<tr>
		<td>
			<h3>Beteiligte Personen</h3>
		</td>
		<td></td>
	</tr>
	<? } 
							  
							  $op_details = $db->query("select * from Fallakten_Personen where Fall_ID = ".$_GET['Fall']." and P_Status = 0");
							  $op_details->execute();
							  $abfrage = $op_details->rowCount();
						 if($abfrage > 0) { ?>

	<tr style="background: #6C6C6C; font-size: 15px;">
		<td>Opfer</td>
		<td></td>
	</tr>
	<? } 
                        $opfer_detail = "select * from Fallakten_Personen where Fall_ID = ".$_GET['Fall']." and P_Status = 0";
	foreach ($db->query($opfer_detail) as $opfer) {
		$name= $db->query("SELECT * FROM Personen WHERE Pers_ID=".$opfer['PERS_ID']."");
		$name->execute();
		$f_name = $name->fetch(PDO::FETCH_OBJ);
		$ff_name = $f_name->Vorname." ".$f_name->Nachname;
		
 ?>

	<tr>
		<td>
			<a href="home.php?p=det_buer&f=&id=<?=$opfer['PERS_ID'];?>">
				<?=$ff_name;?>
			</a> <a href="home.php?p=det_fallakten&f=del_opfer&Fall=<?=$_GET['Fall'];?>&sid=<?=$opfer['PERS_ID'];?>">Löschen</a>
		</td>
		<td></td>
	</tr>
	<? }?>
	<tr>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<?
							  
							  $op_details = $db->query("select * from Fallakten_Personen where Fall_ID = ".$_GET['Fall']." and P_Status = 1");
							  $op_details->execute();
							  $zahl = $op_details->rowCount();
						 if($zahl > 0) { ?>

	<tr style="background: #6C6C6C; font-size: 15px;">
		<td>Täter</td>
		<td></td>
	</tr>
	<? } 
                        $opfer_detail = "select * from Fallakten_Personen where Fall_ID = ".$_GET['Fall']." and P_Status = 1";
	foreach ($db->query($opfer_detail) as $opfer) {
		$name= $db->query("SELECT * FROM Personen WHERE Pers_ID=".$opfer['PERS_ID']."");
		$name->execute();
		$f_name = $name->fetch(PDO::FETCH_OBJ);
		$ff_name = $f_name->Vorname." ".$f_name->Nachname; ?>

	<tr>
		<td>
			<a href="home.php?p=det_buer&f=&id=<?=$opfer['PERS_ID'];?>">
				<?=$ff_name;?>
			</a> <a href="home.php?p=det_fallakten&f=del_opfer&id=<?=$_GET['id'];?>&sid=<?=$opfer['PERS_ID'];?>" style="color: red;">Löschen</a> </td>
		<td></td>
	</tr>
	<? } 					   $zeugen_details = $db->query("select * from Fallakten_Aussagen where Fall_ID = ".$_GET['Fall']);
							  $zeugen_details->execute();
							  $zahl = $zeugen_details->rowCount();
						 if($zahl > 0) { ?>
						 	<tr>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<tr>
		<td>
			<h3>Zeugenaussagen</h3>
		</td>
		<td></td>
	</tr>
		<tr>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<? }
		$zeugen_detail = "select * from Fallakten_Aussagen where Fall_ID = ".$_GET['Fall'];
        foreach ($db->query($zeugen_detail) as $zeuge) {
		if ($zeuge['Pers_ID'] == 0 ){
		$name= $db->query("SELECT * FROM users WHERE uid=".$zeuge['Fall_UID']."");
		$name->execute();
		$f_name = $name->fetch(PDO::FETCH_OBJ);
		$ff_name = $f_name->PA_Deck;
		$id= $zeuge['Fall_UID'];
		$link = "<a href=\"home.php?p=det_fallakten&f=edit_form_perszeuge&Fall=".$zeuge['Fall_ID']."&uid=".$id."\">Aussage bearbeiten</a>";
		}else {
		$name= $db->query("SELECT * FROM Personen WHERE Pers_ID=".$zeuge['Pers_ID']."");
		$name->execute();
		$f_name = $name->fetch(PDO::FETCH_OBJ);
		$ff_name = $f_name->Vorname." ".$f_name->Nachname;
		$id= $zeuge['Pers_ID'];
		
		$link = "<a href=\"home.php?p=det_fallakten&f=edit_form_zeuge&Fall=".$zeuge['Fall_ID']."&id=".$id."\">Aussage bearbeiten</a>";
		}
		?>
	<tr style="background: #6C6C6C; font-size: 15px;">
		<td>Aussage von <? echo $ff_name; ?> | <?if ($allow = 1){?> <?=$link;?><? } ?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>
			<? echo nl2br($zeuge['Aussagen']); ?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<? } 
$bild_pruf = $db->query("select * from Fallakten_Bilder where Fall_ID = ".$_GET['Fall']."");
		$bild_pruf->execute();
		$test = $bild_pruf->fetch(PDO::FETCH_OBJ);
						 if($test > 0) { ?>
	<tr>
		<td>
			<h3>Beweisfotos</h3>
		</td>
		<td></td>
	</tr>
	<? } ?>
</table>
<?
    $bild_detail = "select * from Fallakten_Bilder where Fall_ID = ".$_GET['Fall'];
							  $nr = 0;
	foreach ($db->query($bild_detail) as $bild) {
		$pfad = $bild['Bild']; 
		$nr ++;
		
if ($bild['Beschreibung'] != ""){
	
	echo "<h6>".$bild['Beschreibung']."</h6>";
	echo "<a href=\"home.php?p=det_fallakten&f=remove_pic&id=".$bild['Fall_ID']."&bild=".$bild['Bild']."\">Bild löschen</a>";

	
} ?>
		
<a href="<?=$pfad;?>" data-lightbox="image-<?=$nr;?>" data-title="IMG">Beweisbild <?=$nr;?></a><br>
	<? }?>

<?
    $video_detail = "select * from Fallakten_Video where Fall_ID = ".$_GET['Fall'];
	foreach ($db->query($video_detail) as $video) {
	$url_video = $video['url'];
	
	echo "<h6>".$video['Beschreibung']."</h6>";
	echo "<iframe width=\"560\" height=\"315\" src=\"".$url_video."\" frameborder=\"0\" allowfullscreen></iframe>";
	}
	
?>		 
		 
<?	 } } } }
// Update bzw ÄNDERN
if ($_GET["f"]=="change"){ 
               
$id = $_GET['Fall'];                              
$sql_44 = "SELECT * FROM Fallakten WHERE Fall_ID = ".$id; 
foreach ($db->query($sql_44) as $get_result) {
?>
 <link rel="stylesheet" type="text/css" href="tcal.css" />
<script type="text/javascript" src="tcal.js"></script> 
<form id="update_form" name="update_form" method="post" action="">

	<input type="hidden" name="id" value="<? echo $get_result['Fall_ID']; ?>"/>

	<p>
		<label>Fallbezeichnung</label>
		<input name="bez" type="text" id="bez" value="<? echo $get_result['Bezeichnung']; ?>"/>
	</p>
	<p>
		<label>Zugewiesener Staatsanwalt: </label>
		<input name="staasi" type="text" id="staasi" value="<?=$get_result['Anwalt'];?>"/>
	</p>
	

		<p>
	<label>Datum</label>
	<input type="text" name="datum" class="tcal" value="<? echo $get_result['datum']; ?>" />
	</p>

	<p>
		<label>Kurzbeschreibung:</label>
		<textarea name="kurz" type="text" id="kurz" value="<? echo $get_result['k_Beschreibung']; ?>"/><? echo $get_result['k_Beschreibung']; ?></textarea>
	</p>

	<p>
		<label>Detaillierte Beschreibung:</label>
		<textarea name="detail" type="text" id="detail" value="<? echo $get_result['d_Beschreibung']; ?>"/><? echo $get_result['d_Beschreibung']; ?></textarea>
	</p>
	<p>
		<label>Fallstatus:</label>
		<select name="Status"><br>
			<?
                //Kategorie Suche
                             if   ($get_result['Status']=="0"){
                             echo "<option name=\"Status\" type=\"text\" id=\"Status\" value=\"0\" selected>Offen</option>";
                             }else if ($get_result['Status']=="1"){
							echo "<option name=\"Status\" type=\"text\" id=\"Status\" value=\"1\" selected>In Bearbeitung</option>";
							 } else {
							echo "<option name=\"Status\" type=\"text\" id=\"Status\" value=\"2\" selected>Abgeschlossen</option>";	 
							 }
         ?>
		<option name="Status" id="Status" value="1">In Bearbeitung</option>
		<option name="Status" id="Status" value="2">Abgeschlossen</option>
		<option name="Status" id="Status" value="0">Offen</option>
		</select>
			</p>
	<p>
		<input type="submit" name="update" id="update" class="btn" value="Save Changes"/>
	</p>

</form>        
<?	} }

// Normale Zeugenaussage bearbeiten
if ($_GET["f"]=="edit_form_zeuge"){ 
?>
<form id="update_form" name="update_form" method="post" action="">

	<input type="hidden" name="fall" value="<? echo $_GET['Fall']; ?>"/>
		<p>
		<label>Person (die aussagt):</label>
<select name="Person"><br>                
      <?
 	$sql = "select * from Personen where Pers_ID = ".$_GET['id']."";
	foreach ($db->query($sql) as $row) {
     echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['Pers_ID'] ." selected>". $row['Vorname']." ".$row['Nachname']."</option>";
	 	$sql = "select * from Personen";
	}
	foreach ($db->query($sql) as $row) {
     echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['Pers_ID'] .">". $row['Vorname']." ".$row['Nachname']."</option>";
	}
      ?>
</select>
	</p><?
	$zeugen_detail = "select * from Fallakten_Aussagen where Fall_ID = ".$_GET['Fall']." and Pers_ID = ".$_GET['id']."";
        foreach ($db->query($zeugen_detail) as $zeuge) {
		if ($zeuge['Pers_ID'] == 0 ){
		$name= $db->query("SELECT * FROM users WHERE uid=".$zeuge['Fall_UID']."");
		$name->execute();
		$f_name = $name->fetch(PDO::FETCH_OBJ);
		$ff_name = $f_name->name;
		}?>
	<p>
		<label>Aussage</label>
		<textarea name="ZAussage" type="text" id="ZAussage" value=""/><?=$zeuge['Aussagen'];?></textarea>
	</p>
	<input type="hidden" name="auto_ID" value="<? echo $zeuge['id']; ?>"/>
	<p>
		<input type="submit" name="Aussage" id="Aussage" class="btn" value="Aussage bearbeiten"/>
	</p>

</form>   
<?
} }
if ($_GET["f"]=="edit_form_perszeuge"){ 
?>
<form id="update_form" name="update_form" method="post" action="">

	<input type="hidden" name="fall" value="<? echo $_GET['Fall']; ?>"/>
		<p>
		<label>Person (die aussagt):</label>
<select name="Person"><br>                
      <?
 	$sql = "select * from users where uid = ".$_GET['uid']."";
	foreach ($db->query($sql) as $row) {
     echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['uid'] ." selected>". $row['PA_Deck']."</option>";
	 	$sql = "select * from users";
	}
	foreach ($db->query($sql) as $row) {
     echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['uid'] .">". $row['PA_Deck']."</option>";
	}
      ?>
</select>
	</p><?
	$zeugen_detail = "select * from Fallakten_Aussagen where Fall_ID = ".$_GET['Fall']." and Fall_UID = ".$_GET['uid']."";
        foreach ($db->query($zeugen_detail) as $zeuge) {
		?>
	<p>
		<label>Aussage</label>
		<textarea name="ZAussage" type="text" id="ZAussage" value=""/><?=$zeuge['Aussagen'];?></textarea>
	</p>
	<input type="hidden" name="auto_ID" value="<? echo $zeuge['Fall_UID']; ?>"/>
	<p>
		<input type="submit" name="Perso_Aussage" id="Perso_Aussage" class="btn" value="Aussage bearbeiten"/>
	</p>

</form>   
<?
}								  
								 }
								  
// Normale Zeugenaussage
if ($_GET["f"]=="form_zeuge"){ 
?>
<form id="update_form" name="update_form" method="post" action="">

	<input type="hidden" name="id" value="<? echo $_GET['Fall']; ?>"/>
		<p>
		<label>Person (die aussagt):</label>
<select name="Person"><br>                
      <?
 	$sql = "select * from Personen";
	foreach ($db->query($sql) as $row) {
     echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['Pers_ID'] .">". $row['Vorname']." ".$row['Nachname']."</option>";
	}
      ?>
</select>
	</p>
	
	<p>
		<label>Aussage</label>
		<textarea name="ZAussage" type="text" id="ZAussage" value=""/></textarea>
	</p>
	
	<p>
		<input type="submit" name="Aussage" id="Aussage" class="btn" value="Aussage aufnehmen"/>
	</p>

</form>   
<?
}
	
// Personal Zeugenaussage
if ($_GET["f"]=="form_perso_zeuge"){ 
?>
<form id="update_form" name="update_form" method="post" action="">

	<input type="hidden" name="id" value="<? echo $_GET['Fall']; ?>"/>
		<p>
		<label>Person (die aussagt):</label>
<select name="Person"><br>                
      <?
 	$sql = "select * from users order by PA_Deck ASC";
	foreach ($db->query($sql) as $row) {
		if ($row['PA_Deck'] != "Stadtrat"){
     echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['uid'] .">". $row['PA_Deck']."</option>";
			
	}
	}
      ?>
</select>
	</p>
	
	<p>
		<label>Aussage</label>
		<textarea name="ZAussage" type="text" id="ZAussage" value=""/></textarea>
	</p>
	
	<p>
		<input type="submit" name="Aussage_perso" id="Aussage_perso" class="btn" value="Aussage aufnehmen"/>
	</p>

</form>   
<?
}
	
	if ($_GET["f"]=="bearbeiter_hinzu"){ 
?>
<form id="update_form" name="update_form" method="post" action="">

	<input type="hidden" name="id" value="<? echo $_GET['Fall']; ?>"/>
		<p>
		<label>Agent:</label>
<select name="Person"><br>                
      <?
 	$sql = "select * from users order by PA_Deck ASC";
	foreach ($db->query($sql) as $row) {
		if ($row['PA_Deck'] != "Projektleitung"){
     echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['uid'] .">". $row['PA_Deck']."</option>";
			
	}
	}
      ?>
</select>
	</p>
	
	<p>
		<label>Tätigkeit:</label>
		<textarea name="aufg" type="text" id="aufg" value=""/></textarea>
	</p>
	
	<p>
		<input type="submit" name="Bearb" id="Bearb" class="btn" value="Agent an den Fall setzen"/>
	</p>

</form>   
<?
}
	
	if ($_GET["f"]=="form_url"){ 
?>
<form id="update_form" name="update_form" method="post" action="">

	<input type="hidden" name="id" value="<? echo $_GET['Fall']; ?>"/>
<p>
		<label>Beschreibung</label>
		<textarea name="beschr" type="text" id="beschr" value=""/></textarea>
	</p>
	
	<p>
		<label>YOUTUBE- V-CODE youtube.com/watch?v=qvqLulsjZU4 (Nur nach dem v= das eintragen - > <font color="#FF0004">qvqLulsjZU4</font>)</label>
		<textarea name="url" type="text" id="url" value=""/></textarea>
	</p>
	
	<p>
		<input type="submit" name="beweis" id="beweis" class="btn" value="Videobeweis sichern"/>
	</p>

</form>   
<?
}
	
// Täter oder Opfer Aufnahme
if ($_GET["f"]=="form_opf"){ 
?>
<form id="update_form" name="update_form" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="id" value="<? echo $_GET['Fall']; ?>"/>
<p>
<label>Person:</label>
<select name="Person"><br>                
      <?
 	$sql = "select * from Personen";
	foreach ($db->query($sql) as $row) {
     echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['Pers_ID'] .">". $row['Vorname']." ".$row['Nachname']."</option>";
	}
      ?>
</select>

	</p>
<p>
		<label>Fallstatus:</label>
		<select name="Status"><br>
		<option name="Status" id="Status" value="1">Täter</option>
		<option name="Status" id="Status" value="0">Opfer</option>
		</select>
			</p>
	<p>
		<input type="submit" name="opftat" id="opftat" class="btn" value="Aussage aufnehmen"/>
	</p>
</form>  
           <? }
	
// Bilder hochladen
if ($_GET["f"]=="form_bilder"){ ?>
<form id="update_form" name="update_form" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="id" value="<? echo $_GET['Fall']; ?>"/>

    <p>
	<label>Bild max. 10 MB</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="11000000">
	<input name="userfile" type="file">
	</p>
	<textarea name="Beschr" type="text" id="Beschr" value=""/></textarea>
	<p>
		<input type="submit" name="bilder" id="bilder" class="btn" value="Bilder hochladen"/>
	</p>
</form> 
<? } ?>
</div>
            