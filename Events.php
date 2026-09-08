<?php
/**
 * @package humhub.modules.lichtungtopbar
 */

namespace humhub\modules\lichtungtopbar;

use Yii;

class Events
{
    /**
     * Filtert Mitglieder-Eintrag aus TopMenu vor dem Rendern.
     * Nutzt HumHubs offizielle Menu::getEntryByUrl + removeEntry API.
     * TopMenu::EVENT_RUN feuert IN run() als erstes (nicht EVENT_BEFORE_RUN,
     * das feuert nur beim begin/end-Pattern, nicht bei ::widget()).
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
}
