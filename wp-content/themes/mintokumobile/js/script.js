jQuery(document).ready(function($) {
    var currentUrl = new URL(window.location.href);
    var year = currentUrl.pathname.split('/').slice(-2, -1)[0];

    // Khi chọn tỉnh
    $('#province-filter').on('change', function() {
        var provinceSlug = $('#province-filter option:selected').text().toLowerCase().replace(/\s/g, '-');

        if (provinceSlug) {
            // Xóa tham số `university` khỏi URL
            currentUrl.searchParams.delete('university');
            history.pushState(null, '', currentUrl.toString());

            // Cập nhật URL với tham số `province`
            currentUrl.searchParams.set('province', provinceSlug);
            history.pushState(null, '', currentUrl.toString());

            // Gọi AJAX để lấy dữ liệu trường đại học và công việc
            $.ajax({
                url: myAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'load_universities',
                    province_id: $(this).val(),
                    year: year
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#university-filter').html(data.universities);
                    $('#posts-container').html(data.jobs);
                }
            });
        }
    });

    // Khi chọn trường đại học
    $('#university-filter').on('change', function() {
        var universitySlug = $('#university-filter option:selected').text().toLowerCase().replace(/\s/g, '-');

        if (universitySlug) {
            // Cập nhật URL với tham số `university`
            currentUrl.searchParams.set('university', universitySlug);
            history.pushState(null, '', currentUrl.toString());

            // Gọi AJAX để lấy bài viết
            $.ajax({
                url: myAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_posts_by_university',
                    university_id: $(this).val(),
                    year: year
                },
                success: function(response) {
                    $('#posts-container').html(response);
                }
            });
        }
    });

    // Tải lại trang và duy trì kết quả filter dựa trên URL
    var provinceSlug = currentUrl.searchParams.get('province');
    var universitySlug = currentUrl.searchParams.get('university');
    var firstLoad = true; // Cờ để kiểm tra lần tải đầu tiên

    if (provinceSlug) {
        // Tự động chọn giá trị tỉnh từ URL
        $('#province-filter option').each(function() {
            if ($(this).text().toLowerCase().replace(/\s/g, '-') === provinceSlug) {
                $(this).prop('selected', true);
            }
        });

        // Gọi AJAX để load các trường đại học khi tỉnh đã được chọn
        $('#province-filter').trigger('change');
    }

    if (universitySlug) {
        // Chỉ gọi `ajaxComplete` một lần khi lần đầu tải trang
        $(document).one('ajaxComplete', function() {
            if (firstLoad) {
                $('#university-filter option').each(function() {
                    if ($(this).text().toLowerCase().replace(/\s/g, '-') === universitySlug) {
                        $(this).prop('selected', true);
                    }
                });

                // Gọi AJAX để load bài viết khi trường đại học đã được chọn
                $('#university-filter').trigger('change');
                firstLoad = false; // Đánh dấu lần tải đầu tiên đã hoàn tất
            }
        });
    }
});

jQuery(document).ready(function($) {
    $('#university_search').on('input', function() {
        var query = $(this).val();

        $.ajax({
            url: ajaxurl, // URL cho yêu cầu AJAX
            type: 'POST',
            data: {
                action: 'get_universities', // Tên hành động
                search_query: query
            },
            success: function(response) {
                $('#university_checklist').html(response); // Hiển thị kết quả trong danh sách
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const languageSwitcher = document.querySelector('.language-switcher');
    const currentLangElement = document.querySelector('#current-lang');
    const subMenu = document.querySelector('.language-list');

    // Lấy ngôn ngữ hiện tại từ mục được chọn và hiển thị nó
    const currentLang = document.querySelector('.language-list li.current-lang');
    if (currentLang) {
        const currentFlag = currentLang.querySelector('img').outerHTML;
        const currentText = currentLang.querySelector('span').textContent;
        currentLangElement.innerHTML = `${currentFlag} ${currentText}`;
    }

    if (languageSwitcher && subMenu) {
        languageSwitcher.addEventListener('click', function(event) {
            subMenu.style.display = subMenu.style.display === 'block' ? 'none' : 'block';
            event.stopPropagation();
        });

        document.addEventListener('click', function () {
            subMenu.style.display = 'none';
        });
    }

    // Lấy tất cả các phần tử có class 'thumbnail-item'
    var thumbnailItems = document.querySelectorAll('.thumbnail-item');

    // Lặp qua tất cả các phần tử
    thumbnailItems.forEach(function(item) {
        // Kiểm tra nếu phần tử không chứa nội dung
        if (!item.innerHTML.trim()) {
            // Ẩn phần tử rỗng
            item.style.display = 'none';
        }
    });
});











