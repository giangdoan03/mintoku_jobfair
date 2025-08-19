<?php
/* Template Name: page region */
get_header('region');

$region_slug = get_query_var('region') ?: 'vietnam';
$taxonomy = 'university_' . $region_slug;

// ==== Đa ngôn ngữ ====
$text_translations = [
    'jobfair_now' => [
        'vietnam'   => 'ジョブフェア đang diễn ra',
        'indonesia' => 'Job Fair Sedang Berlangsung',
        'thai'      => 'งาน Job Fair ที่กำลังจัดขึ้น',
        'ja'        => '開催中のジョブフェア',
    ],
    'jobfair_list' => [
        'vietnam'   => 'Danh sách lịch Job Fair',
        'indonesia' => 'Jadwal Job Fair',
        'thai'      => 'ตารางงาน Job Fair',
        'ja'        => 'ジョブフェア予定一覧',
    ],
    'go_to_jobfair' => [
        'vietnam'   => 'Đi đến Job Fair',
        'indonesia' => 'Pergi ke Job Fair',
        'thai'      => 'ไปที่งาน Job Fair',
        'ja'        => 'ジョブフェアへ行く',
    ],
    'participating_companies' => [
        'vietnam'   => 'Công ty tham gia',
        'indonesia' => 'Perusahaan yang Berpartisipasi',
        'thai'      => 'บริษัทที่เข้าร่วม',
        'ja'        => 'Participating Companies',
    ],
    'messe_info' => [
        'vietnam'   => 'Thông tin Messe',
        'indonesia' => 'Informasi Messe',
        'thai'      => 'ข้อมูล Messe',
        'ja'        => 'mintoku messe information',
    ],
    'dummy_info' => [
        'vietnam'   => 'Thông tin mẫu',
        'indonesia' => 'Informasi Contoh',
        'thai'      => 'ข้อมูลตัวอย่าง',
        'ja'        => 'ダミー情報',
    ],
    'jobfair_point' => [
        'vietnam'   => 'Điểm nổi bật của Job Fair',
        'indonesia' => 'Keunggulan Job Fair',
        'thai'      => 'จุดเด่นของงาน Job Fair',
        'ja'        => 'ジョブフェアのポイント​',
    ],
    'tag_1' => [
        'vietnam' => 'Mức lương cao',
        'indonesia' => 'Gaji tinggi',
        'thai' => 'ค่าตอบแทนสูง',
        'ja' => '高時給',
    ],
    'tag_2' => [
        'vietnam' => 'Phụ cấp hấp dẫn',
        'indonesia' => 'Tunjangan lengkap',
        'thai' => 'มีเบี้ยเลี้ยง',
        'ja' => '諸手当あり',
    ],
    'tag_3' => [
        'vietnam' => 'Hỗ trợ đầy đủ',
        'indonesia' => 'Dukungan penuh',
        'thai' => 'มีระบบสนับสนุน',
        'ja' => 'サポート体制',
    ]
];

function t($key) {
    global $text_translations, $region_slug;
    return $text_translations[$key][$region_slug] ?? $text_translations[$key]['vietnam'];
}

function format_region_date_range($start, $end = null, $region_slug = null): string
{
    if (!$start) return 'TBA';

    $region_slug = $region_slug ?: get_query_var('region') ?: 'vietnam';

    $months = [
        'indonesia' => [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ],
        'vietnam' => [
            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
            5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
            9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
        ],
        'thai' => [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ],
    ];

    $start_ts = strtotime($start);
    $end_ts = $end ? strtotime($end) : null;

    if (in_array($region_slug, ['ja', 'japan'])) {
        $start_str = date_i18n('Y.m.d', $start_ts) . '(' . date_i18n('D', $start_ts) . ')';
        if ($end_ts && $end_ts !== $start_ts) {
            $end_str = (date('Y', $start_ts) === date('Y', $end_ts))
                ? date_i18n('m.d', $end_ts) . '(' . date_i18n('D', $end_ts) . ')'
                : date_i18n('Y.m.d', $end_ts) . '(' . date_i18n('D', $end_ts) . ')';
            return $start_str . ' – ' . $end_str;
        }
        return $start_str;
    }

    $month_map = $months[$region_slug] ?? $months['vietnam'];
    $start_str = date('j', $start_ts) . ' ' . $month_map[(int)date('n', $start_ts)] . ' ' . date('Y', $start_ts);

    if ($end_ts && $end_ts !== $start_ts) {
        $end_str = date('j', $end_ts) . ' ' . $month_map[(int)date('n', $end_ts)] . ' ' . date('Y', $end_ts);
        return $start_str . ' – ' . $end_str;
    }

    return $start_str;
}
?>

<main id="primary" class="site-main">
    <div class="header_menu">
        <div class="box_menu">
            <div class="logo_page">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Japan Job Fair" class="logo_c" />
                </a>
            </div>
            <div class="menu-container">
                <div class="fullscreen-menu" id="fullscreenMenu">
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="jobfair-wrapper">
        <div class="jobfair-header">
            <img src="<?php echo get_template_directory_uri(); ?>/images/banner_FV.png" alt="Illustration" class="illustration" />
        </div>

        <div class="jobfair-body">
            <?php if (!taxonomy_exists($taxonomy)) : ?>
                <p style="color:red;">Invalid region.</p>
            <?php else :

                $featured_terms = get_terms([
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                    'meta_query' => [[
                        'key'     => 'is_featured',
                        'value'   => '1',
                        'compare' => '='
                    ]],
                    'number' => 1
                ]);

                if (!empty($featured_terms)) :
                    $term = $featured_terms[0];
                    $name = $term->name;
                    $address = get_term_meta($term->term_id, 'address', true);
                    $start = get_term_meta($term->term_id, 'start_date', true);
                    $end   = get_term_meta($term->term_id, 'end_date', true);
                    $date_range_str = format_region_date_range($start, $end, $region_slug);
            ?>
                <div class="jobfair-box">
                    <div class="jobfair-box-header">
                        <span class="section-title section-title-1"><?= t('jobfair_now') ?></span>
                    </div>
                    <div class="section-body">
                        <h2 class="section-body-title-top"><?= esc_html($name) ?></h2>
                        <div class="section-body-p">
                            <p class="date"><?= esc_html($date_range_str) ?></p>
                            <div class="address"><?= esc_html($address) ?></div>
                            <!-- <h2><?= t('jobfair_point') ?></h2> -->
                            <!-- <div class="tags">
                                <span><?= t('tag_1') ?></span>
                                <span><?= t('tag_2') ?></span>
                                <span><?= t('tag_3') ?></span>
                            </div> -->
                            <?php $university_slug = $term->slug; ?>
                            <a  href="javascript:void(0);" class="cta-button" onclick="goToJobFair(this)"
                               data-region="<?= esc_attr($region_slug) ?>"
                               data-university="<?= esc_attr($university_slug) ?>">
                                <?= t('go_to_jobfair') ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif;

                $excluded_ids = wp_list_pluck($featured_terms, 'term_id');
                $universities = get_terms([
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                    'exclude'    => $excluded_ids
                ]);

                if (!empty($universities)) : ?>
                <div class="jobfair-schedule-box">
                    <div class="jobfair-schedule-header">
                        <span class="section-title section-title-2"><?= t('jobfair_list') ?></span>
                    </div>
                    <ul class="jobfair-schedule-list section-body">
                        <?php foreach ($universities as $uni) :
                            $name = $uni->name;
                            $start = get_term_meta($uni->term_id, 'start_date', true);
                            $end   = get_term_meta($uni->term_id, 'end_date', true);
                            $date_str = format_region_date_range($start, $end, $region_slug);

                            $university_link = add_query_arg([
                                'page_id'    => 307,
                                'region'     => $region_slug,
                                'university' => $uni->slug
                            ], get_permalink(307));
                        ?>
                            <li>
                                <a href="<?= esc_url($university_link) ?>" title="<?= esc_attr($name) ?>">
                                    <strong><?= esc_html($name) ?></strong>
                                    <span><?= esc_html($date_str) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; endif; ?>

            <?php
            $company_taxonomy = 'company_' . $region_slug;
            if (taxonomy_exists($company_taxonomy)) {
                $companies = get_terms([
                    'taxonomy'   => $company_taxonomy,
                    'hide_empty' => false,
                ]);
            }
            ?>

            <?php if (!empty($companies)) : ?>
                <div class="companies-box">
                    <div class="companies-header">
                        <span class="companies-title section-title title_en section-title-3"><?= t('participating_companies') ?></span>
                    </div>
                    <div class="companies-grid section-body">
                        <?php foreach ($companies as $company) :
                            $logo = get_field('company_image', $company_taxonomy . '_' . $company->term_id);
                            $logo_url = is_array($logo) && isset($logo['url']) ? $logo['url'] : 'https://placehold.co/150x150';
                            $company_link = add_query_arg([
                                'page_id' => 307,
                                'region' => $region_slug,
                                'company' => $company->slug,
                            ], get_permalink(307));
                        ?>
                            <div class="company_logo">
                                <a href="<?= esc_url($company_link) ?>" title="<?= esc_attr($company->name) ?>">
                                    <img src="<?= esc_url($logo_url) ?>" alt="<?= esc_attr($company->name) ?>" />
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="messe-info-box">
                <div class="messe-info-header">
                    <span class="messe-title section-title title_en section-title-4"><?= t('messe_info') ?></span>
                </div>
                <ul class="messe-list section-body">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <li>
                            <a href="#">
                                <img src="https://placehold.co/80x60" alt="thumb" />
                                <span><?= t('dummy_info') ?> <?= $i ?></span>
                                <span class="arrow">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/arrow_right_part.png" alt="">
                                </span>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>

        <div id="messe-popup" class="messe-popup">
            <div class="messe-popup-overlay"></div>
            <div class="messe-popup-content">
                <button class="messe-close-btn">×</button>
                <div class="messe-popup-inner">
                    <div class="messe-loader"></div>
                </div>
            </div>
        </div>
    </div>
</main>


<?php get_footer(); ?>