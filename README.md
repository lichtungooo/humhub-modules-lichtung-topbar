# HumHub Lichtung Topbar-Filter

Filtert unerwünschte Menüpunkte aus der Lichtung-Topbar. Aktuell: **Mitglieder** (`/user/people`) wird ausgeblendet. Ergänzt den [LichtungTheme](https://github.com/lichtungooo/humhub-theme-lichtung).

## Setup

HumHub 1.16+. Modul-Verzeichnis nach `protected/modules/lichtungtopbar/` legen, im Admin-Panel aktivieren. Keine Datenbank-Tabellen, keine Migrationen.

## Filter erweitern

Weitere URLs in [`Events.php`](Events.php) → `shouldHide()` eintragen.

## Lizenz

MIT
