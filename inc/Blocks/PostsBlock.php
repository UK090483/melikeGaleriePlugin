<?php

namespace Inc\Blocks;

use Inc\Helper\ParseDate;
use WP_Query;

class PostsBlock
{

    public function register()
    {
        register_block_type(
            'cgb/show-posts', array(
                // Enqueue blocks.style.build.css on both frontend & backend.
                'style' => 'my_block-cgb-style-css',
                // Enqueue blocks.build.js in the editor only.
                'editor_script' => 'my_block-cgb-block-js',
                // Enqueue blocks.editor.build.css in the editor only.
                'editor_style' => 'my_block-cgb-block-editor-css',

                'render_callback' => array($this, 'my_block_render'),
                'attributes' => array(
                    'selectedCategory' => array(
                        'type' => 'string',
                    ),
                    'taxonomies' => array(
                        'type' => 'object',
                    ),
                    'numOfPosts' => array(
                        'type' => 'number',
                    ),
                    'isGutenberg' => array(
                        'type' => 'boolean',
                    ),
                    'fromDate' => array(
                        'type' => 'string',
                    ),
                    'fromDateActive' => array(
                        'type' => 'boolean',
                    ),
                    'toDate' => array(
                        'type' => 'string',
                    ),
                    'toDateActive' => array(
                        'type' => 'boolean',
                    ),

                ),
            )
        );
    }
    public function my_block_render($attributes, $posts)
    {
        $numOfPosts = -1;
        if (array_key_exists('numOfPosts', $attributes) && isset($attributes['numOfPosts'])) {
            $numOfPosts = (int) $attributes['numOfPosts'];

        }
        $isGutenberg = isset($attributes['isGutenberg']) ? true : false;

        if ($attributes['selectedCategory'] == 'ausstellungen' || $attributes['selectedCategory'] == 'projekte') {
            $query =
                [
                'post_type' => $attributes['selectedCategory'],
                'posts_per_page' => $numOfPosts,
                'post_status' => 'publish',
                'orderby' => 'ku-date',
                'order' => 'ASC',

            ];
        } else {
            $query =
                [
                'post_type' => $attributes['selectedCategory'],
                'posts_per_page' => $numOfPosts,
                'post_status' => 'publish',
            ];
        }

        if (!empty($this->get_tax_queries($attributes))) {
            $query['tax_query'] = $this->get_tax_queries($attributes);
        }
        if (!empty($this->get_meta_queries($attributes))) {
            $query['meta_query'] = $this->get_meta_queries($attributes);
        }

        $the_query = new WP_Query($query);

        ob_start();
        if ($the_query->have_posts()) {

            echo "<div class='ku-card-wrap'>";

            while ($the_query->have_posts()) {
                $the_query->the_post();
                $metaData = get_post_meta(get_the_ID());
                $permaLink = get_the_permalink(get_the_ID());
                ?>

<?php echo $isGutenberg ? '<div class="ku-card">' : '<a class="ku-card" href="' . $permaLink . '">' ?>
         <?php $this->getBackgroundImage();?>
               <div class="ku-card-info">



            <h4 style='text-align:center'><?php the_title();?></h4>

            <?php if (get_post_type() == 'ausstellungen' || get_post_type() == 'projekte') {
                    $this->getAusstellungsDate($metaData);
                }

                ?>
</div>
<?php echo $isGutenberg ? '</div>' : '</a>' ?>
            <?php

            }

            echo '</div>';

        } else {
            echo 'no posts';

        }
        return ob_get_clean();
    }

    public function getAusstellungsDate($metaData)
    {

        $parsedDate = ParseDate::getDate($metaData['ku_start_date'][0], $metaData['ku_end_date'][0]);

        echo '<div class=ku-card-date >';
        echo "<h5>  $parsedDate </h5>";
        echo '</div>';

    }

    public function getBackgroundImage()
    {
        echo '<div class="ku-card-pic" style="background-image: url(' . wp_get_attachment_url(get_post_thumbnail_id()) . ')"></div>';
    }

    public function get_tax_queries($attributes)
    {
        $tax_query = array();

        if (!empty($attributes['taxonomies'])) {

            if (count($attributes['taxonomies']) > 1) {
                $tax_query['relation'] = 'AND';
            }

            foreach ($attributes['taxonomies'] as $tax) {
                $t = explode('---', $tax);
                if (!empty($t)) {

                    if ($t[1] == 'noTerm') {

                        array_push($tax_query, array(
                            'taxonomy' => $t[0],
                            'operator' => 'EXISTS',
                        ));

                    } else {

                        array_push($tax_query, array(
                            'taxonomy' => $t[0],
                            'field' => 'slug',
                            'terms' => $t[1],
                            'operator' => 'NOT IN',
                        ));
                    }
                }
            }
        }

        return $tax_query;

    }
    public function get_meta_queries($attributes)
    {
        $meta_query = array();

        if (isset($attributes['fromDateActive']) && $attributes['fromDateActive'] || isset($attributes['toDateActive']) && $attributes['toDateActive']) {

            if (isset($attributes['fromDateActive']) && $attributes['fromDateActive'] && isset($attributes['toDateActive']) && $attributes['toDateActive']) {
                $tax_query['relation'] = 'AND';
            }

            if ($attributes['fromDateActive']) {

                $fromDate = $attributes['fromDate'] == 'now' || '' ? date('c') : $attributes['fromDate'];

                array_push($meta_query, array(
                    'key' => 'ku_start_date',
                    'value' => $fromDate,
                    'compare' => '>',
                ));
            }
            if (isset($attributes['toDateActive']) && $attributes['toDateActive']) {

                $toDate = $attributes['toDate'] == 'now' || '' ? date('c') : $attributes['toDate'];

                array_push($meta_query, array(
                    'key' => 'ku_start_date',
                    'value' => $toDate,
                    'compare' => '<',
                ));
            }

        }

        return $meta_query;

    }

}
