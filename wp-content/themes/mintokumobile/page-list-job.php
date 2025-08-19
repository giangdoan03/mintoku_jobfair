<?php
/* Template Name: page list job */
get_header();

// Start session
if (!session_id()) session_start();

// Lấy region từ $_GET hoặc session hoặc URL
$region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : ($_SESSION['region'] ?? '');
if (empty($region)) {
    $parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
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
        'search_job' => 'Tìm kiếm việc',
        'select_post_type' => 'Chọn quốc gia:',
        'select_province' => 'Tỉnh/thành',
        'select_university' => 'Trường đại học',
        'select_company' => 'Công ty',
        'select_year' => 'Năm',
        'search' => 'Tìm kiếm'
    ],
    'id' => [
        'search_job' => 'Cari pekerjaan',
        'select_post_type' => 'Pilih negara:',
        'select_province' => 'Provinsi',
        'select_university' => 'Universitas',
        'select_company' => 'Perusahaan',
        'select_year' => 'Tahun',
        'search' => 'Cari'
    ]
];
?>

<main id="main" class="page-form-search" <?php body_class('fade-in'); ?>>
    <section class="search-form">
        <div>
            <h1 data-translate="search_job"><?php echo $translations[$lang]['search_job']; ?></h1>

            <label for="post_type"><?php echo $translations[$lang]['select_post_type']; ?></label>
            <select name="region" id="post_type">
                <option value=""><?php echo $translations[$lang]['select_post_type']; ?></option>
                <option value="vietnam" <?php selected($region, 'vietnam'); ?>>Việt Nam</option>
                <option value="indonesia" <?php selected($region, 'indonesia'); ?>>Indonesia</option>
            </select>
            <span id="post_type_error" style="color: red; display: none;"></span>

            <?php
            $selected_slug = isset($_GET['province']) ? sanitize_text_field($_GET['province']) : '';
            $provinces = get_terms([
                'taxonomy' => 'province_vietnam',
                'hide_empty' => false
            ]);
            ?>
            <label for="province"><?php echo $translations[$lang]['select_province']; ?></label>
            <select id="province-select">
                <option value=""><?php echo $translations[$lang]['select_province']; ?></option>
                <?php foreach ($provinces as $province): ?>
                    <option value="<?php echo esc_attr($province->slug); ?>" <?php selected($province->slug, $selected_slug); ?>>
                        <?php echo esc_html($province->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="university"><?php echo $translations[$lang]['select_university']; ?></label>
            <select id="university-select">
                <option value=""><?php echo $translations[$lang]['select_university']; ?></option>
            </select>

            <label for="company"><?php echo $translations[$lang]['select_company']; ?></label>
            <select name="company" id="company" disabled>
                <option value=""><?php echo $translations[$lang]['select_company']; ?></option>
            </select>

            <label for="year_r"><?php echo $translations[$lang]['select_year']; ?></label>
            <select name="year_r" id="year_r" disabled>
                <option value=""><?php echo $translations[$lang]['select_year']; ?></option>
            </select>

            <input type="hidden" id="search_query" name="search_query" />
            <button type="button" id="search-btn" class="btn btn-primary"><?php echo $translations[$lang]['search']; ?></button>
        </div>
        <hr>
        <div id="job-results"></div>
    </section>
</main>

<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<script src="<?php echo get_template_directory_uri(); ?>/js/job-search.js"></script>
<script>
    jQuery(document).ready(function ($) {
        const language = '<?php echo esc_js($lang); ?>';
        if (language) setLanguage(language);
    });
</script>

<?php get_footer(); ?>