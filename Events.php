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
     * Filtert unerwuenschte Items aus TopMenu bevor gerendert wird.
     * (CSS-Fallback in css() sichert nach, falls Reflection versagt.)
     */
    public static function onTopMenuBeforeRun($event)
    {
        try {
            $menu = $event->sender;
            $ref = new \ReflectionClass($menu);
            $prop = $ref->getProperty('items');
            $prop->setAccessible(true);
            $items = $prop->getValue($menu);
            if (!is_array($items)) return;
            $filtered = [];
            foreach ($items as $item) {
                $url = self::urlOf($item);
                if (strpos($url, '/user/people') !== false) continue;
                $filtered[] = $item;
            }
            $prop->setValue($menu, $filtered);
        } catch (\Throwable $e) {
            Yii::error('[lichtungtopbar] filter: ' . $e->getMessage());
        }
    }

    protected static function urlOf($item): string
    {
        if (is_array($item)) {
            if (isset($item['url'])) {
                if (is_string($item['url'])) return $item['url'];
                if (is_array($item['url']) && isset($item['url'][0])) return (string)$item['url'][0];
            }
            return '';
        }
        if (is_object($item)) {
            if (method_exists($item, 'getUrl')) {
                $u = $item->getUrl();
                if (is_string($u)) return $u;
                if (is_array($u) && isset($u[0])) return (string)$u[0];
            }
        }
        return '';
    }

    protected static function css(): string
    {
        return <<<CSS
/* ============ Ein-Leisten-Umbau v0.4 ============ */

body { padding-top: 50px !important; }

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
#topbar-first .topbar-actions,
#topbar-first .notifications {
    pointer-events: auto !important;
}

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
