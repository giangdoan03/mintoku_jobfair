<?php
get_header(); // Gọi header của theme

// Lấy các tham số tìm kiếm từ URL
// Lấy các tham số từ URL
$year = get_query_var('year');
$province_slug = get_query_var('province_vietnam');
$university_slug = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';

// Câu lệnh truy vấn cho WP_Query
$args = array(
    'post_type' => 'vietnam', // Hoặc tên post type của bạn
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'tax_query' => array('relation' => 'AND'),
);

// Thêm điều kiện lọc theo tỉnh nếu có
if ($province_slug) {
    $province_term = get_term_by('slug', $province_slug, 'province_vietnam');
    if ($province_term) {
        $args['tax_query'][] = array(
            'taxonomy' => 'province_vietnam',
            'field'    => 'slug',
            'terms'    => $province_term->slug,
            'operator' => 'IN',
        );
    }
}

// Thêm điều kiện lọc theo trường đại học nếu có
if ($university_slug) {
    $university_term = get_term_by('slug', $university_slug, 'province_vietnam');
    if ($university_term && $university_term->parent == $province_term->term_id) {
        $args['tax_query'][] = array(
            'taxonomy' => 'province_vietnam',
            'field'    => 'slug',
            'terms'    => $university_term->slug,
            'operator' => 'IN',
        );
    }
}

// Thêm điều kiện lọc theo năm nếu có
if ($year) {
    $args['meta_query'] = array(
        array(
            'key'   => 'year', // Meta key cho năm
            'value' => $year,
            'compare' => '='
        ),
    );
}

// Thực hiện truy vấn
$query = new WP_Query($args);
?>

<div class="search-results">
    <?php if ($query->have_posts()) : ?>
        <h2>Kết quả tìm kiếm</h2>
        <ul>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <p><?php the_excerpt(); ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p>Không tìm thấy kết quả nào.</p>
    <?php endif; ?>
</div>

<?php
wp_reset_postdata(); // Đặt lại dữ liệu post
get_footer(); // Gọi footer của theme
?>
