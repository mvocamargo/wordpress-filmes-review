<?php
/*
Plugin Name: WordPress - Filmes Review
Author: Marcus Camargo
Version: 1.0
License: MIT
*/

class Filmes_reviews {
    private static $instance;

    public static function getInstance(){
        if( self::$instance == NULL ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        add_action( 'init', array($this, 'register_post_type') );
    }

    public function register_post_type()
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

}

Filmes_reviews::getInstance();