# Custom AJAX Shop

A **WooCommerce extension plugin** that creates a fully AJAX-powered shop page with filters and add-to-cart functionality.  
It improves the shopping experience by allowing customers to filter products and add them to their cart **without reloading the page**.

---

Plugin Preview: https://www.stompitparts.com/shop-2/
<br/>
Plugin Overview:

<img width="1269" height="578" alt="image" src="https://github.com/user-attachments/assets/a55f2b1d-50bd-492b-bbf5-4b2ddfecc233" />
<img width="1270" height="762" alt="image" src="https://github.com/user-attachments/assets/7bec3917-0983-4b81-8cd6-8d86d3c75c5e" />

## 🚀 Features

- ✅ **AJAX-powered shop page** (no page reloads)  
- ✅ **Custom shortcode** `[custom_ajax_shop]` to render shop anywhere  
- ✅ **Filter products** by:
  - Category
  - Search keyword
  - Price range (under $25, $25–50, $50–100, above $100)
  - Rating (Top-rated, 4★ and above)
- ✅ **AJAX Add-to-Cart** support (works for guests too)  
- ✅ **Customizable product grid and filter sidebar templates**  
- ✅ **Font Awesome 6 icons included**  
- ✅ **Pagination support via AJAX**  

---

## 📦 Installation

1. Download or clone this repository into your WordPress `wp-content/plugins/` directory.
   ```bash
   wp-content/plugins/custom-ajax-shop/

2. Activate the plugin in WordPress Admin → Plugins.

3. Make sure WooCommerce is installed and activated.

4. Create or edit a page and insert the shortcode:
[custom_ajax_shop]

🛠️ Usage

Once you place the [custom_ajax_shop] shortcode on a page:

A filter sidebar and product grid will render.

Customers can:

Search products

Filter by category

Select price ranges

Show only top-rated products

The results update instantly via AJAX (no page reload).

Products can be added to the cart with AJAX.


📂 Plugin Structure

custom-ajax-shop/
│
├── assets/
│   ├── style.css        # Custom styles for the shop
│   └── script.js        # Handles AJAX requests for filters & cart
│
├── templates/
│   ├── filter-sidebar.php       # Sidebar filter UI
│   ├── product-grid.php         # Product grid container
│   └── custom-product-card.php  # Single product card template
│
├── custom-ajax-shop.php   # Main plugin file

⚙️ Hooks & AJAX Endpoints

AJAX Endpoints

custom_filter_products → Filters products dynamically

add_to_cart_ajax → Adds product to cart via AJAX

Hooks Used

wp_enqueue_scripts → Load styles & scripts

init → Ensure cart/session is available for guests

wp_ajax_* / wp_ajax_nopriv_* → Handle AJAX for logged-in & guest users

💡 Customization

Templates are located in templates/.

You can override custom-product-card.php, filter-sidebar.php, or product-grid.php to change the layout/design.

Styles can be adjusted in assets/style.css.

📝 Requirements

WordPress 6.0+

WooCommerce 7.0+

PHP 7.4+

👨‍💻 Author

mohim

Feel free to extend or modify this plugin as needed. Contributions are welcome!


