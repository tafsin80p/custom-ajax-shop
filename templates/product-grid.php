<div class="right_site">
    <!-- Add search filter -->
    <div class="top-search">
        <div class="menu-filter">
            <!-- Hamburger Menu Icon -->
            <div class="menu-icon" id="menu-icon">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </div>
        <div class="search-filter">
            <input type="text" id="product-search" placeholder="Search products..." />
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                    stroke="#ABB7C2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M21 21.0002L16.7 16.7002" stroke="#ABB7C2" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </div>
    </div>


    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div class="spinner"></div> <!-- You can replace this with your own preloader style -->
    </div>

    <!-- Product results will load here -->
    <div class="custom-product-grid" id="product-grid">
        <?php
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 12,
            'post_status'    => 'publish',
        ];

        $loop = new WP_Query($args);

        if ($loop->have_posts()) {
            while ($loop->have_posts()) {
                $loop->the_post();
                global $product;
                $product = wc_get_product(get_the_ID());
                include plugin_dir_path(__FILE__) . 'custom-product-card.php';
            }
        }
        wp_reset_postdata();
        ?>
    </div>
</div>