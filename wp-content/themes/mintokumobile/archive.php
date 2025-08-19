<?php

get_header(); ?>

<main id="main" class="page-form-search">
    <section class="search-form">
        <h1>Tìm kiếm ô tô</h1>
        <label for="color">Chọn tỉnh/thành:</label>
        <select name="color" id="color">
            <option value="">Chọn tỉnh/thành:</option>
            <?php
            $terms = get_terms(array(
                'taxonomy' => 'color',
                'hide_empty' => false,
            ));
            foreach ($terms as $term) {
                echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
            }
            ?>
        </select>

        <label for="search_query">Tìm kiếm từ khóa:</label>
        <input type="text" id="search_query" name="search_query" />

        <button id="search_button">Tìm kiếm</button>

        <!-- Khu vực hiển thị kết quả -->
        <div id="search-results"></div>
    </section>
</main>

<script>
    // Hàm cập nhật URL với tham số bộ lọc
    function updateURLParameter(param, value) {
        var url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(param, value);
        } else {
            url.searchParams.delete(param);
        }
        window.history.replaceState({}, '', url);
    }

    jQuery(document).ready(function($) {
        function performSearch() {
            var province_vietnam = $('#province_vietnam').val();
            var searchQuery = $('#search_query').val();

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'GET',
                data: {
                    action: 'search_cars',
                    color: province_vietnam,
                    search_query: searchQuery
                },
                success: function(response) {
                    if (response.success) {
                        var resultHtml = '';
                        response.data.forEach(function(post) {
                            resultHtml += '<p><a href="' + post.link + '">' + post.title + '</a></p>';
                        });
                        $('#search-results').html(resultHtml);
                    } else {
                        $('#search-results').html('<p>Không có bài viết nào.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    $('#search-results').html('<p>Có lỗi xảy ra khi tìm kiếm.</p>');
                }
            });
        }

        $('#search_button').click(function() {
            updateURLParameter('color', $('#color').val());
            updateURLParameter('search_query', $('#search_query').val());
            performSearch();
        });

        // Xử lý khi trang được tải lại với tham số URL
        var urlParams = new URLSearchParams(window.location.search);
        $('#color').val(urlParams.get('province_vietnam') || '');
        $('#search_query').val(urlParams.get('search_query') || '');

        if (urlParams.has('color') || urlParams.has('search_query')) {
            performSearch();
        }
    });
</script>

<?php get_footer(); ?>
