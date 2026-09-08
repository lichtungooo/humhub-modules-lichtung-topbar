<?php
/**
 * @package humhub.modules.lichtungtopbar
 */

namespace humhub\modules\lichtungtopbar;

use Yii;

class Events
{
    /**
     * Nur CSS-Injection - kein DOM-Move, kein Layout-Override.
     * Positioniert #topbar-second per CSS absolut auf top:0 zwischen
     * dem Brand-Bereich und den Actions von #topbar-first. Optisch eine Zeile.
     *
     * Vorteile: HumHub-Widgets bleiben in ihrem gewohnten Kontext,
     * Bootstrap-Dropdowns funktionieren, TopNavigation-Overflow-JS misst
     * die korrekte Container-Breite.
     */
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

    protected static function css(): string
    {
        return <<<CSS
/* ============ Ein-Leisten-Umbau: nur CSS ============ */

/* Body-Padding fuer nur eine Zeile (statt 100px) */
body { padding-top: 50px !important; }

/* SiteLogo (Lichtung-Text) links im ersten Balken weg */
#topbar-first .topbar-brand { display: none !important; }

/* Mitglieder-Menuepunkt im TopMenu ausblenden */
#top-menu-nav a[href*="/user/people"] { display: none !important; }
#top-menu-nav > li:has(a[href*="/user/people"]) { display: none !important; }

/* Zweiten Balken auf die gleiche Zeile heben */
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

/* Container muss Platz lassen fuer Actions/Notifications rechts */
#topbar-second > .container {
    padding-right: 260px !important;
}

/* Nav-Items im zweiten Balken auf dunklen Hintergrund anpassen */
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

/* Space-Chooser-Icon vor "Meine Spaces" grade halten */
#topbar-second #space-menu i.fa { color: rgba(255,255,255,0.85); }

/* Actions/Notifications im ersten Balken sitzen ueber dem zweiten */
#topbar-first {
    z-index: 1032 !important;
    background: transparent !important;
}
#topbar-first > .container {
    pointer-events: none;
}
#topbar-first .topbar-actions,
#topbar-first .notifications {
    pointer-events: auto;
}

/* Suche im zweiten Balken (rechts) - Icon-Farbe */
#topbar-second #search-menu-nav .nav > li > a {
    color: rgba(255,255,255,0.85) !important;
}
CSS;
    }
}
