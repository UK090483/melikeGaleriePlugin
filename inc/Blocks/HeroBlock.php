<?php

namespace Inc\Blocks;

use Inc\Helper\ParseDate;

class HeroBlock
{

    public function register()
    {
        register_block_type(
            'cgb/hero', array(
                'render_callback' => array($this, 'my_block_render'),
                'attributes' => array(
                    'auto' => array(
                        'type' => 'boolean',

                    ),
                    'imageId' => array(
                        'type' => 'number',
                    ),
                    'align' => array(
                        'type' => 'string',
                    ),
                    'header' => array(
                        'type' => 'string',
                    ),
                    'text' => array(
                        'type' => 'string',
                    ),
                    'date' => array(
                        'type' => 'string',
                    ),

                ),
            )
        );
    }

    public function my_block_render($attributes, $posts)
    {
        error_log(json_encode($posts));
        return isset($attributes['auto']) && $attributes['auto'] ? $this->getAutoGeneratetContent($attributes) : $this->getCustumGeneratetContent($attributes);

    }
    public function getCustumGeneratetContent($attributes)
    {

        $imageUrl = isset($attributes['imageId']) ? wp_get_attachment_url($attributes['imageId']) : false;
        $innerheader = isset($attributes['header']) && !($attributes['header'] == '') ? '<h1>' . $attributes['header'] . '<h1>' : '';
        $innerText = isset($attributes['text']) && !($attributes['text'] == '') ? '<h1>' . $attributes['text'] . '<h1>' : '';
        $innerDate = isset($attributes['date']) && !($attributes['date'] == '') ? '<h1>' . $attributes['date'] . '<h1>' : '';
        ob_start();
        echo '<div class="hero-wrap">';
        echo '<div class="hero-image" style="background-image: url(' . $imageUrl . ')"></div>';
        echo "<div class='hero_innerblock'>";
        echo $imageUrl ? '' : '<h1> Choose an Image !</h1>';
        echo $innerheader;
        echo $innerText;
        echo $innerDate;
        echo '</div></div>';

        return ob_get_clean();
    }

    public function getAutoGeneratetContent($attributes)
    {

        $getNow_events = $this->getNowRunningEvents();
        $next_or_now_Event = $getNow_events ? $getNow_events : $this->getNextEvents();
        $event = $next_or_now_Event ? $next_or_now_Event : $this->getLatestEvents();

        $innerheader = $getNow_events ? 'right Now:' : ($next_or_now_Event ? 'coming Next:' : 'preveoudsly');
        $title = $event[0]->post_title;
        $metaData = get_post_meta($event[0]->ID);
        $paresDate = ParseDate::getDate($metaData['ku_start_date'][0], $metaData['ku_end_date'][0]);
        $innerText = "<div class='hero_innerblock'><h1>$innerheader<h1><h1>$title<h1><h1>$paresDate<h1></div>";

        ob_start();
        echo '<div class="hero-wrap">';
        echo '<div class="hero-image" style="background-image: url(' . wp_get_attachment_url(get_post_thumbnail_id($event[0]->ID)) . ')"></div>';
        echo $innerText;

        echo '</div>';

        // echo '<script> const paralax = new Rellax(".hero-image", {
        //     speed: -2,
        //     center: true,

        //     round: true,
        //     vertical: true,
        //     horizontal: false
        // });</script> ';

        return ob_get_clean();
    }

    public function getNowRunningEvents()
    {

        $meta_query_args = array(
            array(
                'key' => 'ku_end_date',
                'value' => date("Y-m-d H:i:s"),
                'compare' => '>',
                'type' => 'DATE',
            ),
            array(
                'key' => 'ku_start_date',
                'value' => date("Y-m-d H:i:s"),
                'compare' => '<',
                'type' => 'DATE',
            ),
        );
        $args = array(
            'post_type' => 'Ausstellungen',
            'meta_query' => $meta_query_args,
        );
        $getNow = get_posts($args);

        return empty($getNow) ? false : $getNow;

    }
    public function getNextEvents()
    {

        $meta_query_args = array(
            array(
                'key' => 'ku_end_date',
                'value' => date("Y-m-d H:i:s"),
                'compare' => '>',
                'type' => 'DATE',
            ),
            array(
                'key' => 'ku_start_date',
                'value' => date("Y-m-d H:i:s"),
                'compare' => '>',
                'type' => 'DATE',
            ),
        );
        $args = array(
            'post_type' => 'Ausstellungen',
            'meta_query' => $meta_query_args,
            'orderby' => 'ku-date',
            'order' => 'ASC',
        );
        $getNow = get_posts($args);

        return empty($getNow) ? false : $getNow;

    }

    public function getLatestEvents()
    {

        $meta_query_args = array(
            array(
                'key' => 'ku_end_date',
                'value' => date("Y-m-d H:i:s"),
                'compare' => '<',
                'type' => 'DATE',
            ),
        );

        $args = array(
            'post_type' => 'Ausstellungen',
            'meta_query' => $meta_query_args,
            'orderby' => 'ku-date-end',
        );
        $getNow = get_posts($args);

        return empty($getNow) ? false : $getNow;

    }
}
