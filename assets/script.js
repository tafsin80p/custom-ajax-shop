jQuery(function ($) {

    // Handle search filter
    $('#product-search').on('keyup', function () {
        const searchTerm = $(this).val();

        $('#preloader').addClass('active');

        $.ajax({
            url: custom_ajax_shop.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_filter_products',
                search: searchTerm,
                page: 1, 
            },
            success: function (res) {
                $('#product-grid').html(res); 
            },
            complete: function() {
                $('#preloader').removeClass('active');
            }
        });
    });


    // Handle checkbox change for price and top-rated filters
    $('input[name="price_filter"], input[name="rating_filter"]').on('change', function () {
        // Get selected price filters
        let selectedPriceFilters = [];
        $('input[name="price_filter"]:checked').each(function () {
            selectedPriceFilters.push($(this).val());
        });

        // Get the selected rating filter (e.g., 'top-rated')
        const selectedRatingFilter = $('input[name="rating_filter"]:checked').val() || ''; // 'top-rated' if checked

        // Get the search term (if any)
        const searchTerm = $('#search-input').val() || '';

        // Get the selected category filter
        const category = $('#category-select').val() || '';

        // Show preloader before AJAX request
        $('#preloader').addClass('active');

        $.ajax({
            url: custom_ajax_shop.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_filter_products',
                price_filter: selectedPriceFilters, // Send selected price ranges
                rating_filter: selectedRatingFilter, // Send selected rating filter
                search: searchTerm, // Send search term
                category: category, // Send selected category
                page: 1, // Optional for pagination
            },
            success: function (res) {
                $('#product-grid').html(res); // Update product grid with filtered results
            },
            complete: function () {
                // Hide preloader after content is loaded
                $('#preloader').removeClass('active');
            }
        });
    });
    


    // Filter functionality
    $('.custom-filter-sidebar a').on('click', function (e) {
        e.preventDefault();
        let category = $(this).data('category');

        // Show preloader before AJAX request
        $('#preloader').addClass('active');
		$('.custom-filter-sidebar a').removeClass('active').get(0).offsetHeight;
		$(this).addClass('active');
        $.ajax({
            url: custom_ajax_shop.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_filter_products',
                category: category,
                page: 1,
            },
            success: function (res) {
                $('#product-grid').html(res);
            },
            complete: function() {
                // Hide preloader after content is loaded
                $('#preloader').removeClass('active');
            }
        });
    });

    $(document).on('click', '.add-to-cart-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const productId = $(this).data('product-id');
        const $btn = $(this);

        // Show loading state
        $btn.html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: custom_ajax_shop.ajax_url,
            type: 'POST',
            data: {
                action: 'add_to_cart_ajax',
                product_id: productId,
                quantity: 1
            },
            success: function (response) {
                if (response.success) {
                    $btn.html('<i class="fas fa-check"></i>');
                    $btn.css('background', '#28a745');

                    // Update cart fragments if available
                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        $(document.body).trigger('wc_fragment_refresh');
                    }

                    // Reset button after 2 seconds
                    setTimeout(() => {
                        $btn.html('<i class="fas fa-shopping-cart"></i>');
                        $btn.css('background', 'rgba(255, 255, 255, 0.9)');
                    }, 2000);
                } else {
                    $btn.html('<i class="fas fa-times"></i>');
                    $btn.css('background', '#dc3545');

                    setTimeout(() => {
                        $btn.html('<i class="fas fa-shopping-cart"></i>');
                        $btn.css('background', 'rgba(255, 255, 255, 0.9)');
                    }, 2000);
                }
            },
            error: function () {
                $btn.html('<i class="fas fa-times"></i>');
                $btn.css('background', '#dc3545');

                setTimeout(() => {
                    $btn.html('<i class="fas fa-shopping-cart"></i>');
                    $btn.css('background', 'rgba(255, 255, 255, 0.9)');
                }, 2000);
            }
        });
    });

    // Hamburger menu toggle for sidebar
    $('#menu-icon').on('click', function () {
        $('.custom-filter-sidebar').toggleClass('active');
    });
});

// SubCategory Toggle class for dropdown functionality
class SubCategoryToggle {
    constructor() {
        this.toggleIcons = document.querySelectorAll('.custom-filter-sidebar .toggle-icon');
        this.init();
    }

    init() {
        this.toggleIcons.forEach(icon => {
            icon.addEventListener('click', this.handleToggleClick.bind(this));
        });
    }

    handleToggleClick(e) {
        const li = e.target.closest('li');
        const dropdown = e.target.nextElementSibling;

        if (dropdown && dropdown.classList.contains('sub-category-dropdown')) {
            const isOpen = dropdown.style.display === 'block';
            dropdown.style.display = isOpen ? 'none' : 'block';
            e.target.classList.toggle('rotate', !isOpen);
            li.classList.toggle('active', !isOpen);
        }
    }
}

// Initialize SubCategoryToggle class on DOMContentLoaded
document.addEventListener("DOMContentLoaded", function () {
    new SubCategoryToggle();
});