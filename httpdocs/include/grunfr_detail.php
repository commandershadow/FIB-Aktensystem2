<?
$db = getDB();
$typ = $_GET['typ'];
$id = $_GET['id'];

if ($typ=="Frakt"){
$sql = "SELECT Name FROM Fraktionen WHERE Frakt_ID = ".$id."";
foreach ($db->query($sql) as $row) {
$name = $row['Name'];
}
} elseif ($typ == "Unter") {
$sql = "SELECT Name FROM Unternehmen WHERE Unternehm_ID = ".$id."";
foreach ($db->query($sql) as $row) {
$name = $row['Name'];
}
} else {
$sql = "SELECT Name FROM Gruppierungen WHERE Grupp_ID = ".$id."";
foreach ($db->query($sql) as $row) {
$name = $row['Name'];
}
}


?>  <!-- ===========================Content div starting=========================== -->
     
        
<h3><?=$name;?></h3><br />
<center>
<table style="width: 600px;" >
     <tr style="background: #6C6C6C;">
    <td>Vorname</td>
    <td>Nachname</td>
    <td>Telefon</td>
    <td>Akte</td>
  </tr>
  <?
 
if ($typ=="Frakt"){
$sql = "SELECT Vorname, Nachname, Telefon, Pers_ID FROM Personen WHERE Frakt_ID = ".$id."";
foreach ($db->query($sql) as $row) {
echo "<tr>
    <td>". $row['Vorname'] . "</td>
    <td>". $row['Nachname'] . "</td>
	<td>". $row['Telefon'] . "</td>
	<td><a href=\"home.php?p=det_buer&f=&id=".$row['Pers_ID']."\">Bürgerakte aufrufen</a></td>	
  </tr>  ";

}
}

if ($typ=="Unter"){
$sql = "SELECT Vorname, Nachname, Telefon, Pers_ID FROM Personen WHERE Unternehm_ID = ".$id."";
foreach ($db->query($sql) as $row) {
echo" <tr>
    <td>". $row['Vorname'] . "</td>
    <td>". $row['Nachname'] . "</td>
	<td>". $row['Telefon'] . "</td>
	<td><a href=\"home.php?p=det_buer&f=&id=".$row['Pers_ID']."\">Bürgerakte aufrufen</a></td>		
  </tr>  ";
}
}

if ($typ=="Grupp"){
$sql = "SELECT Vorname, Nachname, Telefon, Pers_ID FROM Personen WHERE Grupp_ID = ".$id."";
foreach ($db->query($sql) as $row) {
echo  "<tr>
    <td>". $row['Vorname'] . "</td>
    <td>". $row['Nachname'] . "</td>
	<td>". $row['Telefon'] . "</td>
	<td><a href=\"home.php?p=det_buer&f=&id=".$row['Pers_ID']."\">Bürgerakte aufrufen</a></td>	
  </tr>  ";
}
}
?>
   
</table>
</center>       
          