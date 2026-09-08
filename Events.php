<?php
/**
 * @package humhub.modules.lichtungtopbar
 */

namespace humhub\modules\lichtungtopbar;

use Yii;

class Events
{
    /**
     * Injiziert CSS + JS die HumHubs zwei Topbars zu einer verschmelzen,
     * SiteLogo ausblenden und Mitglieder-Link ausblenden.
     *
     * Kein Layout-Override, kein Reflection. Nur DOM-Move nach initialem Render.
     * HumHub-Widgets bleiben strukturell unangetastet.
     */
    public static function onEndBody($event)
    {
        try {
            if (Yii::$app->user->isGuest) return;

            $view = $event->sender;
            $view->registerCss(self::css());
            $view->registerJs(self::js(), \yii\web\View::POS_END);
        } catch (\Throwable $e) {
            Yii::error('[lichtungtopbar] ' . $e->getMessage());
        }
    }

    protected static function css(): string
    {
        return <<<CSS
/* Vor dem Merge: beide Topbars verstecken um Flackern zu vermeiden */
body:not(.lichtung-merged) #topbar-first,
body:not(.lichtung-merged) #topbar-second { visibility: hidden; }

/* Nach dem Merge: zweite Zeile weg, SiteLogo weg, Mitglieder-Link weg */
body.lichtung-merged #topbar-second { display: none !important; }
body.lichtung-merged .topbar-brand { display: none !important; }
body.lichtung-merged nav a[href*="/user/people"],
body.lichtung-merged #top-menu-nav > li > a[href*="/user/people"] { display: none !important; }

/* Nav-UL vom zweiten Balken sinnvoll positionieren wenn es in erstem Balken landet */
body.lichtung-merged #topbar-first .container { display: flex; align-items: center; }
body.lichtung-merged #topbar-first #top-menu-nav {
    display: flex; align-items: center; margin: 0; padding: 0;
    list-style: none;
}
body.lichtung-merged #topbar-first #search-menu-nav {
    margin-left: auto;
    display: flex; align-items: center;
}
body.lichtung-merged #topbar-first .topbar-actions,
body.lichtung-merged #topbar-first .notifications { order: 10; }
body.lichtung-merged #topbar-first #top-menu-nav { order: 2; }
body.lichtung-merged #topbar-first #search-menu-nav { order: 5; }
CSS;
    }

    protected static function js(): string
    {
        return <<<JS
(function() {
    function merge() {
        var firstContainer = document.querySelector('#topbar-first > .container');
        var secondContainer = document.querySelector('#topbar-second > .container');
        if (!firstContainer || !secondContainer) return;
        var kids = Array.prototype.slice.call(secondContainer.children);
        kids.forEach(function(el) { firstContainer.appendChild(el); });
        document.body.classList.add('lichtung-merged');
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', merge);
    } else {
        merge();
    }
})();
JS;
    }
}
