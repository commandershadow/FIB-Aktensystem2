<div style="max-width: 800px; margin: auto;">
	<!-- Include jQuery, this can be omitted if it's already included -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/jquery.tinymce.min.js"></script>
	<script>
		tinymce.init( {
			selector: 'textarea',
			plugins: 'lists autoresize link textcolor',
			menubar: false,
			toolbar: 'formatselect fontsizeselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist | link forecolor backcolor',
		} );
	</script>
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

//ID Holen 
$id = $_GET['Fall'];
$url = "?p=det_fallakten&Fall=".$_GET['Fall'];
	
if ($_POST['Aussage'] == "Aussage bearbeiten"){
	$dbUpdate = array(
		"Aussagen" => addslashes($_POST['ZAussage']),
		"Pers_ID" => $_POST['Person']);
	$dbWhere = array(
		"Fall_ID" => $_POST["fall"],
		"id" => $_POST['auto_ID']);
	$db->update("Fallakten_Aussagen",$dbUpdate,$dbWhere);
	echo "<h1>Erfolgreich geändert</h1>";
}
if ($_POST['Perso_Aussage'] == "Aussage bearbeiten"){
	$dbUpdate = array(
		"Aussagen" => addslashes($_POST['ZAussage']),
		"Fall_UID" => $_POST['Person']);
	$dbWhere = array(
		"Fall_ID" => $_POST['fall'],
		"id" => $_POST['auto_ID']);
	$db->update("Fallakten_Aussagen",$dbUpdate,$dbWhere);
	echo "<h1>Erfolgreich geändert</h1>";
}

// Agent abziehen
if ($_GET["f"]=="del"){
	$dbWhere = array(
		"Fall_UID" => $_GET['uid'],
		"Fall_ID" => $_GET["Fall"]);
	$db->delete("Fallakten_Agents",$dbWhere);
	echo "Agent erfolgreich abgezogen!";
}

// Zeugenaussage löschen
if ($_GET["f"]=="pers_del_zeuge"){
	$dbWhere = array(
		"Fall_UID" => $_GET['uid'],
		"id" => $_GET['a'],
		"Fall_ID" => $_GET["Fall"]);
	$db->delete("Fallakten_Aussagen",$dbWhere);
	echo "Aussage vom Agent erfolgreich aus den Akten vernichtet!";
}
	
if ($_GET["f"]=="del_zeuge"){
	$dbWhere = array(
		"Pers_ID" => $_GET['id'],
		"id" => $_GET['a'],
		"Fall_ID" => $_GET["Fall"]);
	$db->delete("Fallakten_Aussagen",$dbWhere);
	echo "Zeugenaussage wurde erfolgreich aus den Akten entfernt!";
	}

//Video löschen
if ($_GET["f"]=="del_video"){
	$dbWhere = array(
		"id" => $_GET['id'],
		"Fall_ID" => $_GET["Fall"]);
	$db->delete("Fallakten_Video",$dbWhere);
		echo "Video erfolgreich entfernt!";
	}

// Leitender Agent	
if ($_GET["f"]=="leit"){
	$FA_T = $db->multiQuery("Fallakten_Agents.*, users.PA_Deck AS deck from Fallakten_Agents left 
      join users on users.uid = Fallakten_Agents.Fall_UID where Fallakten_Agents.Fall_ID = ".$_GET['Fall']." AND leitung = 1 LIMIT 1",true);	
	if (sizeof($FA_T) != 0){
		$dbUpdate = array(
		"leitung" => 0);
	    $dbWhere = array("leitung" => 1, "Fall_ID" => $_GET["Fall"]);
		$db->update("Fallakten_Agents",$dbUpdate,$dbWhere);
		echo "Erfolgreich leitenden Agent entfernt... -> Nächster Schritt <br>";
		}
		$dbUpdate = array("leitung" => 1);
	    $dbWhere = array("Fall_UID" => $_GET['uid'], "Fall_ID" => $_GET['Fall']);
		$db->update("Fallakten_Agents",$dbUpdate,$dbWhere);
	 echo "Leitender Agent wurde erfolgreich hinzugefügt!<br>
	";
	}
	
// Aktuallisieren von Eintragungen
if ($_POST['update']== "Save Changes"){
	$dbUpdate = array(
		"Anwalt" => $_POST['staasi'],
		"datum" => $_POST['datum'],
		"Bezeichnung" => $_POST['bez'],
		"k_Beschreibung" =>  addslashes($_POST['kurz']),
		"d_Beschreibung" => addslashes($_POST['detail']),
		"S_Status" => $_POST['secret'],
		"Status" => $_POST['Status']);
	$dbWhere = array("Fall_ID" => $_POST['id']);
	$db->update("Fallakten",$dbUpdate,$dbWhere);
	echo "<h1>Erfolgreich geändert</h1>";
}
	
//neue Personal Aussage aufnehmen
if ($_POST['Aussage_perso']== "Aussage aufnehmen"){
	
	// Variablen aus dem POST nehmen
	$dbInsert = array(
		"Fall_ID" => $_POST['id'],
		"Fall_UID" => $_POST['Person'],
		"Aussagen" => addslashes($_POST['ZAussage']));

	// Wenn es sich bei den Aussagen um Personal handelt
	$db->insert("Fallakten_Aussagen",$dbInsert);
	echo "Personal Zeugenaussage erfolgreich aufgenommen!<br>";

}
	
//Agent den Fall zuweisen
if ($_POST['Bearb']== "Agent an den Fall setzen"){
	
	// Variablen aus dem POST nehmen
	$dbInsert = array(
		"Fall_ID" => $_POST['id'],
		"Fall_UID" => $_POST['Person'],
		"Tätigkeit" => addslashes($_POST['aufg']));

	$db->insert("Fallakten_Agents",$dbInsert);
	echo "Agent erfolgreich an den Fall gesetzt!<br>";
		}
	
	
//neue Aussage aufnehmen
if ($_POST['Aussage']== "Aussage aufnehmen"){
	
	// Variablen aus dem POST nehmen
	$dbInsert = array(
		"Fall_ID" => $_POST['id'],
		"Pers_ID" => $_POST['Person'],
		"Aussagen" => addslashes($_POST['ZAussage']));
	
	// Wenn es sich bei den Aussagen nicht um Personal handelt
	$db->insert("Fallakten_Aussagen",$dbInsert);
	echo "Zeugenaussage erfolgreich aufgenommen!<br>";
			
}
	
	//neue Videobeweise aufnehmen
if ($_POST['beweis']== "Videobeweis sichern"){
	
	// Variablen aus dem POST nehmen
	$dbInsert = array(
		"Fall_ID" => $_POST['id'],
		"url" => "https://www.youtube.com/embed/".$_POST['url'],
		"Beschreibung" => $_POST['beschr']);
	$db->insert("Fallakten_Video", $dbInsert);
	echo "Videobeweise erfolgreich gesichert!<br>";
			
}

// Opfer und Täter hinzufügen
if ($_POST['opftat']== "Aussage aufnehmen"){
	// Variablen aus dem POST nehmen
	$dbInsert = array(
		"Fall_ID" => $_POST['id'],
		"P_Status" => $_POST['Status'],
		"PERS_ID" => $_POST['Person'],
		"uid" => $_SESSION['uid']);
	$db->insert("Fallakten_Personen",$dbInsert);
	echo "Täter / Opfer / Zeuge / Tatverdächtigter erfolgreich aufgenommen!<br>";
		}
	
// Opfer oder Täter wieder löschen	
if ($_GET["f"]=="del_opfer"){
	// Variablen aus dem POST nehmen
	$dbDelete = array(
		"Fall_ID" => $_GET['Fall'],
		"PERS_ID" => $_GET['sid']);
	$db->delete("Fallakten_Personen",$dbDelete);
	echo "Täter / Opfer / Zeuge / Tatverdächtigter gelöscht. ";
	}
	
// Gruppierung Fraktion oder Unternehmen hinzufügen
	if ($_POST['grfrun']== "Organisation hinzufügen"){
	// Variablen aus dem POST nehmen
		$og = explode("|", $_POST['og_id']);

	$dbInsert = array(
		"Fall_ID" => $_POST['id'],
		"OG_ID" => $og[0],
		"Typ" => $og[1]);
	$db->insert("Fallakten_OG",$dbInsert);
	echo "Organisation erfolgreich aufgenommen!<br>";
		}
	
// Organisation wieder löschen	
if ($_GET["f"]=="del_grfrun"){
	// Variablen aus dem POST nehmen
	$dbDelete = array(
		"Fall_ID" => $_GET['Fall'],
		"OG_ID" => $_GET['sid'],
		"Typ" => $_GET['typ']);
	$db->delete("Fallakten_OG",$dbDelete);
	echo "Organisation erfolgreich gelöscht. ";
	}

//Bilder löschen
	
	//Löschen vom Bild
if ($_GET["f"]=="remove_pic"){	
	$dbDelete = array(
		"Bild" => $_GET['bild'],
		"Fall_ID" => $_GET['Fall']);
    $datei_name = './'.$_GET['bild'];
    
    $picture = explode("/", $_GET['bild']);
$end = "./fallakten/thumb/".$picture[1];

  
    if ((@file_exists($datei_name) == true) && (@file_exists($end) == true)) {   
        if ((@unlink($datei_name) == true) && (@unlink($end) == true)) {
			$db->delete("Fallakten_Bilder",$dbDelete);
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
if(strlen($_POST['Beschr']) < 1) $_POST['Beschr'] = 'Keine Beschreibung';	
$dbInsert = array(
	"Fall_ID" => $_POST['id'],
	"Bild" => $new_path,
	"Beschreibung" => $_POST['Beschr']);
	if ( move_uploaded_file($_FILES['userfile']['tmp_name'], $new_path) ) {
   $test = $upload_folder.'/thumb/'.$filename.'.'.$extension;
   make_thumb($new_path, $test);
		$db->insert("Fallakten_Bilder",$dbInsert);	
		echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a> ';	
						} else {
				echo "Fehler beim Hochladen des Bildes";
			} 
	}
			 

	// Auslesen von Fallakten
	$FA_D = $db->singleQuery("* from Fallakten where Fall_ID = ".$_GET['Fall'],true);
	// Überprüfung ob Akten vorhanden sind
	if(sizeof($FA_D) > 0) {
		$agent = new agent;
		$allow = $agent->AC_Fallakte_Zugriff($data->rang, $FA_D['Fall_ID']);
		// Rangabfrage
		if ($allow == 1){ ?>
	<nav class="cf">
		<ul id="nav" class="sf-menu">
			<li>
				<a href="#">Hauptmenü</a>

				<ul class="sub-menu">
					<li> <a href="home.php?p=det_fallakten&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Fallakte</a>
					</li>
					<li> <a href="home.php?p=det_fallakten&f=change&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Fallakte bearbeiten</a>
					</li>
					<lI><a href="home.php?p=det_fallakten&f=bearbeiter_hinzu&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Agent beauftragen</a>
					</lI>

					<lI><a href="http://fib-aktensystem.de/include/fallakte.php?Fall=<? echo $FA_D['Fall_ID']; ?>" target="_blank" class="btn btn-info">
                            PDF herunterladen</a>
					</lI>

				</ul>
			</li>
			<li>
				<a href="#">Verknüpfungen herstellen</a>

				<ul class="sub-menu">
					<li>
						<a href="home.php?p=det_fallakten&f=form_perso_zeuge&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Agentaussage aufnehmen</a>
					
					</li>
					<li><a href="home.php?p=det_fallakten&f=form_zeuge&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
					 Zeugenaussage aufnehmen</a>
					
					</li>
					<li> <a href="home.php?p=det_fallakten&f=form_opf&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
					 Täter / Opfer / Zeuge / Tatverdächtigter</a>
					</li>
					<li> <a href="home.php?p=det_fallakten&f=form_OG&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Gruppierung eintragen</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#">Beweise hinzufügen</a>

				<ul class="sub-menu">
					<li><a href="home.php?p=det_fallakten&f=form_bilder&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Beweisbilder hochladen</a>
					</li>
					<lI><a href="home.php?p=det_fallakten&f=form_url&Fall=<? echo $FA_D['Fall_ID']; ?>" class="btn btn-info">
                            Videobeweis hinzufügen</a>
					</lI>

				</ul>
			</li>

		</ul>
	</nav>
	<? 
if ($_GET["f"]==""){ ?>

	<h2><?=$FA_D['Bezeichnung'];?></h2><br>
	<table width="800px">
		<? if ($FA_D['Status'] == 0){
			$status = "OFFEN";
			$farbe ="#841113";
		}else if ($FA_D['Status'] == 1 ){
			$status = "IN BEARBEITUNG";
			$farbe ="#D7BB03";
		}else if ($FA_D['Status'] == 3 ){
			$status = "EINSATZ";
			$farbe ="#0043ff";
		}else if ($FA_D['Status'] == 4 ){
			$status = "EINSATZ ABGESCHLOSSEN";
			$farbe ="#0043ff";
		}else{
			$status = "ABGESCHLOSSEN";
			$farbe ="#46981F";
		}
	if ($zeile['Anwalt'] == ""){
		$anwalt = "<font color=\"red\">Nicht zugewiesen.</red>";
	}else{
		$anwalt = $zeile['Anwalt'];
	}
		$FA_T = $db->multiQuery("Fallakten_Agents.*, users.PA_Deck AS deck from Fallakten_Agents left 
      join users on users.uid = Fallakten_Agents.Fall_UID where Fallakten_Agents.Fall_ID = ".$_GET['Fall']." ORDER BY leitung DESC",true);
		?>
	<tr style="background: #6C6C6C; font-size: 15px;">
			<td>Datum der Tat:
			</td>
			<td>
				Status:
			</td>
		</tr>
				<tr>
			<td>
				<?=date("d.m.Y", strtotime($FA_D['datum']));?>
			</td>
			<td width="30%" style="background: <?=$farbe;?>; font-size: 13px;">
				<?=$status;?>
			</td>
		</tr>
		<tr style="background: #6C6C6C; font-size: 15px;">
			<td>Staatsanwalt: </td>
			<td>Fallaktenzeichen: </td>
		</tr>

		<tr>
			<td>
				<?=$anwalt;?>
			</td>
			<td> FA#<? echo str_pad($FA_D['Fall_ID'], 5, 0, STR_PAD_LEFT); ?>
			</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td> </td>
		</tr>

		<tr style="background: #6C6C6C; font-size: 15px;">
			<td>Kurzbeschreibung </td>
			<td width="30%">Zugewiesene Agents</td>
		</tr>

		<tr>
			<td>
				<? echo $FA_D['k_Beschreibung']; ?>
			</td>
			<td align="right">
				<? 
			foreach ($FA_T as $key => $FA_M) { 
	$agent= new agent;
	$rang = $agent->Rangabfrage();
	$allow = $agent->AC_Fallakte($rang);
	echo "Agent ".$FA_M['deck']." "; 
		if ($allow == 1){
		
		if ($FA_M['leitung'] == 1){
			echo " <img src=\"include/images/lace.png\">";
		}else{
			echo "<a class=\"one\" href=\"?p=det_fallakten&uid=".$FA_M['Fall_UID']."&Fall=".$_GET['Fall']."&f=leit\" alt=\"Leitender Agent\"> <img src=\"include/images/favorite.png\"></a> ";
			}
			echo " <a class=\"one\" href=\"?p=det_fallakten&uid=".$FA_M['Fall_UID']."&Fall=".$_GET['Fall']."&f=del\" alt=\"Agent abziehen!\"> <img src=\"include/images/delete.png\"></a> <br>";
					}else{
			echo"<br>";
		}		
			} ?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td> </td>
		</tr>
	</table>
	<table class="fallakten">
		<? if ($FA_D['d_Beschreibung'] !== ""){ ?>
		<tr style="background: #6C6C6C; font-size: 15px;">
			<td>Detaillierte Beschreibung </td>
			<td></td>
		</tr>
		<tr>
			<td>
				<? echo $FA_D['d_Beschreibung']; ?>
			</td>
			<td></td>
		</tr>

		<?}?>
		<tr>
			<td>&nbsp;</td>
			<td></td>
		</tr>
		<?  
		  $count = $db->countRow("Fallakten_OG","WHERE Fall_ID = ".$_GET['Fall']."");
		 if($count > 0) { ?>
		<tr>
			<td>
				<h3>Beteiligte Organisationen</h3>
			</td>
			<td></td>
		</tr>
		<? } 
					
	   $grfr_detail = $db->multiQuery("* from Fallakten_OG WHERE Fall_ID = ".$_GET['Fall']." and Typ = 0",true);
	foreach ($grfr_detail as $key => $opfer) {
		$f_name = $db->singleQuery("* FROM Gruppierungen WHERE Grupp_ID=".$opfer['OG_ID']."");
		$ff_name = $f_name->Name;
		
 ?>
		<tr>
			<td>
				<a href="home.php?p=det_grunfr&typ=Grupp&id=<?=$opfer['OG_ID'];?>">
					<?=$ff_name;?>
				</a> -
				<a href="home.php?p=det_fallakten&f=del_grfrun&Fall=<?=$_GET['Fall'];?>&typ=0&sid=<?=$opfer['OG_ID'];?>"><img src="include/images/delete.png">Löschen</a>
			</td>
			<td></td>
		</tr>
		<? }
						
		
		
		 $grfr_detail = $db->multiQuery("* from Fallakten_OG WHERE Fall_ID = ".$_GET['Fall']." and Typ = 1",true);
	foreach ($grfr_detail as $key => $opfer) {
		$f_name = $db->singleQuery("* FROM Fraktionen WHERE Frakt_ID=".$opfer['OG_ID']."");
		$ff_name = $f_name->Name;
		
 ?>
		<tr>
			<td>
				<a href="home.php?p=det_grunfr&typ=Frakt&id=<?=$opfer['OG_ID'];?>">
					<?=$ff_name;?>
				</a> -
				<a href="home.php?p=det_fallakten&f=del_grfrun&Fall=<?=$_GET['Fall'];?>&typ=1&sid=<?=$opfer['OG_ID'];?>"><img src="include/images/delete.png">Löschen </a>
			</td>
			<td></td>
		</tr>
		<? } 

		 $grfr_detail = $db->multiQuery("* from Fallakten_OG WHERE Fall_ID = ".$_GET['Fall']." and Typ = 2",true);
	foreach ($grfr_detail as $key => $opfer) {
		$f_name = $db->singleQuery("* FROM Unternehmen WHERE Unternehm_ID=".$opfer['OG_ID']."");
		$ff_name = $f_name->Name;
		
 ?>
		<tr>
			<td>
				<a href="home.php?p=det_grunfr&typ=Unter&id=<?=$opfer['OG_ID'];?>">
					<?=$ff_name;?>
				</a>
			</td>
			<td><a href="home.php?p=det_fallakten&f=del_grfrun&Fall=<?=$_GET['Fall'];?>&typ=2&sid=<?=$opfer['OG_ID'];?>"><img src="include/images/delete.png"> Löschen</a></td>
		</tr>
			<tr>
			<td>
			
			</td>
			<td></td>
		</tr>
		<? }
	 
		  $count = $db->countRow("Fallakten_Personen","WHERE Fall_ID = ".$_GET['Fall']."");
		 if($count > 0) { ?>
		<tr>
			<td>
				<h3>Beteiligte Personen</h3>
			</td>
			<td></td>
		</tr>
		<? } 
		$abfrage = $db->countRow("Fallakten_Personen","WHERE Fall_ID = ".$_GET['Fall']." and P_Status = 0");
		if($abfrage > 0) { ?>

		<tr style="background: #6C6C6C; font-size: 15px;">
			<td>Opfer</td>
			<td></td>
		</tr>
		<? } 
    $opfer_detail = $db->multiQuery("* from Fallakten_Personen WHERE Fall_ID = ".$_GET['Fall']." and P_Status = 0",true);
	foreach ($opfer_detail as $key => $opfer) {
		$f_name = $db->singleQuery("* FROM Personen WHERE Pers_ID=".$opfer['PERS_ID']."");
		$ff_name = $f_name->Vorname." ".$f_name->Nachname;
		
 ?>

		<tr>
			<td>
				<a href="home.php?p=det_buer&f=&id=<?=$opfer['PERS_ID'];?>">
					<?=$ff_name;?>
				</a> -
				<?=conv2Tel($f_name->Telefon);?><a href="home.php?p=det_fallakten&f=del_opfer&Fall=<?=$_GET['Fall'];?>&sid=<?=$opfer['PERS_ID'];?>"><img src="include/images/delete.png"></a>
			</td>
			<td></td>
		</tr>
		<? }?>
		<?
		$zahl = $db->countRow("Fallakten_Personen","WHERE Fall_ID = ".$_GET['Fall']." and P_Status = 3");
		if($zahl > 0) { ?>

		<tr style="background: #6C6C6C; font-size: 15px;">
			<td>Zeuge</td>
			<td></td>
		</tr>
		<? } 
    $opfer_detail = $db->multiQuery("* from Fallakten_Personen where Fall_ID = ".$_GET['Fall']." and P_Status = 3",true);
	foreach ($opfer_detail as $key => $opfer) {
		$f_name= $db->singleQuery("* FROM Personen WHERE Pers_ID=".$opfer['PERS_ID']."");
		$ff_name = $f_name->Vorname." ".$f_name->Nachname; ?>

        <tr>
			<td>
				<a href="home.php?p=det_buer&f=&id=<?=$opfer['PERS_ID'];?>">
					<?=$ff_name;?>
				</a> -
				<?=conv2Tel($f_name->Telefon);?><a href="home.php?p=det_fallakten&f=del_opfer&Fall=<?=$_GET['Fall'];?>&sid=<?=$opfer['PERS_ID'];?>"><img src="include/images/delete.png"></a>
			</td>
			<td></td>
		</tr>
		<? }?>
		<?
		$zahl = $db->countRow("Fallakten_Personen","WHERE Fall_ID = ".$_GET['Fall']." and P_Status = 4");
		if($zahl > 0) { ?>

		<tr style="background: #6C6C6C; font-size: 15px;">
			<td>Tatverdächtigter</td>
			<td></td>
		</tr>
		<? } 
    $opfer_detail = $db->multiQuery("* from Fallakten_Personen where Fall_ID = ".$_GET['Fall']." and P_Status = 4",true);
	foreach ($opfer_detail as $key => $opfer) {
		$f_name= $db->singleQuery("* FROM Personen WHERE Pers_ID=".$opfer['PERS_ID']."");
		$ff_name = $f_name->Vorname." ".$f_name->Nachname; ?>

        <tr>
			<td>
				<a href="home.php?p=det_buer&f=&id=<?=$opfer['PERS_ID'];?>">
					<?=$ff_name;?>
				</a> -
				<?=conv2Tel($f_name->Telefon);?><a href="home.php?p=det_fallakten&f=del_opfer&Fall=<?=$_GET['Fall'];?>&sid=<?=$opfer['PERS_ID'];?>"><img src="include/images/delete.png"></a>
			</td>
			<td></td>
		</tr>
		<? }?>
		<?
		$zahl = $db->countRow("Fallakten_Personen","WHERE Fall_ID = ".$_GET['Fall']." and P_Status = 1");
		if($zahl > 0) { ?>

		<tr style="background: #6C6C6C; font-size: 15px;">
			<td>Täter</td>
			<td></td>
		</tr>
		<? } 
    $opfer_detail = $db->multiQuery("* from Fallakten_Personen where Fall_ID = ".$_GET['Fall']." and P_Status = 1",true);
	foreach ($opfer_detail as $key => $opfer) {
		$f_name= $db->singleQuery("* FROM Personen WHERE Pers_ID=".$opfer['PERS_ID']."");
		$ff_name = $f_name->Vorname." ".$f_name->Nachname; ?>
		<tr>
			<td>
				<a href="home.php?p=det_buer&f=&id=<?=$opfer['PERS_ID'];?>">
					<?=$ff_name;?>
				</a> -
				<?=conv2Tel($f_name->Telefon);?><a href="home.php?p=det_fallakten&f=del_opfer&Fall=<?=$_GET['Fall']; ?>&sid=<?=$opfer['PERS_ID']; ?>" style="color: red;"><img src="include/images/delete.png"></a></td>
			<td></td>
		</tr>
		<? } 					  
		?>
</table>

		<?	$bild_pruf = $db->multiQuery("* from Fallakten_Bilder where Fall_ID = ".$_GET['Fall'],true);
	if(sizeof($bild_pruf) > 0) { ?>
				<h3>Beweisfotos</h3>

		<? } ?>
	<?
	foreach ($bild_pruf as $nr => $bild) {
		$pfad = $bild['Bild']; 
		
	if ($pfad != ""){
		
		echo $bild['Beschreibung']." -  Beweisbild ".$nr."<br>";
		echo "<a href=\"home.php?p=det_fallakten&f=remove_pic&Fall=".$bild['Fall_ID']."&bild=".$bild['Bild']."\" style=\"color: red;\">Bild löschen <img src=\"include/images/delete.png\"></a> <br>";

		
	} 
  
$picture = explode("/", $pfad);
$end = "fallakten/thumb/".$picture[1];
  ?>

	<a href="<?=$pfad;?>" data-lightbox="image-<?=$nr;?>" data-title="IMG"><img src="<?=$end;?>" alt="<?=$bild['Beschreibung'];?>"></a><br>
	<? } 
  $zahl = $db->countRow("Fallakten_Aussagen","WHERE Fall_ID = ".$_GET['Fall']);
		if($zahl > 0) { ?>

	<h3>Zeugenaussagen</h3><br>
		<? }
		$zeugen_detail = $db->multiQuery("* from Fallakten_Aussagen where Fall_ID = ".$_GET['Fall'],true);
        foreach ($zeugen_detail as $key => $zeuge) {
			if ($zeuge['PERS_ID'] == 0){
				$f_name = $db->singleQuery("* FROM users WHERE uid=".$zeuge['Fall_UID']);
				$ff_name = $f_name->PA_Deck;
				$tel = $f_name->PA_Telefon;
				$id= $zeuge['Fall_UID'];
				$a_id = $zeuge['id'];
				$link = "<a href=\"home.php?p=det_fallakten&f=edit_form_perszeuge&Fall=".$zeuge['Fall_ID']."&uid=".$id."&a=".$a_id."\"><img src=\"include/images/edit.png\"></a>
				<a href=\"home.php?p=det_fallakten&f=pers_del_zeuge&Fall=".$zeuge['Fall_ID']."&uid=".$id."&a=".$a_id."\"><img src=\"include/images/delete.png\"></a>";
			} 
			else 
			{
				$f_name = $db->singleQuery("* FROM Personen WHERE PERS_ID=".$zeuge['PERS_ID']);
				$ff_name = $f_name->Vorname;
				$fff_name = $f_name->Nachname;
				$tel = $f_name->Telefon;
				$id= $zeuge['PERS_ID'];
				$a_id = $zeuge['id'];
				$link = "<a href=\"home.php?p=det_fallakten&f=edit_form_zeuge&Fall=".$zeuge['Fall_ID']."&a=".$a_id."&id=".$id."\"><img src=\"include/images/edit.png\"></a> 
							<a href=\"home.php?p=det_fallakten&f=del_zeuge&Fall=".$zeuge['Fall_ID']."&a=".$a_id."&id=".$id."\"><img src=\"include/images/delete.png\"></a>";
			}
		?>
	<table width="800px">
		<tr style="background: #6C6C6C;">
			<td>Aussage von <b><? echo $ff_name; ?> <? echo $fff_name; ?></b> - <?=conv2Tel($tel);?>
			</td>
			<td align="right" width="20%">
				<?if ($allow = 1){?>
				<?=$link;?>
				<? } ?>
			</td>
		</tr>
	</table>
	<table width="800px">
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
</table>
		<? } ?>
	<?
    $video_detail = $db->multiQuery("* from Fallakten_Video where Fall_ID = ".$_GET['Fall'],true);
	if(sizeof($video_detail) > 0)
		foreach ($video_detail as $nr => $video) {
		$url_video = $video['url'];

		echo "".$video['Beschreibung']." - <a href=\"home.php?p=det_fallakten&f=del_video&Fall=".$_GET['Fall']."&id=".$video['id']."\">Video entfernen<img src=\"include/images/delete.png\"></a><br>";
		echo "<iframe width=\"560\" height=\"315\" src=\"".$url_video."\" frameborder=\"0\" allowfullscreen></iframe><br>";
		}
	
?>

	<?	} }
// Update bzw ÄNDERN
if ($_GET["f"]=="change"){ 
               
$id = $_GET['Fall'];                              
$get_result = $db->singleQuery("* FROM Fallakten WHERE Fall_ID = ".$id,true); 
?>
	<link rel="stylesheet" type="text/css" href="tcal.css"/>
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
	<label>Secret-Level:</label>
    <select name="secret">
    <option name="stat" id="stat" value="0"<?= $get_result['S_Status']=="0"?' selected="selected"':'';?>>Normal</option>    
    <option name="stat" id="stat" value="2"<?= $get_result['S_Status']=="2"?' selected="selected"':'';?>>Geheim</option>
    <option name="stat" id="stat" value="1"<?= $get_result['S_Status']=="1"?' selected="selected"':'';?>>TOP-SECRET</option> 
</select>
	</p>
		<p>
			<label>Datum</label>
			<input type="text" name="datum" class="tcal" value="<? echo $get_result['datum']; ?>"/>
		</p>

		<p>
			<label>Kurzbeschreibung:</label>
			<textarea name="kurz" type="text" id="kurz">
				<? echo $get_result['k_Beschreibung']; ?>
			</textarea>
		</p>

		<p>
			<label>Detaillierte Beschreibung:</label>
			<textarea name="detail" type="text" id="editor">
				<? echo $get_result['d_Beschreibung']; ?>
			</textarea>
		</p>
		<p>
			<label>Fallstatus:</label>
			<select name="Status"><br>
		<option name="Status" id="Status" value="1"<?= $get_result['Status']=="1"?' selected="selected"':'';?>>In Bearbeitung</option>
		<option name="Status" id="Status" value="2"<?= $get_result['Status']=="2"?' selected="selected"':'';?>>Abgeschlossen</option>
		<option name="Status" id="Status" value="3"<?= $get_result['Status']=="3"?' selected="selected"':'';?>>Einsatz</option>
		<option name="Status" id="Status" value="4"<?= $get_result['Status']=="4"?' selected="selected"':'';?>>Einsatz Abgeschlossen</option>
		<option name="Status" id="Status" value="0"<?= $get_result['Status']=="0"?' selected="selected"':'';?>>Offen</option>
		</select>
		</p>
		<p>
			<input type="submit" name="update" id="update" class="btn" value="Save Changes"/>
		</p>

	</form>
	<?	}

// Normale Zeugenaussage bearbeiten
if ($_GET["f"]=="edit_form_zeuge"){ 
?>
	<form id="update_form" name="update_form" method="post" action="">

		<input type="hidden" name="fall" value="<? echo $_GET['Fall']; ?>"/>
		<p>
			<label>Person (die aussagt):</label>
			<select name="Person">              
      <?
	$zeuge = $db->multiQuery("Pers_ID, Vorname, Nachname FROM Personen ORDER BY Nachname ASC",true);
	foreach ($zeuge as $id => $row) {
		echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=".$row['Pers_ID'] .($row['Pers_ID']==$_GET['id'] ? " selected=\"selected\"" : "").">". $row['Vorname']." ".$row['Nachname']."</option>";
	}
      ?>
</select>
		
		</p>
		<?
	$zeugen_detail = $db->multiQuery("* FROM Fallakten_Aussagen WHERE Fall_ID = ".$_GET['Fall']." and Pers_ID = ".$_GET['id']."	 and id = ".$_GET['a'],true);
        foreach ($zeugen_detail as $id => $zeuge) {
?>
		<p>
			<label>Aussage</label>
			<textarea name="ZAussage" type="text" id="ZAussage" value=""/>
			<?=$zeuge['Aussagen'];?>
			</textarea>
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
 	$zeuge = $db->multiQuery("uid, PA_Deck from users",true);
	foreach ($zeuge as $id => $row) {
		echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=".$row['uid'].($_GET['uid']==$row['uid']?' selected="selected">':'').">". $row['PA_Deck']."</option>";
	}
      ?>
</select>
		</p>
		<?
	$zeugen_detail = $db->multiQuery("* FROM Fallakten_Aussagen WHERE Fall_ID = ".$_GET['Fall']." and Fall_UID = ".$_GET['uid']." and id = ".$_GET['a'],true);
    foreach ($zeugen_detail as $id => $zeuge) {
		?>
		<p>
			<label>Aussage</label>
			<textarea name="ZAussage" type="text" id="ZAussage" value=""/>
			<?=$zeuge['Aussagen'];?>
			</textarea>
		</p>
		<input type="hidden" name="auto_ID" value="<? echo $zeuge['id']; ?>"/>
		<p>
			<input type="submit" name="Perso_Aussage" id="Perso_Aussage" class="btn" value="Aussage bearbeiten"/>
		</p>

	</form>
	<?
}}
								  
// Normale Zeugenaussage
if ($_GET["f"]=="form_zeuge"){ 
?>
	<form id="update_form" name="update_form" method="post" action="">

		<input type="hidden" name="id" value="<? echo $_GET['Fall']; ?>"/>
		<p>
			<label>Person (die aussagt):</label>
			<select name="Person"><br>                
      <?
 	$zeugen = $db->multiQuery("Pers_ID, Vorname, Nachname, Spitzname FROM Personen ORDER BY Vorname ASC",true);
	foreach ($zeugen as $id => $row) {
		if ($row['Spitzname'] == ""){
			$spitzname = "";
		} else {
			$spitzname = " ~ ".$row['Spitzname'];
		}
		echo "<option name=\"Person\" type=\"text\" id=\"Person\" value=". $row['Pers_ID'] .">". $row['Vorname']." ".$row['Nachname']." ".$spitzname."</option>";
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
 	$sql = $db->multiQuery("uid,PA_Deck FROM users ORDER BY PA_Deck ASC",true);
	foreach ($sql as $key => $row) {
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
 	$sql = $db->multiQuery("uid, PA_Deck from users order by PA_Deck ASC",true);
	foreach ($sql as $id => $row) {
		if ($row['PA_Deck'] != "Stadtrat"){
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
			<input name="beschr" type="text" id="beschr" value=""/>
		</p>

		<p>
			<label>YOUTUBE- V-CODE youtube.com/watch?v=<font color="#FF0004">qvqLulsjZU4</font> (Rot markierte)</label>
			<input name="url" type="text" id="url" value=""/>
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
 	$sql = $db->multiQuery("Pers_ID,Vorname,Nachname FROM Personen ORDER BY Vorname ASC",true);
	foreach ($sql as $id => $row) {
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
        <option name="Status" id="Status" value="3">Zeuge</option>
		<option name="Status" id="Status" value="4">Tatverdächtigter</option>
		</select>
		</p>
		<p>
			<input type="submit" name="opftat" id="opftat" class="btn" value="Aussage aufnehmen"/>
		</p>
	</form>

	<? }
	
//Organisation aufnahme
if ($_GET["f"]=="form_OG"){ 
?>
	<form id="update_form" name="update_form" method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<? echo $_GET['Fall']; ?>"/>


		<p><label>Organisation:</label>
	<select name="og_id"><br>                
      <?
 	$sql = $db->multiQuery("Grupp_ID, Name FROM Gruppierungen ORDER BY Name ASC",true);
	foreach ($sql as $id => $row) {
		echo "<option name=\"og_id\" type=\"text\" id=\"og_id\" value=\"". $row['Grupp_ID']."|0\">".$row['Name']."</option>";
	} $sql = $db->multiQuery("Frakt_ID, Name FROM Fraktionen ORDER BY Name ASC",true);
	foreach ($sql as $id => $row) {
		echo "<option name=\"og_id\" type=\"text\" id=\"og_id\" value=\"". $row['Frakt_ID']."|1\">".$row['Name']."</option>";
	}
	$sql = $db->multiQuery("Unternehm_ID, Name FROM Unternehmen ORDER BY Name ASC",true);
	foreach ($sql as $id => $row) {
		echo "<option name=\"og_id\" type=\"text\" id=\"og_id\" value=\"". $row['Unternehm_ID']."|2\">".$row['Name']."</option>";
	}
      ?>
</select>
		</p>
		
		<p>
		  <input type="submit" name="grfrun" id="grfrun" class="btn" value="Organisation hinzufügen"/>
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
	<? }
}else{
    echo "Zugang verwehrt! - Du besitz nicht die nötigen Rechte!"; }?>
</div>