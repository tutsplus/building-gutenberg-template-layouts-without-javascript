<?php

function styles_and_scripts(){
    wp_enqueue_style('landingpage-style', get_stylesheet_uri());

    wp_enqueue_style('lp-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700');
}
add_action('wp_enqueue_scripts', 'styles_and_scripts');

function remove_admin_login_header(){
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');

function setup_the_theme(){
    add_theme_support('title-tag');
    add_theme_support('editor-styles');

    add_editor_style('style-editor.css');
}
add_action('after_setup_theme', 'setup_the_theme');

function register_landingpage_post_type(){
    $post_settings = array(
        'show_in_rest' => true,
        'public' => true,
        'label' => 'Landing Page',
        'template_lock' => 'all',
        'template' => array(
            // heading
            array( 'core/heading',
                array(
                    'placeholder' => 'Add Categories Heading...',
                    'className' => 'tour_categories_heading'
                )
            ),
            // tour categories, row one
            array( 'core/columns',
                array(
                    'className' => 'tour_categories',
                    'columns' => 2
                ),
                array(
                    // featured tour category
                    array( 'core/column',
                        array('className' => 'tour_cat_one'),
                        array(
                            array('core/image', array()),
                            array('core/button', array(
                                'placeholder' => 'Button text',
                                'className' => 'button_two'
                            )),
                            array('core/paragraph', array(
                                'placeholder' => 'Tour category text',
                                'className' => 'tour_text'
                            ))
                        )
                    ),
                    // second tour category
                    array( 'core/column',
                        array('className' => 'tour_cat_two'),
                        array(
                            array('core/image', array()),
                            array('core/button', array(
                                'placeholder' => 'Button text',
                                'className' => 'button_two'
                            )),
                            array('core/paragraph', array(
                                'placeholder' => 'Tour category text',
                                'className' => 'tour_text'
                            ))
                        )
                    )
                )
            ),
            // tour categories, row two
            array( 'core/columns',
                array(
                    'className' => 'tour_categories',
                    'columns' => 3
                ),
                array(
                    // tour category
                    array( 'core/column',
                        array('className' => 'tour_cat_three'),
                        array(
                            array('core/image', array()),
                            array('core/button', array(
                                'placeholder' => 'Button text',
                                'className' => 'button_two'
                            )),
                            array('core/paragraph', array(
                                'placeholder' => 'Tour category text',
                                'className' => 'tour_text'
                            ))
                        )
                    ),
                    // tour category
                    array( 'core/column',
                        array('className' => 'tour_cat_four'),
                        array(
                            array('core/image', array()),
                            array('core/button', array(
                                'placeholder' => 'Button text',
                                'className' => 'button_two'
                            )),
                            array('core/paragraph', array(
                                'placeholder' => 'Tour category text',
                                'className' => 'tour_text'
                            ))
                        )
                    ),
                    // tour category
                    array( 'core/column',
                        array('className' => 'tour_cat_five'),
                        array(
                            array('core/image', array()),
                            array('core/button', array(
                                'placeholder' => 'Button text',
                                'className' => 'button_two'
                            )),
                            array('core/paragraph', array(
                                'placeholder' => 'Tour category text',
                                'className' => 'tour_text'
                            ))
                        )
                    )
                )
            )
        )
    );
    register_post_type('landing', $post_settings);
}
add_action('init', 'register_landingpage_post_type');



function get_landing_page_content($content){

    $blocks = parse_blocks($content);

    $lpcontent = array();

    $allowed_tags = "<h2><img><a><button><p><br><strong>";

    foreach($blocks as $key => $block){

        $section_class = $block['attrs']['className'];

        if($block['blockName'] === 'core/columns'){

            if(!array_key_exists($section_class, $lpcontent)){
                $lpcontent[$section_class] = [];
            }

            foreach( $block['innerBlocks'] as $key => $column ){

                $column_class = $column['attrs']['className'];

                $lpcontent[$section_class][$column_class] = [];

                foreach($column['innerBlocks'] as $key => $colBlock){

                    $colBlock_rendered = render_block($colBlock);

                    $colBlock_rendered = strip_tags($colBlock_rendered, $allowed_tags);

                    if($colBlock['blockName'] === 'core/button'){
                        $colBlock_rendered = str_replace('wp-block-button__link', $colBlock['attrs']['className'], $colBlock_rendered);
                    }
        
                    $lpcontent[$section_class][$column_class][$key] = $colBlock_rendered;

                }

            }

        } else {

            $block_rendered = render_block($block);

            $block_rendered = strip_tags($block_rendered, $allowed_tags);

            $lpcontent[$section_class] = $block_rendered;
                
        }

    }

    return $lpcontent;

}

add_filter('landing_page_content', 'get_landing_page_content');

?>