<?php

/**
 * Template Name: Danh sách việc được tìm thấy
 */

get_header();

// Lấy tham số từ URL
$year = isset($_GET['year_r']) ? sanitize_text_field($_GET['year_r']) : '';
$post_type = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
$province_slug = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
$university_slug = isset($_GET['university']) ? sanitize_text_field($_GET['university']) : '';
$company_slug = isset($_GET['company']) ? sanitize_text_field($_GET['company']) : '';

$valid_post_types = ['vietnam', 'indonesia', 'laos', 'cambodia'];
if (!in_array($post_type, $valid_post_types)) {
    $post_type = 'vietnam';
}
$prefix = $post_type;

// Dịch tiếng Việt và tiếng Indonesia
$translations = [
    'vi' => [
        'recommended' => 'Đề xuất',
        'salary' => 'Lương',
        'view_detail' => 'Xem chi tiết',
        'no_results' => 'Không tìm thấy bài viết nào.'
    ],
    'id' => [
        'recommended' => 'Direkomendasikan',
        'salary' => 'Gaji',
        'view_detail' => 'Lihat detail',
        'no_results' => 'Tidak ada postingan yang ditemukan.'
    ]
];
$lang = $post_type === 'indonesia' ? 'id' : 'vi';

$taxonomies = array(
    "year_{$prefix}" => $year,
    "province_{$prefix}" => $province_slug,
    "university_{$prefix}" => $university_slug,
    "company_{$prefix}" => $company_slug
);

$university_name = '';
if (!empty($university_slug)) {
    $university_term = get_term_by('slug', $university_slug, "university_{$prefix}");
    if ($university_term) {
        $university_name = $university_term->name;
    }
}

$args = array(
    'post_type' => $post_type,
    'posts_per_page' => -1,
    'tax_query' => array('relation' => 'AND'),
);
foreach ($taxonomies as $taxonomy => $term) {
    if (!empty($term)) {
        $args['tax_query'][] = array(
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $term,
        );
    }
}

$query = new WP_Query($args);
$posts_with_last_viewed = array();
$posts_without_last_viewed = array();
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $last_viewed = get_post_meta(get_the_ID(), '_last_viewed', true);
        if (!empty($last_viewed)) {
            $posts_with_last_viewed[] = $post;
        } else {
            $posts_without_last_viewed[] = $post;
        }
    }
}

$final_posts = array_merge($posts_with_last_viewed, $posts_without_last_viewed);
wp_reset_postdata();

$args_recommended = array(
    'post_type' => $post_type,
    'meta_key' => '_last_viewed',
    'orderby' => 'meta_value',
    'order' => 'DESC',
    'posts_per_page' => 3,
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'recommended_work',
            'value' => 'recommended',
            'compare' => 'LIKE',
        ),
        array(
            'key' => '_last_viewed',
            'compare' => 'EXISTS',
        )
    ),
);
$recommended_query = new WP_Query($args_recommended);

function display_job_item($post, $block_post = false, $block_post_recommended = false, $post_type = 'vietnam', $translations = [])
{
    setup_postdata($post);
    $lang = $post_type === 'indonesia' ? 'id' : 'vi';
    $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'medium');
    if (!$thumbnail_url) {
        $thumbnail_url = 'https://placehold.co/600x400';
    }

    $company_terms = wp_get_post_terms($post->ID, "company_{$post_type}", array('fields' => 'all'));
    $company_image_url = '';
    $company_name = '';
    if (!empty($company_terms)) {
        $company_term_id = $company_terms[0]->term_id;
        $company_name = $company_terms[0]->name;
        $company_image_id = get_term_meta($company_term_id, 'company_image', true);
        if (!empty($company_image_id)) {
            $company_image_url = wp_get_attachment_url($company_image_id);
        }
        if (!$company_image_url) {
            $company_image_url = 'https://placehold.co/100x100';
        }
    }

    $company_info_class = $block_post_recommended ? 'company-info company-info-recommended' : 'company-info';
    $text_info_job = $block_post_recommended ? 'text_info_job text_info_job_recommended' : 'text_info_job';
    $avatar_job = $block_post_recommended ? 'avatar_job' : 'avatar_job avatar_job_not_recommended';

    $region = $_GET['region'] ?? '';
    $permalink = get_permalink();
    if (!empty($region)) {
        $permalink = add_query_arg('region', $region, $permalink);
    }


?>
    <li>
        <div class="job-item">
            <p class="title_job">
                <?php if ($block_post_recommended) : ?>
                    <span class="label_job_recommended"><?php echo $translations[$lang]['recommended']; ?></span>
                <?php endif; ?>
                <span class="text"><?php the_title(); ?></span>
            </p>
            <div class="job_content">
                <div class="<?php echo esc_attr($text_info_job); ?>">
                    <?php if ($company_image_url) : ?>
                        <div class="<?php echo esc_attr($company_info_class); ?> title_box">
                            <div class="title_box_left">
                                <div class="bl_logo">
                                    <p class="logo_company" style="--w:120px; --h:60px">
  <img src="<?php echo esc_url($company_image_url); ?>" alt="Company Image" loading="lazy" decoding="async">
</p>
                                    <?php if ($company_name) : ?>
                                        <p class="company_name"><?php echo esc_html($company_name); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="box_w">
                                    <div class="hashtag">
                                        <span class="tag_item">土日祝休み</span>
                                        <span class="tag_item">昨端賞与あり</span>
                                        <span class="tag_item">個室あり</span>
                                        <span class="tag_item">夜勤あり</span>
                                    </div>
                                    <div class="salary">
                                        <span class="label_text"><?php echo $translations[$lang]['salary']; ?></span>
                                        <span class="salary_text">
                                            <?php
                                            $slide2 = get_field('slide_2'); // trả về mảng các sub-field
                                            echo '<div class="salary">
                                            <span class="salary_text">' . esc_html($slide2['noi_dung_1'] ?? '') . '</span>
                                          </div>';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="title_box_right">
                                <div class="has_label label_jobs_recommended">
                                	<img class="<?php echo esc_attr($avatar_job); ?>" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php display_job_info_fields(get_the_ID()); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
                    <div class="btn_detail">
                        <div class="btn_content">
                            <a href="<?php echo esc_url($permalink); ?>">
                                <?php echo $translations[$lang]['view_detail']; ?>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </li>
<?php
}
?>

<div id="content" class="page-list-job-filter">
    <div class="logo_mintoku_mess">
        <img src="<?php echo get_template_directory_uri(); ?>/images/logo_mintoku_mess.png"
            alt="company mintoku mess">
        <div class="title_job_area">
            <p><?php echo $university_name; ?></p>
        </div>
    </div>

    <div class="block_jobs_recommended">
        <div class="container">
            <div class="border_black">
                <div class="border_purple">
                    <?php echo do_shortcode('[acf_recommended_work_slider]'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($recommended_query->have_posts()) : ?>
        <div class="content_list_job_filter">
            <ul>
                <?php while ($recommended_query->have_posts()) : $recommended_query->the_post(); ?>
                    <?php display_job_item(get_post(), false, true, $post_type, $translations); ?>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($final_posts)) : ?>
        <div class="content_list_job_filter bl_list_qr">
            <ul>
                <?php foreach ($final_posts as $post) : ?>
                    <?php setup_postdata($post); ?>
                    <?php display_job_item(get_post(), false, false, $post_type, $translations); ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p><?php echo $translations[$lang]['no_results']; ?></p>
    <?php endif; ?>
</div>

<?php
// Lấy text quay lại theo ngôn ngữ
$back_text = ($lang === 'id') ? 'Kembali' : 'Quay lại';
?>
<button class="btn-back-fixed" onclick="window.history.back()">
    <span class="icon-back">←</span>
    <span class="text-back"><?php echo esc_html($back_text); ?></span>
</button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>

<?php

wp_reset_postdata();
get_footer();

?>