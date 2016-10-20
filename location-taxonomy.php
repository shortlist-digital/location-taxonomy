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
        // add_filter('post_link', array($this, 'location_permalink'), 10, 3);
        // add_filter('post_type_link', array($this, 'location_permalink'), 10, 3);
        add_filter('timber_context', array($this, 'add_location_to_context'), 10, 3);
        add_filter('agreable_base_theme_category_widgets_acf', array($this, 'apply_acf_to_location'), 10, 1);
        add_filter('agreable_base_theme_social_media_acf', array($this, 'apply_acf_to_location'), 10, 1);
        add_filter('agreable_base_theme_html_overrides_acf', array($this, 'apply_acf_to_location'), 10, 1);
        add_filter('admin_menu', array($this, 'remove_location_box'), 10, 1);
        add_Filter('agreable_base_theme_article_basic_acf', array($this, 'add_nice_location_selector'), 10, 2);
    }

    public function remove_location_box()
    {
        remove_meta_box('locationdiv', 'post', 'normal');
    }

    public function add_nice_location_selector($acf_fields, $key)
    {
        $locationSelector = array(
            'key' => $key . '_location',
            'label' => 'Location',
            'name' => 'location',
            'type' => 'taxonomy',
            'instructions' => 'Select a location for this content to live under',
            'required' => 1,
            'taxonomy' => 'location',
            'field_type' => 'select',
            'allow_null' => 0,
            'add_term' => 0,
            'save_terms' => 1,
            'load_terms' => 1,
            'return_format' => 'object',
            'multiple' => 0,
        );
        array_push($acf_fields['fields'], $locationSelector);
        return $acf_fields;
    }

    public function apply_acf_to_location($acf_fields)
    {
        array_push($acf_fields['location'], array(
            array(
                'param' => 'taxonomy',
                'operator' => '==',
                'value' => 'location',
            ),
        ));
        return $acf_fields;
    }

    public function add_location_to_context($context)
    {
        global $post;
        if ($post) {
            $context['locations'] = get_the_terms($post->ID, 'location');
        }
        return $context;
    }

    public function location_permalink($permalink, $post_id, $leavename)
    {
        // No location in the permalink so bail out
        if (strpos($permalink, '%location%') === false) {
            return $permalink;
        }

        // If no post is returned for some reason bail out
        $post = get_post($post_id);
        if (!$post) {
            return $permalink;
        }


        // Tryr and get value of the 'location' field
        $terms = wp_get_object_terms($post->ID, 'location');
        if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
            $taxonomy_slug = $terms[0]->slug;
        } else {
            // set a default if one isn't set.
            $taxonomy_slug = 'uk';
        }

        return str_replace('%location%', $taxonomy_slug, $permalink);
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
            'rewrite'                      => array(
                // 'slug' => '/',
                'with_front' => false
            ),
            'show_in_rest'       => true,
            'rest_base'          => 'locations',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        );
        register_taxonomy('location', array( 'post' ), $args);
    }
}
new LocationTaxonomy();
