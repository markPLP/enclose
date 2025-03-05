<?php 

add_action('rest_api_init', 'customMegaMenuRestRoute');

function customMegaMenuRestRoute() {
  register_rest_route('customMegaMenu/v1', 'mega-menu', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'getMegaMenuItems'
  ));
}

function getMegaMenuItems() {
  $results = array(
    'products'      => array(),
    'menu'          => array(),
    'allCategories' => array(),
  );

  $mainQuery = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => 5,
    'tax_query' => array(
        array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => 'clothing' // Change this to your desired category slug
        ),
    ),
  ));

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();
    // global $product;

    $product = wc_get_product(get_the_ID()); // Get the WooCommerce product object

    if ($product) {
      array_push($results['products'], array(
        'title'       => get_the_title(),
        'permalink'   => get_permalink(),
        'thumbnail'   => get_the_post_thumbnail_url(get_the_ID(), 'full'),
        'price'       => $product->get_price_html(), // Returns formatted price with currency symbol
        'cart_url'    => $product->add_to_cart_url(),
        'cart_text'   => $product->add_to_cart_text(),
      ));
    }
  }

  wp_reset_postdata(); // Always reset post data after WP_Query

  // Fetch menu items and add them to the results
  $menu_name = 'main_menu'; // Change to your actual menu slug
  $menu_items = get_menu_items_by_name(['menu_name' => $menu_name]);

  // Ensure `$menu_items` is an array before adding
  if (!is_wp_error($menu_items) && is_array($menu_items)) {
      array_push($results['menu'], ...$menu_items);
  }

  $all_categories = get_woocommerce_categories_directly();

  if (!is_wp_error($all_categories) && is_array($all_categories)) {
      array_push($results['allCategories'], ...$all_categories);
  }

  // $results['categories'] = $all_categories;

  return rest_ensure_response($results);
}

/**
* get main navigation items by menu name
 */

function get_menu_items_by_name($request) {
  $menu_name = sanitize_text_field($request['menu_name']);

  // Try to get cached menu
  $cached_menu = get_transient("menu_items_{$menu_name}");
  if ($cached_menu) {
    return $cached_menu;
  }

  // Fetch menu items if not cached
  $menu = wp_get_nav_menu_object($menu_name);
  if (!$menu) {
    return [];
  }

  $menu_items = wp_get_nav_menu_items($menu->term_id);
  $filtered_items = array_map(function ($item) {
    return [
      'title' => $item->title,
      'url'   => $item->url,
    ];
  }, array_filter($menu_items, fn($item) => $item->menu_item_parent == 0));

  // Cache the menu for 12 hours
  set_transient("menu_items_{$menu_name}", $filtered_items, 12 * HOUR_IN_SECONDS);

  return $filtered_items;
}

/**
* Get all product categories
 */

function get_woocommerce_categories_directly() {
  $args = array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true, // Set to false to include empty categories
  );

  $categories = get_terms($args);

  if (empty($categories) || is_wp_error($categories)) {
    return rest_ensure_response(['error' => 'No categories found']);
  }

  $formatted_categories = array_map(function ($category) {
    return [
    'id' => $category->term_id,
    'name' => $category->name,
    'slug' => $category->slug,
    'count' => $category->count,
    ];
  }, $categories);

  return $formatted_categories;
}