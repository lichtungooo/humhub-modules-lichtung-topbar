<?php
/**
 * Lichtung Topbar-Filter — Config
 *
 * @package humhub.modules.lichtungtopbar
 */

use humhub\widgets\TopMenu;

return [
    'id' => 'lichtungtopbar',
    'class' => 'humhub\modules\lichtungtopbar\Module',
    'namespace' => 'humhub\modules\lichtungtopbar',
    'events' => [
        [TopMenu::class, TopMenu::EVENT_BEFORE_RUN, ['\humhub\modules\lichtungtopbar\Events', 'onTopMenuRun']],
    ],
];
