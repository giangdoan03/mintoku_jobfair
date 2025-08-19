<?php
/* Template Name: Trang Tìm kiếm */
?>

<?php
get_header(); ?>

<main id="main" class="page-form-search">
    <section class="search-form">
        <h1>Tìm kiếm bài viết</h1>
        <label for="post_type">Chọn quốc gia:</label>
        <select name="post_type" id="post_type">
            <option value="">Chọn quốc gia</option>
            <option value="vietnam">Việt Nam</option>
            <option value="laos">Lào</option>
            <option value="cambodia">Campuchia</option>
        </select>

        <label for="province_vietnam">Chọn tỉnh/thành phố:</label>
        <select name="province_vietnam" id="vietnam-provinces">
            <option value="">Chọn tỉnh/thành phố</option>
        </select>

        <label for="university_vietnam">Chọn trường đại học:</label>
        <select name="university_vietnam" id="vietnam-university">
            <option value="">Chọn trường đại học</option>
        </select>

        <label for="year_vietnam">Chọn năm:</label>
        <select name="year_vietnam" id="vietnam-year">
            <option value="">Chọn năm</option>
        </select>

        <!-- Nơi hiển thị kết quả -->
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
            var postType = $('#post_type').val();
            var provinceSlug = $('#vietnam-provinces').val();
            var universitySlug = $('#vietnam-university').val();
            var yearSlug = $('#vietnam-year').val();

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'GET',
                data: {
                    action: 'search_posts',
                    post_type: postType,
                    province_slug: provinceSlug,
                    university_slug: universitySlug,
                    year_slug: yearSlug
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

        $('#post_type').change(function() {
            var postType = $(this).val();
            localStorage.setItem('post_type', postType);
            updateURLParameter('post_type', postType);

            if (postType === 'vietnam') {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'GET',
                    data: {
                        action: 'load_filters',
                        post_type: postType
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#vietnam-provinces').html('<option value="">Chọn tỉnh/thành phố</option>');
                            response.data.provinces.forEach(function(province) {
                                $('#vietnam-provinces').append('<option value="' + province.slug + '">' + province.name + '</option>');
                            });

                            $('#vietnam-university').html('<option value="">Chọn trường đại học</option>');
                            response.data.universities.forEach(function(university) {
                                $('#vietnam-university').append('<option value="' + university.slug + '">' + university.name + '</option>');
                            });

                            $('#vietnam-year').html('<option value="">Chọn năm</option>');
                            response.data.years.forEach(function(year) {
                                $('#vietnam-year').append('<option value="' + year.slug + '">' + year.name + '</option>');
                            });

                            performSearch();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', error);
                    }
                });
            } else {
                $('#vietnam-provinces').html('<option value="">Chọn tỉnh/thành phố</option>');
                $('#vietnam-university').html('<option value="">Chọn trường đại học</option>');
                $('#vietnam-year').html('<option value="">Chọn năm</option>');
                $('#search-results').html('');
            }
        });

        $('#vietnam-provinces, #vietnam-university, #vietnam-year').change(function() {
            console.log( $(this).val())
            updateURLParameter($(this).attr('id'), $(this).val());
            performSearch();
        });

        // var savedPostType = localStorage.getItem('post_type');
        // if (savedPostType) {
        //     $('#post_type').val(savedPostType).trigger('change');
        // }

        var urlParams = new URLSearchParams(window.location.search);
        $('#post_type').val(urlParams.get('post_type') || '');
        $('#vietnam-provinces').val(urlParams.get('vietnam-provinces') || '');
        $('#vietnam-university').val(urlParams.get('vietnam-university') || '');
        $('#vietnam-year').val(urlParams.get('vietnam-year') || '');

        if (urlParams.has('post_type')) {
            performSearch();
        }
    });
</script>

<?php get_footer(); ?>
