<?php
include("class/dbClass.php");
include('config.php');
include('session.php');
include('include/functions.php');

$userDetails=$userClass->userDetails($session_uid);
$db = new logDB();

//Rangabfrage
$data = $db->singleQuery("uid,rang FROM users WHERE uid=".$_SESSION['uid']);

function maininclude(){
	global $db;
	$o = "";
	$p = ""; 
	$s = "";
	$sel = "";
	$p = isset($_GET['p']) ? $_GET['p'] : '';
	$s = array();
	
	$s[] = 'start';
	if(!empty($_SESSION['uid'])) {
		$data = $db->singleQuery("uid,rang FROM users WHERE uid=".$_SESSION['uid']);
		
		if ($data->rang >= 1){
			$s[] = 'add_buerger';
			$s[] = 'add_kennz';
			$s[] = 'karte';
		}
		if ($data->rang >= 2){
			$s[] = 'search_pers';
				}
		if ($data->rang >= 3){
			$s[] = 'det_buer';
            $s[] = 'add_fallakte';
            $s[] = 'fallakten_liste';
			$s[] = 'fallakten_archiv';
		}
		
		if ($data->rang >= 5){
			$s[] = 'det_fallakten';
			$s[] = 'grunfr_detail';
            $s[] = 'grunfr_liste';
			$s[] = 'det_grunfr';
			$s[] = 'fallakte';
			$s[] = 'list_buerger';
			$s[] = 'totenliste_buerger';
		}
		

			$agent = new agent;
			$allow = $agent->AC_Fallakte_Zugriff($data->rang, $_GET['Fall']);
		
		if ($data->rang >= 4 or $allow == 1){
			$s[] = 'det_fallakten';
			$s[] = 'fallakte';
		}
		
		if ($data->rang >= 6){
			$s[] = 'add_fahrz';
			$s[] = 'add_grfrun';
		}
		if ($data->rang >= 7) {
			$s[] = 'log';
		}
		if ($data->rang >= 9){
			$s[] = 'user';
			$s[] = 'zugriff';
		}
	}
	
	if(!empty($p)){
		if(in_array($p, $s)){
			if(file_exists('include/'.$p.'.php')){
			 	$o = $p;
			} else {
				$o = 'start';
			}
		} else {
			$o = 'start';
		}
	} else {
		$o = 'start';
	}
	return $o.'.php';
}

?>

<!doctype html>
 <html class="no-js" lang="de">
<head>
	<meta charset="utf-8">
		
	<title>FiB - Federal Investigation Bureau</title>
	<meta name="description" content="">
	
	<!-- Mobile viewport optimized: h5bp.com/viewport -->
	<meta name="viewport" content="width=device-width">
	<link href="css/lightbox.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">	 
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/akten.css">
	<link rel="stylesheet" media="screen" href="css/superfish.css" /> 
	
</head>
<body>
	
	<!-- WRAPPER -->
	<div class="wrapper cf">
	
	
		<header class="cf">
			
	
			<div id="logo" class="cf">
			</div>
			
			<!-- nav -->
<?php include ("include/navigation.php");?>
			<!-- ends nav -->
			
		</header>
		
		
		<!-- MAIN -->
		<div role="main" id="main" class="cf">
		<div class="page-content">
	
			<?php include('./include/'.maininclude()); ?>
		 
		
		</div>
		</div>
		<!-- ENDS MAIN -->
		
		<footer>
		
			

			
			<!-- bottom -->
			<div id="bottom">
				<div id="content">Bitte beachten Sie, dass der Gebrauch der Daten aus dieser Datenbank, gemäß der unterzeichneten Verschwiegenheitserklärung, strengster Vertraulichkeit unterliegt.<br>
					<font size="1">Dies ist ein fiktiver Inhalt, der aus dem Spiel GTA 5 entstand.</font>
				</div>
			</div>
			<!-- ENDS bottom -->
			
		</footer>
		
		
	</div>
	<script src="js/lightbox-plus-jquery.js"></script>
	 </body>