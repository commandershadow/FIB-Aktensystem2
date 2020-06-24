<?php
include("class/dbClass.php");
include("config.php");
include("include/functions.php");

$db = new logDB();
	$data = $db->singleQuery("uid, rang FROM users WHERE uid='".$_SESSION['uid']."'");
if($_POST)
{
    $q = $_POST['search'];
    $strSQL_Result = $db->multiQuery("Personen.*, Kennzeichen.Kennzeichen, Kennzeichen.Pers_ID AS P_UID FROM Personen LEFT JOIN Kennzeichen ON Personen.Pers_ID = Kennzeichen.Pers_ID WHERE Vorname LIKE '%$q%' OR Nachname LIKE '%$q%' OR Telefon LIKE '%$q%' OR Kennzeichen LIKE '%$q%' OR Spitzname LIKE '%$q%' ORDER BY Personen.Pers_ID LIMIT 5",true);
	foreach ($strSQL_Result as $id => $row) {
		$p_uid = $row['Pers_ID'];
       	$vorname   = $row['Vorname'];
		$nachname   = $row['Nachname'];
		$telefon   = $row['Telefon'];
		$spitz   = $row['Spitzname'];
		$kennzeichen   = $row['Kennzeichen'];
        $auid = $row['ausweisid'];
        $pic = $row['passfoto'];
        
        switch ($pic){
        case "": 
        $bild =  "pl_bild.png";
        break;
        default:
        $bild =  $row['passfoto'];
        }
        
		$b_spitzname = '<strong><font color="red">'.$q.'</font></strong>';
        $b_vorname = '<strong><font color="red">'.$q.'</font></strong>';
        $b_nachname    = '<strong><font color="red">'.$q.'</font></strong>';
		$b_kennzeichen    = '<strong><font color="red">'.$q.'</font></strong>';
		$b_uid    = $q;
        $final_vor = str_ireplace($q, $b_vorname, $vorname);
		$final_nachname = str_ireplace($q, $b_nachname, $nachname);
		$final_uid = str_ireplace($q, $b_uid, $p_uid);
		$final_spitz = str_ireplace($q, $b_spitzname, $spitz);
		$final_kennzeichen = str_ireplace($q, $b_kennzeichen, $kennzeichen);					
        ?>
            <div class="show" align="left">
                <img src="<?=$bild;?>" style="width:50px; height:75px; float:left; margin-right:5px;" />
                <span class="name"> <?php echo $final_vor; ?></span>
                &nbsp;<?=$final_nachname;?> <br>
                <b>Tel.:</b> <?=conv2Tel($telefon);?> | <b>Spitzname:</b> <?=$final_spitz;?> <br>
                <b>Geschlecht:</b> <?=$row['Geschlecht'];?> | Geb.: <?=date("d.m.Y", strtotime($row['geb']));?> <br>
                <b>Ausweis-ID:</b> <?=$auid;?><br>
                <? if ($final_kennzeichen != "") {?> <b>Kennzeichen:</b><?echo $final_kennzeichen; 
                }  if ($data->rang >= $BUE_Details){ ?>
                <div align="right" style="margin-top: -50px;">	
                <font size="2" style="align-content: "><a href="home.php?p=det_buer&f=&id=<?php echo $final_uid; ?>">Details anzeigen</a></font></div>  
                <? } ?>
				
           
				
            </div>
        <?php
    }
}
?>
