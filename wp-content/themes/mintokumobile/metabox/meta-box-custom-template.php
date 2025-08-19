<div class="custom-meta-box-tabs-visa">
    <button type="button" data-tab="tab1" class="active">Tab 1</button>
    <button type="button" data-tab="tab2">Tab 2</button>
    <button type="button" data-tab="tab3">Tab 3</button>
</div>
<div id="tab1_f" class="custom-meta-box-content-visa active">
    <?php
    // Thêm trình soạn thảo văn bản WordPress vào tab 1
    wp_editor(
        $tab1_value_1,          // Giá trị mặc định
        'tab1_editor_f',        // ID của trình soạn thảo
        array(
            'textarea_name' => 'tab1_field_f', // Tên của trường dữ liệu
            'media_buttons' => true,         // Hiển thị nút thêm media
            'teeny' => true,                // sử dụng giao diện gọn nhẹ
            'textarea_rows' => 10,           // Số hàng của textarea
        )
    );
    ?>
</div>
<div id="tab2_f" class="custom-meta-box-content-visa">
    <label for="tab2_field">Nhập giá trị Tab 2:</label>
    <input type="text" id="tab2_field" name="tab2_field" value="<?php echo esc_attr($tab2_value); ?>" size="25" />
</div>
<div id="tab3_f" class="custom-meta-box-content-visa">
    <label for="tab3_images">Ảnh đại diện:</label>
    <input type="hidden" id="tab3_image_ids" name="tab3_image_ids" value="<?php echo esc_attr($tab3_image_ids_string); ?>" />
    <div id="tab3_images_container">
        <?php if (!empty($tab3_image_ids)): ?>
            <?php foreach ($tab3_image_ids as $image_id): ?>
                <?php $image_url = wp_get_attachment_url($image_id); ?>
                <div class="tab3_image_wrapper">
                    <img src="<?php echo esc_url($image_url); ?>" class="tab3_image" />
                    <button class="tab3_remove_image_button" data-id="<?php echo esc_attr($image_id); ?>">
                        <span class="dashicons dashicons-no-alt"></span>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button id="tab3_upload_images_button" class="button">Tải lên ảnh</button>
</div>
