<?php
/**
 * Shortcode: [acf_recommended_work_slider]
 */

function display_acf_recommended_work_slider($atts)
{
    // 1. region
    $region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
    $allowed = ['vietnam','indonesia','laos','cambodia','thailand'];
    if (!in_array($region, $allowed)) {
        return '<p>Invalid region.</p>';
    }

    // 2. language
    $lang = ($region === 'indonesia') ? 'id' : 'vi';
    $translations = [
        'vi' => ['salary' => 'Lương'],
        'id' => ['salary' => 'Gaji']
    ];

    $post_type = $region;
    $taxonomy  = "company_{$region}";

    $args = [
        'post_type'      => $post_type,
        'posts_per_page' => 3,
        'meta_query'     => [
            'relation' => 'OR',
            [
                'key'     => 'recommended_work',
                'value'   => 'recommended',
                'compare' => 'LIKE',
            ],
            [
                'key'     => 'recommended_work',
                'compare' => 'NOT EXISTS',
            ],
        ],
        'orderby' => [
            'meta_value' => 'DESC',
            'date'       => 'DESC',
        ]
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start(); ?>
        <div class="box_slider jobs_recommend" id="box_slider">
            <div class="swiper-container"><div class="swiper-wrapper">
                    <?php while ($query->have_posts()): $query->the_post(); ?>
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
                                <div class="job_info"><div class="text_desc">
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
                                            $logo = get_field('company_image', $term);
                                            if ($logo) {
                                                echo '<div class="logo_company logo_company_recommended"><img src="'.esc_url($logo['url']).'"><p>'.esc_html($term->name).'</p></div>';
                                            }
                                        }
                                        ?>
                                    </div></div>
                                <div class="salary">
                                    <span class="label_text"><?php echo $translations[$lang]['salary']; ?></span>
                                    <span class="salary_text"><?php $slide_2 = get_field('slide_2'); echo esc_html($slide_2['noi_dung_1'] ?? ''); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div></div>
            <div class="bl_dot"><div class="swiper-pagination"></div></div>
        </div>
        <?php wp_reset_postdata();
        return ob_get_clean();
    } else {
        return '<p>No recommended work found.</p>';
    }
}
add_shortcode('acf_recommended_work_slider', 'display_acf_recommended_work_slider');

// enqueue Swiper init
function enqueue_swiper_assets_for_acf(){
    wp_add_inline_script('swiper-js', "
        document.addEventListener('DOMContentLoaded',function(){
            new Swiper('#box_slider .swiper-container',{
                slidesPerView:3,spaceBetween:10,loop:true,
                navigation:{nextEl:'.swiper-button-next',prevEl:'.swiper-button-prev'},
                pagination:{el:'.swiper-pagination',clickable:true},
                breakpoints:{640:{slidesPerView:1},768:{slidesPerView:1,spaceBetween:20},1024:{slidesPerView:3,spaceBetween:30}}
            });
        });
    ");
}
add_action('wp_enqueue_scripts','enqueue_swiper_assets_for_acf');
