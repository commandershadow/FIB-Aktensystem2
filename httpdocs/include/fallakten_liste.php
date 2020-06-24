<?

if ($_GET["f"]=="del"){
	$dbWhere = array(
		"Fall_UID" => $_GET['uid'],
		"Fall_ID" => $_GET["Fall"]);
	$db->delete("Fallakten_Agents",$dbWhere);
	echo "Agent erfolgreich abgezogen! <a href=\"?p=fallakten_liste\">Zurück zur Übersicht.</a>";
}


if ($_GET["f"]==""){
$s_status = array(0);

		if (intval($userDetails->rang) >= 3) {
			$s_status[] = 3;
		}
		if (intval($userDetails->rang) >= 5) {
        $s_status[] = 2;
    }
    if (intval($userDetails->rang) >= 9) {
        $s_status[] = 1;
    }
		$fahr_suche= $db->multiQuery("f.* FROM Fallakten f LEFT JOIN Fallakten_Agents fa ON fa.Fall_ID = f.Fall_ID WHERE (f.S_Status IN (".implode(',',$s_status).") OR fa.Fall_UID = $userDetails->uid) AND f.Status IN (0,1,3) GROUP BY Fall_ID ORDER BY Fall_ID DESC",true);
		?>
<h3>Fallakten</h3><br />
<center>
<table style="width: 950px; font-size: 11px;">
<? if (sizeof($fahr_suche) != 0) { ?> 
    <tr style="background: #6C6C6C; padding-left: 2px;">
    <td width="7%">Fallnummer</td>
    <td width="7%">Datum</td>
    <td width="12%">Leitender Agent</td>
    <td width="12%">Zust. Staatsanwalt</td>
    <td width="14%">Bezeichnung</td>
    <td width="20%">Kurzbeschreibung</td>
    <td width="15%">Status</td>
    <td width="5%"></td>
  </tr>
  <style>
a.one:hover {color:#FF0004;}
	  </style>
<? } ?>
<?
foreach($fahr_suche as $key => $zeile)
    {
/* NICHT GENUTZT???   

$statement = $db->prepare("SELECT * FROM Fallakten WHERE Fall_ID = ?");
$statement->execute(array($zeile['Fall_ID'])); 
$mitgl_anzahl = $statement->rowCount(); 

*/

	if ($zeile['Status'] == "0"){
		$status = "OFFEN";
		$farbe ="#841113";
	}else if ($zeile['Status'] =="1"){
		$status = "IN BEARBEITUNG";
		$farbe ="#D7BB03";
	}else if ($zeile['Status'] =="3") {
		$status = "EINSATZ";
		$farbe ="#0043ff";
	}
	else{
		$status = "ABGESCHLOSSEN";
		$farbe ="#46981F";
	}
    
  if ($zeile['S_Status'] =="1"){
		$status = "TOP SECRET";
		$farbe ="#841113";
	}
  if ($zeile['S_Status'] =="2"){
      $status = "GEHEIM";
      $farbe ="#841113";
  }
    
	if ($zeile['Anwalt'] == ""){
		$anwalt = "<font color=\"red\">Nicht zugewiesen.</red>";
	}else{
		$anwalt = $zeile['Anwalt'];
	}
	
	
	$FA_T = $db->multiQuery("Fallakten_Agents.*, users.PA_Deck AS deck from Fallakten_Agents left 
      join users on users.uid = Fallakten_Agents.Fall_UID where Fallakten_Agents.Fall_ID = ".$zeile['Fall_ID']." AND leitung = 1 LIMIT 1",true);						
							              

      echo "
    <tr>
    <td>FA".str_pad($zeile['Fall_ID'], 5, 0, STR_PAD_LEFT). "</td>
	<td>". date("d.m.Y", strtotime($zeile['datum']))."</td><td>";
	
	foreach ($FA_T as $key => $FA_M) { 
	echo "										  
	Agent ". $FA_M['deck']."<br>";
	}
	if(sizeof($FA_T) == 0) {
		echo '<font color=\"red\">Nicht zugewiesen</red>';
	}
	$shortDescription = strip_tags($zeile['k_Beschreibung']);
	if(strlen($shortDescription) > 32) {
		$shortDescription = substr($shortDescription,0,29).'...';
	}

	$name = $zeile['Bezeichnung'];
	if(strlen($name) > 29) {
		$name = substr($name,0,26).'...';
	}
	echo"</td>
	<td>". $anwalt. "</td>
    <td>". $name . "</td>
	<td>". $shortDescription . "</td>
	<td style=\"background: ".$farbe."; font-size: 12px; text-align: center;\">". $status . "</td>
	<td style=\"font-size: 15px; text-align: center;\"><a href=\"?p=det_fallakten&Fall=".$zeile['Fall_ID']."\">Details</a></td>
  </tr>";
    
		
	}
                
?>           
           
          </table>
</center>
 <? }?>
          