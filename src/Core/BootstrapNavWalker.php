<?php

namespace Forme\Framework\Core;

use Walker_Nav_Menu;

/**
 * Bootstrap 5 nav walker from https://github.com/AlexWebLab/bootstrap-5-wordpress-navbar-walker.
 *
 * @deprecated use Forme\Framework\Models\MenuItem instead
 */
class BootstrapNavWalker extends Walker_Nav_menu
{
    private $current_item;
    private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end',
  ];

    protected $liClasses                    = 'nav-item';
    protected $hasChildrenAClasses          = 'dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
    protected $hasChildrenLiClasses         = 'dropdown';
    protected $hasChildrenAndDepthLiClasses = 'dropdown-menu dropdown-menu-end';

    /**
     * This gives us a way of accessing protected attributes from outside of this class
     * This is specifically for the above css class attributes
     * We can do setLiClasses('foo'), setHasChildrenAClasses('bar baz')
     * etc.
     * Todo: improve this api, it's a little clunky on top of the WP nav walker one, which is itself pretty horrible.
     */
    public function __call($method, $arguments)
    {
        if (str_starts_with($method, 'set')) {
            $attributeName = lcfirst(substr($method, 3));

            $this->$attributeName = $arguments[0];
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $dropdown_menu_class[] = '';
        foreach ($this->current_item->classes as $class) {
            if (in_array($class, $this->dropdown_menu_alignment_values)) {
                $dropdown_menu_class[] = $class;
            }
        }
        $indent  = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(' ', $dropdown_menu_class)) . " depth_$depth\">\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $this->current_item = $item;

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $li_attributes = '';
        $class_names   = $value   = '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;

        $classes[] = ($args->walker->has_children) ? $this->hasChildrenLiClasses : '';
        $classes[] = $this->liClasses;
        $classes[] = 'nav-item-' . $item->ID;
        if ($depth && $args->walker->has_children) {
            $classes[] = $this->hasChildrenAndDepthLiClasses;
        }

        $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $active_class   = ($item->current || $item->current_item_ancestor || in_array('current_page_parent', $item->classes, true) || in_array('current-post-ancestor', $item->classes, true)) ? 'active' : '';
        $nav_link_class = ($depth > 0) ? 'dropdown-item ' : 'nav-link ';
        $attributes .= ($args->walker->has_children) ? ' class="' . $nav_link_class . $active_class . ' ' . $this->hasChildrenAClasses : ' class="' . $nav_link_class . $active_class . '"';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
