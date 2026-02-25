<?php

function cpt_emplois() {

    $labels = array(
        'name'                  => 'Emplois',
        'singular_name'         => 'Emploi',
        'menu_name'             => 'Emplois',
        'name_admin_bar'        => 'Emploi',
        'add_new'               => 'Ajouter',
        'add_new_item'          => 'Ajouter un emploi',
        'new_item'              => 'Nouveau emploi',
        'edit_item'             => 'Modifier le emploi',
        'view_item'             => 'Voir le emploi',
        'all_items'             => 'Tous les emplois',
        'search_items'          => 'Rechercher des emplois',
        'not_found'             => 'Aucun emploi trouvé',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'emplois'),
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt'
        ),
        'show_in_rest'       => false, // IMPORTANT pour Gutenberg
    );

    register_post_type('emploi', $args);
}

add_action('init', 'cpt_emplois');


function cpt_candidacys() {

    $labels = array(
        'name'                  => 'candidacys',
        'singular_name'         => 'candidacy',
        'menu_name'             => 'candidacys',
        'name_admin_bar'        => 'candidacy',
        'add_new'               => 'Ajouter',
        'add_new_item'          => 'Ajouter un candidacy',
        'new_item'              => 'Nouveau candidacy',
        'edit_item'             => 'Modifier le candidacy',
        'view_item'             => 'Voir le candidacy',
        'all_items'             => 'Tous les candidacys',
        'search_items'          => 'Rechercher des candidacys',
        'not_found'             => 'Aucun candidacy trouvé',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'candidacys'),
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt'
        ),
        'show_in_rest'       => false, // IMPORTANT pour Gutenberg
        'exclude_from_search' => true,
    );

    register_post_type('candidacy', $args);
}

add_action('init', 'cpt_candidacys');

function cpt_avis() {

    $labels = array(
        'name'                  => 'avis',
        'singular_name'         => 'avis',
        'menu_name'             => 'avis',
        'name_admin_bar'        => 'avis',
        'add_new'               => 'Ajouter',
        'add_new_item'          => 'Ajouter un avis',
        'new_item'              => 'Nouveau avis',
        'edit_item'             => 'Modifier le avis',
        'view_item'             => 'Voir le avis',
        'all_items'             => 'Tous les avis',
        'search_items'          => 'Rechercher des avis',
        'not_found'             => 'Aucun avis trouvé',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'avis'),
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt'
        ),
        'show_in_rest'       => false, // IMPORTANT pour Gutenberg
        'exclude_from_search' => true,
    );

    register_post_type('avis', $args);
}

add_action('init', 'cpt_avis');

function education_register_genre_taxonomy() {
    $labels = array(
        'name'              => _x( 'Educations', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Education', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Educations', 'textdomain' ),
        'all_items'         => __( 'All Educations', 'textdomain' ),
        'parent_item'       => __( 'Parent Education', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Education:', 'textdomain' ),
        'edit_item'         => __( 'Edit Education', 'textdomain' ),
        'update_item'       => __( 'Update GenreEducation', 'textdomain' ),
        'add_new_item'      => __( 'Add New Education', 'textdomain' ),
        'new_item_name'     => __( 'New Education Name', 'textdomain' ),
        'menu_name'         => __( 'Education', 'textdomain' ),
    );

    $args = array(
        'hierarchical'      => true, // Set to true for category-like, false for tag-like
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ), // Defines the URL slug (e.g., yoursite.com/genre/sci-fi/)
        'show_in_rest'      => true, // Essential for the taxonomy to appear in the Gutenberg block editor
    );

    register_taxonomy( 'education', array( 'emploi' ), $args ); // 'genre' is the taxonomy slug; array('post') associates it with posts
}

// Hook the function into the 'init' action
add_action( 'init', 'education_register_genre_taxonomy' );


?>