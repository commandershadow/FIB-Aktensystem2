<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
include('../config.php');
include('functions.php');
include('../class/dbClass.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SNOWDEN');
$pdf->SetTitle('SNOWDEN FIB');
$pdf->SetSubject('FIB SNOWDEN');
$pdf->SetKeywords('SNOWDEN');


// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 9);

// add a page
$pdf->AddPage();

$id = $_GET['Fall'];

$db = new logDB();

//TOTO: Secure this Statement
$id = $_GET['Fall'];
$FA_D = $db->singleQuery("* from Fallakten where Fall_ID = ".$id,true);

//Modify results
if ($FA_D['Status'] == 0){
			$status = "OFFEN";
			$farbe ="#841113";
}else if ($FA_D['Status'] == 1 ){
			$status = "IN BEARBEITUNG";
			$farbe ="#D7BB03";
}else{
			$status = "ABGESCHLOSSEN";
			$farbe ="#46981F";
}

if ($zeile['Anwalt'] == ""){
  $anwalt = "<font color=\"red\">Nicht zugewiesen.</red>";
}else{
  $anwalt = $zeile['Anwalt'];
}

$FA_T = $db->multiQuery("Fallakten_Agents.*, users.PA_Deck AS deck from Fallakten_Agents left
  join users on users.uid = Fallakten_Agents.Fall_UID where Fallakten_Agents.Fall_ID = ".$id." ORDER BY leitung DESC",true);

$datum = date("d.m.Y", strtotime($FA_D['datum']));
$fa_number = 'FA#'.str_pad($FA_D['Fall_ID'], 5, 0, STR_PAD_LEFT);

$html .= "<style>
td {
	vertical-align:middle;
}
</style>
<!-- ENDE FIX -->
<img src='http://www.fib-aktensystem.de/include/images/FIB_Bericht_1.png'></img>
<h3>Fallaktennummer: ".$fa_number."</h3>
<h3>Bearbeitung von: ".$FA_T."</h3>
<h3>Status: ".$status."</h3>
<h3>Stand vom: </h3>
<p>Datum: ".$datum."| Bearbeitung von: ".$FA_T."| Zugewiesener Staatsanwalt: ".$anwalt."</p>
<h3>Fall: ".$FA_D['Bezeichnung']."</h3>
<h3>Kurzbeschreibung: ".$FA_D['k_Beschreibung']."</h3>
<h3>Detaillierte Beschreibung</h3>";
$html .= $FA_D['d_Beschreibung'];

$html .= "";
// create some HTML content

        
// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);

// reset pointer to the last page
$pdf->lastPage();


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($name.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>