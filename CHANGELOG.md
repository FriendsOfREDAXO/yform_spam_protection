# Changelog - yform spam protetion

## [2.0.0] - 2022-xx-xx

>**Hinweis**: Das YForm-Value muss in seiner Schreibweise angepasst werden. Neu ist die optionale Angabe eines E-Mail-Feldnamens, wenn bestimmte Endungen blockiert werden sollen, bspw. `.ru`

### Neue Features

* Log der aktuellen Sperren einsehbar
* Optionales Blockieren von unerwünschten Mail-Adressen in Kontaktformularen (TLD-Sperre)
* optionales Whitelisting von statischen oder lokalen IPs
* optionales Whitelisting von eingeloggten YCom-Benutzern
* Detaillierte Ausgabe von Fehlermeldungen über das Addon Sprog möglich

### Verbesserungen

* Überarbeitete Einstellungsseite
* Einstellungen und Log sind jetzt auch für andere Benutzer-Rollen verfügbar, z.B. Redakteure
* Eigene `spam_protection`-Klasse auf Basis von YForm, mit der die Validierung auch außerhalb von YForm nutzbar wird oder eigene und einzelne Prüfungen abgeleitet werden können:

```php
$ip_dataset = spam_protection::check($ip, $debug = false, $form_microtime = null, $session_microtime = null, $honeypot_value = '', $email = '');

/* Fehlermeldeungen erhalten */
if ($ip_dataset->getValue('reason')) {
    // Fehlermeldung ausgeben, eigene Skriptverarbeitung stoppen 
}
```

## [1.1.0] - 2021-04-26

### Feature

* Classic Template hinzugefügt (vorher hat SpamProtection nur mit dem yform Bootstrap Template funktioniert) @NGWNGW

---

## [1.0.6] - 2021-03-28

### Bugfix

* AddOn jetzt auch mit PHP 8.x installierbar @ritzfritz
* `$session_timestamp` ist jetzt int - Warning wurde damit unterdrückt @tobiaskrais

### Doku

* Schreibfehler behoben, Tabellenname angepasst @aeberhard

---

## [1.0.5] - 2020-08-28

* Diverse kleinere Bugfixes
* Bugfix honeypot (wurde in manchen Fällen nicht richtig ausgeblendet)
* Felder die noch nicht funktionieren ausgeblendet
* Aktueller Entwicklungsstand aus master ausgelagert (alexplusde_1.0.5-dev)
