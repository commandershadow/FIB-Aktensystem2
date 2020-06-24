<?

if ($_POST["update"]=="Save Changes"){
	$dbUpdate = array(
		"rang" => $_POST['rang'],
		"PA_ID" => $_POST['nummer'],
		"PA_Name" => $_POST['vorname'],
		"PA_Nachname" => $_POST['nachname'],
		"PA_Beitritt" => $_POST['dienst'],
		"PA_Geburtstag" => $_POST['geb'],
		"PA_Deck" => $_POST['deckname'],
		"PA_Telefon" => $_POST['telefon'],
		"PA_Sonstiges" => $_POST['sons']);
	$dbWhere = array('uid' => $_GET['id']);
	$db->update("users",$dbUpdate,$dbWhere);
	echo "Geändert.";
}
// Löschen von Eintragungen von Bürgern
if ($_GET["action"]=="remove"){
	$db->delete("users",array("uid" => $_GET['uid']));
	echo "Bürger erfolgreich gelöscht.";	
}
	
if ($_GET["f"]=="change"){
	
	 $id = $_GET['uid'];   
	 $zeile2 = $db->singleQuery("* from users where uid = '".$id."'",true);
	?>
	 <link rel="stylesheet" type="text/css" href="tcal.css" />
<script type="text/javascript" src="tcal.js"></script> 

<form id="update_form" name="update_form" method="post" action="home.php?p=user&f=&id=<? echo $zeile2['uid']; ?>" style="width: 400px; margin: auto;">

	<input type="hidden" name="id" value="<? echo $zeile2['uid']; ?>"/>

	<p>
		<label>Vorname:</label>
		<input name="vorname" type="text" id="vorname" value="<? echo $zeile2['PA_Name']; ?>"/>
	</p>

	<p>
		<label>Nachname:</label>
		<input name="nachname" type="text" id="nachname" value="<? echo $zeile2['PA_Nachname']; ?>"/>
	</p>
	<p>
		<label>Deckname:</label>
		<input name="deckname" type="text" id="deckname" value="<? echo $zeile2['PA_Deck']; ?>"/>
	</p>
		<p>
		<label>Dienstnummer:</label>
		<input name="nummer" type="text" id="nummer" value="<? echo $zeile2['PA_ID']; ?>"/>
	</p>
	<p>
		<label>Dienstantritt:</label>
		<input name="dienst" type="text" class="tcal" id="dienst" value="<? echo $zeile2['PA_Beitritt']; ?>"/>
	</p>
		<p>
		<label>Geburtstag:</label>
		<input name="geb" type="text" id="geb" class="tcal" value="<? echo $zeile2['PA_Geburtstag']; ?>"/>
	</p>
	<p>
		<label>Telefon:</label>
		<input name="telefon" type="text" id="telefon" value="<? echo $zeile2['PA_Telefon']; ?>"/>
	</p>
	<p>
		<label>Sonstiges:</label>
		<textarea name="sons" type="text" id="sons" value="<? echo $zeile2['sons']; ?>"/><? echo $zeile2['PA_Sonstiges']; ?></textarea>
	</p>
	<p>
	<label>Rang:</label>
	 <select name="rang"><br>                                       
      <?
	  #where rang = ".$zeile2['rang'].");
	  $zeile3 = $db->multiQuery("* FROM rang",true);
	 ?>
	 <?
	foreach ($zeile3 as $key => $zeile) {
		echo "<option name=\"rang\" type=\"text\" id=\"rang\" value=". $zeile['rang'] .($zeile2['rang']==$zeile['rang']?' selected="selected"':'').">".$zeile['rang']." - ". $zeile['name'] . "</option>";
	}
      ?>
     </select> 
</p>
	<p>
		<input type="submit" name="update" id="update" class="btn" value="Save Changes"/>
	</p>

</form>        


<?
}

if ($_GET["f"]==""){
?>



<h3>Personen mit Zugriff</h3><br/>
<center>
 <table width="600px;" align="center">
  <tr style="background: #6C6C6C;">
    <td>Vorname</td>
    <td>Nachname</td>
    <td>Deckname</td>
    <td>Rang</td>
	<td></td>
  </tr>
<?
$strSQL_Result = $db->multiQuery("username, uid, rang, PA_Name, PA_Nachname, PA_Deck FROM users ORDER BY rang DESC",true);

foreach($strSQL_Result as $key => $row) 
    {
	 ?>
<tr>
    <td><? echo $row['PA_Name'];?></td>
    <td><? echo $row['PA_Nachname'];?></td>
    <td><? echo $row['PA_Deck'];?></td>
    <td><? echo $row['rang'];?></td>
	<td><a href="?p=user&f=det&uid=<?=$row['uid'];?>">Details</a></td>
</tr>
<?
	}
?>
</table>
</center>   
<?
}

//Neuhochladen von Bildern -> Speichern in der DB
if ($_POST["bilder"]=="Hochladen"){		
$upload_folder = 'personal/'; //Das Upload-Verzeichnis
$filename = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));	
		
 
//Alles okay, verschiebe Datei an neuen Pfad
if ($filename != ""){
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
	
	
	if ( move_uploaded_file($_FILES['userfile']['tmp_name'], $new_path) ) {
				$db->update("users",array("bild" => $new_path),array("uid" => $_GET['id']));
	echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';
				
			} else {
				echo "Fehler beim Hochladen des Bildes";
			}
} }
	
//Speichern in der DB
	if ($_GET['f'] == "update_pic"){?>
	<form action="" method="post" enctype="multipart/form-data">     
	<p>
	<label>Bild max. 10 MB</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="11000000">
	<input name="userfile" type="file">
	</p>
	<div class="reg_button">
    <input class="submit btn" type="submit" id="bilder" name="bilder" value="Hochladen">
    </div>
	</form>
	<?}?>
<?
//Löschen vom Bild
if ($_GET["f"]=="remove_pic"){	
	
	$datei_name = $_GET['bild'];
      
    if (@file_exists($datei_name) == true) {
      
        if (@unlink($datei_name) == true) {
			$db->update("users",array("bild" => ""),array("uid" => $_GET['id']));		
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

if ($_GET["f"]=="det"){

	$row = $db->singleQuery("* from users where uid = ".$_GET['uid'],true);
			 if($row>0)	// if user exists
			 {?>
			<style>
*{
padding : 0;
margin : 0;
border : 0;
}
.blended_grid{
display : block;
width : 800px;
overflow : auto;
margin : 20px auto 0 auto;
}
.pageLeftMenu{
float : left;
clear : none;
height : 600px;
width : 300px;
}
.pageContent{
float : left;
clear : none;
height : 600px;
width : 500px;
}
</style>
 <h2><font size="5">Personalakte von</font> <font color="#020187"><? echo $row['PA_Name']; ?> <? echo $row['PA_Nachname']; ?> </font></h2>
<div class="blended_grid">
<div class="pageLeftMenu">
<?if ($row['bild'] != ""){
			$bild   = $row['bild'];
			$meldung = "<a href=\"home.php?p=user&f=remove_pic&id=".$row['uid']."&bild=".$row['bild']."\">Bild löschen</a>";
		}else{
			$bild   = "pl_bild.png";
			$meldung = "<a href=\"home.php?p=user&f=update_pic&id=".$row['uid']."\">Bild hochladen</a>";
		}?>
		 <img src="<?=$bild;?>" style="width:280px; height:400px;" />
		 <? if ($data->rang >= $BUE_BU){ ?>
		 <br><?=$meldung;?>
		 <? } ?>
</div>
<div class="pageContent">
<table width="500px">
                    <tr>
                            <td> <?if ($data->rang >= $BUE_Bearb){?>
                            <a href="home.php?p=user&f=change&uid=<? echo $row['uid']; ?>" class="btn btn-info" >
                            Bearbeiten</a>
                        
                        <? } ?>    
								<? if ($data->rang >= $BUE_Loesch){?>
                        <a href="home.php?p=user&action=remove&uid=<? echo $row['uid']; ?>" class="btn btn-danger delet">
                        <font color="#8A0B0D">L&ouml;schen</font></a>
                         <? } ?> </td>
                    </tr>
						 <tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Vorname </td><td> Nachname</td>
                        </tr>
 
                         <tr>
                           <td><? echo $row['PA_Name']; ?> </td><td> <? echo $row['PA_Nachname']; ?></td
                        </tr>
						 <tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Deckname </td><td> Geburtstag</td>
                        </tr>
						<tr>
                           <td><? echo $row['PA_Deck']; ?> </td><td><?=date("d.m.Y", strtotime($row['PA_Geburtstag']));?></td
                        </tr>
						<tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Telefon </td> <td>Rang</td>
                        </tr>
  <?
	  $zeile = $db->singleQuery("name FROM rang WHERE rang = ".$row['rang']);
	$tel = conv2Tel($row['PA_Telefon']);
						?>
                         <tr>
                           <td><?=$tel;?> </td><td> <? echo $zeile->name; ?></td
                        </tr>
						<tr style="background: #6C6C6C; font-size: 15px;">
                           <td>Sonstiges </td><td> Dienstantritt </td>
                        </tr>
 
                         <tr>
                           <td><? echo $row['PA_Sonstiges']; ?> </td><td><?=date("d.m.Y", strtotime($row['PA_Beitritt']));?> </td
                        </tr>
							                 </table>
                    
                    
</div>
</div>
<? } }?>