<?    
if (!$DB){
echo "error";
}
if (!empty($_POST['anlegen'])) 
{

function generatePassword ( $passwordlength = 8, 
                            $numNonAlpha = 0, 
                            $numNumberChars = 0, 
                            $useCapitalLetter = false ) { 
     
    $numberChars = '123456789'; 
    $specialChars = '!$%&=?*-:;.,+~@_'; 
    $secureChars = 'abcdefghjkmnpqrstuvwxyz'; 
    $stack = ''; 
         
    // Stack für Password-Erzeugung füllen 
    $stack = $secureChars; 
     
    if ( $useCapitalLetter == true ) 
        $stack .= strtoupper ( $secureChars ); 
         
    $count = $passwordlength - $numNonAlpha - $numNumberChars; 
    $temp = str_shuffle ( $stack ); 
    $stack = substr ( $temp , 0 , $count ); 
     
    if ( $numNonAlpha > 0 ) { 
        $temp = str_shuffle ( $specialChars ); 
        $stack .= substr ( $temp , 0 , $numNonAlpha ); 
    } 
         
    if ( $numNumberChars > 0 ) { 
        $temp = str_shuffle ( $numberChars ); 
        $stack .= substr ( $temp , 0 , $numNumberChars ); 
    } 
             
         
    // Stack durchwürfeln 
    $stack = str_shuffle ( $stack ); 
         
    // Rückgabe des erzeugten Passwort 
    return $stack; 
     
} 
    
$passwod = generatePassword ( 8, 2, 2, true ); 

$empfaenger = $_POST['email'];
$betreff = "ZUGANG ZUR FIB DATENBANK";
$from = "From: FIB  <director@fib.de>";
$text = "Sehr geehrter Agent ".$_POST['deckname'].", \n \n Nochmal herzlich willkommen beim Federal Investigation Bureau. In dieser E-mail erhalten sie Ihre Zugangsdaten zu unserem internen Aktensystem. Alle Daten und Informationen  die sie in unserem System sichten sind streng vertraulich. Sollten sie fahrlässig mit den Informationen oder den  Zugangsdaten umgehen, werden weitreichende Konsequenzen und eine sofortige Kündigung folgen!\n Zusätzlich sollten sie nur auf Daten zugreifen die für Ihren Fall relevant sind, andernfalls kann auch dies zu Konsequenzen führen. \n \n Dein Passwort und dein Benutzername für die FIB Datenbank lauten: \n Benutzername: ".$_POST['user']." \n Passwort: ".$passwod." \n Zugriff erhalten sie unter der Folgenden Link: http://www.fib-aktensystem.de \n \n Dieses Dokument ist nach dem lesen mit sofortiger Wirkung von ihren Datenträgern zu entfernen um das Risiko externer Zugriffe zu minimieren.
\n \n Mit freundlichen Grüßen, \n Garrus Revernant \n FIB Director ";

 
mail($empfaenger, $betreff, $text, $from);

$dbInsert = array(
	"username" => $_POST['user'],
	"password" => hash('sha256', $passwod),
	"email" => $_POST['email'],
	"PA_Name" => $_POST['vorname'],
	"PA_Nachname" => $_POST['nachname'],
	"PA_Beitritt" => $_POST['dienst'],
	"PA_Geburtstag" => $_POST['geb'],
	"PA_Deck" => $_POST['deckname'],
	"PA_Telefon" => $_POST['telefon'],
	"PA_Sonstiges" => $_POST['sons'],
	"rang" => $_POST['rang']);
$db->insert("users",$dbInsert);
	echo "Zugang erfolgreich erstellt! <a href=\".home.php?p=zugriff\">Nächsten Zugang anlegen.</a><br>";
}

?>

<h3>Zugang für einen Agent erstellen</h3><br/>
 <link rel="stylesheet" type="text/css" href="tcal.css" />
<script type="text/javascript" src="tcal.js"></script>


<form id="update_form" name="update_form" method="post" action="" style="width: 400px; margin: auto;">
		<p>
		<label>Username:</label>
		<input name="user" type="text" id="user" />
	</p>
	<p>
		<label>E-Mail:</label>
		<input name="email" type="text" id="email" />
	</p>
	<p>
		<label>Vorname:</label>
		<input name="vorname" type="text" id="vorname"/>
	</p>

	<p>
		<label>Nachname:</label>
		<input name="nachname" type="text" id="nachname">
	</p>
	<p>
		<label>Deckname:</label>
		<input name="deckname" type="text" id="deckname">
	</p>
	<p>
		<label>Dienstantritt:</label>
		<input name="dienst" type="text" class="tcal" id="dienst" >
	</p>
		<p>
		<label>Geburtstag:</label>
		<input name="geb" type="text" id="geb" class="tcal">
	</p>
	<p>
		<label>Telefon:</label>
		<input name="telefon" type="text" id="telefon" >
	</p>
	<p>
		<label>Sonstiges:</label>
		<textarea name="sons" type="text" id="sons"></textarea>
	</p>
	<p>
	<label>Rang:</label>
	 <select name="rang"><br>                                       
      <?
      $grupp_suche= $db->multiQuery("* from rang", true);
	  foreach ($grupp_suche as $key => $zeile) {
		echo "<option name=\"rang\" type=\"text\" id=\"rang\" value=". $zeile['rang'] .">".$zeile['rang']." - ". $zeile['name'] . "</option>";
      }
      ?>
     </select> 
</p>
	<p>
		<input type="submit" name="anlegen" id="anlegen" class="btn" value="Zugang anlegen"/>
	</p>

</form>      