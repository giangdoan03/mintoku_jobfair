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

            // Lấy region từ session (mặc định 'vietnam')
            $region = $_SESSION['region'] ?? 'vietnam';

            // Xác định mã ngôn ngữ theo region
            switch ($region) {
                case 'indonesia':
                    $lang = 'id';
                    break;
                case 'thailand':
                    $lang = 'th';
                    break;
                default:
                    $lang = 'vi';
                    break;
            }

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
                    'terms' => 'https://mintoku.com/messe/jobfair/?page_id=790',
                    'privacy' => 'https://mintoku.com/messe/jobfair/?page_id=787',
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
    // Biến toàn cục từ PHP
    const messePostIds = <?= json_encode($list_ids_arr); ?>;
    const themeUrl = '<?= get_template_directory_uri(); ?>';
</script>

<!-- Fancybox -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<!-- JS chính -->
<script src="<?= get_template_directory_uri(); ?>/js/page-region.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ===== 1. Menu toggle =====
        const menuToggle = document.getElementById('menuToggle');
        const fullscreenMenu = document.getElementById('fullscreenMenu');

        if (menuToggle && fullscreenMenu) {
            menuToggle.addEventListener('click', function () {
                this.classList.toggle('active');
                fullscreenMenu.classList.toggle('active');
            });
        }

        // ===== 2. Fancybox Gallery =====
        Fancybox.bind('[data-fancybox="gallery"]', {
            Thumbs: false,
            Toolbar: {
                display: ["close"]
            }
        });

        // ===== 3. Back to top + đẩy nút khi gần đáy =====
        const backToTopButton = document.getElementById('back-to-top');
        const btnActionFixed = document.querySelector('.btn_action_fixed');

        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const distanceToBottom = documentHeight - (scrollTop + windowHeight);

            // Hiện nút back to top nếu scroll đủ cao
            if (backToTopButton) {
                backToTopButton.classList.toggle('show', scrollTop > 200);
            }

            // Đẩy nút lên khi gần đáy
            if (btnActionFixed) {
                btnActionFixed.classList.toggle('fixed-bottom-class', distanceToBottom <= 40);
            }
        });

        // ===== 4. Scroll lên đầu =====
        if (backToTopButton) {
            backToTopButton.addEventListener('click', function (e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    });
    

    const urlParams = new URLSearchParams(window.location.search);
    const regionParam = urlParams.get('region');

 
</script>






<?php wp_footer(); ?>