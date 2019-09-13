<?php

namespace Inc\Classes;

class AusstellungenController
{
    public $custom_post_types = array();

    public function register()
    {

        add_filter("manage_edit-ausstellungen_columns", array($this, 'ausstellungen_edit_columns'));
        add_action("manage_posts_custom_column", array($this, 'ausstellungen_custom_columns'));
        add_filter('manage_edit-ausstellungen_sortable_columns', array($this, 'my_sortable_cake_column'));
        add_action('pre_get_posts', array($this, 'my_slice_orderby'));
        add_theme_support('post-thumbnails', array('ausstellungen'));
        add_action('init', array($this, 'myguten_register_post_meta'));

    }

    public function my_slice_orderby($query)
    {
        // if (!is_admin()) {
        //     return;
        // }

        $orderby = $query->get('orderby');

        // error_log(json_encode($orderby, JSON_PRETTY_PRINT));

        if ('ku-date' == $orderby) {
            $query->set('meta_key', 'ku_start_date');
            $query->set('orderby', 'meta_value');
            $query->set('meta_type', 'DATE');
        }
        if ('ku-date-end' == $orderby) {

            $query->set('meta_key', 'ku_end_date');
            $query->set('orderby', 'meta_value');
            $query->set('meta_type', 'DATE');
        }
    }

    public function myguten_register_post_meta()
    {
        register_post_meta('ausstellungen', 'ku_start_date', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_post_meta('ausstellungen', 'ku_end_date', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));

    }

    public function my_sortable_cake_column($columns)
    {
        $columns['ku_ausstellungen_year'] = 'ku-date';
        $columns['ku_ausstellungen_date'] = 'ku-date';

        //To make a column 'un-sortable' remove it from the array
        //unset($columns['date']);

        return $columns;
    }
    public function ausstellungen_edit_columns($columns)
    {

        $new_columns = array(
            "cb" => "<input type=\"checkbox\" />",
            "title" => "Event",
            "ku_ausstellungen_thumb" => "Thumbnail",
            "ku_ausstellungen_year" => "Year",
            "ku_ausstellungen_date" => "Date",
            "ku_ausstellungen_times" => "Time",
            'taxonomy-kutypes' => "bla",
            "date" => "published",
        );
        return array_merge($new_columns);
    }

    public function ausstellungen_custom_columns($column)
    {

        $custom = get_post_custom();

        if (isset($custom["ku_start_date"]) && isset($custom["ku_end_date"])) {
            $startdate = strtotime($custom["ku_start_date"][0]);
            $enddate = strtotime($custom["ku_end_date"][0]);
        }

        switch ($column) {

            case "ku_ausstellungen_date":
                //- show dates -
                $startdateShow = '---';
                $startdateShow = '---';
                if (isset($startdate) && isset($enddate)) {
                    $startdateShow = date(('d M'), $startdate);
                    $enddateShow = date(('d M'), $enddate);
                }
                echo $startdateShow . '<br />' . $enddateShow;
                break;

            case "ku_ausstellungen_year":
                $startdateShow = '---';
                $startdateShow = '---';
                if (isset($startdate) && isset($enddate)) {
                    $startdateShow = date(('Y'), $startdate);
                    $enddateShow = date(('Y'), $enddate);
                }
                echo $startdateShow . '<br />' . $enddateShow;
                break;

            case "ku_ausstellungen_times":
                $startdateShow = '---';
                $startdateShow = '---';
                if (isset($startdate) && isset($enddate)) {
                    $startdateShow = date(('G:i'), $startdate);
                    $enddateShow = date(('G:i'), $enddate);
                }
                echo $startdateShow . '<br />' . $enddateShow;
                break;

            case "ku_ausstellungen_thumb":
                // - show thumb -
                $post_image_id = get_post_thumbnail_id(get_the_ID());
                if ($post_image_id) {
                    echo get_the_post_thumbnail(get_the_ID(), array(60, 60));
                }
                break;
            case "ku_ausstellungen_desc";
                the_excerpt();
                break;

        }
    }

}
