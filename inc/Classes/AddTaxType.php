<?php
namespace Inc\Classes;

class AddTaxType
{
    public $custom_post_types = array();

    public function register()
    {

        add_action('init', array($this, 'crunchify_create_deals_custom_taxonomy'), 0);

    }

//create a custom taxonomy name it "type" for your posts
    public function crunchify_create_deals_custom_taxonomy()
    {

        $labels = array(
            'name' => _x('KUtypes', 'taxonomy general name'),
            'singular_name' => _x('Type', 'taxonomy singular name'),
            'search_items' => __('Search Types'),
            'all_items' => __('All Types'),
            'parent_item' => __('Parent Type'),
            'parent_item_colon' => __('Parent Type:'),
            'edit_item' => __('Edit Type'),
            'update_item' => __('Update Type'),
            'add_new_item' => __('Add New Type'),
            'new_item_name' => __('New Type Name'),
            'menu_name' => __('Types'),
        );

        register_taxonomy('kutypes', array('ausstellungen'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'kutype'),

        ));
    }

}
