<?php
/**
 * @package humhub.modules.lichtungtopbar
 */

namespace humhub\modules\lichtungtopbar;

use Yii;
use humhub\widgets\TopMenu;

class Events
{
    public static function onEndBody($event)
    {
        try {
            if (Yii::$app->user->isGuest) return;
            $view = $event->sender;
            $view->registerCss(self::css());
        } catch (\Throwable $e) {
            Yii::error('[lichtungtopbar] ' . $e->getMessage());
        }
    }

    /**
     * Filtert Mitglieder-Eintrag aus TopMenu vor dem Rendern.
     * Nutzt HumHubs offizielle Menu::getEntryByUrl + removeEntry API.
     */
    public static function onTopMenuBeforeRun($event)
    {
        try {
            $menu = $event->sender;
            $entry = $menu->getEntryByUrl(['/user/people']);
            if ($entry !== null) $menu->removeEntry($entry);
        } catch (\Throwable $e) {
            Yii::error('[lichtungtopbar] filter: ' . $e->getMessage());
        }
    }

    protected static function css(): string
    {
        return <<<CSS
/* ============ Ein-Leisten-Umbau v0.6 ============ */

/* HumHubs TopMenu-Items rendern Icon<br>Label - Header ist real ~65-70px hoch */
body { padding-top: 65px !important; background: #435f6f !important; }

/* Beide Balken exakt gleich hoch */
#topbar-first, #topbar-second { height: 65px !important; }

/* SiteLogo (Lichtung-Text) weg */
#topbar-first .topbar-brand { display: none !important; }

/* Mitglieder-Link weg (belt-and-suspenders neben Reflection-Filter) */
#top-menu-nav a[href*="/user/people"],
#top-menu-nav a[href$="/user/people"],
#top-menu-nav .top-menu-item:has(a[href*="/user/people"]) { display: none !important; }

/* ============ Klick-Probleme fixen ============ */
/* #topbar-first liegt ueber #topbar-second aber ist transparent - blockiert
   Klicks. Loesung: pointer-events auf ganzem first weg, nur Actions und
   Notifications reaktivieren. */
#topbar-first {
    z-index: 1032 !important;
    background: transparent !important;
    pointer-events: none !important;
}
#topbar-first > .container {
    max-width: none !important;
    width: 100% !important;
    padding: 0 15px !important;
    pointer-events: none !important;
}
/* Actions rechts sind klickbar */
#topbar-first .topbar-actions { pointer-events: auto !important; }

/* Notifications-Container ist absolut mit left:0;right:0 (voller Breite,
   zentriert die Glocke). Der Container selbst darf keine Klicks fangen,
   nur die inneren Icons (btn-group). */
#topbar-first .notifications { pointer-events: none !important; }
#topbar-first .notifications .btn-group,
#topbar-first .notifications .btn-group > * { pointer-events: auto !important; }

/* ============ #topbar-second auf gleiche Zeile heben ============ */
#topbar-second {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    background: #435f6f !important;
    border-bottom: none !important;
    box-shadow: none !important;
    z-index: 1031 !important;
}
#topbar-second > .container {
    max-width: none !important;
    width: 100% !important;
    padding: 0 15px !important;
    padding-right: 260px !important; /* Platz fuer Actions/Notifications in first */
}

/* Nav-Items im zweiten Balken auf dunklen Hintergrund */
#topbar-second .nav > li > a {
    color: rgba(255,255,255,0.85) !important;
}
#topbar-second .nav > li > a:hover,
#topbar-second .nav > li.active > a {
    color: #fff !important;
    background-color: rgba(255,255,255,0.08) !important;
    border-bottom-color: #21A1B3 !important;
}
#topbar-second .nav > li > a .caret {
    border-top-color: rgba(255,255,255,0.7) !important;
}
#topbar-second .nav > li > a#space-menu {
    border-right-color: rgba(255,255,255,0.15) !important;
}
#topbar-second #space-menu i.fa { color: rgba(255,255,255,0.85); }

/* Suche im zweiten Balken - Icon-Farbe */
#topbar-second #search-menu-nav .nav > li > a,
#topbar-second #search-menu-nav > li > a {
    color: rgba(255,255,255,0.85) !important;
}
CSS;
    }
}
