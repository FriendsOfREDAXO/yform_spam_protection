# Spamschutz für REDAXO 5 YForm 3.x

Das Addon `yform_spam_protection` kombiniert verschiedene Maßnahmen, um zuverlässig **Spam und Bots abzuwehren**. Die Einrichtung ist in **weniger als 5 Minuten** erledigt.

## Features

* **Einfache Integration** - nur eine Zeile Code in das YForm-Formular einfügen.
* **Bestmögliche Usability** – keine zusätzlichen Eingaben vom Benutzer notwendig. 
* **Barrierefrei** – ohne Rechenaufgabe oder Bild-Captcha. 
* **DSGVO-konform** – sofern keine Konfiguration mit externen Anbietern gewählt wird.
* **Mehrsprachig** - Die Fehlermeldung kann je Sprache durch Addons wie Sprog oder XOutputFilter angepasst werden.

Weitere geplante Features unter [https://github.com/FriendsOfREDAXO/yform_spam_protection/issues](https://github.com/FriendsOfREDAXO/yform_spam_protection/issues)

## Installation

* Im REDAXO-Backend unter `Installer` abrufen und
* anschließend unter `Hauptmenü` > `AddOns` installieren.
* Den gewünschten YForm-Formularen das Feld `spam_protection` hinzufügen:

**PHP-Schreibweise**
```php
        $yform->setValueField('spam_protection', array("honeypot","Bitte nicht ausfüllen.","Ihre Anfrage wurde als Spam erkannt und gelöscht. Bitte versuchen Sie es in einigen Minuten erneut oder wenden Sie sich persönlich an uns.", 0));
```

**Pipe-Schreibweise**
```
spam_protection|honeypot|Bitte nicht ausfüllen|Ihre Anfrage wurde als Spam erkannt und gelöscht. Bitte versuchen Sie es in einigen Minuten erneut oder wenden Sie sich persönlich an uns.|0
```

> Hinweis: Bei der Installation wird folgende Datenbanktabelle erstellt: `rex_yform_spam_protection_frequency`. Diese beinhaltet die IP-Adresse des Besuchers und löscht diese nach Ablauf eines in den Einstellungen festgelegten Zeitraums.

## Funktionsweise

### Timer
Spambots füllen das Formular in der Regel schneller als ein Mensch aus. Beim 1. Aufruf des Formulars sowie bei jeder weiteren Validierung wird ein Zeitstempel gespeichert. 

Wenn der vorgegebene Zeitwert unterschritten wird (z. B. `5 Sekunden`), dann deutet dies auf einen Spambot hin. Die Validierung schlägt dann fehl.

> Tipp: Zum Testen/Debuggen können die Timer auf einen wesentlich höheren Wert gestellt werden, bspw. 30 Sekunden.

### IP-Sperre
Wird das Formular innerhalb weniger Minuten mehrfach von derselben IP abgesendet, deutet dies auf einen Spambot hin, der möglichst viele Abfragen absenden möchte. Übliches Verhalten ist, im Minutentakt Anfragen abzusenden.

Wenn vorgegebene Limits überschritten werden (z. B. `5` Anfragen in `300 Sekunden`), deutet dies auf einen Spambot hin. Die Validierung schlägt dann fehl.

Die IPs werden temporär gespeichert und nach Ablauf des vorgegebenen Limits (z. B. `300 Sekunden`) gelöscht.

### Honeypot 
Dem Formular wird ein per CSS für Menschen - jedoch nicht für Bots - ausgeblendetes Eingabefeld angeboten. Spambots sind dazu geneigt, jedes Feld auszufüllen.

Wenn das Feld ausgefüllt wurde, deutet dies auf einen Spambot hin. Die Validierung schlägt dann fehl.

> Tipp: Die Umsetzung ist barrierefrei, da Autofill für dieses Feld deaktiviert wurde und dadurch sichergestellt ist, dass Screenreader oder unerfahrene Internetnutzer dieses Feld nicht versehentlich ausfüllen.

### GeoIP-Sperre (geplant)

Besucher-IP-Adressen, die nicht in den erwarteten Haupt- und Zielregionen liegen, sind mit hoher Wahrscheinlichkeit Spambots.

> Hinweis: Es ist jedoch nicht auszuschließen, dass durch VPN-Verbindungen oder Besucher im Urlaub oder auf Geschäftsreise dadurch versehentlich ausgesperrt werden. Diese Option sollte nur in Ausnahmefällen aktiviert werden.

### Top-Level-Domain-Sperre (geplant)

Bei Formularen mit Eingabe der E-Mail-Adresse, bspw. einem Kontaktformular, können Absender gesperrt werden, deren Top-Level-Domain ungewöhnlich erscheinen.

> Hinweis:  Es ist jedoch nicht auszuschließen, dass Kontakte im Ausland dadurch versehentlich ausgesperrt werden. Diese Option sollte nur in Ausnahmefällen aktiviert werden.

### Sprach-Sperre (geplant)

Bei Formularen mit Eingabe einer Nachricht, bspw. einem Kontaktformular, können Absender gesperrt werden, die sich in einer unerwartetten Sprache melden: Bspw. `englisch`, wenn die Firma nur eine deutschsprachige Website betreibt, oder `russisch`, obwohl die Website sich nicht an Besucher au0erhalb der EU richtet. 

> Hinweis:  Es ist jedoch nicht auszuschließen, dass Kontakte im Ausland dadurch versehentlich ausgesperrt werden. Diese Option sollte nur in Ausnahmefällen aktiviert werden.

## Einstellungen

* Im REDAXO-Backend unter `YForm` > `Spamschutz` optional Einstellungen vornehmen.

## Achtung
Einige Einstellungen haben aktuell noch keine Funktion. Beteilige dich an der Umsetzung unter [https://github.com/FriendsOfREDAXO/yform_spam_protection/issues](https://github.com/FriendsOfREDAXO/yform_spam_protection/issues) oder unterstütze den Initiator finanziell für die Weiterentwicklung und Wartung: [https://github.com/sponsors/alexplusde](https://github.com/sponsors/alexplusde)

## Tipps und Tricks

Der 4. Parameter am Feld aktiviert den Debug-Modus

## Lizenz

[MIT Lizenz](LICENSE.md)

## Autor

* [@alexplusde](https://github.com/sponsors/alexplusde) (Initiator)
