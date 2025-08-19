<?php

/**
 * mintokumobile functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mintokumobile
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mintokumobile_setup()
{

    load_theme_textdomain('mintokumobile', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');

    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    register_nav_menus(
        array(
            'menu-1' => esc_html__('Primary', 'mintokumobile'),
        )
    );

    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
    add_theme_support(
        'custom-background',
        apply_filters(
            'mintokumobile_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    add_theme_support(
        'custom-logo',
        array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        )
    );
}

add_action('after_setup_theme', 'mintokumobile_setup');

function mintokumobile_content_width()
{
    $GLOBALS['content_width'] = apply_filters('mintokumobile_content_width', 640);
}

add_action('after_setup_theme', 'mintokumobile_content_width', 0);


function mintokumobile_widgets_init()
{
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar', 'mintokumobile'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'mintokumobile'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}

add_action('widgets_init', 'mintokumobile_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function mintokumobile_scripts()
{
    wp_enqueue_style('mintokumobile-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_style_add_data('mintokumobile-style', 'rtl', 'replace');

    wp_enqueue_script('mintokumobile-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'mintokumobile_scripts');


require get_template_directory() . '/inc/custom-header.php';


require get_template_directory() . '/inc/template-tags.php';


require get_template_directory() . '/inc/template-functions.php';

require get_template_directory() . '/inc/customizer.php';


if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

function enqueue_swiper_script()
{
    // Enqueue Swiper CSS và các file style khác
    wp_enqueue_style('swiper-css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/css/swiper.min.css');
    wp_enqueue_style('style-css', get_template_directory_uri() . '/css/style.css');

    // Enqueue Select2 CSS
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');

    // Enqueue Flag Icons CSS
    wp_enqueue_style('flag-icons-css', 'https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css');


    // Enqueue font awesome CSS
    wp_enqueue_style('awesomes-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue các file script
    wp_enqueue_script('jquery-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');
    wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/js/swiper.min.js');

    // Enqueue Select2 JS
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), null, true);

    // Enqueue script custom
    wp_enqueue_script('my-ajax-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);

    // Truyền ajaxurl vào script với đúng handle
    wp_localize_script('my-ajax-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_script');


// Đăng ký custom post types cho ba quốc gia
function create_custom_post_types()
{
    // Vietnam
    register_post_type('vietnam', array(
        'labels' => array(
            'name' => __('Jobs Việt Nam'),
            'singular_name' => __('Jobs Việt Nam')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'vietnam', 'with_front' => true),
        //        'supports' => array('title', 'editor', 'thumbnail'),
        'supports' => array('title', 'thumbnail'),
    ));


    // Indonesia
    register_post_type('indonesia', array(
        'labels' => array(
            'name' => __('Jobs Indonesia'),
            'singular_name' => __('Jobs Indonesia')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'indonesia', 'with_front' => true),
        //        'supports' => array('title', 'editor', 'thumbnail'),
        'supports' => array('title', 'thumbnail'),
    ));

    // Laos
    //    register_post_type('laos', array(
    //        'labels' => array(
    //            'name' => __('Laos Jobs'),
    //            'singular_name' => __('Laos Jobs')
    //        ),
    //        'public' => true,
    //        'has_archive' => true,
    //        'rewrite' => array('slug' => 'laos', 'with_front' => true),
    //        'supports' => array('title', 'editor', 'thumbnail'),
    //    ));

    // Cambodia
    //    register_post_type('cambodia', array(
    //        'labels' => array(
    //            'name' => __('Cambodia Jobs'),
    //            'singular_name' => __('Cambodia Jobs')
    //        ),
    //        'public' => true,
    //        'has_archive' => true,
    //        'rewrite' => array('slug' => 'cambodia', 'with_front' => true),
    //        'supports' => array('title', 'editor', 'thumbnail'),
    //    ));
}

add_action('init', 'create_custom_post_types');

// Đăng ký taxonomy riêng cho mỗi quốc gia
function create_province_taxonomies()
{
    // Taxonomy cho Vietnam
    register_taxonomy('province_vietnam', 'vietnam', array(
        'labels' => array(
            'name' => __('Province'),
            'singular_name' => __('Vietnam Province')
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'province', 'with_front' => true),
    ));

    // Taxonomy cho Indonesia
    register_taxonomy('province_indonesia', 'indonesia', array(
        'labels' => array(
            'name' => __('Province'),
            'singular_name' => __('Vietnam Indonesia')
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'province', 'with_front' => true),
    ));


    // Taxonomy cho Laos
    register_taxonomy('province_laos', 'laos', array(
        'labels' => array(
            'name' => __('Laos Provinces'),
            'singular_name' => __('Laos Province')
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'province', 'with_front' => true),
    ));

    // Taxonomy cho Cambodia
    register_taxonomy('province_cambodia', 'cambodia', array(
        'labels' => array(
            'name' => __('Cambodia Provinces'),
            'singular_name' => __('Cambodia Province')
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'province', 'with_front' => true),
    ));
}

add_action('init', 'create_province_taxonomies');


function create_year_taxonomy()
{

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('year_vietnam', 'vietnam', array(
        'label' => __('Year', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'year_r', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));

    // Đăng ký taxonomy cho Indonesia
    register_taxonomy('year_indonesia', 'indonesia', array(
        'label' => __('Year', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'year_r', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));

    // Đăng ký taxonomy cho Laos
    register_taxonomy('year_laos', 'laos', array(
        'label' => __('Year Laos', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'year_r', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => false,
    ));

    // Đăng ký taxonomy cho Cambodia
    register_taxonomy('year_cambodia', 'cambodia', array(
        'label' => __('Year Cambodia', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'year_r', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => false,
    ));
}

add_action('init', 'create_year_taxonomy');


function create_university_taxonomy()
{

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('university_indonesia', 'indonesia', array(
        'label' => __('University', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'university', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('university_vietnam', 'vietnam', array(
        'label' => __('University', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'university', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));

    // Đăng ký taxonomy cho Laos
    register_taxonomy('university_laos', 'laos', array(
        'label' => __('University Laos', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'university', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => false,
    ));

    // Đăng ký taxonomy cho Cambodia
    register_taxonomy('university_cambodia', 'cambodia', array(
        'label' => __('University Cambodia', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'university', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => false,
    ));
}

add_action('init', 'create_university_taxonomy');

// Đăng ký taxonomy cho Company
function create_company_taxonomy()
{

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('company_vietnam', 'vietnam', array(
        'label' => __('Company Vietnam', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'company', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));

    // Đăng ký taxonomy cho Vietnam
    register_taxonomy('company_indonesia', 'indonesia', array(
        'label' => __('Company', 'textdomain'),
        'public' => true,
        'rewrite' => array(
            'slug' => 'company', // Điều chỉnh URL
            'with_front' => true,
        ),
        'hierarchical' => true,
    ));
}

add_action('init', 'create_company_taxonomy');


// Thêm vào functions.php
add_filter('query_vars', 'add_custom_query_vars');
function add_custom_query_vars($vars)
{
    // Vietnam filters
    $vars[] = 'year_r'; // alias chung nếu dùng chung cho cả hai
    $vars[] = 'province_vietnam';
    $vars[] = 'university_vietnam';
    $vars[] = 'company_vietnam';

    // Indonesia filters
    $vars[] = 'province_indonesia';
    $vars[] = 'university_indonesia';
    $vars[] = 'company_indonesia';
    $vars[] = 'year_indonesia';

    return $vars;
}


// Xử lý Ajax để nạp dữ liệu cho các trường chọn (province, university, year)
add_action('wp_ajax_load_filters', 'ajax_load_filters');
add_action('wp_ajax_nopriv_load_filters', 'ajax_load_filters');

function ajax_load_filters()
{
    $post_type = sanitize_text_field($_GET['post_type'] ?? '');

    if ($post_type === 'vietnam' || $post_type === 'indonesia') {
        $prefix = $post_type; // dùng để động tên taxonomy

        $provinces = get_terms([
            'taxonomy' => "province_{$prefix}",
            'hide_empty' => false,
        ]);

        $universities = get_terms([
            'taxonomy' => "university_{$prefix}",
            'hide_empty' => false,
        ]);

        $years = get_terms([
            'taxonomy' => "year_{$prefix}",
            'hide_empty' => false,
        ]);

        wp_send_json_success([
            'provinces' => array_map(function ($term) {
                return [
                    'slug' => $term->slug,
                    'name' => $term->name,
                ];
            }, $provinces),
            'universities' => array_map(function ($term) {
                return [
                    'slug' => $term->slug,
                    'name' => $term->name,
                ];
            }, $universities),
            'years' => array_map(function ($term) {
                return [
                    'slug' => $term->slug,
                    'name' => $term->name,
                ];
            }, $years),
        ]);
    }

    wp_send_json_error();
}




// Xử lý Ajax để tìm kiếm bài viết theo các điều kiện
function ajax_search_posts()
{
    // Lấy các tham số từ URL
    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'vietnam';
    $province_slug = isset($_GET['province_slug']) ? sanitize_text_field($_GET['province_slug']) : '';
    $university_slug = isset($_GET['university_slug']) ? sanitize_text_field($_GET['university_slug']) : '';
    $year_slug = isset($_GET['year_slug']) ? sanitize_text_field($_GET['year_slug']) : '';

    // Danh sách các post_type được cho phép
    $allowed_post_types = array('vietnam', 'indonesia', 'laos', 'cambodia');
    if (!in_array($post_type, $allowed_post_types)) {
        wp_send_json_error('Post type không hợp lệ.');
        wp_die();
    }

    // Tạo tiền tố taxonomy theo post_type
    $prefix = $post_type;

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'tax_query' => array('relation' => 'AND'),
    );

    if (!empty($province_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => "province_{$prefix}",
            'field' => 'slug',
            'terms' => $province_slug,
        );
    }

    if (!empty($university_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => "university_{$prefix}",
            'field' => 'slug',
            'terms' => $university_slug,
        );
    }

    if (!empty($year_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => "year_{$prefix}",
            'field' => 'slug',
            'terms' => $year_slug,
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $posts = array();
        while ($query->have_posts()) {
            $query->the_post();
            $posts[] = array(
                'title' => get_the_title(),
                'link' => get_permalink(),
            );
        }
        wp_send_json_success($posts);
    } else {
        wp_send_json_error('Không có bài viết nào.');
    }

    wp_die();
}


add_action('wp_ajax_nopriv_search_posts', 'ajax_search_posts');
add_action('wp_ajax_search_posts', 'ajax_search_posts');


function search_jobs()
{
    $post_type = sanitize_text_field($_GET['region']);
    $province_slug = sanitize_text_field($_GET['province']);
    $university_slug = sanitize_text_field($_GET['university']);
    $year_slug = sanitize_text_field($_GET['year_r']);
    $company_slug = sanitize_text_field($_GET['company']);
    $search_query = sanitize_text_field($_GET['search_query'] ?? '');

    // Danh sách post_type hợp lệ
    $allowed_post_types = ['vietnam', 'indonesia', 'laos', 'cambodia'];
    if (!in_array($post_type, $allowed_post_types)) {
        wp_send_json_error('Invalid post type.');
        wp_die();
    }

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        's' => $search_query,
        'tax_query' => array('relation' => 'AND'),
    );

    // Taxonomy prefix theo region/post_type
    $prefix = $post_type;

    if (!empty($province_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => "province_{$prefix}",
            'field' => 'slug',
            'terms' => $province_slug,
        );
    }

    if (!empty($university_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => "university_{$prefix}",
            'field' => 'slug',
            'terms' => $university_slug,
        );
    }

    if (!empty($year_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => "year_{$prefix}",
            'field' => 'slug',
            'terms' => $year_slug,
        );
    }

    if (!empty($company_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => "company_{$prefix}",
            'field' => 'slug',
            'terms' => $company_slug,
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $posts = array();

        while ($query->have_posts()) {
            $query->the_post();

            // Helper: Get first term name or fallback
            $get_first_term_name = function ($taxonomy) {
                $terms = get_the_terms(get_the_ID(), $taxonomy);
                return !empty($terms) && !is_wp_error($terms) ? $terms[0]->name : 'N/A';
            };

            // Helper: Get all term names for a taxonomy
            $get_all_term_names = function ($taxonomy) {
                $terms = get_the_terms(get_the_ID(), $taxonomy);
                return !empty($terms) && !is_wp_error($terms) ? array_map(fn($t) => $t->name, $terms) : [];
            };

            $posts[] = array(
                'title' => get_the_title(),
                'link' => get_permalink(),
                'province_name' => $get_first_term_name("province_{$prefix}"),
                'university' => $get_first_term_name("university_{$prefix}"),
                'company_name' => $get_first_term_name("company_{$prefix}"),
                'year' => $get_all_term_names("year_{$prefix}"),
            );
        }

        wp_send_json_success($posts);
    }

    wp_send_json_error();
    wp_die();
}



add_action('wp_ajax_search_jobs', 'search_jobs');
add_action('wp_ajax_nopriv_search_jobs', 'search_jobs');


function get_taxonomy_terms()
{
    $post_type = sanitize_text_field($_GET['region']);

    $selected_province = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
    $selected_university = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
    $selected_company = isset($_GET['company']) ? sanitize_text_field($_GET['company']) : '';
    $selected_year = isset($_GET['year_r']) ? sanitize_text_field($_GET['year_r']) : '';

    $taxonomy_data = [];

    $region_suffix = $post_type === 'indonesia' ? 'indonesia' : 'vietnam';

    // 1. Lấy province
    $taxonomy_data['provinces'] = get_terms([
        'taxonomy' => 'province_' . $region_suffix,
        'hide_empty' => false
    ]);

    // 2. Lấy university và bổ sung province_id
    $universities = get_terms([
        'taxonomy' => 'university_' . $region_suffix,
        'hide_empty' => false
    ]);
    $taxonomy_data['universities'] = array_map(function ($term) {
        $term->province_id = get_term_meta($term->term_id, 'province_id', true); // thêm field
        return $term;
    }, $universities);

    // 3. Lấy company
    $taxonomy_data['company'] = get_terms([
        'taxonomy' => 'company_' . $region_suffix,
        'hide_empty' => false
    ]);

    // 4. Lấy year
    $taxonomy_data['years'] = get_terms([
        'taxonomy' => 'year_' . $region_suffix,
        'hide_empty' => false
    ]);

    // 5. Optional: filter selected (không bắt buộc nếu bạn muốn load toàn bộ)
    if ($selected_province) {
        $taxonomy_data['provinces'] = array_filter($taxonomy_data['provinces'], function ($term) use ($selected_province) {
            return $term->slug === $selected_province;
        });
    }
    if ($selected_university) {
        $taxonomy_data['universities'] = array_filter($taxonomy_data['universities'], function ($term) use ($selected_university) {
            return $term->slug === $selected_university;
        });
    }
    if ($selected_year) {
        $taxonomy_data['years'] = array_filter($taxonomy_data['years'], function ($term) use ($selected_year) {
            return $term->slug === $selected_year;
        });
    }
    if ($selected_company) {
        $taxonomy_data['company'] = array_filter($taxonomy_data['company'], function ($term) use ($selected_company) {
            return $term->slug === $selected_company;
        });
    }

    if (!empty($taxonomy_data)) {
        wp_send_json_success($taxonomy_data);
    } else {
        wp_send_json_error();
    }
}


add_action('wp_ajax_get_taxonomy_terms', 'get_taxonomy_terms');
add_action('wp_ajax_nopriv_get_taxonomy_terms', 'get_taxonomy_terms');

//****************************************************************************************

// Thêm field để chọn province_id khi tạo term
function add_custom_fields_to_university_add($taxonomy)
{
    if (strpos($taxonomy, 'university_') !== 0) return;

    $region = str_replace('university_', '', $taxonomy);
    $province_taxonomy = 'province_' . $region;

    $provinces = get_terms(array(
        'taxonomy' => $province_taxonomy,
        'hide_empty' => false
    ));
?>
    <!-- Select Province -->
    <div class="form-field term-group">
        <label for="province_id"><?php _e('Select Province'); ?></label>
        <select name="province_id" id="province_id">
            <option value=""><?php _e('Please select a province'); ?></option>
            <?php foreach ($provinces as $province): ?>
                <option value="<?php echo esc_attr($province->term_id); ?>">
                    <?php echo esc_html($province->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Address -->
    <div class="form-field term-group">
        <label for="address"><?php _e('Address'); ?></label>
        <input type="text" name="address" id="address" />
    </div>

    <!-- Start and End Dates -->
    <div class="form-field term-group">
        <label for="start_date"><?php _e('Start Date'); ?></label>
        <input type="date" name="start_date" id="start_date" />
    </div>
    <div class="form-field term-group">
        <label for="end_date"><?php _e('End Date'); ?></label>
        <input type="date" name="end_date" id="end_date" />
    </div>

    <div class="form-field term-group">
        <label for="is_featured">
            <input type="checkbox" name="is_featured" id="is_featured" value="1">
            Mark as Featured
        </label>
    </div>

<?php
}

add_action('university_vietnam_add_form_fields', 'add_custom_fields_to_university_add', 10, 2);
add_action('university_indonesia_add_form_fields', 'add_custom_fields_to_university_add', 10, 2);


// Thêm field để chọn province_id khi chỉnh sửa term
function add_custom_fields_to_university_edit($term, $taxonomy)
{
    if (strpos($taxonomy, 'university_') !== 0) return;

    $region = str_replace('university_', '', $taxonomy);
    $province_taxonomy = 'province_' . $region;

    $provinces = get_terms(['taxonomy' => $province_taxonomy, 'hide_empty' => false]);
    $selected_province = get_term_meta($term->term_id, 'province_id', true);
    $address = get_term_meta($term->term_id, 'address', true);
    $start_date = get_term_meta($term->term_id, 'start_date', true);
    $end_date = get_term_meta($term->term_id, 'end_date', true);
?>
    <!-- Select Province -->
    <tr class="form-field">
        <th><label for="province_id">Select Province</label></th>
        <td>
            <select name="province_id" id="province_id">
                <option value="">Please select a province</option>
                <?php foreach ($provinces as $province): ?>
                    <option value="<?= esc_attr($province->term_id) ?>" <?= selected($selected_province, $province->term_id) ?>>
                        <?= esc_html($province->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>

    <!-- Address -->
    <tr class="form-field">
        <th><label for="address">Address</label></th>
        <td><input type="text" name="address" id="address" value="<?= esc_attr($address) ?>" /></td>
    </tr>

    <!-- Start and End Dates -->
    <tr class="form-field">
        <th><label for="start_date">Start Date</label></th>
        <td><input type="date" name="start_date" id="start_date" value="<?= esc_attr($start_date) ?>" /></td>
    </tr>
    <tr class="form-field">
        <th><label for="end_date">End Date</label></th>
        <td><input type="date" name="end_date" id="end_date" value="<?= esc_attr($end_date) ?>" /></td>
    </tr>

    <?php

    $is_featured = get_term_meta($term->term_id, 'is_featured', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="is_featured">Featured</label></th>
        <td>
            <label>
                <input type="checkbox" name="is_featured" id="is_featured" value="1" <?= checked($is_featured, '1', false) ?>>
                Mark this university as featured
            </label>
        </td>
    </tr>
    <?php
}

add_action('university_vietnam_edit_form_fields', 'add_custom_fields_to_university_edit', 10, 2);
add_action('university_indonesia_edit_form_fields', 'add_custom_fields_to_university_edit', 10, 2);



function save_university_term_meta($term_id)
{
    if (isset($_POST['province_id'])) {
        update_term_meta($term_id, 'province_id', sanitize_text_field($_POST['province_id']));
    }
    if (isset($_POST['address'])) {
        update_term_meta($term_id, 'address', sanitize_text_field($_POST['address']));
    }
    if (isset($_POST['start_date'])) {
        update_term_meta($term_id, 'start_date', sanitize_text_field($_POST['start_date']));
    }
    if (isset($_POST['end_date'])) {
        update_term_meta($term_id, 'end_date', sanitize_text_field($_POST['end_date']));
    }
}
add_action('edited_university_vietnam', 'save_university_term_meta', 10, 2);
add_action('created_university_vietnam', 'save_university_term_meta', 10, 2);
add_action('edited_university_indonesia', 'save_university_term_meta', 10, 2);
add_action('created_university_indonesia', 'save_university_term_meta', 10, 2);



function save_university_featured_meta($term_id)
{
    $is_featured = isset($_POST['is_featured']) ? '1' : '0';
    update_term_meta($term_id, 'is_featured', $is_featured);
}
add_action('created_university_indonesia', 'save_university_featured_meta', 10, 2);
add_action('edited_university_indonesia', 'save_university_featured_meta', 10, 2);
add_action('created_university_vietnam', 'save_university_featured_meta', 10, 2);
add_action('edited_university_vietnam', 'save_university_featured_meta', 10, 2);




// Thêm cột
function add_university_featured_column($columns)
{
    $columns['is_featured'] = 'Featured';
    return $columns;
}
add_filter('manage_edit-university_indonesia_columns', 'add_university_featured_column');
add_filter('manage_edit-university_vietnam_columns', 'add_university_featured_column');


// Hiển thị nội dung cột
function show_university_featured_column($content, $column_name, $term_id)
{
    if ($column_name === 'is_featured') {
        $is_featured = get_term_meta($term_id, 'is_featured', true);
        return $is_featured ? '✔️' : '';
    }
    return $content;
}
add_filter('manage_university_indonesia_custom_column', 'show_university_featured_column', 10, 3);
add_filter('manage_university_vietnam_custom_column', 'show_university_featured_column', 10, 3);


// Ẩn trường Description trong trang Add/Edit term
function hide_taxonomy_description_field()
{
    $screen = get_current_screen();
    if (strpos($screen->taxonomy, 'university_') === 0) {
        echo '<style>
            .term-description-wrap, .form-field.term-description-wrap {
                display: none !important;
            }
        </style>';
    }
}
add_action('admin_head', 'hide_taxonomy_description_field');


// Xoá cột "Description" khỏi danh sách terms
function remove_description_column($columns)
{
    if (isset($columns['description'])) {
        unset($columns['description']);
    }
    return $columns;
}
add_filter('manage_edit-university_indonesia_columns', 'remove_description_column');
add_filter('manage_edit-university_vietnam_columns', 'remove_description_column');



// Thêm cột 'Province' sau cột 'Description' cho university_vietnam và university_indonesia
add_filter('manage_edit-university_vietnam_columns', 'add_province_column_to_university_taxonomy');
add_filter('manage_edit-university_indonesia_columns', 'add_province_column_to_university_taxonomy');

function add_province_column_to_university_taxonomy($columns)
{
    $new_columns = [];

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Chèn cột 'province' sau cột 'description'
        if ($key === 'description') {
            $new_columns['province'] = __('Province');
        }
    }

    return $new_columns;
}

// Hiển thị dữ liệu của cột 'Province'
add_filter('manage_university_vietnam_custom_column', 'show_province_column_data', 10, 3);
add_filter('manage_university_indonesia_custom_column', 'show_province_column_data', 10, 3);

function show_province_column_data($out, $column_name, $term_id)
{
    if ($column_name === 'province') {
        $province_id = get_term_meta($term_id, 'province_id', true);
        if ($province_id) {
            $province = get_term($province_id);
            if ($province && !is_wp_error($province)) {
                return esc_html($province->name);
            }
        }
        return '<em>Chưa chọn</em>';
    }

    return $out;
}


function save_province_id_meta_generic($term_id, $tt_id, $taxonomy)
{
    if (strpos($taxonomy, 'university_') === 0 && isset($_POST['province_id'])) {
        update_term_meta($term_id, 'province_id', intval($_POST['province_id']));
    }
}
add_action('created_term', 'save_province_id_meta_generic', 10, 3);
add_action('edited_term', 'save_province_id_meta_generic', 10, 3);


// Lưu lại province_id khi tạo hoặc chỉnh sửa term
function save_province_id_for_university($term_id)
{
    if (isset($_POST['province_id']) && !empty($_POST['province_id'])) {
        update_term_meta($term_id, 'province_id', intval($_POST['province_id']));
    } else {
        delete_term_meta($term_id, 'province_id'); // Xóa nếu không có giá trị được chọn
    }
}

add_action('created_university_vietnam', 'save_province_id_for_university', 10, 2);
add_action('edited_university_vietnam', 'save_province_id_for_university', 10, 2);



function get_universities_by_province()
{
    $province_slug = trim($_POST['province_slug'] ?? '');
    $region = sanitize_text_field($_POST['region'] ?? 'vietnam');

    $province_taxonomy = 'province_' . $region;
    $university_taxonomy = 'university_' . $region;

    $province_term = get_term_by('slug', $province_slug, $province_taxonomy);
    if (!$province_term) {
        wp_send_json_error('Tỉnh thành không tồn tại.');
    }

    $province_id = $province_term->term_id;

    // 1. Lấy các trường từ meta province_id
    $universities_meta = get_terms(array(
        'taxonomy' => $university_taxonomy,
        'hide_empty' => false,
        'meta_query' => array(
            array(
                'key' => 'province_id',
                'value' => $province_id,
                'compare' => '='
            )
        )
    ));

    // 2. Lấy các trường từ các job có gắn tỉnh đó
    $job_posts = get_posts(array(
        'post_type' => 'job',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => $province_taxonomy,
                'field' => 'term_id',
                'terms' => $province_id
            )
        ),
        'fields' => 'ids'
    ));

    $university_ids_from_jobs = array();

    if (!empty($job_posts)) {
        $university_terms = wp_get_object_terms($job_posts, $university_taxonomy, ['fields' => 'all']);
        foreach ($university_terms as $term) {
            $university_ids_from_jobs[$term->term_id] = $term; // unique by ID
        }
    }

    // 3. Gộp tất cả vào mảng kết quả
    $result = array();

    if (!empty($universities_meta) && !is_wp_error($universities_meta)) {
        foreach ($universities_meta as $u) {
            $result[$u->term_id] = $u; // đảm bảo không trùng
        }
    }

    foreach ($university_ids_from_jobs as $id => $u) {
        $result[$id] = $u;
    }

    if (!empty($result)) {
        $university_data = array_map(function ($term) {
            return array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug
            );
        }, $result);

        wp_send_json_success(array_values($university_data)); // ✅ fix lỗi JS
    } else {
        wp_send_json_error('Không tìm thấy trường đại học nào.');
    }
}

add_action('wp_ajax_get_universities_by_province', 'get_universities_by_province');
add_action('wp_ajax_nopriv_get_universities_by_province', 'get_universities_by_province');


// Function to display noi_dung_1 and noi_dung_2 from slide_1 in Flexible Content field
function display_job_info_fields($post_id)
{
    // 1. Lấy giá trị region từ URL
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : 'vietnam';

    // 2. Xác định ngôn ngữ theo region
    switch (strtolower($region)) {
        case 'indonesia':
            $lang = 'id';
            break;
        case 'thailand':
            $lang = 'th';
            break;
        default:
            $lang = 'vi';
            break;
    }

    // 3. Hàm dịch (có thể tách riêng nếu cần)
    if (!function_exists('translate_content')) {
        function translate_content($text, $lang)
        {
            $translations = [
                'Thực tập sinh' => [
                    'id' => 'Magang',
                    'th' => 'ฝึกงาน',
                    'vi' => 'Thực tập sinh'
                ],
                'Kỹ năng đặc định' => [
                    'id' => 'Spesifikasi Keterampilan',
                    'th' => 'ทักษะเฉพาะทาง',
                    'vi' => 'Kỹ năng đặc định'
                ],
                'Kỹ sư, Nhân văn, Quốc tế' => [
                    'id' => 'Insinyur, Humaniora, Internasional',
                    'th' => 'วิศวกรรม มนุษยศาสตร์ สากล',
                    'vi' => 'Kỹ sư, Nhân văn, Quốc tế'
                ],
                'Việc làm tại Việt Nam' => [
                    'id' => 'Pekerjaan di Vietnam',
                    'th' => 'งานในเวียดนาม',
                    'vi' => 'Việc làm tại Việt Nam'
                ],
                'Việc làm tại Nhật Bản' => [
                    'id' => 'Pekerjaan di Jepang',
                    'th' => 'งานในญี่ปุ่น',
                    'vi' => 'Việc làm tại Nhật Bản'
                ]
            ];

            return $translations[$text][$lang] ?? $text;
        }
    }


    // 4. Lấy và hiển thị nội dung từ group field 'slide_1'
    $slide_1 = get_field('slide_1', $post_id);

    if (is_array($slide_1)) {
        $noi_dung_1 = $slide_1['noi_dung_1'] ?? '';
        $noi_dung_2 = $slide_1['noi_dung_2'] ?? '';

        // Xử lý class cho noi_dung_1
        $class_1 = '';
        switch ($noi_dung_1) {
            case 'Thực tập sinh':
            case 'Intern':
            case 'Technical Intern Trainee':
                $class_1 = 'color_1';
                break;
            case 'Kỹ năng đặc định':
            case 'Specified Skilled Worker':
            case 'Specified Skilled Worker (SSW)':
                $class_1 = 'color_2';
                break;
            case 'Kỹ sư, Nhân văn, Quốc tế':
            case 'Engineer, Humanities, International':
            case 'Engineer / Humanities / International Services':
                $class_1 = 'color_3';
                break;
        }

        // Xử lý class cho noi_dung_2
        $class_2 = '';
        switch ($noi_dung_2) {
            case 'Việc làm tại Việt Nam':
            case 'Jobs in Vietnam':
                $class_2 = 'red1';
                break;
            case 'Việc làm tại Nhật Bản':
            case 'Jobs in Japan':
                $class_2 = 'red2';
                break;
            case 'Việc làm tại Indonesia':
            case 'Jobs in Indonesia':
                $class_2 = 'red3';
                break;
        }

        // Hiển thị nội dung noi_dung_1
        if (!empty($noi_dung_1)) {
            echo '<div class="noi-dung-field-ab_1 ' . esc_attr($class_1) . '">';
            echo '<p>' . esc_html(translate_content($noi_dung_1, $lang)) . '</p>';
            echo '</div>';
        }

        // Hiển thị nội dung noi_dung_2
        if (!empty($noi_dung_2)) {
            echo '<div class="noi-dung-field-ab_2 ' . esc_attr($class_2) . '">';
            echo '<p>' . esc_html(translate_content($noi_dung_2, $lang)) . '</p>';
            echo '</div>';
        }
    }
}



function display_acf_recommended_work_slider($atts)
{

    // 1. Lấy region
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
    $allowed_regions = ['vietnam', 'indonesia', 'laos', 'cambodia'];
    if (!in_array($region, $allowed_regions)) {
        return '<p>Invalid region.</p>';
    }

    // 2. Xác định ngôn ngữ
    $lang = ($region === 'indonesia') ? 'id' : 'vi';
    $translations = [
        'vi' => ['salary' => 'Lương'],
        'id' => ['salary' => 'Gaji']
    ];


    // Xác định post_type và taxonomy theo region
    $post_type = $region;
    $taxonomy = "company_{$region}";

    // WP_Query cho bài viết được đánh dấu 'recommended_work'
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => 3,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'recommended_work',
                'value' => 'recommended',
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'recommended_work',
                'compare' => 'NOT EXISTS',
            ),
        ),
        'orderby' => array(
            'meta_value' => 'DESC',
            'date' => 'DESC',
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
    ?>
        <div class="box_slider jobs_recommend" id="box_slider">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="swiper-slide">
                            <a href="<?php the_permalink(); ?>">
                                <div class="avatar_job">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('medium');
                                    } else {
                                        echo '<img src="https://placehold.co/600x300" alt="Placeholder">';
                                    }
                                    display_job_info_fields(get_the_ID());
                                    ?>
                                </div>
                            </a>
                            <p class="title_job"><?php the_title(); ?></p>
                            <div class="content_p">
                                <div class="job_info">
                                    <div class="text_desc">
                                        <div class="hashtag">
                                            <a class="tag_item" href="#">土日祝休み</a>
                                            <a class="tag_item" href="#">昇給賞与あり</a>
                                            <a class="tag_item" href="#">個室あり</a>
                                            <a class="tag_item" href="#">夜勤あり</a>
                                        </div>
                                        
                                    </div>
                                    <div class="logo_box">
                                        <?php
                                            $terms = get_the_terms(get_the_ID(), $taxonomy);
                                            if ($terms && !is_wp_error($terms)) {
                                                $term = $terms[0];
                                                $company_logo = get_field('company_image', $term);
                                                if ($company_logo) {
                                                    echo '<div class="logo_company logo_company_recommended">';
                                                    echo '<img src="' . esc_url($company_logo['url']) . '" alt="' . esc_attr($company_logo['alt']) . '">';
                                                    echo '<p>' . esc_html($term->name) . '</p>';
                                                    echo '</div>';
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="salary">
                                    <span class="label_text"><?php echo $translations[$lang]['salary']; ?></span>
                                    <span class="salary_text">
                                        <?php
                                        $slide_2 = get_field('slide_2'); // ACF Group -> mảng
                                        echo esc_html($slide_2['noi_dung_1'] ?? '');
                                        ?>
                                    </span>
                                </div>
                                <div class="text_desc">
                                    <?php
                                    // Nếu ở Options Page thì dùng: get_field('slide_2', 'option')
                                    
                                    $slide_2 = get_field('slide_2');
                                    if ($slide_2) {
                                        $nd4 = trim($slide_2['noi_dung_4'] ?? '');
                                        $nd5 = trim($slide_2['noi_dung_5'] ?? '');

                                        if ($nd4 !== '') {
                                            echo '<div class="nd-block nd-4">' . nl2br(esc_html($nd4)) . '</div>';
                                        }
                                        if ($nd5 !== '') {
                                            echo '<div class="nd-block nd-5">' . nl2br(esc_html($nd5)) . '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                               
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="bl_dot">
                <div class="swiper-pagination"></div>
            </div>
        </div>
    <?php
        wp_reset_postdata();
        return ob_get_clean();
    } else {
        return '<p>No recommended work found.</p>';
    }
}

add_shortcode('acf_recommended_work_slider', 'display_acf_recommended_work_slider');


function enqueue_swiper_assets_for_acf()
{

    // Custom JS để khởi tạo Swiper
    wp_add_inline_script('swiper-js', "
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('#box_slider .swiper-container', {
                slidesPerView: 3, // Số slide mặc định
                spaceBetween: 10, // Khoảng cách giữa các slide
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    // Khi màn hình >= 640px
                    640: {
                        slidesPerView: 1, // Hiển thị 1 slide trên màn hình nhỏ
                        spaceBetween: 10,
                    },
                    // Khi màn hình >= 768px
                    768: {
                        slidesPerView: 1, // Hiển thị 2 slide trên màn hình tablet
                        spaceBetween: 20,
                    },
                    // Khi màn hình >= 1024px
                    1024: {
                        slidesPerView: 3, // Hiển thị 3 slide trên màn hình lớn
                        spaceBetween: 30,
                    }
                },
                loop: true
            });

        });
    ");
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_assets_for_acf');


// Xóa meta box mặc định của taxonomy
function remove_taxonomy_meta_box($taxonomy)
{
    remove_meta_box($taxonomy . 'div', 'vietnam', 'side');
}

// Hàm thêm meta box tùy chỉnh chung
function add_custom_taxonomy_meta_box($taxonomy, $label)
{
    add_meta_box(
        $taxonomy . '_taxonomy_meta_box', // ID của meta box
        $label, // Tiêu đề của meta box
        function ($post) use ($taxonomy) {
            render_custom_taxonomy_meta_box($post, $taxonomy);
        }, // Hàm render nội dung
        'vietnam', // Post type
        'side', // Vị trí
        'default' // Độ ưu tiên
    );
}

// Hàm render nội dung cho meta box
function render_custom_taxonomy_meta_box($post, $taxonomy)
{
    // Lấy danh sách các terms của taxonomy
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ));

    // Lấy các terms đã được gán cho bài viết hiện tại
    $selected_terms = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));

    // Ô tìm kiếm
    echo '<input type="text" id="search_' . esc_attr($taxonomy) . '_taxonomy" placeholder="Tìm kiếm..." style="width: 100%; margin-bottom: 10px;">';

    // Hiển thị danh sách checkbox các terms của taxonomy
    echo '<div id="' . esc_attr($taxonomy) . '-taxonomy-list">';
    if (!empty($terms)) {
        foreach ($terms as $term) {
            $checked = in_array($term->term_id, $selected_terms) ? 'checked="checked"' : '';
            echo '<div>';
            echo '<input style="margin-top: 0" type="checkbox" name="selected_' . esc_attr($taxonomy) . '_taxonomy[]" value="' . esc_attr($term->term_id) . '" id="' . esc_attr($taxonomy) . '_term_' . esc_attr($term->term_id) . '" ' . $checked . '>';
            echo '<label for="' . esc_attr($taxonomy) . '_term_' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</label>';
            echo '</div>';
        }
    } else {
        echo '<p>Không có mục nào được tìm thấy.</p>';
    }
    echo '</div>';

    // Nonce field để bảo mật
    wp_nonce_field('save_' . esc_attr($taxonomy) . '_meta_box', esc_attr($taxonomy) . '_meta_box_nonce');
}

// Hàm lưu dữ liệu được chọn từ meta box
function save_custom_taxonomy_meta_box($post_id, $taxonomy)
{
    // Kiểm tra bảo mật với nonce field
    if (!isset($_POST[$taxonomy . '_meta_box_nonce']) || !wp_verify_nonce($_POST[$taxonomy . '_meta_box_nonce'], 'save_' . $taxonomy . '_meta_box')) {
        return;
    }

    // Kiểm tra quyền chỉnh sửa bài viết
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Lưu các giá trị được chọn (terms đã được chọn từ checkbox)
    if (isset($_POST['selected_' . $taxonomy . '_taxonomy']) && is_array($_POST['selected_' . $taxonomy . '_taxonomy'])) {
        $selected_terms = array_map('intval', $_POST['selected_' . $taxonomy . '_taxonomy']);
        wp_set_object_terms($post_id, $selected_terms, $taxonomy);
    } else {
        wp_set_object_terms($post_id, array(), $taxonomy);
    }
}

// Hàm chung để thêm script tìm kiếm
function add_custom_search_script($taxonomy)
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#search_<?php echo esc_js($taxonomy); ?>_taxonomy').on('keyup', function() {
                var keyword = $(this).val().toLowerCase();
                $('#<?php echo esc_js($taxonomy); ?>-taxonomy-list div').each(function() {
                    var term = $(this).text().toLowerCase();
                    if (term.indexOf(keyword) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
<?php
}

// Xóa meta box mặc định của cả 'university_vietnam' và 'company_vietnam'
function remove_taxonomy_meta_boxes()
{
    remove_taxonomy_meta_box('university_vietnam');
    remove_taxonomy_meta_box('company_vietnam');
}

add_action('admin_menu', 'remove_taxonomy_meta_boxes');

// Thêm các meta box tùy chỉnh
function add_all_custom_meta_boxes()
{
    add_custom_taxonomy_meta_box('university_vietnam', __('Chọn trường đại học', 'textdomain'));
    add_custom_taxonomy_meta_box('company_vietnam', __('Chọn công ty', 'textdomain'));
}

add_action('add_meta_boxes', 'add_all_custom_meta_boxes');

// Lưu dữ liệu cho từng taxonomy
function save_all_taxonomy_meta_boxes($post_id)
{
    save_custom_taxonomy_meta_box($post_id, 'university_vietnam');
    save_custom_taxonomy_meta_box($post_id, 'company_vietnam');
}

add_action('save_post', 'save_all_taxonomy_meta_boxes');

// Thêm script tìm kiếm cho cả hai taxonomy
function add_all_search_scripts()
{
    add_custom_search_script('university_vietnam');
    add_custom_search_script('company_vietnam');
}

add_action('admin_footer', 'add_all_search_scripts');





if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Cài đặt chung',
        'menu_title' => 'Cài đặt chung',
        'menu_slug' => 'global-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

$custom_regions = ['vietnam', 'indonesia', 'laos', 'cambodia'];

function add_custom_columns($columns)
{
    global $current_screen;
    $post_type = $current_screen->post_type ?? '';

    if (!in_array($post_type, ['vietnam', 'indonesia', 'laos', 'cambodia'])) {
        return $columns;
    }

    $new_columns = [];

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        if ($key === 'title') {
            $new_columns["province_{$post_type}"] = __('Province', 'textdomain');
            $new_columns["university_{$post_type}"] = __('University', 'textdomain');
            $new_columns["company_{$post_type}"] = __('Company', 'textdomain');
            $new_columns['recommended_work'] = __('Recommended Work', 'textdomain');
        }
    }

    return $new_columns;
}

foreach (['vietnam', 'indonesia', 'laos', 'cambodia'] as $pt) {
    add_filter("manage_{$pt}_posts_columns", 'add_custom_columns');
}


function show_custom_columns($column, $post_id)
{
    global $post;
    $post_type = get_post_type($post_id);

    if (!in_array($post_type, ['vietnam', 'indonesia', 'laos', 'cambodia'])) {
        return;
    }

    // Province
    if ($column === "province_{$post_type}") {
        $terms = get_the_terms($post_id, "province_{$post_type}");
        echo !empty($terms) && !is_wp_error($terms) ? esc_html(implode(', ', wp_list_pluck($terms, 'name'))) : __('No province', 'textdomain');
    }

    // Company
    if ($column === "company_{$post_type}") {
        $terms = get_the_terms($post_id, "company_{$post_type}");
        echo !empty($terms) && !is_wp_error($terms) ? esc_html(implode(', ', wp_list_pluck($terms, 'name'))) : __('No company', 'textdomain');
    }

    // University
    if ($column === "university_{$post_type}") {
        $terms = get_the_terms($post_id, "university_{$post_type}");
        echo !empty($terms) && !is_wp_error($terms) ? esc_html(implode(', ', wp_list_pluck($terms, 'name'))) : __('No university', 'textdomain');
    }

    // Recommended Work
    if ($column === 'recommended_work') {
        $value = get_post_meta($post_id, 'recommended_work', true);

        if (is_array($value)) {
            echo implode(', ', $value);
        } elseif ($value === 'recommended') {
            echo '<span style="color: green;">Checked</span>';
        } elseif ($value) {
            echo '<span style="color: orange;">' . esc_html($value) . '</span>';
        } else {
            echo '<span style="color: red;">Not Checked</span>';
        }
    }
}

foreach (['vietnam', 'indonesia', 'laos', 'cambodia'] as $pt) {
    add_action("manage_{$pt}_posts_custom_column", 'show_custom_columns', 10, 2);
}


function make_columns_sortable($columns)
{
    global $current_screen;
    $post_type = $current_screen->post_type ?? '';

    if (!in_array($post_type, ['vietnam', 'indonesia', 'laos', 'cambodia'])) {
        return $columns;
    }

    $columns["province_{$post_type}"] = "province_{$post_type}";
    $columns["company_{$post_type}"] = "company_{$post_type}";
    $columns['recommended_work'] = 'recommended_work';

    return $columns;
}

foreach (['vietnam', 'indonesia', 'laos', 'cambodia'] as $pt) {
    add_filter("manage_edit-{$pt}_sortable_columns", 'make_columns_sortable');
}


function sort_recommended_column($query)
{
    if (!is_admin() || !$query->is_main_query()) return;

    $post_type = $_GET['post_type'] ?? '';
    if (!in_array($post_type, ['vietnam', 'indonesia', 'laos', 'cambodia'])) return;

    if (isset($_GET['orderby']) && $_GET['orderby'] === 'recommended_work') {
        $query->set('meta_key', 'recommended_work');
        $query->set('orderby', ['meta_value' => 'DESC', 'date' => 'DESC']);
    }

    if (!isset($_GET['orderby'])) {
        $query->set('meta_key', 'recommended_work');
        $query->set('orderby', ['meta_value' => 'DESC', 'date' => 'DESC']);
    }
}

add_action('pre_get_posts', 'sort_recommended_column');



function update_post_view_time()
{
    if (is_single()) {
        global $post;
        $current_time = current_time('mysql'); // Lấy thời gian hiện tại

        // Lưu thời gian truy cập vào post meta
        update_post_meta($post->ID, '_last_viewed', $current_time);
    }
}
add_action('wp_head', 'update_post_view_time');


function lay_noi_dung_field($slides, $field_name)
{
    // Đảm bảo $slides là mảng
    if (is_array($slides)) {
        foreach ($slides as $slide) {
            if (
                is_array($slide) &&
                isset($slide['acf_fc_layout']) &&
                $slide['acf_fc_layout'] === 'slide_2' &&
                !empty($slide[$field_name])
            ) {
                return $slide[$field_name];
            }
        }
    }

    return '';
}


function custom_page_title($title)
{
    if (is_page(250)) { // Thay 123 bằng ID của trang bạn muốn thay đổi
        $title = "Job Fair Đại Học Mở Thành Phố Hồ Chí Minh 2024-11";
    }
    return $title;
}
add_filter('pre_get_document_title', 'custom_page_title');


add_action('wp_ajax_get_companies_by_university', 'get_companies_by_university');
add_action('wp_ajax_nopriv_get_companies_by_university', 'get_companies_by_university');

function get_companies_by_university()
{
    $university_slug = sanitize_text_field($_POST['university_slug']);
    $region = sanitize_text_field($_POST['region']);

    $university = get_term_by('slug', $university_slug, 'university_' . $region);
    if (!$university) {
        wp_send_json_error(['message' => 'Trường không tồn tại']);
    }

    $args = [
        'post_type' => 'jobs_' . $region,
        'posts_per_page' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'university_' . $region,
                'field' => 'slug',
                'terms' => [$university_slug]
            ]
        ]
    ];
    $query = new WP_Query($args);

    $company_ids = [];
    foreach ($query->posts as $post) {
        $terms = wp_get_post_terms($post->ID, 'company_' . $region);
        foreach ($terms as $term) {
            $company_ids[$term->term_id] = $term;
        }
    }

    $result = array_values(array_map(function ($term) {
        return [
            'term_id' => $term->term_id,
            'name' => $term->name,
            'slug' => $term->slug
        ];
    }, $company_ids));

    wp_send_json_success($result);
}


add_action('wp_ajax_get_company_job_counts', 'get_company_job_counts');
add_action('wp_ajax_nopriv_get_company_job_counts', 'get_company_job_counts');

function get_company_job_counts()
{
    $region = sanitize_text_field($_GET['region'] ?? '');
    $province_slug = sanitize_text_field($_GET['province'] ?? '');
    $university_slug = sanitize_text_field($_GET['university'] ?? '');
    $year = sanitize_text_field($_GET['year_r'] ?? '');

    if (!$region || !in_array($region, ['vietnam', 'indonesia', 'laos', 'cambodia'])) {
        wp_send_json_error('Invalid region.');
    }

    $post_type = $region;
    $company_tax = "company_{$region}";
    $province_tax = "province_{$region}";
    $university_tax = "university_{$region}";

    $companies = get_terms([
        'taxonomy' => $company_tax,
        'hide_empty' => false
    ]);

    $results = [];

    foreach ($companies as $company) {
        $tax_query = [
            [
                'taxonomy' => $company_tax,
                'field' => 'term_id',
                'terms' => $company->term_id
            ]
        ];

        // Lọc theo tỉnh
        if (!empty($province_slug)) {
            $province = get_term_by('slug', $province_slug, $province_tax);
            if ($province) {
                $tax_query[] = [
                    'taxonomy' => $province_tax,
                    'field' => 'term_id',
                    'terms' => $province->term_id
                ];
            }
        }

        // Lọc theo trường đại học
        if (!empty($university_slug)) {
            $university = get_term_by('slug', $university_slug, $university_tax);
            if ($university) {
                $tax_query[] = [
                    'taxonomy' => $university_tax,
                    'field' => 'term_id',
                    'terms' => $university->term_id
                ];
            }
        }

        $meta_query = [];
        if (!empty($year)) {
            $meta_query[] = [
                'key' => 'year_vietnam', // ⚠️ tùy theo bạn đặt key là gì
                'value' => $year,
                'compare' => '='
            ];
        }

        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }

        $args = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'tax_query' => $tax_query
        ];

        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        if (!empty($search_query)) {
            $args['s'] = $search_query;
        }

        $query = new WP_Query($args);

        $count = $query->found_posts;

        if ($count > 0) {
            $results[] = [
                'id' => $company->term_id,
                'name' => $company->name,
                'slug' => $company->slug,
                'count' => $count
            ];
        }
    }

    wp_send_json_success($results);
}


// Bắt đầu session
function start_session()
{
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'start_session', 1);

// AJAX lưu region vào session
add_action('wp_ajax_save_region_session', 'save_region_session');
add_action('wp_ajax_nopriv_save_region_session', 'save_region_session');
function save_region_session()
{
    if (!session_id()) session_start();

    if (!empty($_POST['region'])) {
        $_SESSION['region'] = sanitize_text_field($_POST['region']);
    }

    wp_send_json_success(['region' => $_SESSION['region'] ?? null]);
}

// Cho phép nhận biến 'region' từ query string
function register_region_query_var($vars)
{
    $vars[] = 'region';
    return $vars;
}
add_filter('query_vars', 'register_region_query_var');

// Thêm rewrite rule để ánh xạ URL /messe/jobfair/region/{slug}
function region_rewrite_rule()
{
    add_rewrite_rule(
        '^messe/jobfair/region/([^/]+)/?$',
        'index.php?pagename=region&region=$matches[1]',
        'top'
    );
}
add_action('init', 'region_rewrite_rule');

// Hàm lấy ngôn ngữ hiện tại từ session
function get_current_lang()
{
    if (!session_id()) session_start();

    $region = $_SESSION['region'] ?? 'vietnam';

    switch ($region) {
        case 'indonesia':
            return 'id';
        case 'vietnam':
        default:
            return 'vi';
    }
}