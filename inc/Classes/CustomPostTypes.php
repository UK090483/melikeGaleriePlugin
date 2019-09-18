<?php
namespace Inc\Classes;

class CustomPostTypes
{
    public $custom_post_types = array();

    public function register()
    {

        $this->custom_post_types = array(
            array(
                'name' => 'artists',
                'singular_name'=> 'artist',
                'label' => 'Artists',
              

            ),
            array(
                'name' => 'ausstellungen',
                'singular_name'=> 'ausstellung',
                'label' => 'Ausstellungen',
                'taxonomies' => array('kutypes'),
            ),
            array(
                'name' => 'projekte',
                'singular_name'=> 'projekt',
                'label' => 'Projekte',
            ),

        );

        if (!empty($this->custom_post_types)) {
            add_action('init', array($this, 'registerCustomPostTypes'));
        }
    }

    public function registerCustomPostTypes()
    {
        foreach ($this->custom_post_types as $post_type) {

            register_post_type($post_type['name'],
                array(
                    'labels' => array(
                        'name' => $post_type['label'],
                        'singular_name' => (isset($post_type['singular_name']) ? $post_type['singular_name'] : $post_type['name']),
                        'menu_name' => (isset($post_type['menu_name']) ? $post_type['menu_name'] : $post_type['label']),
                        'name_admin_bar' => (isset($post_type['name_admin_bar']) ? $post_type['name_admin_bar'] : $post_type['name']),
                        'archives' => (isset($post_type['archives']) ? $post_type['archives'] : ucfirst($post_type['singular_name']) . ' Archives'),
                        'attributes'            => (isset($post_type['attributes']) ? $post_type['attributes'] : ucfirst($post_type['singular_name']) . ' Attributes'),
                    // 'parent_item_colon'     => $post_type['parent_item_colon'] || 'parent' . $post_type['name'] ,
                        'all_items'             => (isset($post_type['all_items']) ? $post_type['all_items'] : 'All ' . $post_type['name']),
                        'add_new_item' => (isset($post_type['add_new_item']) ? $post_type['add_new_item'] : ('Add New' . ucfirst($post_type['singular_name']))),
                        'add_new'               => 'Add New',
                        'new_item' => (isset($post_type['new_item']) ? $post_type['new_item'] : 'New' . $post_type['label']),
                        'edit_item'             => (isset($post_type['edit_item']) ? $post_type['edit_item'] : 'New ' . ucfirst($post_type['singular_name'])),
                        // 'update_item'           => $post_type['update_item'] || 'Update' . $post_type['name'],
                        // 'view_item'             => $post_type['view_item'] || 'Update' . $post_type['name'],
                        // 'view_items'            => $post_type['view_items'] || 'Update' . $post_type['name'],
                        // 'search_items'          => $post_type['search_items'] || 'Update' . $post_type['name'],
                        // 'not_found'             => $post_type['not_found'] || 'Update' . $post_type['name'],
                        // 'not_found_in_trash'    => $post_type['not_found_in_trash'] || 'Update' . $post_type['name'],
                        // 'featured_image'        => $post_type['featured_image'] || 'Featured Image',
                        // 'set_featured_image'    => $post_type['set_featured_image'] || 'Set Featured Image',
                        // 'remove_featured_image' => $post_type['remove_featured_image'] || 'Remove Featured Image',
                        // 'use_featured_image'    => $post_type['use_featured_image'] || 'Use Featured Image',
                        // 'insert_into_item'      => $post_type['insert_into_item'] || 'Insert into' . $post_type['name'],
                        // 'uploaded_to_this_item' => $post_type['uploaded_to_this_item'] || 'Upload to' . $post_type['name'],,
                        // 'items_list'            => $post_type['items_list'] || 'Insert into' . $post_type['name'],,
                        // 'items_list_navigation' => $post_type['items_list_navigation'] || $post_type['name'] . 'List',
                        // 'filter_items_list'     => $post_type['filter_items_list'] || 'Filter' . $post_type['name'] . 'List',
                    ),
                    'label' => $post_type['label'],
                    'description' => $post_type['name'] . 'Custom Post Type',
                    // 'supports' => array('thumbnail,editor'),
                    'show_in_rest' => true,
                    'taxonomies' => isset($post_type['taxonomies']) ? $post_type['taxonomies'] : array(),
                    'hierarchical' => false,
                    'public' => true,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'menu_position' => 110,
                    'show_in_admin_bar' => true,
                    'show_in_nav_menus' => true,
                    'can_export' => true,
                    'has_archive' => true,
                    'exclude_from_search' => false,
                    'publicly_queryable' => true,
                    'capability_type' => 'post',
                    'rest_base' => $post_type['name'],
                )
            );

            add_post_type_support($post_type['name'], array('thumbnail', 'excerpt', 'custom-fields'));
        }

    }
}

