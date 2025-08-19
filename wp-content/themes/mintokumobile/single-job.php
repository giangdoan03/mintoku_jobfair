<?php get_header('region'); ?>
<?php
if (!session_id()) session_start();
$region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
if (empty($region) && !empty($_SESSION['region'])) {
    $region = sanitize_text_field($_SESSION['region']);
}
if (empty($region)) {
    $request_uri = trim($_SERVER['REQUEST_URI'], '/');
    $parts = explode('/', $request_uri);
    $known_regions = ['vietnam', 'indonesia', 'laos', 'cambodia'];
    foreach ($parts as $part) {
        if (in_array($part, $known_regions)) {
            $region = $part;
            break;
        }
    }
}
if (!in_array($region, ['vietnam', 'indonesia', 'laos', 'cambodia'])) {
    $region = 'vietnam';
}
$_SESSION['region'] = $region;

$lang = $region === 'indonesia' ? 'id' : 'vi';
$translations = [
    'vi' => [
        'apply' => 'Ứng tuyển',
        'back' => 'Quay lại',
        'salary' => 'Lương'
    ],
    'id' => [
        'apply' => 'Lamar sekarang',
        'back' => 'Kembali',
        'salary' => 'Gaji'
    ]
];

function trans($key) {
    global $translations, $lang;
    return $translations[$lang][$key] ?? $key;
}
?>

<div id="page-single-job" class="page_single_job">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php
            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'medium') ?: 'https://placehold.co/600x400';

            // Xác định taxonomy dựa trên region
            $region = $_GET['region'] ?? 'vietnam'; // mặc định là 'vietnam'
            $company_taxonomy = 'company_' . $region;

            // Lấy thông tin taxonomy công ty
            $company_terms = wp_get_post_terms($post->ID, $company_taxonomy, array('fields' => 'all'));

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


            $short_job_description = get_field('short_job_description');
            $slide_1 = get_field('slide_1');
            $slide_2 = get_field('slide_2');
            $slide_3 = get_field('slide_3');
            $slide_4 = get_field('slide_4');
            ?>

            <?php if (!empty($slide_1)) : ?>
                <div class="slide slide-1 swiper-slide">
                    <div class="logo_mintoku_mess">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo_slide_1.png" alt="company mintoku mess">
                    </div>
                    <div class="avatar_job">
                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                    </div>
                    <div class="job_info_slide_1">
                        <div class="job-item">
                            <p class="title_job"><?php the_title(); ?></p>
                            <div class="job_content">
                                <div class="container">
                                    <div class="company-info">
                                        <div class="bl_logo">
                                            <p class="logo_company">
                                                <img src="<?php echo esc_url($company_image_url); ?>" alt="Company Image">
                                            </p>
                                            <?php if ($company_name) : ?>
                                                <p class="company_name"><?php echo esc_html($company_name); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="salary">
                                        <span class="label_text"><?php echo trans('salary'); ?></span>
                                       <?php
$slide_2_1 = get_field('slide_2'); // group
?>
<span class="salary_text"><?php echo esc_html($slide_2_1['noi_dung_1'] ?? ''); ?></span>

                                    </div>
                                    <div class="text_desc">
                                        <?php if (!empty($short_job_description)) : ?>
                                            <div class="summary_job">
                                                <p><?php echo esc_html($short_job_description); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php
                                    $slide_1_obj = get_field_object('slide_1');
                                    for ($i = 1; $i <= 4; $i++) {
                                        $key = 'noi_dung_' . $i;
                                        $label = $slide_1_obj['sub_fields'][$i - 1]['label'] ?? '';
                                        if (!empty($slide_1[$key])) {
                                            echo '<div class="noi-dung-field">';
                                            echo '<strong>' . esc_html($label) . ':</strong> ';
                                            echo '<p>' . esc_html($slide_1[$key]) . '</p>';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($slide_2)) : ?>
                <div class="slide slide-2 swiper-slide">
                    <div class="logo_mintoku_mess">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo_slide_1.png" alt="company mintoku mess">
                    </div>
                    <div class="container slide_2_">
                        <?php
                        $slide_2_obj = get_field_object('slide_2');
                        for ($i = 1; $i <= 15; $i++) {
                            $key = 'noi_dung_' . $i;
                            $label = $slide_2_obj['sub_fields'][$i - 1]['label'] ?? '';
                            if (!empty($slide_2[$key])) {
                                echo '<div class="noi-dung-field">';
                                echo '<strong>' . esc_html($label) . ':</strong> ';
                                echo '<p class="custom-textarea-content">' . esc_html($slide_2[$key]) . '</p>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($slide_3['image_job'])) : ?>
                <div class="slide slide-3 swiper-slide">
                    <div class="logo_mintoku_mess">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo_slide_1.png" alt="company mintoku mess">
                    </div>
                    <div class="container">
                        <div class="product-gallery">
                            <?php foreach ($slide_3['image_job'] as $image) :
                                $full = esc_url($image['url']);
                                $thumb = esc_url($image['sizes']['medium']);
                                if (!$full || !$thumb) continue;
                                ?>
                                <div class="gallery-item" style="display: inline-block; margin: 10px;">
                                    <a href="<?php echo $full; ?>" data-fancybox="gallery" data-caption="<?php echo esc_attr($image['caption'] ?? ''); ?>">
                                        <img src="<?php echo $thumb; ?>" alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" style="max-width: 150px; height: auto;" />
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php if (!empty($slide_4['video_url'])) : ?>
                <div class="slide slide-4 swiper-slide">
                    <div class="logo_mintoku_mess">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo_slide_1.png" alt="company mintoku mess">
                    </div>
                    <div class="container">
                        <div style="height: 100vh; display: flex; align-items: center; position: relative; bottom: 150px;">
                            <script type="text/javascript">
                                var Eviry = Eviry || {};
                                Eviry.Player || (Eviry.Player = {});
                                Eviry.Player.embedkey = "<?php echo esc_js($slide_4['video_url']); ?>";
                            </script>
                            <script type="text/javascript" src="https://d1euehvbqdc1n9.cloudfront.net/001/eviry/js/eviry.player.min.js"></script>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="box_btn_navigation">
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <div class="btn_action_fixed" id="btn_action_fixed">
        <div class="btn_action">
            <div class="message_link">
                <?php $messenger_link = get_field('messenger_link_facebook', 'option'); ?>
                <?php if ($messenger_link): ?>
                    <a target="_blank" href="<?php echo esc_url($messenger_link); ?>"><?php echo esc_html($translations[$lang]['apply']); ?></a>
                <?php endif; ?>
            </div>
            <div class="back_page">
                <a href="#" id="backButton"><?php echo esc_html($translations[$lang]['back']); ?></a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.swiper-container', {
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            autoHeight: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            slidesPerView: 1
        });

        const sliderItems = document.querySelectorAll('.slider-item');
        const thumbnailItems = document.querySelectorAll('.thumbnail-item');
        let currentPosition = 0;

        function updateSlider(index) {
            if (!sliderItems || sliderItems.length === 0) return;
            if (index < 0 || index >= sliderItems.length) return;

            sliderItems.forEach((item) => item.style.display = 'none');
            sliderItems[index].style.display = 'block';
            thumbnailItems.forEach((item, i) => item.classList.toggle('active', i === index));
            currentPosition = index;
        }

        thumbnailItems.forEach((item, index) => {
            item.addEventListener('click', () => updateSlider(index));
        });

        if (!document.querySelector('.thumbnail-item.active')) updateSlider(0);

        document.getElementById('backButton').addEventListener('click', function() {
            if (window.history.length > 1) {
                window.history.go(-1);
            } else {
                var baseURL = window.location.origin;
                if (baseURL.includes('localhost')) {
                    window.location.href = baseURL + "/mintoku_mobile/list-job/";
                } else {
                    window.location.href = baseURL + "/list-job/";
                }
            }
        });
    });
</script>