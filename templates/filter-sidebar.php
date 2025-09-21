<div class="custom-filter-sidebar">
    <div id="catagory_filter" class="catagory_filter">
        <ul>
            <?php
        $parent_terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0
        ]);
		
        foreach ($parent_terms as $parent) {
//             echo "<li class='has-children'>";
//             echo "<a href='#' data-category='{$parent->slug}'>{$parent->name}</a>";

            // Get subcategories
            $child_terms = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => $parent->term_id
            ]);

			$li_class = !empty($child_terms) ? 'category-list-item has-children' : 'category-list-item';
			
			echo "<li class='{$li_class}'>";
    		echo "<a href='#' data-category='{$parent->slug}'>{$parent->name}</a>";
			
            if (!empty($child_terms)) {
                // Toggle icon
                echo "<i class='fa-solid fa-angle-right toggle-icon'></i>";

                echo "<ul class='sub-category-dropdown' style='display: none;'>";
                foreach ($child_terms as $child) {
                    echo "<li><a href='#' data-category='{$child->slug}'>{$child->name}</a></li>";
                }
                echo "</ul>";
            }

            echo "</li>";
        }
        ?>
        </ul>
    </div>


    <div id="price_filter" class="price_filter">
        <h3>Price</h3>
        <ul>
            <li>
                <label for="under-25">
                    Under $25
                </label>
                <input type="checkbox" name="price_filter" id="under-25" value="under-25">
            </li>
            <li>
                <label for="25-50">
                    $25 - $50
                </label>
                <input type="checkbox" name="price_filter" id="25-50" value="25-50">
            </li>
            <li>
                <label for="50-100">
                    $50 - $100
                </label>
                <input type="checkbox" name="price_filter" id="50-100" value="50-100">
            </li>
            <li>
                <label for="above-100">
                    Above $100
                </label>
                <input type="checkbox" name="price_filter" id="above-100" value="above-100">
            </li>
        </ul>
    </div>

    <div id="top_rated_filter" class="top_rated_filter">
        <h3>Rating</h3>
        <ul>
            <li>
                <label for="top-rated">
                    Top Rated
                </label>
                <input type="checkbox" name="rating_filter" id="top-rated" value="top-rated">
            </li>
        </ul>
    </div>

</div>