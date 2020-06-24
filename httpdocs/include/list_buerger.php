<?
// Lade die Bürger aus der Datenbank
$u_detail = $db->multiQuery("Personen.*, Gruppierungen.Name AS Grupp_name, Unternehmen.Name AS unternehm_name, Fraktionen.Name AS Frakt_name from Personen left 
  join Gruppierungen on Personen.Grupp_ID = Gruppierungen.Grupp_ID left
  join Unternehmen on Personen.Unternehm_ID = Unternehmen.Unternehm_ID left
  join Fraktionen on Personen.Frakt_ID = Fraktionen.Frakt_ID WHERE status > 0 ORDER BY Personen.Nachname",true);

?>
<!-- FIX Für ZELLEN -->
<style>
td {
	vertical-align:middle;
}
</style>
<!-- ENDE FIX -->
<h3>Bekannte B&uuml;rger</h3>
<center>
<table style="width: 800px;" id="burger_list_fix">
    <tr style="background: #6C6C6C;">
		<td width="65px">Foto</td>
		<td width="125px">
						Vorname</td>
		<td width="125px">
					Spitzname</td>
		<td width="125px">
					Nachname</td>
		<td width="125px">Telefon</td>
		<td>Zugehörigkeit</td>
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
</tr>
<? } ?> 
 </table>
 </center>
 
