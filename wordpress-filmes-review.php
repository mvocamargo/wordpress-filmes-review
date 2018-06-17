<?php
/*
Plugin Name: WordPress - Filmes Review
Author: Marcus Camargo
Version: 1.0
License: MIT
Text Domain: wordpress_filmes_review
*/

require dirname(__FILE__).'/lib/class-tgm-plugin-activation.php';

class Filmes_reviews {
    private static $instance;
    const FIELD_PREFIX = 'filmes_review_';
    public static function getInstance(){
        if( self::$instance == NULL ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'init', array($this, 'Filmes_reviews::register_post_type') );
        add_action( 'init', array($this, 'Filmes_reviews::register_taxonomies') );
        add_action( 'tgmpa_register', array($this, 'check_required_plugins') );
        add_action( 'rwmb_meta_boxes', array($this, 'metabox_custom_fileds') );
    }

    public static function register_post_type()
    {
        register_post_type( 'filmes_review', array(
            'labels' => array(
                'name' => 'Filmes Reviews',
                'singular_name' => 'Filme Review',
            ),
            'supports' => ['title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail', 'custom-fields'],
            'public' => true,
            'menu_position' => 4,
            'menu_icon' => 'dashicons-format-video',
        ) );
    }

    public static function register_taxonomies()
    {
        register_taxonomy( 'tipos_filmes', array('filmes_review'), array(
            'labels' => array(
                'name' => __('Filmes Tipos'),
                'singular_name' => __('Filme TIpo'),
            ),
            'public'=> true,
            'hierarchical' => true,
            'rewrite' => array('slug' => 'tipos-filmes'),
        ) );
    }

    public function check_required_plugins()
    {
        $plugins = array(
            array(
                'name' => 'Meta Box',
                'slug' => 'meta-box',
                'required' => true,
                'force_activation' => false,
                'force_desactivation' => false,

            )
        );

        $config = array(
            'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'install-required-plugins', // Menu slug.
            'parent_slug'  => 'plugins.php',            // Parent menu slug.
            'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            /*
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'theme-slug' ),
                'menu_title'                      => __( 'Install Plugins', 'theme-slug' ),
                // <snip>...</snip>
                'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            )
            */
        );
        tgmpa( $plugins, $config );
    }

    public function metabox_custom_fileds()
    {
        $meta_boxes[] = array(
            'id' => 'data_filme',
            'title' => __( 'Informações Adicionais' ),
            'pages' => ['filmes_review', 'post'],
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => __('Ano de Lançamento'),
                    'desc' => __('Ano em que o filme foi lançado'),
                    'id' => self::FIELD_PREFIX.'filme_ano',
                    'type' => 'number',
                    'std' => date('Y'),
                    'min' => '1880',
                ),
                array(
                    'name' => __('Diretor'),
                    'desc' => __('Quem dirigiu o filme'),
                    'id' => self::FIELD_PREFIX.'filme_diretor',
                    'type' => 'text',
                    'std' => '',
                ),
                array(
                    'name' => __('Site'),
                    'desc' => __('Link do site do filme'),
                    'id' => self::FIELD_PREFIX.'filme_site',
                    'type' => 'url',
                    'std' => '',
                ),
            )
        );
        $meta_boxes[] = array(
            'id' => 'nota_filme',
            'title' => __( 'Review do Filme' ),
            'pages' => ['filmes_review'],
            'context' => 'side',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => __('Rating'),
                    'desc' => __('Nota de 1 a 10'),
                    'id' => self::FIELD_PREFIX.'filme_rating',
                    'type' => 'select',
                    'options' => array(
                        '' => __('Avalie o filme'),
                        1 => __('1'),
                        2 => __('2'),
                        3 => __('3'),
                        4 => __('4'),
                        5 => __('5'),
                        6 => __('6'),
                        7 => __('7'),
                        8 => __('8'),
                        9 => __('9'),
                        10 => __('10'),
                    ),
                    'std' => '',
                ),
            )
        );
        return $meta_boxes;
    }

    public static function activate()
    {
        self::register_post_type();
        flush_rewrite_rules();
    }
}

Filmes_reviews::getInstance();

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'Filmes_reviews::activate' );