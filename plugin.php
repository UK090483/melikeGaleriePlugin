<?php
/**
 * Plugin Name: Melike Gallerie

 * Description: Plugin for Melikes Gallerie
 * Author: Konrad Ullrich
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path(__FILE__) . '/blocks/src/init.php';

add_action('rest_api_init', 'mgku_create_api_artist_thumbnail');

function mgku_create_api_artist_thumbnail()
{

    // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
    register_rest_field('artist', 'thumbnail', array(
        'get_callback' => 'mgku_get_post_meta_for_api',
        'schema' => null,
    )
    );
}
function mgku_get_post_meta_for_api($object)
{
    //get the id of the post object array
    $post_id = $object['id'];
    //return the post meta
    return get_the_post_thumbnail_url($post_id);
}

add_action('rest_api_init', 'mgku_create_api_ausstellungen_thumbnail');

function mgku_create_api_ausstellungen_thumbnail()
{
    register_rest_field('ausstellungen', 'thumbnail', array(
        'get_callback' => 'mgku_get_thumbnail_ausstellungen',
        'schema' => null,
    )
    );
}
function mgku_get_thumbnail_ausstellungen($object)
{
    //get the id of the post object array
    $post_id = $object['id'];

    //return the post meta
    return get_the_post_thumbnail_url($post_id);
}

$cpt = new Inc\Init;
$cpt->registerServices();

function cl($input)
{
    $res = json_encode($input);
    echo "<script> console.log($res)</script>";
}
