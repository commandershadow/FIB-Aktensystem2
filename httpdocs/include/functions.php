<?php
// Telefonnummern konvertieren (01337 / 1234 123)
function conv2Tel($number) {
	if(strlen($number) > 6) 
		return substr($number,0,5).' '.substr($number,5,4).' '.substr($number,9);
	else
      		return "- - -";
}



//Akten Freigabe Status
function akten_freigabe($rang){
    global $db;
    
$dbTableJoin = "fib_zugriff";
$dbWhere = "WHERE z_rang = ".$rang." OR z_rang <= ".$rang."";
$aktenFreigabe = $db->multiQuery("* FROM ".$dbTableJoin." ".$dbWhere,true);
    
	foreach ($aktenFreigabe as $af => $afr) {
    echo $afr['datei'];
    } 
}



// Automatisch Inhalt auf mehrere Seiten verlegen.
function generatePageList($aktSeite,$seitenLimit,$url,$param = "") {
	$return = '';
	if($aktSeite > 0) $return .= '<a href="'.$url.'0'.$param.'"><<<</a> <a href="'.$url.($aktSeite-1).$param.'"><</a> ';
	$min = $aktSeite > 3 ? $aktSeite - 3 : 0;
	$max = $aktSeite < ($seitenLimit - 3) ? $aktSeite + 3 : $seitenLimit;
	for($i = $min; $i <= $max; $i++) {
		if($i != $aktSeite)
			$return .= '<a href="'.$url.$i.$param.'">'.($i+1).'</a> ';
		else 
			$return .= '<span style="font-weight:bold; color:#999;">| '.($aktSeite+1).' / '.($seitenLimit+1).' |</span> ';
	}
	if($aktSeite < $seitenLimit) $return .= ' <a href="'.$url.($aktSeite+1).$param.'">></a> <a href="'.$url.$seitenLimit.$param.'">>>></a>';
	return $return;
}

// FUNKTION: Bilder auf passende H�he und Breite verkleinern (Nur .jpg, .png und .gif)
function make_thumb($image, $target) {
  $max_width=600;
  $max_height=375;
	$picsize     = getimagesize($image);
	if(($picsize[2]==1)OR($picsize[2]==2)OR($picsize[2]==3)) {
	if($picsize[2] == 1) {
	  $src_img     = imagecreatefromgif($image);
	}
	if($picsize[2] == 2) {
	  $quality=100;
	  $src_img     = imagecreatefromjpeg($image);
	}
	if($picsize[2] == 3) {
	  $quality=9;
	  $src_img     = imagecreatefrompng($image);
	}
	$src_width   = $picsize[0];
	$src_height  = $picsize[1];
	$skal_vert = $max_height/$src_height;
	$skal_hor = $max_width/$src_width;
	$skal = min($skal_vert, $skal_hor);
	if ($skal > 1) {
	 $skal = 1;
	}
	$dest_height = $src_height*$skal;
	$dest_width = $src_width*$skal;
	$dst_img = imagecreatetruecolor($dest_width,$dest_height);
	imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
	if($picsize[2] == 1) {
	  imagegif($dst_img, "$target");
	}
	if($picsize[2] == 2) {
	  imagejpeg($dst_img, "$target", $quality);
	}
	if($picsize[2] == 3) {
	  imagepng($dst_img, "$target", $quality);
	}
	}
}

//Inhalt automatischen Escapen:
function escape($var){
	return addslashes(htmlspecialchars($var));
 }
 
//Rangabfrage + Fallaktenzugriffabfrage
class agent {
	var $uid;
	var $allow;
	var $falluid;
	var $rang;
	var $var;
	
public function Rangabfrage(){
	global $db;
	$data = $db->singleQuery("uid,rang FROM users WHERE uid = '".$_SESSION['uid']."'");
	return $data->rang;
}

/**
 * Prüft ob ein Agent Zugriff auf einen Fall hat, abhängig von der Sicherheitsstufe.
 * 
 * @param int $rang
 * @param int $fall_id
 * 
 * @return bool
 */
public function AC_Fallakte_Zugriff($rang, $fall_id)
{
  global $db;

  $data = $db->singleQuery("S_Status FROM Fallakten WHERE Fall_ID = '{$fall_id}'");
  $sicherheits_status = $data->S_Status;

  // Prüfe ob der Fall einem Zugewisen ist
  $count = $db->countRow("Fallakten_Agents", "WHERE FALL_ID = '{$fall_id}' AND Fall_UID = '{$_SESSION['uid']}'");

  if($count > 0) {
    return 1;
  }
  

  switch($sicherheits_status) {
    case 0: // public access
      if($rang >= 3) { return 1; }
      break;
    case 2: // Secret access
      if($rang >= 5) { return 1; }
      break;
    default:
      if($rang >= 9) { return 1; }
      break;
  }
  return 0;
}

		
public function AC_Fallakte($rang) {
	if ($rang >= 7) {
		return 1;
	}else{
		return 0;
	}

}
}
	

?>