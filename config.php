<?php
/**
 * Lichtung Topbar - Filter-Modul, ergaenzt LichtungTheme.
 *
 * @package humhub.modules.lichtungtopbar
 */

use humhub\widgets\TopMenu;

return [
    'id' => 'lichtungtopbar',
    'class' => 'humhub\modules\lichtungtopbar\Module',
    'namespace' => 'humhub\modules\lichtungtopbar',
    'events' => [
        [TopMenu::class, TopMenu::EVENT_RUN, ['\humhub\modules\lichtungtopbar\Events', 'onTopMenuBeforeRun']],
    ],
];
