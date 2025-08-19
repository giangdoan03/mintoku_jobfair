<?php

function get_universities_by_province(): void
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
function display_job_info_fields($post_id): void
{
    // 1. Lấy giá trị region từ URL
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : 'vietnam';

    // 2. Xác định ngôn ngữ theo region
    $lang = match (strtolower($region)) {
        'indonesia' => 'id',
        'thailand' => 'th',
        default => 'vi',
    };

    // 3. Hàm dịch (có thể tách riêng nếu cần)
    if (!function_exists('translate_content')) {
        function translate_content($text, $lang): string
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
