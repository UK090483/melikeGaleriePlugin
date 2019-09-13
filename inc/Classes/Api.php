<?php
namespace Inc\Classes;

class Api
{

    public function register()
    {
        add_action('rest_api_init', function () {
            register_rest_route('ku/v1', '/showposts', array(
                'methods' => 'GET',
                'callback' => array($this, 'ku_showpost'),
            ));
        });

    }

    public function ku_showpost($data)
    {

        $types = get_post_types(array(
            'public' => true,
        ), 'objects');
        $result;
        foreach ($types as $value) {

            $taxonomies = get_taxonomies(array('object_type' => array($value->name)));
            $taxres = [];

            foreach ($taxonomies as $tax) {

                $terms = get_terms($tax);
                $termArray = array();
                foreach ($terms as $term) {
                    array_push($termArray, $term);
                }

                $taxres[$tax] = array(
                    'name' => $tax,
                    'terms' => $termArray,
                );
            }

            $result[$value->name] = array(
                'label' => $value->label,
                'name' => $value->name,
                'taxonomies' => $taxres,
            );
            unset($taxonomies);
            unset($taxres);
        }

        return rest_ensure_response($result);
    }

}
