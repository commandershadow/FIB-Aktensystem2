<?php

// Setze eine WHERE anweisung
switch($_GET['c']) {
	case 'i': $qFilter = " WHERE logType = 'INSERT'"; break;
	case 'u': $qFilter = " WHERE logType = 'UPDATE'"; break;
	case 'd': $qFilter = " WHERE logType = 'DELETE'"; break;
	default: $qFilter = '';
}
if(strlen($_GET['u']) > 0 && intval($_GET['u']) == $_GET['u']) {
	if(strlen($qFilter) > 0) $qFilter .= ' AND logUserID = '.$_GET['u'];
	else $qFilter = ' WHERE logUserID = '.$_GET['u'];
}

// Variablen der Sortierfunktion
$qLimit = 25;
$countFilter = strlen($qFilter) > 0 ? $qFilter : null;
$qSeiten = $db->countRow('Log',$countFilter);
$qSeite = intval($_GET['s']);
$logUser = $db->multiQuery('uid, PA_Deck FROM users ORDER BY PA_Deck ASC',true);
$seitenLimit = floor($qSeiten / $qLimit);
$qSeite = $qSeite > $seitenLimit ? $seitenLimit : $qSeite; // Maximal die Letzte seite soll angezeigt werden
$LIMIT = $qSeite * $qLimit; // der LIMIT parameter für den OFFSET des query


// Erstelle eine Seitenanzeige für die Liste
$pageList = generatePageList($qSeite,$seitenLimit,"?p=log&s=","&c=".$_GET['c']."&u=".$_GET['u']);

// Aufsteigend oder Absteigend Sortieren
if($qOrder == 'asc') $qOrder = 'ASC'; else $qOrder = 'DESC';


// Daten die aus dem Log geladen werden sollen
$logRows = 'Log.logID,Log.logDate,Log.logTable,Log.logTableID,Log.logType,Log.logParam,users.PA_Deck';
$log = $db->multiQuery($logRows." FROM Log LEFT JOIN users on users.uid = Log.logUserID".$qFilter." ORDER BY Log.logDate ".$qOrder." LIMIT ".$LIMIT.",".$qLimit);

?> 
<style>
.logAdd {
	color:#0b0 !important;
}
.logChange {
	color:#bb0 !important;
}
.logDelete {
	color:#b00 !important;
	font-weight:bold !important;
}
.hoverInfoLink {
	display:block;
	cursor:pointer;
	width:60px;
	height:25px;
	padding:1px 21px;
}
.hoverInfoLink:hover {
	background:#2c2f31;
	padding:0px 20px;
	border:1px solid #000;
}
.hoverInfoLink > div {
	position:relative;
	width:400px;
	background:#2c2f31;
	min-height:150px;
	border:1px solid #000;
	border-radius:10px;
	top:-100px;
	left:-432px;
	visibility:hidden;
	display:none;
	padding:10px;
	z-index:2000;
}
.hoverInfoLink:hover > div {
	visibility:visible;
	display:block;
	cursor:default;
	opacity:0.975;
}

</style>
<script>
function changeFilterOption(option,type) {
	var u = '<? echo strlen($_GET['u']) > 0 ? $_GET['u'] : ''; ?>'; 
	var c = '<? echo strlen($_GET['c']) > 0 ? $_GET['c'] : ''; ?>'; 
	var url = "home.php?p=log&";
	if(type == 'change') {
		url += "u=" + u + "&c=";
		switch(option) {
			case 'i':
				window.location.href = url+"i";
				break;
			case 'u':
				window.location.href = url+"u";
				break;
			case 'd':
				window.location.href = url+"d";
				break;
			default:
				window.location.href = url;
		}
	} else if(type == 'user') {
		if(option == 0) 
			window.location.href = url+"u=&c=" + c;
		else
			window.location.href = url+"u=" + option + "&c=" + c;
	}
}
</script>
Filteroptionen:
<select name="change" onchange="changeFilterOption(this.options[this.selectedIndex].value,this.name);" style="width:140px; display:inline;">
	<option value="0">Alle Änderungen</option>
	<option value="i"<? if($_GET['c'] == 'i') echo ' selected="selected">'; ?>>Hinzugefügt</option>
	<option value="u"<? if($_GET['c'] == 'u') echo ' selected="selected">'; ?>>Geändert</option>
	<option value="d"<? if($_GET['c'] == 'd') echo ' selected="selected"> '; ?>>Gelöscht</option>
</select>
<select name="user" onchange="changeFilterOption(this.options[this.selectedIndex].value,this.name);" style="width:125px; display:inline;">
	<option value="0">Alle Agenten</option>
<? foreach($logUser as $id => $dat) {
	// Leni FIX
	if(strlen($dat['PA_Deck']) < 1) $dat['PA_Deck'] = 'Leni';
	if($dat['PA_Deck'] != 'Stadtrat') 
		echo '<option value="'.$dat['uid'].'"'.($_GET['u'] == $dat['uid']?' selected="selected">':'').'>'.$dat['PA_Deck'].'</option>';		
} ?>
</select>
<br>
<center>
	<? echo $pageList; ?>
<table width="800px">
	<tr style="background: #6C6C6C;">
		<td width="100px">User</td>
		<td width="115px">Änderung</td>
		<td width="135px">Datum</td>
		<td width="150px">Bezeichnung</td>
		<td width="80px"></td>
	</tr>
<?
foreach($log as $key => $content) {
	$datum = date('d.m.y H:i',$content->logDate);
	$info = '';
	switch($content->logType) {
		case 'INSERT': 
			$change = '<span class="logAdd">Hinzugef&uuml;gt</span>'; 
			$info = 'Hinzugefügt:';
			foreach(unserialize($content->logParam) as $k2 => $v2) {
				if(strlen($v2) > 250) {$v2 = substr($v2,0,250).' [...]'; $cutInfo = true;}
				if(strlen($v2) > 0 && $v2 != '0')
					$info .= '<br><span style="text-decoration:underline;">'.$k2.'</span>: <span style="color:#9f9">'.$v2.'</span>';
			}
			break;
		case 'UPDATE': 
			$change = '<span class="logChange">Ge&auml;ndert</span>';
			$info = 'Änderungen:';
			$cutInfo = false;
			foreach(unserialize($content->logParam) as $k2 => $v2) {
				if(strlen($v2[0]) > 250) {$v2[0] = substr($v2[0],0,250).' [...]'; $cutInfo = true;}
				if(strlen($v2[1]) > 250) {$v2[1] = substr($v2[1],0,250).' [...]'; $cutInfo = true;}
				$info .= '<br><span style="text-decoration:underline;">'.$k2.'</span><br>Von: <span style="color:#f99">'.$v2[0].'</span><br>Zu: <span style="color:#9f9">'.$v2[1].'</span>';
			}
			break;
		case 'DELETE':
			$change = '<span class="logDelete">Gel&ouml;scht</span>';
			$info = 'Gelöscht:';
			foreach(unserialize($content->logParam) as $k2 => $v2) {
				if(strlen($v2) > 250) {$v2 = substr($v2,0,250).' [...]'; $cutInfo = true;}
				if(strlen($v2) > 0)
					$info .= '<br><span style="text-decoration:underline;">'.$k2.'</span>: <span style="color:#f99">'.$v2.'</span>';
			}
			break;
		default: $change = '>HACKED';
	}
	?>
	<tr>
		<td><? echo $content->PA_Deck; ?></td>
		<td><? echo $change; ?></td>
		<td><? echo $datum; ?></td>
		<td><? echo $content->logTable; ?></td>
		<td class="hoverInfoLink">Details<div><? echo '(#'.str_pad($content->logID,5,'0',STR_PAD_LEFT).') '.$info; ?></div></td>
	</tr>
	<?
}
?>
</table><? echo $pageList; ?></center>
