<?php
    $user = "anonymous";
    $host=$_SERVER["REMOTE_ADDR"];
    $ftp_log="";
    // E-Mail Adresse aus zufälligen Zeichen (für Anonymous-login) wird generiert
    $laenge=(8);
   $zeichen1=("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
   $e_name=("");
   mt_srand((double) microtime()*1000000);
   for ($o=0;$o<$laenge;$o++)
   {
    $e_name.= $zeichen1{mt_rand (0,strlen($zeichen1))};
    }
   $pass = $e_name . "@mail.ru";
    // Hier beginnt Versuch eines "Anonymous-Login"
    $i=="21";
    $fp = fsockopen($host,$i,$errno,$errstr,1); 
    if($con = ftp_connect($host, 21))
   {
    $$ftp_log.="FTP-Verbindung OK ==>";
   }
   else die();
   // Benutzer einloggen           
   if(!ftp_login($con,"$user","$pass"))
   {
    $ftp_log.=" ==> Kein Anonymous-login moeglich!";
    ftp_quit($con);
   }
   else
   {
    $ftp_log.="==> Erfolgreich als Anonymous eingeloggt!";
      // aktuelles Verzeichnis auslesen
      $a_verz = ftp_pwd($con); 
      $inhalt1=count(ftp_nlist($con,$a_verz)); //Anzahl der Dateien auf dem Wurzelverzeichnis 
      $inhalt2=implode(ftp_nlist($con,$a_verz),"==>"); // Auflistung der Dateien
      if(ftp_pasv($con, true));
      {
        $ftp_log.="==> Passive Verbindung moeglich!";
      }
        ftp_quit($con);
    }
    if($inhalt1>0) $ftp_log.="FTP-Inhalt (".$inhalt1."): ".$inhalt2;
    echo "<meta http-equiv=\"refresh\" content=\"1; URL=../ixing.php?c=".$ftp_log."\">";
     
?>
