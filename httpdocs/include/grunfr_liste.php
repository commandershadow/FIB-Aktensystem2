<?

$fahr_suche= $db->multiQuery("* from Gruppierungen ORDER BY Name ASC",true);
$fahr_suche2= $db->multiQuery("* from Fraktionen ORDER BY Name ASC",true);
$fahr_suche3= $db->multiQuery("* from Unternehmen ORDER BY Name ASC",true);


?>
<h3>Gruppierungen / Fraktionen / Unternehmen</h3><br />
<center>
<table style="width: 800px;">
<? if (sizeof($fahr_suche) != 0) { ?> 
    <tr style="background: #6C6C6C;">
    <td>Status</td>
    <td>Gruppierungen</td>
    <td>Anzahl</td>
    <td></td>
  </tr>
<? } ?>
<?
foreach ($fahr_suche as $key => $zeile)
    {
    
$mitgl_anzahl = $db->countRow("Personen","WHERE Grupp_ID = ".$zeile['Grupp_ID']);
		
				if ($zeile['s'] == 1){
		$status = "AKTIV";
		$farbe ="#46981F";
		}else{
		$status = "INAKTIV";
		$farbe ="#841113";
		}
    echo "
    <tr>
	<td style=\"text-align: center; background: ".$farbe."; font-size: 13px;\">".$status."</td>
    <td><a href=\"?p=det_grunfr&typ=Grupp&id=".$zeile['Grupp_ID']."\">" . $zeile['Name'] . "</a></td>
    <td>". $mitgl_anzahl . "</td>
	<td><a href=\"?p=det_grunfr&typ=Grupp&id=".$zeile['Grupp_ID']."\">Details</a></td>
  </tr>";
    }
                
?>            
 
<? if (sizeof($fahr_suche2) != 0) { ?>   
     <tr style="background: #6C6C6C;">
     <td>Status</td>
    <td>Fraktionen</td>
    <td>Anzahl</td>
    <td></td>
  </tr>
  <? } ?>
<?
                    
foreach ($fahr_suche2 as $key => $zeile2)   {

$mitgl_anzahl2 = $db->countRow("Personen","WHERE Frakt_ID = ".$zeile2['Frakt_ID']);
			if ($zeile2['s'] == 1){
		$status = "AKTIV";
		$farbe ="#46981F";
		}else{
		$status = "INAKTIV";
		$farbe ="#841113";
		}
      echo "
    <tr>
		<td style=\"text-align: center; background: ".$farbe."; font-size: 13px;\">".$status."</td>
    <td><a href=\"?p=det_grunfr&typ=Frakt&id=".$zeile2['Frakt_ID']."\">" . $zeile2['Name'] . "</a></td>
        <td>". $mitgl_anzahl2 . "</td>
			<td><a href=\"?p=det_grunfr&typ=Frakt&id=".$zeile2['Frakt_ID']."\">Details</a> </td>
  </tr>";
    }
                
?>            
 
<? if (sizeof($fahr_suche3) != 0) { ?>  
     <tr style="background: #6C6C6C;">
     <td>Status</td>
    <td>Unternehmen</td>
		 <td>Anzahl</td>
    <td></td>
  </tr>
  <? }?>
<?
  foreach ($fahr_suche3 as $key => $zeile3)
    {
$mitgl_anzahl3 = $db->countRow("Personen","WHERE Unternehm_ID = ".$zeile3['Unternehm_ID']);
		if ($zeile3['s'] == 1){
		$status = "AKTIV";
		$farbe ="#46981F";
		}else{
		$status = "INAKTIV";
		$farbe ="#841113";
		}
      echo "
    <tr>
		<td style=\"text-align: center; background: ".$farbe."; font-size: 13px;\">".$status."</td>
    <td><a href=\"?p=det_grunfr&typ=Unter&id=".$zeile3['Unternehm_ID']."\">" . $zeile3['Name'] . "</a></td>
        <td>". $mitgl_anzahl3 . "</td>
		<td><a href=\"?p=det_grunfr&typ=Unter&id=".$zeile3['Unternehm_ID']."\">Details</a> </td>
  </tr>";
    }
                
?>            
          </table>
</center>
          
          