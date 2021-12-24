<?php
declare(strict_types=1);

namespace Forme\Framework\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MenuItem extends Post
{
    public function childItems(): Collection
    {
        $type = $this->menuType;

        $collection = self::with('meta')->whereHas('meta', function (Builder $query) {
            $query->where('meta_key', '_menu_item_menu_item_parent')->where('meta_value', (string) $this->ID);
        })->get();

        return self::attachWPMenuItemData($collection, $type);
    }

    public static function getByMenuType(string $type): ?Collection
    {
        $indexedItems = self::getIndexedWPNavMenuItems($type);

        // bail if null
        if (!$indexedItems) {
            return null;
        }
        // convert to an array of ids
        $itemIds = array_keys($indexedItems);

        $collection = self::with('meta')->whereHas('meta', function (Builder $query) {
            $query->where('meta_key', '_menu_item_menu_item_parent')->where('meta_value', '0');
        })->find($itemIds);

        return self::attachWPMenuItemData($collection, $type);
    }

    private static function attachWPMenuItemData(Collection $collection, string $type): ?Collection
    {
        $indexedItems = self::getIndexedWPNavMenuItems($type);

        // bail if null
        if (!$indexedItems) {
            return null;
        }

        return $collection->map(function ($item) use ($indexedItems, $type) {
            $item->menuType = $type;
            $WPMenuItem = $indexedItems[$item->ID];
            $item->title = $WPMenuItem->title;
            $item->url = $WPMenuItem->url;
            $item->classes = $WPMenuItem->classes;
            $item->target = $WPMenuItem->target;
            $item->description = $WPMenuItem->description;
            $item->wordPressMenuItem = $indexedItems[$item->ID];

            return $item;
        });
    }

    private static function getIndexedWPNavMenuItems(string $type): ?array
    {
        // Get the nav menu based on the requested menu.
        $menu = wp_get_nav_menu_object($type);

        // Get the nav menu based on the theme_location.
        $locations = get_nav_menu_locations();
        if (!$menu && isset($locations[$type])) {
            $menu = wp_get_nav_menu_object($locations[$type]);
        }

        /*
         * If no menu was found: bail.
         */
        if (!$menu) {
            return null;
        }

        // If the menu exists, get its items.
        $items = wp_get_nav_menu_items($menu->term_id, ['update_post_term_cache' => false]) ?? null;

        $indexedItems = [];
        foreach ($items as $item) {
            $indexedItems[$item->ID] = $item;
        }
        array_walk($items, function ($item) use ($indexedItems) {
            $indexedItems[$item->ID] = $item;
        });

        return $indexedItems;
    }
}
