(function ($) {
    // ==== Điều hướng Job Fair ====

   const BASE_API_URL = 'https://mintoku.com/life-support/id/wp-json/wp/v2/posts';

   function goToJobFair(el) {
    const $el = $(el);
    const region = $el.data('region');
    const university = $el.data('university');

    // Đường dẫn mới cố định
    const baseUrl = 'https://mintoku.com/messe/jobfair/';
    const pageId = 307;

    const url = new URL(baseUrl);
    url.searchParams.set('page_id', pageId);
    if (region) url.searchParams.set('region', region);
    if (university) url.searchParams.set('university', university);

    window.location.href = url.toString();
}


    // Gắn vào global để gọi từ HTML inline handler (nếu cần)
    window.goToJobFair = goToJobFair;

    // ==== Khởi tạo danh sách bài viết ====
    function initMesseList() {
        const $messeList = $('.messe-list');
        if (typeof messePostIds === 'undefined' || messePostIds.length === 0) return;

        $messeList.html('<li><div class="messe-loader"></div></li>');

        const apiUrl = `${BASE_API_URL}?_embed=true&orderby=include&include=${messePostIds.join(',')}`;
        $.getJSON(apiUrl)
            .done(posts => renderMesseList(posts, $messeList))
            .fail(() => {
                $messeList.html('<li>Không tải được bài viết</li>');
            });
    }

    function renderMesseList(posts, $container) {
        $container.empty();
        posts.forEach(post => {
            const thumb = post._embedded?.['wp:featuredmedia']?.[0]?.source_url || 'https://placehold.co/80x60';
            const html = `
                <li>
                    <a href="#" data-post-id="${post.id}">
                        <img src="${thumb}" alt="thumb" />
                        <span class="text_title">${post.title.rendered}</span>
                        <span class="arrow">
                            <img src="${themeUrl}/images/arrow_right_part.png" alt="">
                        </span>
                    </a>
                </li>`;
            $container.append(html);
        });
    }

    // ==== Xử lý khi click xem chi tiết bài viết ====
    function bindMesseItemClick() {
        $(document).on('click', '.messe-list a[data-post-id]', function (e) {
            e.preventDefault();
            const postId = $(this).data('post-id');
            showMessePopup(postId);
        });
    }

    function showMessePopup(postId) {
        const $popup = $('#messe-popup');
        const $inner = $popup.find('.messe-popup-inner');

        $inner.html('<div class="messe-loader"></div>');
        $popup.addClass('show');

        const apiUrl = `${BASE_API_URL}/${postId}?_embed`;
        $.getJSON(apiUrl)
            .done(post => renderMessePopup(post, $inner))
            .fail(() => {
                $inner.html('<p>Lỗi tải bài viết.</p>');
            });
    }

    function renderMessePopup(post, $container) {
        const thumb = post._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
        const html = `
            <h2>${post.title.rendered}</h2>
            ${thumb ? `<img src="${thumb}" style="max-width:100%;margin-bottom:10px;" />` : ''}
            <div class="content">${post.content.rendered}</div>`;
        $container.html(html);
    }

    // ==== Đóng popup ====
    function bindPopupClose() {
        $(document).on('click', '.messe-close-btn, .messe-popup-overlay', function () {
            $('#messe-popup').removeClass('show');
        });
    }

    // ==== Khởi chạy toàn bộ khi DOM sẵn sàng ====
    $(function () {
        initMesseList();
        bindMesseItemClick();
        bindPopupClose();
    });

})(jQuery);
