<?php
// Eintragen eines Punktes in die DB
if($userDetails->rang >= 6 && isset($_POST['save']) && $_GET['f'] == 'add') {
	// Trage den Punkt ein wenn alles da ist
	if(strlen($_POST['details']) > 5 && strlen($_POST['pos']) > 2) {
		$pos = explode(';',$_POST['pos']);
		$details = explode(';',$_POST['details']);
		$top = $pos[1];
		$left = $pos[0];
		$name = $details[0];
		$link = $details[1];
		$desc = $db->quote($_POST['desc']);
		// Wähle den passenden Marker aus
		switch(substr($details[1],0,1)) {
			case "G": $marker = 'img/marker_gang.png'; break 1;
			case "F": $marker = 'img/marker_frak.png'; break 1;
			case "U": $marker = 'img/marker_unt.png'; break 1;
			default: $marker = 'img/marker.png'; break 1;
		}
		// Wohnungen /Garage vorhanden?
		$hh = $_POST['wohnung'] == 'on' ? 1 : 0;
		$hg = $_POST['garage'] == 'on' ? 1 : 0;
		
		// Eintragen
		$dbInsert = array(
			"Name" => $name,
			"posTop" => $top,
			"posLeft" => $left,
			"Beschreibung" => $desc,
			"det_link" => $link,
			"marker" => $marker,
			"wohnung" => $hh,
			"garage" => $hg);
		$db->insert("karte",$dbInsert);
	}
}

// Speichern eines Bildes mit den Übergebenen Parametern
if($userDetails->rang >= 6 && $_GET['f'] == 'savImg' && intval($_GET['id']) > 0 && intval($_GET['img']) > 0) {
	$dbUpdate = array('grfrun_bilder_id' => intval($_GET['img']));
	$dbWhere = array('id' => intval($_GET['id']));
	$db->update("karte", $dbUpdate, $dbWhere);
}

// Löschen einer Bildverknüpfung aus der Datenbank
if($userDetails->rang >= 6 && $_GET['f'] == 'delImg' && intval($_GET['id']) > 0) {
	$dbUpdate = array('grfrun_bilder_id' => 0);
	$dbWhere = array('id' => intval($_GET['id']));
	$db->update("karte", $dbUpdate, $dbWhere);
}

// Löschen eines Punktes aus der DB
if($userDetails->rang >= 6 && $_GET['f'] == 'delete' && !empty($_GET['id'])) {
	$db->delete("karte",array("id" => $_GET['id']));
}

###################################################################################
if($_GET['f'] == 'addImg' && intval($_GET['id']) > 0 && $userDetails->rang >= 6) { 
	#VERKNÜPFEN EINES BILDES. KARTE SOLL SOLANGE NICHT ANGEZEIGT WERDEN 
	$q = "karte.id, karte.Name, Gruppierungen.Grupp_ID, Unternehmen.Unternehm_ID, Fraktionen.Frakt_ID FROM karte 
			LEFT JOIN Fraktionen ON Fraktionen.Name = karte.Name
			LEFT JOIN Gruppierungen ON Gruppierungen.Name = karte.Name
			LEFT JOIN Unternehmen ON Unternehmen.Name = karte.Name
			WHERE karte.id = '".$_GET['id']."'"; 
	$q = $db->singleQuery($q,true);
	// Lade die Entsprechenden Bilder falls vorhanden
	if(!empty($q['Grupp_ID'])) {
		$where = 'Grupp_ID = '.$q['Grupp_ID'];
	} elseif(!empty($q['Unternehm_ID'])) {
		$where = 'Unternehm_ID = '.$q['Unternehm_ID'];
	} elseif(!empty($q['Frakt_ID'])) {
		$where = 'Frakt_ID = '.$q['Frakt_ID'];
	} else 
		die("SIE HABEN ETWAS NICHT VORHANDENES GESUCHT");
	$q = $db->multiQuery("SELECT id,Bild FROM grfrun_Bilder WHERE $where",true);
	
	?> Klicken Sie ein Bild an um es zu verknüpfen<br> <?

	#<!-- BILDER LADEN UND LINK HINZUFÜGEN DER DIE DANN VERKNÜPFT -->
	if(sizeof($q) > 0) {		
		foreach($q as $id => $img) {
			echo '<a href="home.php?p=karte&f=savImg&id='.$_GET['id'].'&img='.$img['id'].'"><img src="'.$img['Bild'].'" style="width:490px;"></a>';
		}
	} else {
	?>
	 - Hierfür sind keine Bilder vorhanden - <br>
	 <a href="home.php?p=karte">Zurück zur Karte</a>
	<? } 
} else { 
// Laden der Daten Für das hinzufügen von Punkten
if($userDetails->rang >= "6") {
	// Die options Werte für das Eintragen 
	$options = '<option selected="selected" disabled>Bitte Wählen</option>';
	
	$gruppDB = $db->multiQuery("Grupp_ID,Name from Gruppierungen ORDER BY Name ASC",true);
	$fraktDB = $db->multiQuery("Frakt_ID,Name from Fraktionen ORDER BY Name ASC",true);
	$untDB = $db->multiQuery("Unternehm_ID,Name from Unternehmen ORDER BY Name ASC",true);
	
	$options .= '<optgroup label="Gruppierungen">';
	foreach($gruppDB as $key => $res) {
		$options .= '<option value="'.$res['Name'].';Grupp&id='.$res['Grupp_ID'].'">'.$res['Name'].'</option>';
	}
	$options .= '</optgroup><optgroup label="Fraktionen">';
	foreach($fraktDB as $key => $res) {
		$options .= '<option value="'.$res['Name'].';Frakt&id='.$res['Frakt_ID'].'">'.$res['Name'].'</option>';
	}
	$options .= '</optgroup><optgroup label="Unternehmen">';
	foreach($untDB as $key => $res) {
		$options .= '<option value="'.$res['Name'].';Unter&id='.$res['Unternehm_ID'].'">'.$res['Name'].'</option>';
	}
	$options .= '</optgroup>';
}
## ANZEIGEN DER KARTE
// Laden der daten für die Punkte
$pointsDB = $db->multiQuery("karte.id, karte.Name, karte.posTop, karte.posLeft, karte.Beschreibung, karte.det_link, karte.marker, karte.garage, karte.wohnung, karte.grfrun_Bilder_id, grfrun_Bilder.Bild FROM `karte` LEFT JOIN grfrun_Bilder on grfrun_Bilder.id = karte.grfrun_Bilder_id",true);
// home.php?p=det_grunfr&typ={Grupp|Frakt|Unter}&id=xx
$points = '';
foreach($pointsDB as $key => $res) {
	$image = $res['Bild'] == null ? 'null' : $res['Bild'];
	if(strlen($res['Beschreibung']) < 2 ) $res['Beschreibung'] = 'Keine Beschreibung.';
	$points .= 'points.push(["'.$res['Name'].'","'.$res['marker'].'","'.$res['det_link'].'",'.$res['posLeft'].','.$res['posTop'].','.$res['wohnung'].','.$res['garage'].','.$res['id'].',"'.$res['Beschreibung'].'","'.$image.'"]);';
}

?>
<script>
mapdata = {zoomin: false, data: false, zoompos: {top: 0, left: 0}, action: false, rang: <?php echo $userDetails->rang; ?>, addMode: false};
points = new Array();
<?php echo $points; ?>
</script>
<script src="karte.js"></script>
<style>
#map {
	background-image:url(img/map.jpg);
	background-size:900px 900px;
	background-repeat:no-repeat;
	width:900px;
	height:900px;
	overflow:visible;
	position:relative;
	margin:20px auto;
	cursor:crosshair;
}

#loading_map {
	text-align:center;
	padding-top:20%;
	background:#aaa;
	opacity:0.6;
}
#loading_map > span {
	color:#000;
	font-weight:bold;
}

#mapInfo {
	border:1px solid #000;
	padding:5px;
	margin-left:200px;
	text-align:right;
}
.fakeinput {
	display:inline-block;
	border:1px solid #000;
	width:50px;
	text-align:center;
}

#map > div:hover > div {
	top:70px !important;
	color:#000;
	opacity:0.9;
}
</style>
<span id="mapNav"><a href="javascript:initMap('grunfr')">Organisationen</a><!-- | <a href="javascript:initMap('tact')">Taktische Punkte</a> | <a href="javascript:initMap('ill')">Illegales</a>--></span>
<span id="mapInfo">Keine Funktionen ohne Zoom verf&uuml;gbar</span>
<span id="mapAction" style="display:none; visibility:hidden">
<?php if($userDetails->rang >= "6") { ?>
	<form action="home.php?p=karte&f=add" method="post"><br>
		<table cellspacing="10px"><tr><td>
			Y Position: <span id="ypos" class="fakeinput">-</span>
			X Position: <span id="xpos" class="fakeinput">-</span>
			<select name="details" style="width:200px;"><?php echo $options; ?></select>
		</td><td>
			<input type="hidden" id="koordInput" name="pos" value="">
			<textarea name="desc" placeholder="Beschreibung..."></textarea>
			<input type="submit" name="save" value="Eintragen">
		</td><td>
			<input type="checkbox" name="wohnung"> Wohnung/en Vorhanden<br>
			<input type="checkbox" name="garage"> Garage/n vorhanden
		</td></tr></table>
	</form>
<? } ?>
</span>
<div id="map" onclick="mapclick()">
</div>
<script>
document.addEventListener("DOMContentLoaded", initMap);
</script>
<? } ?>