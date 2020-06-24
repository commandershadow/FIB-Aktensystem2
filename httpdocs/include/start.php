<center><h1>Hallo, Agent <?php echo $userDetails->PA_Deck; ?> !</h1>
Ich hoffe Sie hatten einen angenehmen Tag bis jetzt. <br><br>


<?
$dbTableJoin = "Fallakten_Agents LEFT JOIN Fallakten ON Fallakten.Fall_ID = Fallakten_Agents.Fall_ID";
$dbWhere = "WHERE (Fallakten_Agents.Fall_UID = '".$userDetails->uid."' AND Status = '1') OR (Fallakten_Agents.Fall_UID = '".$userDetails->uid."' AND Status = '0')";
$aktenDaten = $db->multiQuery("Fallakten.* , Fallakten_Agents.* FROM ".$dbTableJoin." ".$dbWhere,true);
?>
<? if (sizeof($aktenDaten) != 0) { ?> 
<h3>Fallakten</h3><br />
    
<center>
<table style="width: 950px; font-size: 11px;">
    <tr style="background: #6C6C6C; padding-left: 2px;">
    <td width="7%">Fallnummer</td>
    <td width="7%">Datum</td>
    <td width="14%">Bezeichnung</td>
    <td width="20%">Kurzbeschreibung</td>
    <td width="15%">Status</td>
    <td width="5%"></td>
  </tr>
<? } else {
	echo "<font color=\"red\">Dir wurden noch keine Fallakten zugewiesen oder alle Fallakten sind abgeschlossen.</font>";
}?>
<?
foreach($aktenDaten as $key => $zeile)
    {
	/* UNGENUTZT???	    
	 * *****     *****     *****     *****     *****     *****
	 * $statement = $db->prepare("SELECT Fallakten.* , Fallakten_Agents.* FROM Fallakten_Agents left join Fallakten on Fallakten.Fall_ID = Fallakten_Agents.Fall_ID WHERE Fallakten_Agents.Fall_UID = '".$userDetails->uid."'");
	 * $statement->execute(); 
	 * $mitgl_anzahl = $statement->rowCount();
	 * *****     *****     *****     *****     *****     *****
	 */
	if ($zeile['Status'] == "0"){
		$status = "OFFEN";
		$farbe ="#841113";
	}else if ($zeile['Status'] =="1"){
		$status = "IN BEARBEITUNG";
		$farbe ="#D7BB03";
	}else{
		$status = "ABGESCHLOSSEN";
		$farbe ="#46981F";
	}
	
	
	  echo "
    <tr>
    <td>FA".str_pad($zeile['Fall_ID'], 5, 0, STR_PAD_LEFT). "</td>
	<td>". date("d.m.Y", strtotime($zeile['datum']))."</td>
    <td>". $zeile['Bezeichnung'] . "</td>
	<td>". $zeile['k_Beschreibung'] . "</td>
	<td style=\"background: ".$farbe."; font-size: 12px; text-align: center;\">". $status . "</td>
	<td style=\"font-size: 15px; text-align: center;\"><a href=\"?p=det_fallakten&Fall=".$zeile['Fall_ID']."\">Details</a></td>
  </tr>";
    
		
	}
                
?>           
           
          </table>
</center>

</center>