<footer class="mintoku-footer-c">
    <div class="mintoku-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <span class="logo-bold">mintoku</span>
                <span class="logo-light">messe</span>
                <span class="logo-sub">for Students</span>
            </div>

            <?php
            if (!session_id()) session_start();

            $region = $_SESSION['region'] ?? 'vietnam';

            // Xác định mã ngôn ngữ theo region
            $lang = match ($region) {
                'indonesia' => 'id',
                'thailand' => 'th',
                default => 'vi',
            };

            // Mảng dịch ngôn ngữ
            $translations = [
                'vi' => [
                    'terms' => 'Điều khoản sử dụng',
                    'privacy' => 'Chính sách bảo mật',
                    'company_info' => 'Thông tin công ty vận hành',
                ],
                'id' => [
                    'terms' => 'Syarat Penggunaan',
                    'privacy' => 'Kebijakan Privasi',
                    'company_info' => 'Informasi Perusahaan Pengelola',
                ],
                'th' => [
                    'terms' => 'ข้อกำหนดในการให้บริการ',
                    'privacy' => 'นโยบายความเป็นส่วนตัว',
                    'company_info' => 'ข้อมูลบริษัทที่ให้บริการ',
                ]
            ];

            // Link điều khoản & chính sách riêng theo vùng
            $region_links = [
                'id' => [
                    'terms' => 'https://stg.mintoku.com/messe/jobfair/?page_id=790',
                    'privacy' => 'https://stg.mintoku.com/messe/jobfair/?page_id=787',
                    'company_info' => 'https://lpkaqnesia.com/'
                ],
                'vi' => [
                    'terms' => '#',
                    'privacy' => '#',
                    'company_info' => ''
                ],
                'th' => [
                    'terms' => '#',
                    'privacy' => '#',
                    'company_info' => ''
                ]
            ];
            ?>

            <ul class="footer-links">
                <li>
                    <a href="<?php echo esc_url($region_links[$lang]['terms'] ?? '#'); ?>">
                        <?php echo esc_html($translations[$lang]['terms']); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($region_links[$lang]['privacy'] ?? '#'); ?>">
                        <?php echo esc_html($translations[$lang]['privacy']); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($region_links[$lang]['company_info'] ?? '#'); ?>">
                        <?php echo esc_html($translations[$lang]['company_info']); ?>
                    </a>
                </li>
            </ul>

            <div class="footer-copy">
                ©mintoku messe
            </div>
        </div>
    </div>
</footer>

<?php
$list_ids_str = get_field('list_id_post');
$list_ids_arr = [];

if (!empty($list_ids_str)) {
    $list_ids_arr = array_map('intval', explode(',', $list_ids_str));
}
?>

<script>
    const messePostIds = <?= json_encode($list_ids_arr); ?>;
    const themeUrl = '<?php echo get_template_directory_uri(); ?>';
</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/page-region.js"></script>

<script>
    document.getElementById('menuToggle')?.addEventListener('click', function () {
        this.classList.toggle('active');
        document.getElementById('fullscreenMenu')?.classList.toggle('active');
    });
</script>

<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        Fancybox.bind('[data-fancybox="gallery"]', {
            Thumbs: false,
            Toolbar: {
                display: ["close"]
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const backToTopButton = document.getElementById('back-to-top');
        const btnActionFixed = document.querySelector('.btn_action_fixed');

        window.addEventListener('scroll', function () {
            // Hiện nút back to top
            if (window.scrollY > 200) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }

            // Tính khoảng cách tới cuối trang
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const distanceToBottom = documentHeight - (scrollTop + windowHeight);

            if (btnActionFixed) {
                if (distanceToBottom <= 40) {
                    btnActionFixed.classList.add('fixed-bottom-class');
                } else {
                    btnActionFixed.classList.remove('fixed-bottom-class');
                }
            }
        });

        backToTopButton?.addEventListener('click', function (event) {
            event.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

</script>

<?php wp_footer(); ?>