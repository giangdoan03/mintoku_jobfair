<?php
/**
 * The header for our theme
 *
 * @package mintokumobile
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/page-region.css">

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

    <!-- Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
    
    <!--Google tag (gtag.js)-->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0E70789S3S"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-0E70789S3S');
    </script> 


    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="header">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'mintokumobile'); ?></a>
    <header id="header_s" style="display: none">
        <div class="language-switcher">
            <div class="current-language">
                <span id="current-lang"></span>
                <i class="arrow-down"></i> <!-- Thêm mũi tên chỉ xuống -->
            </div>
            <ul class="language-list sub-menu">
                <?php
                if (function_exists('pll_the_languages')) {
                    pll_the_languages(array(
                        'show_flags' => 1, // Hiển thị cờ quốc gia
                        'show_names' => 1, // Hiển thị tên ngôn ngữ
                        'hide_if_empty' => 0, // Hiển thị ngôn ngữ ngay cả khi không có bản dịch
                        'display_names_as' => 'name', // Hiển thị tên ngôn ngữ dưới dạng slug
                    ));
                }
                ?>
            </ul>
        </div>
    </header>
</div>

<style>
    /* CSS cho phần header */
    #header_s {
        background-color: #f5f5f5;
        padding: 20px;
        display: flex;
        justify-content: flex-end;
    }

    /* CSS cho phần chuyển ngôn ngữ */
    .language-switcher {
        position: relative;
        display: inline-block;
        width: 200px; /* Độ rộng của dropdown */
        font-family: Arial, sans-serif;
        cursor: pointer;
    }

    /* Hiển thị ngôn ngữ hiện tại */
    .current-language {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Thêm mũi tên chỉ xuống */
    .arrow-down {
        border: solid black;
        border-width: 0 3px 3px 0;
        display: inline-block;
        padding: 3px;
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
    }

    /* Dropdown menu (ẩn ban đầu) */
    .language-list {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 5px;
        z-index: 100;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        padding: 0;
        margin: 0;
        list-style: none;
        /*max-height: 200px; !* Đặt giới hạn chiều cao cho dropdown *!*/
        overflow-y: auto; /* Thêm thanh cuộn nếu có quá nhiều mục */
    }

    /* Các mục trong dropdown */
    .language-list li {
        padding: 10px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s;
    }

    .language-list li:last-child {
        border-bottom: none;
    }

    .language-list li a {
        text-decoration: none;
        color: #333;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .language-list li a img {
        margin-right: 8px;
        width: 24px;
        height: auto;
    }

    /* Hiệu ứng hover */
    .language-list li:hover {
        background-color: #f1f1f1;
    }

    /* Hiển thị dropdown khi click */
    .language-switcher.open .language-list {
        display: block;
    }

    /* Kiểu cho ngôn ngữ hiện tại */
    .language-switcher .current-lang {
        background-color: #f5f5f5;
    }
</style>

<?php wp_footer(); ?>
<script type="text/javascript">

</script>

</body>
</html>