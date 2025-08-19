<?php
/* Template Name: page list job */

/** BASIC AUTH chỉ cho template này */
$BASIC_USER = 'admin';
$BASIC_PASS = 'admin123@@';

function _prompt_basic_auth($realm = 'Protected Page') {
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    header('WWW-Authenticate: Basic realm="'.$realm.'", charset="UTF-8"');
    header('HTTP/1.1 401 Unauthorized');
    echo 'Authentication required.';
    exit;
}

// Lấy credential từ server (hỗ trợ cả CGI/FastCGI)
$user = $_SERVER['PHP_AUTH_USER'] ?? null;
$pass = $_SERVER['PHP_AUTH_PW']   ?? null;

// Một số server chỉ set HTTP_AUTHORIZATION
if ((!$user || !$pass) && isset($_SERVER['HTTP_AUTHORIZATION']) && stripos($_SERVER['HTTP_AUTHORIZATION'], 'Basic ') === 0) {
    $decoded = base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6));
    if ($decoded !== false && strpos($decoded, ':') !== false) {
        list($user, $pass) = explode(':', $decoded, 2);
    }
}

// So khớp, sai thì yêu cầu đăng nhập
if ($user !== $BASIC_USER || $pass !== $BASIC_PASS) {
    _prompt_basic_auth('Job List');
}


$placeholder_image = get_stylesheet_directory_uri() . '/images/mintokumesse_logo.png';
get_header(); ?>

    <main id="main" class="page-form-search" <?php body_class('fade-in'); ?>>

        <div class="logo_job" style="margin-bottom: 30px">
            <img src="<?php echo esc_url($placeholder_image); ?>" alt="">
        </div>


        <section class="search-form">
            <div>
                <h1 data-translate="search_job">Tìm kiếm việc</h1>
                <!-- Province Dropdown -->

                <label for="post_type" data-translate="select_post_type">Quốc gia:</label>
                <select name="region" id="post_type">
                    <option value=""
                            data-translate="select_post_type"><?php echo esc_html__('Chọn quốc gia:', 'text-domain'); ?></option>
                    <option value="vietnam"><?php echo esc_html__('Việt Nam', 'text-domain'); ?></option>
                    <option value="indonesia"><?php echo esc_html__('Indonesia', 'text-domain'); ?></option>
                </select>
                <span id="post_type_error" style="color: red; display: none;"
                      data-translate="error_no_post_type"></span>


                <!--                --><?php
                //                $selected_slug = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
                //                $provinces = get_terms(array(
                //                    'taxonomy' => 'province_vietnam',
                //                    'hide_empty' => false
                //                ));
                //                ?>
                <!--                <label for="province" data-translate="select_province">Tỉnh/thành</label>-->
                <!--                <select id="province-select" disabled>-->
                <!--                    <option value="">Đang tải...</option>-->
                <!--                </select>-->
                <!--                -->
                <!--                <label for="university" data-translate="select_university">Trường đại học</label>-->
                <!--                <select id="university-select">-->
                <!--                    <option value="">Chọn trường</option>-->
                <!--                </select>-->
                <!--                -->
                <!--                <label for="company" data-translate="select_company">Công ty</label>-->
                <!--                <select name="company" id="company" disabled>-->
                <!--                    <option value="" data-translate="select_company">-->
                <!--                        --><?php //echo esc_html__('Chọn công ty:', 'text-domain'); ?>
                <!--                    </option>-->
                <!--                </select>-->
                <!--                <label for="year_r" data-translate="select_year">Năm</label>-->
                <!--                <select name="year_r" id="year_r" disabled>-->
                <!--                    <option value="">Chọn năm</option>-->
                <!--                </select>-->

                <input type="hidden" id="search_query" name="search_query"/>
                <button type="button" id="search-btn" class="btn btn-primary">Tìm kiếm</button>
            </div>
            <!--            <hr>-->
            <!--            <div id="job-results"></div>-->
        </section>
    </main>

    <script>
        //var ajaxurl = '<?php //echo admin_url('admin-ajax.php'); ?>//';
    </script>
    <!--    <script src="--><?php //echo get_template_directory_uri(); ?><!--/js/job-search.js"></script>-->
    <script>
        const translations = {
            vi: {
                search_job: "Tìm kiếm việc",
                select_post_type: "Chọn quốc gia:",
                select_province: "Tỉnh/thành",
                select_university: "Trường đại học",
                select_company: "Chọn công ty:",
                select_year: "Chọn năm",
                error_no_post_type: "Vui lòng chọn quốc gia."
            },
            id: {
                search_job: "Cari Pekerjaan",
                select_post_type: "Pilih negara:",
                select_province: "Provinsi",
                select_university: "Universitas",
                select_company: "Pilih perusahaan:",
                select_year: "Pilih tahun",
                error_no_post_type: "Silakan pilih negara."
            }
        };

        function setFormLanguage(lang) {
            document.querySelectorAll('[data-translate]').forEach(el => {
                const key = el.getAttribute('data-translate');
                if (translations[lang] && translations[lang][key]) {
                    el.textContent = translations[lang][key];
                }
            });

            // Cập nhật cả placeholder nếu có input
            document.querySelectorAll('option[data-translate]').forEach(option => {
                const key = option.getAttribute('data-translate');
                if (translations[lang] && translations[lang][key]) {
                    option.textContent = translations[lang][key];
                }
            });
        }

        jQuery(document).ready(function ($) {
            $('#post_type').on('change', function () {
                const val = $(this).val();
                if (val === 'indonesia') {
                    setFormLanguage('id');
                } else {
                    setFormLanguage('vi');
                }
            });

            // Mặc định ban đầu nếu đã có sẵn giá trị
            const initialLang = $('#post_type').val() === 'indonesia' ? 'id' : 'vi';
            setFormLanguage(initialLang);
        });

        jQuery(document).ready(function ($) {
            $('#search-btn').on('click', function () {
                const selectedRegion = $('#post_type').val();

                if (!selectedRegion) {
                    $('#post_type_error').show();
                    return;
                }

                $('#post_type_error').hide();

                // Lấy current URL không có query string
                const baseUrl = window.location.origin + window.location.pathname;

                // Redirect đến chính trang đó kèm query
                const redirectUrl = `${baseUrl}?page_id=771&region=${selectedRegion}`;
                window.location.href = redirectUrl;
            });
        });


        
 

       $('#post_type').on('change', function () {
            const val = $(this).val();
            const lang = val === 'indonesia' ? 'id' : 'vi';

            setFormLanguage(lang);

            // Gửi AJAX nếu cần
            $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                action: 'save_region_session',
                region: val
            });
        });




    </script>

<div style="display: none">
    <?php get_footer(); ?>
</div>