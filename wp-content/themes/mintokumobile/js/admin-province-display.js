document.addEventListener('DOMContentLoaded', function () {
    const countryCheckboxes = document.querySelectorAll('input[name="taxonomy-country[]"]');
    const provinceWrappers = document.querySelectorAll('.taxonomy-province');

    countryCheckboxes.forEach(countryCheckbox => {
        countryCheckbox.addEventListener('change', function () {
            const countryId = this.value;
            const isChecked = this.checked;

            provinceWrappers.forEach(wrapper => {
                if (wrapper.dataset.parentCountryId === countryId) {
                    wrapper.style.display = isChecked ? '' : 'none';
                }
            });
        });
    });

    // Hiển thị các tỉnh cho quốc gia đã chọn khi trang được tải
    countryCheckboxes.forEach(countryCheckbox => {
        const countryId = countryCheckbox.value;
        const isChecked = countryCheckbox.checked;

        provinceWrappers.forEach(wrapper => {
            if (wrapper.dataset.parentCountryId === countryId) {
                wrapper.style.display = isChecked ? '' : 'none';
            }
        });
    });
});
