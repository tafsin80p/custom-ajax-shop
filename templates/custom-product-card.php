<?php
/**
 * Custom Product Card Template (Image-based)
 */

global $product;
if (!$product || !is_a($product, 'WC_Product')) return;

$product_id = $product->get_id();
$product_title = $product->get_name();
$product_price = $product->get_price_html();
$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
$product_url = get_permalink($product_id);
$product_rating = $product->get_average_rating();

?>
<div class="custom-product-card minimal-card" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="card-image-wrapper">
        <?php if ($product_image) : ?>
            <a href="<?php echo esc_url($product_url); ?>" class="custom-product-card-link" style="text-decoration:none;color:inherit;display:block;">
                <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php echo esc_attr($product_title); ?>"
                    class="card-product-image" loading="lazy">
            </a>
        <?php else : ?>
            <div class="card-no-image"><i class="fas fa-image"></i></div>
        <?php endif; ?>
        <?php if ($product_rating) : ?>
        <div class="card-rating-badge">
            <i class="fas fa-star"></i> <?php echo number_format($product_rating, 1); ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-content">
        <h3 class="card-title" style="margin:0;">
            <a href="<?php echo esc_url($product_url); ?>" style="text-decoration:none;color:inherit;">
                <?php echo esc_html($product_title); ?>
            </a>
        </h3>
        <div class="card-price"><?php echo wp_kses_post($product_price); ?></div>
        <?php if ($product->is_type('simple')) : ?>
        <button class="add-to-cart-btn full-width-btn" data-product-id="<?php echo esc_attr($product_id); ?>" type="button">
            Add To Cart
            <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M1.33333 14.667C0.966667 14.667 0.652778 14.5364 0.391667 14.2753C0.130556 14.0142 0 13.7003 0 13.3337V5.33366C0 4.96699 0.130556 4.6531 0.391667 4.39199C0.652778 4.13088 0.966667 4.00033 1.33333 4.00033H2.66667C2.66667 3.0781 2.99167 2.29199 3.64167 1.64199C4.29167 0.991992 5.07778 0.666992 6 0.666992C6.92222 0.666992 7.70833 0.991992 8.35833 1.64199C9.00833 2.29199 9.33333 3.0781 9.33333 4.00033H10.6667C11.0333 4.00033 11.3472 4.13088 11.6083 4.39199C11.8694 4.6531 12 4.96699 12 5.33366V13.3337C12 13.7003 11.8694 14.0142 11.6083 14.2753C11.3472 14.5364 11.0333 14.667 10.6667 14.667H1.33333ZM6 9.33366C6.92222 9.33366 7.70833 9.00866 8.35833 8.35866C9.00833 7.70866 9.33333 6.92255 9.33333 6.00033H8C8 6.55588 7.80556 7.0281 7.41667 7.41699C7.02778 7.80588 6.55556 8.00033 6 8.00033C5.44444 8.00033 4.97222 7.80588 4.58333 7.41699C4.19444 7.0281 4 6.55588 4 6.00033H2.66667C2.66667 6.92255 2.99167 7.70866 3.64167 8.35866C4.29167 9.00866 5.07778 9.33366 6 9.33366ZM4 4.00033H8C8 3.44477 7.80556 2.97255 7.41667 2.58366C7.02778 2.19477 6.55556 2.00033 6 2.00033C5.44444 2.00033 4.97222 2.19477 4.58333 2.58366C4.19444 2.97255 4 3.44477 4 4.00033Z"
                    fill="white" />
            </svg>
        </button>
        <?php endif; ?>
    </div>
</div>