<?php
/*
Plugin Name: Custom AJAX Shop
Description: Custom WooCommerce shop page with AJAX filters.
Version: 1.0
Author: Your Name
*/

defined('ABSPATH') || exit;

// Ensure WooCommerce is loaded for AJAX requests
if (defined('DOING_AJAX') && DOING_AJAX && !function_exists('WC')) {
    include_once dirname(__FILE__) . '/../../../../wp-load.php';
    include_once dirname(__FILE__) . '/../../../../wp-content/plugins/woocommerce/woocommerce.php';
}

// insert font icons
add_action('wp_enqueue_scripts', 'enqueue_fontawesome_for_plugin');
function enqueue_fontawesome_for_plugin() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css');
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('custom-shop-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('custom-shop-script', plugin_dir_url(__FILE__) . 'assets/script.js', ['jquery'], null, true);
    wp_localize_script('custom-shop-script', 'custom_ajax_shop', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});

// Shortcode to render shop
add_shortcode('custom_ajax_shop', function () {
    ob_start();
    echo '<div class="custom-shop-wrapper">';
    include plugin_dir_path(__FILE__) . 'templates/filter-sidebar.php';
    include plugin_dir_path(__FILE__) . 'templates/product-grid.php';
    echo '</div>';
    return ob_get_clean();
});

// Ensure WooCommerce cart session is available for AJAX requests (especially for guests)
add_action('init', function() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'add_to_cart_ajax') {
            if (function_exists('wc_load_cart')) {
                wc_load_cart();
            }
            if (null === WC()->session) {
                WC()->session = new WC_Session_Handler();
                WC()->session->init();
            }
        }
    }
});

// Handle AJAX filtering
add_action('wp_ajax_custom_filter_products', 'custom_filter_products');
add_action('wp_ajax_nopriv_custom_filter_products', 'custom_filter_products');

// Handle AJAX add to cart
add_action('wp_ajax_add_to_cart_ajax', 'custom_add_to_cart_ajax');
add_action('wp_ajax_nopriv_add_to_cart_ajax', 'custom_add_to_cart_ajax');


// Add the catagory, search, price & top rated functionality in your filter function
function custom_filter_products() {
    // Initialize the query args
    $args = [
        'post_type' => 'product',
        'posts_per_page' => 12,
        'paged' => $_POST['page'] ?? 1,
    ];

    // Search filter
    if (!empty($_POST['search'])) {
        $search = sanitize_text_field($_POST['search']);
        $args['s'] = $search; // Add search term to query
    }

    // Category filter
    if (!empty($_POST['category'])) {
        $category = sanitize_text_field($_POST['category']);
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
                'operator' => 'IN',
            ]
        ];
    }

    // Price filter
    if (!empty($_POST['price_filter'])) {
        $price_filters = $_POST['price_filter'];
        $meta_query = [];

        // Price range: under $25
        if (in_array('under-25', $price_filters)) {
            $meta_query[] = [
                'key'     => '_price',
                'value'   => 25,
                'compare' => '<',
                'type'    => 'NUMERIC',
            ];
        }

        // Price range: $25 - $50
        if (in_array('25-50', $price_filters)) {
            $meta_query[] = [
                'key'     => '_price',
                'value'   => [25, 50],
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            ];
        }

        // Price range: $50 - $100
        if (in_array('50-100', $price_filters)) {
            $meta_query[] = [
                'key'     => '_price',
                'value'   => [50, 100],
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            ];
        }

        // Price range: above $100
        if (in_array('above-100', $price_filters)) {
            $meta_query[] = [
                'key'     => '_price',
                'value'   => 100,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ];
        }

        // If any price filters are selected, add to the query
        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }
    }

    // Top rated filter
    if (!empty($_POST['rating_filter']) && $_POST['rating_filter'] === 'top-rated') {
        // Only include top-rated products with a rating of 4 and above
        $args['meta_query'][] = [
            'key'     => '_wc_average_rating', // WooCommerce average rating field
            'value'   => 4,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }

    // Run the query
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            global $product;
            $product = wc_get_product(get_the_ID());
            include plugin_dir_path(__FILE__) . 'templates/custom-product-card.php';
        }
    } else {
        echo '<p>No products found</p>';
    }

    wp_die();
}


// Robust AJAX add to cart handler for WooCommerce
function custom_add_to_cart_ajax() {
    if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
        wp_send_json_error(['message' => 'Missing product or quantity.']);
    }
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if (!$product_id || !$quantity) {
        wp_send_json_error(['message' => 'Invalid product or quantity.']);
    }

    // Ensure WooCommerce cart is loaded
    if (function_exists('wc_load_cart')) {
        wc_load_cart();
    }

    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Product not found.']);
    }

    // Add to cart
    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
    if ($cart_item_key) {
        // Trigger cart fragments refresh for frontend
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        wp_send_json_success([
            'message' => 'Product added to cart successfully.',
            'cart_item_key' => $cart_item_key
        ]);
    } else {
        wp_send_json_error(['message' => 'Failed to add product to cart.']);
    }
}
