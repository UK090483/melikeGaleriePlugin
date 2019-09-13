<?php

namespace Inc\Classes;

class ProjekteController
{
    public $custom_post_types = array();

    public function register()
    {
        add_action('init', array($this, 'myguten_register_post_meta'));
        add_filter("manage_edit-projekte_columns", array($this, 'projekte_edit_columns'));
        add_action("manage_posts_custom_column", array($this, 'projekte_custom_columns'));
        add_filter('manage_edit-projekte_sortable_columns', array($this, 'my_sortable_cake_column'));
        add_action('pre_get_posts', array($this, 'my_slice_orderby'));
        add_theme_support('post-thumbnails', array('projekte'));

    }

    public function my_slice_orderby($query)
    {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('ku-date' == $orderby) {
            $query->set('meta_key', 'ku_start_date');
            $query->set('orderby', 'meta_value');
            $query->set('meta_type', 'DATE');
        }
    }

    public function myguten_register_post_meta()
    {
        register_post_meta('projekte', 'ku_start_date', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_post_meta('projekte', 'ku_end_date', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));

    }

    public function my_sortable_cake_column($columns)
    {
        $columns['ku_projecte_date'] = 'ku-date';
        $columns['ku_projecte_year'] = 'ku-date';

        //To make a column 'un-sortable' remove it from the array
        //unset($columns['date']);

        return $columns;
    }
    public function projekte_edit_columns($columns)
    {

        $new_columns = array(
            "cb" => "<input type=\"checkbox\" />",
            "title" => "Event",
            "ku_projecte_thumb" => "Thumbnail",
            "ku_projecte_year" => "Year",
            "ku_projecte_date" => "Date",
            "ku_projecte_times" => "Time",
            "date" => "published",
        );
        return array_merge($new_columns);
    }

    public function projekte_custom_columns($column)
    {
        // global $post;
        $custom = get_post_custom();
        // $post->post_type;
        if (get_post_type() != 'projekte') {
            return;
        };

        switch ($column) {
            case "ku_projecte_cat":
                // // - show taxonomy terms -
                // $eventcats = get_the_terms($post->ID, "tf_eventcategory");
                // $eventcats_html = array();
                // if ($eventcats) {
                //     foreach ($eventcats as $eventcat) {
                //         array_push($eventcats_html, $eventcat->name);
                //     }

                //     echo implode($eventcats_html, ", ");
                // } else {
                //     _e('None', 'themeforce');
                // }
                break;
            case "ku_projecte_date":
                //- show dates -
                $startdate = 'Nodate';
                $enddate = 'Nodate';
                if (isset($custom["ku_start_date"]) && isset($custom["ku_end_date"])) {
                    $startdate = date(('t M'), strtotime($custom["ku_start_date"][0]));
                    $enddate = date(('t M'), strtotime($custom["ku_end_date"][0]));
                }

                // echo $startdate . '<br /><em>' . $enddate . '</em>';
                echo $startdate . '<br />' . $enddate;
                break;

            case "ku_projecte_year":
                $startdate = 'No Start Year';
                $enddate = 'No End Year';
                if (isset($custom["ku_start_date"]) && isset($custom["ku_end_date"])) {
                    $startdate = date(('Y'), strtotime($custom["ku_start_date"][0]));
                    $enddate = date(('Y'), strtotime($custom["ku_end_date"][0]));
                }

                // $starttime = date($time_format, $startt);
                // $endtime = date($time_format, $endt);
                echo $startdate . '<br />' . $enddate;
                break;

            case "ku_projecte_times":
                $startdate = 'No Start Time';
                $enddate = 'No End Time';
                if (isset($custom["ku_start_date"]) && isset($custom["ku_end_date"])) {
                    $startdate = date(('G:i'), strtotime($custom["ku_start_date"][0]));
                    $enddate = date(('G:i'), strtotime($custom["ku_end_date"][0]));
                }

                // $starttime = date($time_format, $startt);
                // $endtime = date($time_format, $endt);
                echo $startdate . '<br />' . $enddate;
                break;
            case "ku_projecte_thumb":
                // - show thumb -
                $post_image_id = get_post_thumbnail_id(get_the_ID());
                if ($post_image_id) {
                    echo get_the_post_thumbnail(get_the_ID(), array(60, 60));
                }
                break;
            case "ku_projecte_desc";
                the_excerpt();
                break;

        }
    }

}
