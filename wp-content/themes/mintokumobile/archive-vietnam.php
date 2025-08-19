<?php
get_header(); ?>

<main id="main" class="page-form-search">
    <section class="search-form">
        <h1>Tìm kiếm ô tô</h1>

        <!-- Post Type Dropdown -->
        <label for="post_type">Chọn loại bài viết:</label>
        <select name="post_type" id="post_type">
            <option value="">Chọn loại bài viết:</option>
            <option value="vietnam">Việt Nam</option>
            <option value="laos">Lào</option>
            <option value="cambodia">Campuchia</option>
        </select>

        <!-- Province Dropdown -->
        <label for="province">Chọn tỉnh/thành:</label>
        <select name="province" id="province" disabled>
            <option value="">Chọn tỉnh/thành:</option>
        </select>

        <!-- University Dropdown -->
        <label for="university">Chọn trường đại học:</label>
        <select name="university" id="university" disabled>
            <option value="">Chọn trường đại học:</option>
        </select>

        <!-- Year Dropdown -->
        <label for="year">Chọn năm:</label>
        <select name="year" id="year" disabled>
            <option value="">Chọn năm:</option>
        </select>

        <!-- Search Query -->
        <label for="search_query">Tìm kiếm từ khóa:</label>
        <input type="text" id="search_query" name="search_query" />

        <!-- Search Button -->
        <button id="search_button">Tìm kiếm</button>

        <!-- Results Section -->
        <div id="search-results"></div>
    </section>
</main>

<script>
    // Function to update the taxonomy dropdown based on post type
    function updateProvinceDropdown(postType) {
        var provinceDropdown = $('#province');
        var universityDropdown = $('#university');
        var yearDropdown = $('#year');

        provinceDropdown.prop('disabled', true).html('<option value="">Loading...</option>');
        universityDropdown.prop('disabled', true).html('<option value="">Loading...</option>');
        yearDropdown.prop('disabled', true).html('<option value="">Loading...</option>');

        if (!postType) {
            provinceDropdown.html('<option value="">Chọn tỉnh/thành:</option>');
            universityDropdown.html('<option value="">Chọn trường đại học:</option>');
            yearDropdown.html('<option value="">Chọn năm:</option>');
            provinceDropdown.prop('disabled', true);
            universityDropdown.prop('disabled', true);
            yearDropdown.prop('disabled', true);
            return;
        }

        // Fetch taxonomy terms based on the selected post type
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'GET',
            data: {
                action: 'get_taxonomy_terms',
                post_type: postType
            },
            success: function(response) {
                if (response.success) {
                    provinceDropdown.prop('disabled', false).html('<option value="">Chọn tỉnh/thành:</option>');
                    universityDropdown.prop('disabled', false).html('<option value="">Chọn trường đại học:</option>');
                    yearDropdown.prop('disabled', false).html('<option value="">Chọn năm:</option>');

                    response.data.provinces.forEach(function(term) {
                        provinceDropdown.append('<option value="' + term.slug + '">' + term.name + '</option>');
                    });

                    response.data.universities.forEach(function(term) {
                        universityDropdown.append('<option value="' + term.slug + '">' + term.name + '</option>');
                    });

                    response.data.years.forEach(function(term) {
                        yearDropdown.append('<option value="' + term.slug + '">' + term.name + '</option>');
                    });
                } else {
                    provinceDropdown.html('<option value="">Không có tỉnh/thành nào.</option>');
                    universityDropdown.html('<option value="">Không có trường đại học nào.</option>');
                    yearDropdown.html('<option value="">Không có năm nào.</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
                provinceDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                universityDropdown.html('<option value="">Có lỗi xảy ra.</option>');
                yearDropdown.html('<option value="">Có lỗi xảy ra.</option>');
            }
        });
    }


    // Function to add query parameters to the URL
    function updateUrlParams(params) {
        var url = new URL(window.location.href);
        Object.keys(params).forEach(function(key) {
            if (params[key]) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        });
        window.history.pushState({}, '', url);
    }


    // Perform the search based on selected filters
    jQuery(document).ready(function($) {
        // Perform the search based on selected filters
        function performSearch() {
            var postType = $('#post_type').val();
            var province = $('#province').val();
            var university = $('#university').val();
            var year = $('#year').val();
            var searchQuery = $('#search_query').val();

            // Update the URL with the search parameters
            updateUrlParams({
                post_type: postType,
                province: province,
                university: university,
                year: year,
                search_query: searchQuery
            });

            // Perform AJAX search
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'GET',
                data: {
                    action: 'search_jobs',
                    post_type: postType,
                    province: province,
                    university: university,
                    year: year,
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

        // Update province, university, and year dropdowns when post type changes
        $('#post_type').change(function() {
            var selectedPostType = $(this).val();
            updateProvinceDropdown(selectedPostType);
        });

        // Perform search on button click
        $('#search_button').click(function() {
            performSearch();
        })

        // On page load, handle URL parameters and populate the form fields
        var urlParams = new URLSearchParams(window.location.search);
        var postType = urlParams.get('post_type') || '';
        var province = urlParams.get('province') || '';
        var university = urlParams.get('university') || '';
        var year = urlParams.get('year') || '';
        var searchQuery = urlParams.get('search_query') || '';

        $('#post_type').val(postType);
        $('#province_vietnam').val(province);
        $('#university_vietnam').val(university);
        $('#year_vietnam').val(year);
        $('#search_query').val(searchQuery);

        if (postType) {
            updateProvinceDropdown(postType);
        }

        // Trigger search on page load if there are any filter values in the URL
        if (postType || searchQuery || province || university || year) {
            performSearch();
        }
    });
</script>

<?php get_footer(); ?>
