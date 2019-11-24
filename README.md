# Template Engine

Eingene Template Engine um Variablen, Include und Kommentartags aus Templates zu entfernen und mit Inhalten zu füllen.

### Functions

Default Path für Templates lautet "templates/" - falls ein anderer Pfad gewünscht ist,
ist es Möglich diesen als String zu übergeben.
Bsp.:
```
$tplDir = "Alt/Pfad/";
$obj = new Template_Engine($tplDir);
```

Um eine Template zu laden, wird die Funktion load($file) genutzt.
Bsp.:
```
$file = "name.ext";
$obj->load($file);
```

Um in der geladenen Template die Tags zu ersetzen wird die Funktion assign($replace, $replacement) genutzt
Bsp.:
```
$replace = "tag_name";
$replacement = "content";
$obj->assign($replace, $replacement);
```

Private Funktion parseFunctions() wird von load($file) aufgerufen.
Die Funktion fügt weitere Templates hinzu, die von der geladenen Template benötigt werden.
Ebenso entfernt diese Funktion alle Templatekommentare,
in Form von {* kommetar \*}, aus den Templates.
![Vorher](https://github.com/kunzalexander/img/blob/master/vorher.png)
![Nachher](https://github.com/kunzalexander/img/blob/master/nachher.png)


Um die fertige Template abschließend auszugeben wird die Funktion display() genutzt.
Bsp.:
```
$obj->display();
```


### Built with

Derzeit nur unter nutzung von [PHP](https://www.php.net)
