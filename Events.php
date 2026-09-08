<?php
/**
 * @package humhub.modules.lichtungtopbar
 */

namespace humhub\modules\lichtungtopbar;

use Yii;

class Events
{
    /**
     * Entfernt unerwuenschte Items aus der TopMenu-Leiste, bevor sie gerendert werden.
     * Kandidaten: /user/people (Mitglieder-Liste)
     */
    public static function onTopMenuRun($event)
    {
        try {
            $menu = $event->sender;
            $items = $menu->getItems();
            $filtered = [];
            foreach ($items as $item) {
                if (self::shouldHide($item)) continue;
                $filtered[] = $item;
            }
            // Menu::items ist protected; ueber Reflection setzen
            $ref = new \ReflectionClass($menu);
            $prop = $ref->getProperty('items');
            $prop->setAccessible(true);
            $prop->setValue($menu, $filtered);
        } catch (\Throwable $e) {
            Yii::error('[lichtungtopbar] ' . $e->getMessage());
        }
    }

    protected static function shouldHide(array $item): bool
    {
        $url = self::urlOf($item);
        if ($url === '') return false;
        // Ausblenden: /user/people (Mitglieder)
        if (strpos($url, '/user/people') !== false) return true;
        return false;
    }

    protected static function urlOf(array $item): string
    {
        if (isset($item['url'])) {
            if (is_string($item['url'])) return $item['url'];
            if (is_array($item['url']) && isset($item['url'][0])) return (string)$item['url'][0];
        }
        return '';
    }
}
