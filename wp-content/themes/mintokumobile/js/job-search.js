jQuery(document).ready(function ($) {
    const $postType = $('#post_type');
    const $province = $('#province-select');
    const $university = $('#university-select');
    const $company = $('#company');
    const $year = $('#year_r');
    const $searchButton = $('#search-btn');
    const $results = $('#job-results');

    const translations = {
        vi: {
            placeholder_company: 'Chọn công ty',
            placeholder_province: 'Chọn tỉnh/thành:',
            placeholder_university: 'Chọn trường:',
            placeholder_year: 'Chọn năm:',
            loading: 'Đang tải...',
            searching: 'Đang tìm...',
            search: 'Tìm kiếm',
            no_results: 'Không có bài viết nào.',
            search_error: 'Có lỗi xảy ra khi tìm kiếm.',
            default_link_label: 'Jobs Fair',
            default_university_placeholder: 'Chọn trường đại học'
        },
        id: {
            placeholder_company: 'Pilih perusahaan',
            placeholder_province: 'Pilih provinsi:',
            placeholder_university: 'Pilih universitas:',
            placeholder_year: 'Pilih tahun:',
            loading: 'Memuat...',
            searching: 'Sedang mencari...',
            search: 'Cari',
            no_results: 'Tidak ada postingan.',
            search_error: 'Terjadi kesalahan saat mencari.',
            default_link_label: 'Pameran Kerja',
            default_university_placeholder: 'Pilih universitas'
        }
    };

    function getLang() {
        const region = $postType.val() || getUrlParam('region') || 'vietnam';
        return region === 'indonesia' ? 'id' : 'vi';
    }

    function updateUIText(lang) {
        $company.html(`<option value="">${translations[lang].placeholder_company}</option>`);
        $province.html(`<option value="">${translations[lang].placeholder_province}</option>`);
        $university.html(`<option value="">${translations[lang].placeholder_university}</option>`);
        $year.html(`<option value="">${translations[lang].placeholder_year}</option>`);
        $searchButton.text(translations[lang].search);
    }

    if ($.fn.select2) {
        $company.select2({ placeholder: translations[getLang()].placeholder_company, allowClear: true });
    }

    function getUrlParam(param) {
        return new URLSearchParams(window.location.search).get(param);
    }

    function updateUrlParams(params = {}, clearParams = []) {
        const url = new URL(window.location.href);
        Object.entries(params).forEach(([key, val]) => {
            if (val) url.searchParams.set(key, val);
            else url.searchParams.delete(key);
        });
        clearParams.forEach(p => url.searchParams.delete(p));
        window.history.replaceState({}, '', url);
    }

    function populateCompanyDropdown(region, provinceSlug = '', universitySlug = '', year = '', searchQuery = '', selectedCompanySlug = '') {
        const lang = getLang();
        $company.prop('disabled', true).html(`<option>${translations[lang].loading}</option>`);

        $.get(ajaxurl, {
            action: 'get_company_job_counts',
            region,
            province: provinceSlug,
            university: universitySlug,
            year_r: year,
            search_query: searchQuery
        }).done(function (response) {
            if (response.success && Array.isArray(response.data)) {
                $company.prop('disabled', false).html(`<option value="">${translations[lang].placeholder_company}</option>`);
                response.data.forEach(company => {
                    const selected = company.slug === selectedCompanySlug ? 'selected' : '';
                    const label = `${company.name} (${company.count})`;
                    $company.append(`<option value="${company.slug}" ${selected}>${label}</option>`);
                });
            }
        });
    }

    function updateDropdowns(postType, selectedProvinceSlug = '', selectedUniversitySlug = '', selectedCompanySlug = '', selectedYearSlug = '') {
        const lang = getLang();
        $province.prop('disabled', true).html(`<option>${translations[lang].loading}</option>`);
        $university.prop('disabled', true).html(`<option>${translations[lang].loading}</option>`);
        $company.prop('disabled', true).html(`<option>${translations[lang].loading}</option>`);
        $year.prop('disabled', true).html(`<option>${translations[lang].loading}</option>`);

        if (!postType) return;

        $.get(ajaxurl, {
            action: 'get_taxonomy_terms',
            region: postType
        }).done(function (response) {
            if (!response.success || !response.data) {
                $province.prop('disabled', false).html('<option value="">(Không có dữ liệu tỉnh)</option>');
                $university.prop('disabled', false).html('<option value="">(Không có trường)</option>');
                $company.prop('disabled', false).html('<option value="">(Không có công ty)</option>');
                $year.prop('disabled', false).html('<option value="">(Không có năm)</option>');
                console.warn('Không nhận được dữ liệu taxonomy:', response);
                return;
            }


            updateUIText(lang);

            $province.prop('disabled', false);
            $university.prop('disabled', false);
            $company.prop('disabled', false);
            $year.prop('disabled', false);

            response.data.provinces.forEach(term => {
                const selected = term.slug === selectedProvinceSlug ? 'selected' : '';
                $province.append(`<option value="${term.slug}" data-slug="${term.slug}" data-term-id="${term.term_id}" ${selected}>${term.name}</option>`);
            });

            const selectedProvinceId = parseInt($province.find(':selected').data('term-id'));

            response.data.universities.forEach(term => {
                if (!selectedProvinceId || parseInt(term.province_id) === selectedProvinceId) {
                    const selected = term.slug === selectedUniversitySlug ? 'selected' : '';
                    $university.append(`<option value="${term.slug}" data-slug="${term.slug}" ${selected}>${term.name}</option>`);
                }
            });

            populateCompanyDropdown(postType, selectedProvinceSlug, selectedUniversitySlug, selectedCompanySlug);

            if (Array.isArray(response.data.years)) {
                response.data.years.forEach(term => {
                    const selected = term.slug === selectedYearSlug ? 'selected' : '';
                    $year.append(`<option value="${term.slug}" ${selected}>${term.name}</option>`);
                });
            }

            performSearch();
        });
    }

    function performSearch() {
        const lang = getLang();
        const postType = $postType.val() || 'vietnam';
        const provinceSlug = $province.find(':selected').val();
        const provinceName = $province.find(':selected').text();
        const universitySlug = $university.find(':selected').val();
        const universityName = $university.find(':selected').text();
        const companySlug = $company.val();
        const companyName = $company.find(':selected').text();
        const year = $year.val();
        const searchQuery = $('#search_query').val();

        if (!postType) {
            $('#post_type_error').show();
            return;
        }
        $('#post_type_error').hide();

        updateUrlParams({
            region: postType,
            province: provinceSlug,
            university: universitySlug,
            company: companySlug,
            year_r: year,
            search_query: searchQuery
        });

        $searchButton.prop('disabled', true).text(translations[lang].searching);

        $.get(ajaxurl, {
            action: 'search_jobs',
            region: postType,
            province: provinceSlug,
            university: universitySlug,
            company: companySlug,
            year_r: year,
            search_query: searchQuery
        }).done(function (response) {
            $results.empty();

            if (!response.success || !response.data || response.data.length === 0) {
                $results.html(`<p>${translations[lang].no_results}</p>`);
                return;
            }

            const seenLinks = {};
            const baseURL = window.location.hostname.includes('localhost') ? window.location.origin + '/mintoku_mobile' : window.location.origin;

            let link = `${baseURL}/messe/jobfair/?page_id=250&region=${encodeURIComponent(postType)}`;
            if (provinceSlug) link += `&province=${encodeURIComponent(provinceSlug)}`;
            if (universitySlug) link += `&university=${encodeURIComponent(universitySlug)}`;
            if (companySlug) link += `&company=${encodeURIComponent(companySlug)}`;
            if (year) link += `&year_r=${encodeURIComponent(year)}`;
            if (searchQuery) link += `&search_query=${encodeURIComponent(searchQuery)}`;

            let label = translations[lang].default_link_label;
            if (provinceSlug) label += ` - ${provinceName}`;
            if (universitySlug) label += ` - ${universityName}`;
            if (year) label += ` - ${year}`;
            if (companySlug) label += ` - ${companyName}`;

            if (!seenLinks[link]) {
                seenLinks[link] = true;
                $results.append(`<p><a href="${link}" target="_blank">${label}</a></p>`);
            }

        }).fail(() => {
            $results.html(`<p>${translations[lang].search_error}</p>`);
        }).always(() => {
            $searchButton.prop('disabled', false).text(translations[lang].search);
        });
    }

    $postType.on('change', function () {
        const val = $(this).val();
        updateUrlParams({ region: val }, ['province', 'university', 'company']);
        updateDropdowns(val);
        updateUIText(getLang());
    });

    $province.on('change', function () {
        const slug = $(this).find(':selected').data('slug');
        updateUrlParams({ province: slug }, ['university']);
        const currentRegion = $postType.val() || getUrlParam('region') || 'vietnam';

        $university.empty().append(`<option value="">${translations[getLang()].default_university_placeholder}</option>`);

        $.get(ajaxurl, {
            action: 'search_jobs',
            region: currentRegion,
            province: slug
        }).done(function (response) {
            if (response.success) {
                const universityMap = {};

                response.data.forEach(job => {
                    const uName = job.university;
                    if (uName && !universityMap[uName]) {
                        universityMap[uName] = true;
                        $university.append(`<option value="${uName}" ${uName === getUrlParam('university') ? 'selected' : ''}>${uName}</option>`);
                    }
                });
            }
        });

        performSearch();
    });

    $university.on('change', function () {
        const slug = $(this).find(':selected').data('slug');
        updateUrlParams({ university: slug });
    });

    $company.on('change', function () {
        updateUrlParams({ company: $(this).val() });
    });

    $searchButton.on('click', function () {
        performSearch();
    });

    const initRegion = getUrlParam('region') || '';
    const initProvince = getUrlParam('province') || '';
    const initUniversity = getUrlParam('university') || '';
    const initCompany = getUrlParam('company') || '';

    if (initRegion) {
        $postType.val(initRegion);
        updateUIText(getLang());
        updateDropdowns(initRegion, initProvince, initUniversity, initCompany);
    }


    $('#post_type').on('change', function () {
        const region = $(this).val();
        $.post(ajaxurl, {
            action: 'save_region_session',
            region: region
        });
    });
});
