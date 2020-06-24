<?

//ermittle wie viele daten es insgesamt gibt für seitenzahl
$anzahl_q = $db->countRow("Personen","WHERE status = 0");
$qSeite = intval($_GET['s']);
$seitenLimit = floor($anzahl_q / 20);
$qSeite = $qSeite > $seitenLimit ? $seitenLimit : $qSeite; // Maximal die Letzte seite soll angezeigt werden
$LIMIT = $qSeite * 20; // der LIMIT parameter für den OFFSET des query


// Variablen der Sortierfunktion
$qFilter = $_GET['o'];
$qOrder = $_GET['d'];
$ORDERBY = ''; // Keine Namenssortierung

// Erstelle eine Seitenanzeige für die Liste
$pageList = generatePageList($qSeite,$seitenLimit,"?p=totenliste_buerger&s=","&o=".$qFilter."&d=".$qOrder);

// Aufsteigend oder Absteigend Sortieren
if(!empty($qOrder)) {
	if($qOrder == 'desc') $qOrder = 'DESC'; else $qOrder = 'ASC';
} else $qOrder = '';

// Setze eine ORDER BY anweisung
if(!empty($qFilter)) {
	switch($qFilter) {
		case 'nn': $ORDERBY = 'ORDER BY Personen.Nachname '.$qOrder; break;
		case 'sn': $ORDERBY = 'ORDER BY Personen.Spitzname '.$qOrder; break;
		case 'vn': $ORDERBY = 'ORDER BY Personen.Vorname '.$qOrder; break;
	}
}

// Lade die Bürger aus der Datenbank
$u_detail = $db->multiQuery("Personen.*, Gruppierungen.Name AS Grupp_name, Unternehmen.Name AS unternehm_name, Fraktionen.Name AS Frakt_name from Personen left 
  join Gruppierungen on Personen.Grupp_ID = Gruppierungen.Grupp_ID left
  join Unternehmen on Personen.Unternehm_ID = Unternehmen.Unternehm_ID left
  join Fraktionen on Personen.Frakt_ID = Fraktionen.Frakt_ID WHERE status = 0 ".$ORDERBY." LIMIT ".$LIMIT.",20",true);

?>
<!-- FIX Für ZELLEN -->
<style>
td {
	vertical-align:middle;
}
</style>
<!-- ENDE FIX -->
<h3>Verstorbene B&uuml;rger</h3>
<center>
<? echo $pageList; ?>
<table style="width: 800px;" id="burger_list_fix">
    <tr style="background: #6C6C6C;">
		<td width="65px">Foto</td>
		<td width="125px">
			<? if($qFilter == 'vn' && $qOrder == 'ASC') { ?> &and; <? } else { ?>	<a href="?p=totenliste_buerger&s=<? echo $qSeite; ?>&o=vn&d=asc" title="Aufsteigend">&#9650;</a> <? } ?>
			<? if($qFilter == 'vn' && $qOrder == 'DESC') { ?> &or; <? } else { ?> <a href="?p=totenliste_buerger&s=<? echo $qSeite; ?>&o=vn&d=desc" title="Absteigend">&#9660;</a> <? } ?>
			Vorname</td>
		<td width="125px">
			<? if($qFilter == 'sn' && $qOrder == 'ASC') { ?> &and; <? } else { ?>	<a href="?p=totenliste_buerger&s=<? echo $qSeite; ?>&o=sn&d=asc" title="Aufsteigend">&#9650;</a> <? } ?>
			<? if($qFilter == 'sn' && $qOrder == 'DESC') { ?> &or; <? } else { ?> <a href="?p=totenliste_buerger&s=<? echo $qSeite; ?>&o=sn&d=desc" title="Absteigend">&#9660;</a> <? } ?>
			Spitzname</td>
		<td width="125px">
			<? if($qFilter == 'nn' && $qOrder == 'ASC') { ?> &and; <? } else { ?>	<a href="?p=totenliste_buerger&s=<? echo $qSeite; ?>&o=nn&d=asc" title="Aufsteigend">&#9650;</a> <? } ?>
			<? if($qFilter == 'nn' && $qOrder == 'DESC') { ?> &or; <? } else { ?> <a href="?p=totenliste_buerger&s=<? echo $qSeite; ?>&o=nn&d=desc" title="Absteigend">&#9660;</a> <? } ?>
			Nachname</td>
		<td width="125px">Telefon</td>
		<td>Zugehörigkeit</td>
		<td><a href="?p=totenliste_buerger&s=<? echo $qSeite; ?>" title="Sortierung Aufheben">X</a></td>
	</tr>
	
<? //Bereite die Anzeige vor
foreach ($u_detail as $key => $row) {
	// Ist ein Bild verfügbar?
	if ($row['bild'] != "") $bild = $row['bild']; else $bild = "pl_bild.png"; ?>
		<tr>
			<td><img src="<?=$bild;?>" style="width:60px; height:80px;" /></td>
			<td><? echo $row['Vorname'];?></td>
			<td><? if(strlen($row['Spitzname']) > 0) echo '\''.$row['Spitzname'].'\''; ?></td>
			<td><? echo $row['Nachname']; ?></td>
			<td><? echo conv2Tel($row['Telefon']); ?></td>
			<td><? 
			if(!empty($row['unternehm_name'])) echo $row['unternehm_name'].'<br>';
			if(!empty($row['Frakt_name'])) echo $row['Frakt_name'].'<br>';
			if(!empty($row['Grupp_name'])) echo $row['Grupp_name'];			
			?></td>
			<td><a href="home.php?p=det_buer&f=&id=<? echo $row['Pers_ID']; ?>">Details</a></td>
		</tr>
<? } ?> 
 </table>
 <? echo $pageList; ?>
 </center>
 
