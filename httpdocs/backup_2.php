<?php
$dbhost = 'db690704140.db.1and1.com';
$dbuser = 'dbo690704140';
$dbpassword = 'ILMM1993';
$dbname = 'db690704140';
$dumpfile = 'backup/' . $dbname . '_' . date("Y-m-d_H-i-s") . '.sql.gz';
function mail_att($to, $from, $subject, $message, $file) {
 // $to Empfänger
 // $from Absender ("email@domain.de" oder "Name <email@domain.de>")
 // $subject Betreff
 // $message Inhalt der Email
 // $file Pfad zur Datei die versendet werden soll
 
 $mime_boundary = "-----=" . md5(uniqid(rand(), 1));
 
 $header = "From: ".$from."\r\n";
 $header.= "MIME-Version: 1.0\r\n";
 $header.= "Content-Type: multipart/mixed;\r\n";
 $header.= " boundary=\"".$mime_boundary."\"\r\n";
 
 $content = "This is a multi-part message in MIME format.\r\n\r\n";
 $content.= "--".$mime_boundary."\r\n";
 $content.= "Content-Type: text/plain charset=\"iso-8859-1\"\r\n";
 $content.= "Content-Transfer-Encoding: 7bit\r\n\r\n";
 $content.= $message."\r\n";
 
 //Datei anhaengen     
 $name = basename($file);
 $data = chunk_split(base64_encode(file_get_contents($file)));
 $len = filesize($file);
 $content.= "--".$mime_boundary."\r\n";
 $content.= "Content-Disposition: attachment;\r\n";
 $content.= "\tfilename=\"$name\";\r\n";
 $content.= "Content-Length: .$len;\r\n";
 $content.= "Content-Type: application/x-gzip; name=\"".$file."\"\r\n";
 $content.= "Content-Transfer-Encoding: base64\r\n\r\n";
 $content.= $data."\r\n"; 
 
 return mail($to, $subject, $content, $header);
}  
 
include_once('src/Ifsnop/Mysqldump/Mysqldump.php');
$dump = new Ifsnop\Mysqldump\Mysqldump("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
$dump->start($dumpfile);


mail_att("samantha.weihrauch@googlemail.com", "FIB@FIB.de", "Backup ".$dumpfile, "Backup wurde erfolgreich erstellt und befindet sich im Anhang", $dumpfile);
?>