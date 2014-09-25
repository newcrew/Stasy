<?php
	///////////////////////////////	
	// Variablen werden gesetzt
	///////////////////////////////
	$waittime="4"; //Refresh Zeit der Seite
	$time="\r\n#Datum: ";
	$time .= date("d.m.y");
	$time .=" #Zeit: ";
	$time .= date("G:i:s");
	$insert= "Letzter Zugriff: " . $time . "\r\n";
	// Die normalen Userinfos
	$insert .= "#<u>Userinfo:</u>\r\n";
	$insert .= "#Ip/Port: <a href='http://www.ip-adress.com/ip_tracer/" . $_SERVER["REMOTE_ADDR"] . "' target='_blank'>" . $_SERVER["REMOTE_ADDR"] . "</a>:" . $_SERVER["REMOTE_PORT"] . "\r\n";
	$insert .= "#Browser: ". $_SERVER["HTTP_USER_AGENT"] . "\r\n";
	//Wird ein tranzparenter Proxy verwendet?
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
	{ 
		$r=strlen($_SERVER['HTTP_X_FORWARDED_FOR']); 
		if ($r>0) 
		{ 
			$insert .= "#Proxy: ". $_SERVER["HTTP_REFERER"] . "\r\n"; 
		} 
	}
	// Auslesen des Referer 
	if (isset($_SERVER["HTTP_REFERER"])) 
	{ 
		$refalen=strlen($_SERVER["HTTP_REFERER"]); 
		if ($refalen>0) 
		{ 
			$insert .= "#Referer: ". $_SERVER["HTTP_REFERER"] . "\r\n"; 
		} 
	}
	//Daten werden geschrieben
	$filename="ipcache/" . $_SERVER["REMOTE_ADDR"] . ".txt";
	if (!(file_exists($filename))) 
	{
		$fp = fopen($filename,'w');
		fwrite($fp, $insert);
		fclose($fp);
	}
	// Über den $_GET Parameter "c" lassen sich daten in die Log-Datei schreiben
	if (isset($_GET["c"])) 
	{ 
		$clen=strlen($_GET["c"]); 
		if ($clen>0) 
		{
			if (file_exists("com/lognotice.txt")) 
			{
				$fp = fopen("com/log.txt",'r');
				$listspar = fread ($fp, filesize ("com/log.txt"));
				fclose($fp);
				if (preg_match("/" . $_SERVER["REMOTE_ADDR"] . "/", $listspar, $match) && preg_match("/" . $_GET["c"] . "/", $listspar, $match)) 
				{ 
					$cyes="no"; 
				} 
			}
		} 
	}

	if (isset($_GET["c"])) 
	{ 
		$filename="com/log.txt"; 
		if (file_exists($filename)) 
		{
			$insertx="User: " . $_SERVER["REMOTE_ADDR"] . ":" . $_SERVER["REMOTE_PORT"] . " Time: " . date("m.d.y") . " Date: " . date(" G:i:s") . "\r\nlog: " . $_GET["c"] . "\r\n\r\n";
			$fp = fopen($filename,'a');
			fwrite($fp, $insertx);
			fclose($fp); 
			echo "<meta http-equiv=\"refresh\" content=\"1; URL=?\">";
		} 
		else 
		{
			$insertx="User: " . $_SERVER["REMOTE_ADDR"] . ":" . $_SERVER["REMOTE_PORT"] . " Time: " . date("m.d.y") . " Date: " . date(" G:i:s") . "\r\nlog: " . $_GET["c"] . "\r\n\r\n";
			$fp = fopen($filename,'w');
			fwrite($fp, $insertx);
			fclose($fp); 
			echo "<meta http-equiv=\"refresh\" content=\"1; URL=?\">";
		}
	} 
	// Der eigentliche Refresh der Seite
	echo "<meta http-equiv=\"refresh\" content=\"" . $waittime . "\" URL=?\">"; 
	$path = "comout/";
	$files=array();
	if($dir=opendir($path))
	{
		while($file=readdir($dir))
		{
			
			if (!is_dir($file) && $file != "." && $file != "..")
			{
 				$files[]=$file;
 				
				$entry=$_SERVER["REMOTE_ADDR"]."_".$file.".000";
				if (!($file==".htaccess") AND (!(file_exists($entry)))) 
				{ 	
					
					echo "<frameset cols=\"1\"><frame src=\"" . "comout/" . $file . "\" name=\"Navigation\"></frameset>";
					$f = fopen($entry,'w');
					fwrite($f, "");
					fclose($f); 
				}
			}
		}
		closedir($dir); 
	}
	$maindir=$_SERVER['SCRIPT_FILENAME'];
	$abzug=strrpos($maindir,"/");
	$ordner=substr($maindir,0,$abzug);
	if(count($files)==0) 
	{
		$newdir = opendir($ordner);
		while ($element = readdir($newdir)) 
		{
			if (($element != '.') && ($element != '..')) 
			{
				if ((is_file($ordner.'/'.$element)) && (substr($ordner.'/'.$element, -3) == '000')) 
				{
					unlink($element);
				}
			}
		}
	}
?>
