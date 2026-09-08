<?php
/**
 * Lichtung Topbar - Config
 *
 * @package humhub.modules.lichtungtopbar
 */

use humhub\modules\ui\view\components\View;

return [
    'id' => 'lichtungtopbar',
    'class' => 'humhub\modules\lichtungtopbar\Module',
    'namespace' => 'humhub\modules\lichtungtopbar',
    'events' => [
        [View::class, View::EVENT_END_BODY, ['\humhub\modules\lichtungtopbar\Events', 'onEndBody']],
    ],
];
