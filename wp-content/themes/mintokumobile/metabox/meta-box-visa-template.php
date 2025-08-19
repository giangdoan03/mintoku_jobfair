<div class="custom-meta-box-tabs-visa">
    <button type="button" data-tab="tab1" class="active">Tab 1</button>
    <button type="button" data-tab="tab2">Tab 2</button>
    <button type="button" data-tab="tab3">Tab 3</button>
</div>
<div id="tab1" class="custom-meta-box-content-visa active">
    <?php
    // Thêm trình soạn thảo văn bản WordPress vào tab 1
    wp_editor(
        $tab1_value,          // Giá trị mặc định
        'tab1_editor',        // ID của trình soạn thảo
        array(
            'textarea_name' => 'tab1_field', // Tên của trường dữ liệu
            'media_buttons' => true,         // Hiển thị nút thêm media
            'teeny' => true,                // sử dụng giao diện gọn nhẹ
            'textarea_rows' => 10,           // Số hàng của textarea
        )
    );
    ?>
</div>
<div id="tab2" class="custom-meta-box-content-visa">
    <label for="tab2_field">Nhập giá trị Tab 2:</label>
    <input type="text" id="tab2_field" name="tab2_field" value="<?php echo esc_attr($tab2_value); ?>" size="25" />
</div>
<div id="tab3" class="custom-meta-box-content-visa">
  <?php
  function custom_display_images_on_frontend($post_id) {
      $post = get_post($post_id);
      if ($post) {
          // Call the metabox render function
          render_custom_image_metabox($post);
      }
  }

  // Call this function in your desired location
  custom_display_images_on_frontend(get_the_ID());
  ?>
</div>
