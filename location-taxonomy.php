
<?php
/**
* @wordpress-plugin
* Plugin Name: Location Taxonomy
* Plugin URI: http://github.com/shortlist-digital/location-taxonomy
* Description: Add a location taxonomy to a WordPress site
* Version: 1.0.0
* Author: Shortlist Studio
* Author URI: http://shortlist.studio
* License: MIT
*/
class LocationTaxonomy
{
    public function __construct()
    {
        add_action('init', array($this, 'register_custom_taxonomy'));
    }

    public function register_custom_taxonomy()
    {
        $labels = array(
         'name'                       => _x('Locations', 'Taxonomy General Name', 'text_domain'),
         'singular_name'              => _x('Location', 'Taxonomy Singular Name', 'text_domain'),
         'menu_name'                  => __('Locations', 'text_domain'),
         'all_items'                  => __('All Locations', 'text_domain'),
         'parent_item'                => __('Parent Location', 'text_domain'),
         'parent_item_colon'          => __('Parent Location:', 'text_domain'),
         'new_item_name'              => __('New Location Name', 'text_domain'),
         'add_new_item'               => __('Add New Location', 'text_domain'),
         'edit_item'                  => __('Edit Location', 'text_domain'),
         'update_item'                => __('Update Location', 'text_domain'),
         'view_item'                  => __('View Location', 'text_domain'),
         'separate_items_with_commas' => __('Separate locations with commas', 'text_domain'),
         'add_or_remove_items'        => __('Add or remove locations', 'text_domain'),
         'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
         'popular_items'              => __('Popular Locations', 'text_domain'),
         'search_items'               => __('Search Locations', 'text_domain'),
         'not_found'                  => __('Not Found', 'text_domain'),
         'no_terms'                   => __('No locations', 'text_domain'),
         'items_list'                 => __('Locations list', 'text_domain'),
         'items_list_navigation'      => __('Locations list navigation', 'text_domain'),
       );
        $args = array(
         'labels'                     => $labels,
         'hierarchical'               => true,
         'public'                     => true,
         'show_ui'                    => true,
         'show_admin_column'          => true,
         'show_in_nav_menus'          => true,
         'show_tagcloud'              => false,
         'rewrite'                      => true
       );
        register_taxonomy('location', array( 'post', ' category', ' page' ), $args);
    }
}
new LocationTaxonomy();
