<?php 

if ($_GET["f"]=="new") {


	
// Variablen aus dem POST nehmen	
	$dbInsert = array(
		"datum" => $_POST['date'],
		"Anwalt" => addslashes(htmlspecialchars($_POST['staasi'])),
		"Bezeichnung" => $_POST['bez'],
		"k_Beschreibung" => $_POST['kurz'],
		"d_Beschreibung" => addslashes(htmlspecialchars($_POST['detail'])),
		"S_Status" => 0,
		"Status" => 0);
	$db->insert("Fallakten",$dbInsert);
	echo "Daten erfolgreich eingetragen! <a href=\"home.php?p=add_fallakte\">Nächsten Fall anlegen</a><br>";
	
	
// Wenn nichts ansteht...
}else if($_GET["f"]==''){
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/jquery.tinymce.min.js"></script>
	<script>
		tinymce.init( {
			selector: 'textarea',
			plugins: 'lists autoresize link textcolor',
			menubar: false,
			toolbar: 'formatselect fontsizeselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist | link forecolor backcolor',
		} );
	</script>

 <link rel="stylesheet" type="text/css" href="tcal.css" />
<script type="text/javascript" src="tcal.js"></script> 
           
<h3>Fallakte anlegen</h3>
<!--- Formular --->
<div style="max-width: 500px; margin-top: 50px; margin-left: auto; margin-right: auto;">
<form action="home.php?p=add_fallakte&f=new" method="post" enctype="multipart/form-data">  
	<p>
	 <label>Bezeichnung</label>
	 <input type="text" name="bez" placeholder="Geiselnahme JVA, 01-01-1991" />
	</p>
		<p>
	 <label>Zugewiesener Staatsanwalt:</label>
	 <input type="text" name="staasi" placeholder="Dr. Edward Nelson, ..." />
	</p>
	<p>
	<label>Datum (NICHT PER HAND EINGEBEN)</label>
	<input type="text" name="date" class="tcal" value="" />
	</p>
	<p>
		<label>Kurzbeschreibung</label>
		<textarea name="kurz" type="text" id="kurz"></textarea>
    </p>  
    <p>
    <label>Detaillierte Beschreibung</label>
    <textarea name="detail" placeholder="" /></textarea>
    </p>   
            

    <div class="reg_button">
    <input class="submit btn" type="submit" name="submit" value="Eintragen">
    </div>
                  
 </form> 
</div>                
</div>        
             
<?
}
?>
              
      