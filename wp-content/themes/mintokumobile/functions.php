<?php
/**
 * Theme functions
 * @package mintokumobile
 */

defined('_S_VERSION') || define('_S_VERSION', '1.0.0');

/**
 * Theme setup
 */
function mintokumobile_setup(): void
{
    load_theme_textdomain('mintokumobile', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-background', ['default-color'=>'ffffff']);
    add_theme_support('custom-logo', ['height'=>250,'width'=>250,'flex-width'=>true,'flex-height'=>true]);

    register_nav_menus([
        'menu-1' => esc_html__('Primary', 'mintokumobile'),
    ]);
}
add_action('after_setup_theme','mintokumobile_setup');

/**
 * Content width
 */
add_action('after_setup_theme', function(){
    $GLOBALS['content_width'] = apply_filters('mintokumobile_content_width', 640);
}, 0);

/**
 * Widget init
 */
add_action('widgets_init', function(){
    register_sidebar([
        'name'          => esc_html__('Sidebar', 'mintokumobile'),
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
});

/**
 * Scripts & Styles
 */
function mintokumobile_scripts(): void
{
    wp_enqueue_style('mintokumobile-style', get_stylesheet_uri(), [], _S_VERSION);
    wp_style_add_data('mintokumobile-style', 'rtl', 'replace');
    wp_enqueue_script('mintokumobile-navigation', get_template_directory_uri().'/js/navigation.js', [], _S_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')){
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts','mintokumobile_scripts');

/**
 * Include modules
 */
foreach([
            'custom-header',
            'template-tags',
            'template-functions',
            'customizer',
            ( defined('JETPACK__VERSION') ? 'jetpack' : null )
        ] as $file) {
    if ($file) {
        $path = get_template_directory()."/inc/{$file}.php";
        if (file_exists($path)) require_once $path;
    }
}


function enqueue_swiper_script()
{
    // CSS libs
    $css_libs = [
            'swiper-css'      => ['https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/css/swiper.min.css', '4.2.0'],
            'select2-css'     => ['https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', '4.1.0'],
            'flag-icons-css'  => ['https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css', '7.2.3'],
            'fontawesome-css' => ['https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', '6.0.0'],
    ];

    foreach ($css_libs as $handle => $data) {
        wp_enqueue_style($handle, $data[0], [], $data[1]);
    }
    wp_enqueue_style(
            'theme-style',
            get_template_directory_uri().'/css/style.css',
            [],
            filemtime(get_template_directory().'/css/style.css')
    );

    // JS libs
    wp_enqueue_script('jquery');
    $js_libs = [
            'swiper-js'  => ['https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.0/js/swiper.min.js','4.2.0'],
            'select2-js' => ['https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js','4.1.0'],
    ];

    foreach ($js_libs as $handle => $data) {
        wp_enqueue_script($handle, $data[0], ['jquery'], $data[1], true);
    }

    wp_enqueue_script(
            'theme-script',
            get_template_directory_uri().'/js/script.js',
            ['jquery', 'swiper-js', 'select2-js'],
            filemtime(get_template_directory().'/js/script.js'),
            true
    );

    wp_localize_script('theme-script', 'ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
    ]);
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

    // Thailand
    register_post_type('thailand', array(
            'labels' => array(
                    'name' => __('Jobs Thailand'),
                    'singular_name' => __('Jobs Thailand')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'thailand', 'with_front' => true),
//            'supports' => array('title', 'thumbnail'),
    ));
}

add_action('init', 'create_custom_post_types');

function register_country_taxonomies(): void
{
    // Danh sách post type (theo quốc gia)
    $countries = ['vietnam', 'indonesia', 'thailand'];

    // Danh sách taxonomy theo nhóm
    $taxonomies = [
            'province'   => [
                    'slug'   => 'province',
                    'label'  => 'Province',
            ],
            'year'       => [
                    'slug'   => 'year_r',
                    'label'  => 'Year',
            ],
            'university' => [
                    'slug'   => 'university',
                    'label'  => 'University',
            ],
            'company'    => [
                    'slug'   => 'company',
                    'label'  => 'Company',
            ],
    ];

    foreach ($taxonomies as $tax_key => $tax_data) {
        foreach ($countries as $country) {
            register_taxonomy(
                    "{$tax_key}_{$country}",    // id taxonomy
                    $country,                  // post type
                    array(
                            'label'        => __($tax_data['label'] . ' ' . ucfirst($country), 'textdomain'),
                            'public'       => true,
                            'hierarchical' => true,
                            'rewrite'      => array(
                                    'slug'       => $tax_data['slug'],
                                    'with_front' => true,
                            ),
                    )
            );
        }
    }
}
add_action('init', 'register_country_taxonomies');


add_filter('query_vars', 'add_custom_query_vars');
function add_custom_query_vars($vars)
{
    $countries = ['vietnam', 'indonesia', 'thailand'];   // chỉ cần thêm tên post-type quốc gia vào đây

    // alias dùng chung nếu bạn có filter "year_r" cho tất cả
    $vars[] = 'year_r';

    foreach ($countries as $country) {
        $vars[] = 'province_'   . $country;
        $vars[] = 'university_' . $country;
        $vars[] = 'company_'    . $country;
        $vars[] = 'year_'       . $country;
    }

    return $vars;
}



add_action('wp_ajax_load_filters', 'ajax_load_filters');
add_action('wp_ajax_nopriv_load_filters', 'ajax_load_filters');

function ajax_load_filters()
{
    $post_type = sanitize_text_field($_GET['post_type'] ?? '');

    // Nếu post type là 1 trong các quốc gia bạn cho phép
    $countries = ['vietnam', 'indonesia', 'thailand'];
    if (in_array($post_type, $countries, true)) {
        $prefix = $post_type;

        $taxonomies = [
                'provinces'    => "province_{$prefix}",
                'universities' => "university_{$prefix}",
                'years'        => "year_{$prefix}",
        ];

        $results = [];
        foreach ($taxonomies as $key => $taxonomy) {
            $terms = get_terms([
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
            ]);
            $results[$key] = array_map(function ($term) {
                return [
                        'slug' => $term->slug,
                        'name' => $term->name,
                ];
            }, $terms);
        }

        wp_send_json_success($results);
    }

    wp_send_json_error();
}


// Xử lý Ajax để tìm kiếm bài viết theo các điều kiện
function ajax_search_posts()
{
    $post_type      = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'vietnam';
    $province_slug  = isset($_GET['province_slug']) ? sanitize_text_field($_GET['province_slug']) : '';
    $university_slug= isset($_GET['university_slug']) ? sanitize_text_field($_GET['university_slug']) : '';
    $year_slug      = isset($_GET['year_slug']) ? sanitize_text_field($_GET['year_slug']) : '';

    // Thêm thailand tại đây
    $allowed_post_types = array('vietnam', 'indonesia', 'thailand');
    if (!in_array($post_type, $allowed_post_types)) {
        wp_send_json_error('Post type không hợp lệ.');
        wp_die();
    }

    $prefix = $post_type;
    $args   = array(
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'tax_query'      => array('relation' => 'AND'),
    );

    if (!empty($province_slug)) {
        $args['tax_query'][] = array(
                'taxonomy' => "province_{$prefix}",
                'field'    => 'slug',
                'terms'    => $province_slug,
        );
    }
    if (!empty($university_slug)) {
        $args['tax_query'][] = array(
                'taxonomy' => "university_{$prefix}",
                'field'    => 'slug',
                'terms'    => $university_slug,
        );
    }
    if (!empty($year_slug)) {
        $args['tax_query'][] = array(
                'taxonomy' => "year_{$prefix}",
                'field'    => 'slug',
                'terms'    => $year_slug,
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $posts = array();
        while ($query->have_posts()) {
            $query->the_post();
            $posts[] = array(
                    'title' => get_the_title(),
                    'link'  => get_permalink(),
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
    $post_type        = sanitize_text_field($_GET['region']);
    $province_slug    = sanitize_text_field($_GET['province']);
    $university_slug  = sanitize_text_field($_GET['university']);
    $year_slug        = sanitize_text_field($_GET['year_r']);
    $company_slug     = sanitize_text_field($_GET['company']);
    $search_query     = sanitize_text_field($_GET['search_query'] ?? '');

    // Thêm 'thailand' vào đây
    $allowed_post_types = ['vietnam', 'indonesia', 'thailand'];
    if (!in_array($post_type, $allowed_post_types, true)) {
        wp_send_json_error('Invalid post type.');
        wp_die();
    }

    $args = [
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            's'              => $search_query,
            'tax_query'      => ['relation' => 'AND'],
    ];

    $prefix = $post_type;

    if (!empty($province_slug)) {
        $args['tax_query'][] = [
                'taxonomy' => "province_{$prefix}",
                'field'    => 'slug',
                'terms'    => $province_slug,
        ];
    }
    if (!empty($university_slug)) {
        $args['tax_query'][] = [
                'taxonomy' => "university_{$prefix}",
                'field'    => 'slug',
                'terms'    => $university_slug,
        ];
    }
    if (!empty($year_slug)) {
        $args['tax_query'][] = [
                'taxonomy' => "year_{$prefix}",
                'field'    => 'slug',
                'terms'    => $year_slug,
        ];
    }
    if (!empty($company_slug)) {
        $args['tax_query'][] = [
                'taxonomy' => "company_{$prefix}",
                'field'    => 'slug',
                'terms'    => $company_slug,
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $posts = [];

        while ($query->have_posts()) {
            $query->the_post();

            $get_first_term_name = function ($taxonomy) {
                $terms = get_the_terms(get_the_ID(), $taxonomy);
                return (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'N/A';
            };

            $get_all_term_names = function ($taxonomy) {
                $terms = get_the_terms(get_the_ID(), $taxonomy);
                return (!empty($terms) && !is_wp_error($terms)) ? array_map(fn($t) => $t->name, $terms) : [];
            };

            $posts[] = [
                    'title'         => get_the_title(),
                    'link'          => get_permalink(),
                    'province_name' => $get_first_term_name("province_{$prefix}"),
                    'university'    => $get_first_term_name("university_{$prefix}"),
                    'company_name'  => $get_first_term_name("company_{$prefix}"),
                    'year'          => $get_all_term_names("year_{$prefix}"),
            ];
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

    $selected_province   = isset($_GET['province'])   ? sanitize_text_field($_GET['province'])   : '';
    $selected_university = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
    $selected_company    = isset($_GET['company'])    ? sanitize_text_field($_GET['company'])    : '';
    $selected_year       = isset($_GET['year_r'])     ? sanitize_text_field($_GET['year_r'])     : '';

    $taxonomy_data = [];

    // Prefix động theo post_type (ví dụ: vietnam / indonesia / thailand ...)
    $prefix = sanitize_title($post_type);

    // 1. Province
    $taxonomy_data['provinces'] = get_terms([
            'taxonomy'   => 'province_' . $prefix,
            'hide_empty' => false,
    ]);

    // 2. University (kèm meta province_id)
    $universities = get_terms([
            'taxonomy'   => 'university_' . $prefix,
            'hide_empty' => false,
    ]);
    $taxonomy_data['universities'] = array_map(function ($term) {
        $term->province_id = get_term_meta($term->term_id, 'province_id', true);
        return $term;
    }, $universities);

    // 3. Company
    $taxonomy_data['company'] = get_terms([
            'taxonomy'   => 'company_' . $prefix,
            'hide_empty' => false,
    ]);

    // 4. Year
    $taxonomy_data['years'] = get_terms([
            'taxonomy'   => 'year_' . $prefix,
            'hide_empty' => false,
    ]);

    // --- Filter nếu user có chọn ---
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
    if ($selected_company) {
        $taxonomy_data['company'] = array_filter($taxonomy_data['company'], function ($term) use ($selected_company) {
            return $term->slug === $selected_company;
        });
    }
    if ($selected_year) {
        $taxonomy_data['years'] = array_filter($taxonomy_data['years'], function ($term) use ($selected_year) {
            return $term->slug === $selected_year;
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

function save_province_id_meta_generic($term_id, $tt_id, $taxonomy): void
{
    if (str_starts_with($taxonomy, 'university_') && isset($_POST['province_id'])) {
        update_term_meta($term_id, 'province_id', intval($_POST['province_id']));
    }
}
add_action('created_term', 'save_province_id_meta_generic', 10, 3);
add_action('edited_term', 'save_province_id_meta_generic', 10, 3);


// Lưu lại province_id khi tạo hoặc chỉnh sửa term
function save_province_id_for_university($term_id): void
{
    if (!empty($_POST['province_id'])) {
        update_term_meta($term_id, 'province_id', intval($_POST['province_id']));
    } else {
        delete_term_meta($term_id, 'province_id'); // Xóa nếu không có giá trị được chọn
    }
}

add_action('created_university_vietnam', 'save_province_id_for_university', 10, 2);
add_action('edited_university_vietnam', 'save_province_id_for_university', 10, 2);

require_once get_template_directory().'/inc/taxonomy-university.php';
require_once get_template_directory().'/inc/helpers-job.php';
require_once get_template_directory() . '/inc/shortcode-acf-recommended-work.php';
require_once get_template_directory().'/inc/admin-taxonomy-meta-box.php';
require_once get_template_directory().'/inc/taxonomy-university.php';

function update_post_view_time(): void
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

function get_companies_by_university(): void
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

function get_company_job_counts(): void
{
    $region = sanitize_text_field($_GET['region'] ?? '');
    $province_slug = sanitize_text_field($_GET['province'] ?? '');
    $university_slug = sanitize_text_field($_GET['university'] ?? '');
    $year = sanitize_text_field($_GET['year_r'] ?? '');

    if (!in_array($region, ['vietnam', 'indonesia', 'laos', 'cambodia'])) {
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
                'key' => 'year_vietnam',
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
function start_session(): void
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