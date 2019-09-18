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
                    'dateQuery'=> array(
                        'type'=> 'object'
                    )

                ),
            )
        );
    }
    public function my_block_render($attributes, $posts)
    {
        // error_log( var_export( $attributes, true));
       
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
 

        $taxquery = $this->get_tax_queries($attributes);
        if (!empty($taxquery)) {
            $query['tax_query'] = $taxquery;
        }
        if(  $attributes['selectedCategory'] !== 'artists'){
            $datequery=$this->get_meta_queries($attributes);
             if (!empty($datequery)) {
                $query['meta_query'] = $datequery;
             }
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

            error_log('not empty taxquerry');

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
    public function ku_test_bool($bool)
    {
        $res = true === $bool? true : 'true' ===$bool? true : false;
        return boolval($res);
    }


    public function get_meta_queries($attributes)
    {
        $meta_query = array();
        $datequery = isset($attributes['dateQuery'])?$attributes['dateQuery']:false;

        if(!$datequery )
        {
            return;   
        }

        $fromDateActive = $this->ku_test_bool($datequery['fromDateActive']);
        $fromDateNow = $this->ku_test_bool($datequery['fromDateNow']);
        $toDateActive = $this->ku_test_bool($datequery['toDateActive']);
        $toDateNow = $this->ku_test_bool($datequery['toDateActive']);


        if ($fromDateActive && $toDateActive) {
            $meta_query['relation'] = 'AND';
            error_log('both active');
        }
  
            
        if($fromDateActive){
            $fromDate = $fromDateNow ? date('c') : $datequery['fromDate'];
            array_push($meta_query, array(
                'key' => 'ku_start_date',
                'value' => $fromDate,
                'compare' => '>',
                ));
            }

            
       
            if ($toDateActive) {

                $toDate =  $toDateNow  ? date('c') : $attributes['toDate'];
                array_push($meta_query, array(
                    'key' => 'ku_start_date',
                    'value' => $toDate,
                    'compare' => '<',
                ));
            }

        return $meta_query;

    }

}
