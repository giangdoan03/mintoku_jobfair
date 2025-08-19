jQuery(document).ready(function($) {
    console.log('Document ready');

    $('.custom-meta-box-tabs button').on('click', function() {
        console.log('Button clicked:', $(this));

        var tabId = $(this).data('tab');
        console.log('Tab ID:', tabId);

        // Xóa lớp active từ tất cả các tab
        $('.custom-meta-box-tabs button').removeClass('active');
        console.log('All buttons removed active class');

        // Ẩn tất cả các nội dung của tab
        $('.custom-meta-box-content').removeClass('active');
        console.log('All tab contents removed active class');

        // Thêm lớp active vào tab được chọn
        $(this).addClass('active');
        console.log('Active class added to:', $(this));

        // Hiển thị nội dung của tab được chọn
        $('#' + tabId).addClass('active');
        console.log('Content for tab', tabId, 'is now active');
    });

    // Mở tab đầu tiên khi tải trang
    $('.custom-meta-box-tabs button:first').trigger('click');
    console.log('First tab clicked on page load');

    $('.custom-meta-box-content').each(function() {
        var $this = $(this);
        var post_type = $this.attr('id').split('-')[0];

        // Handle the Upload Images button
        $this.on('click', '#upload-' + post_type + '-image-button', function(event) {
            event.preventDefault();

            var file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or Upload Images',
                button: {
                    text: 'Use these images'
                },
                multiple: true // Set to true to allow multiple files to be selected
            });

            file_frame.on('select', function() {
                var attachments = file_frame.state().get('selection').toArray();
                var imagesContainer = $('#' + post_type + '-images-container');

                attachments.forEach(function(attachment) {
                    attachment = attachment.toJSON();

                    var imageHtml = `
                        <div class="${post_type}-image-item" style="position: relative; display: inline-block;">
                            <img src="${attachment.url}" style="max-width: 150px; height: auto; display: block;" />
                            <input type="hidden" name="${post_type}_images[]" value="${attachment.url}" />
                            <button class="remove-${post_type}-image" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; cursor: pointer;">Remove</button>
                        </div>
                    `;
                    imagesContainer.append(imageHtml);
                });
            });

            file_frame.open();
        });

        // Handle the Remove button to remove the image
        $(document).on('click', '.remove-' + post_type + '-image', function(event) {
            event.preventDefault();
            $(this).closest('.' + post_type + '-image-item').remove();
        });
    });

    // custom metabox visa
    $('.custom-meta-box-tabs-visa button').click(function() {
        var tab_id = $(this).data('tab');

        // Chuyển đổi tab
        $('.custom-meta-box-tabs-visa button').removeClass('active');
        $(this).addClass('active');
        $('.custom-meta-box-content-visa').removeClass('active');
        $('#' + tab_id).addClass('active');
    });


    // Khởi tạo uploader
    var mediaUploader;
    $('#tab3_upload_images_button').click(function(e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media({
            title: 'Chọn ảnh',
            button: {
                text: 'Chọn ảnh'
            },
            multiple: true // Cho phép chọn nhiều ảnh
        }).on('select', function() {
            var attachments = mediaUploader.state().get('selection').toJSON();
            var image_ids = [];
            var image_urls = [];
            $.each(attachments, function(index, attachment) {
                image_ids.push(attachment.id);
                image_urls.push(attachment.url);
                $('#tab3_images_container').append(
                    '<div class="tab3_image_wrapper">' +
                    '<img src="' + attachment.url + '" style="max-width: 100px; height: auto;" />' +
                    '<button class="tab3_remove_image_button button">Xóa ảnh</button>' +
                    '</div>'
                );
            });
            $('#tab3_image_ids').val(image_ids.join(','));
        }).open();
    });

    // Xóa ảnh
    // Xử lý sự kiện nhấp vào nút xóa ảnh
    $('#tab3_images_container').on('click', '.tab3_remove_image_button', function(e) {
        e.preventDefault();

        var button = $(this);
        var imageId = button.data('id');
        var container = button.closest('.tab3_image_wrapper');
        var imageIdsField = $('#tab3_image_ids');
        var imageIds = imageIdsField.val().split(',').filter(id => id != imageId);

        // Xóa ảnh khỏi danh sách hiện tại
        container.remove();

        // Cập nhật giá trị của trường ẩn với danh sách ảnh còn lại
        imageIdsField.val(imageIds.join(','));

        // Gửi yêu cầu AJAX để xóa ảnh
        $.post(
            ajaxurl, // URL của file admin-ajax.php
            {
                action: 'remove_image',
                image_id: imageId,
                post_id: $('#post_ID').val()
            },
            function(response) {
                // Xử lý phản hồi từ server nếu cần
            }
        );
    });

    function setupImageMetaBox(uploadButtonId, previewContainerId, hiddenInputId) {
        var file_frame;

        // Handle image upload
        $(uploadButtonId).on('click', function(event) {
            event.preventDefault();

            if (file_frame) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or Upload Images',
                button: {
                    text: 'Use Selected Images',
                },
                multiple: true
            });

            file_frame.on('select', function() {
                var attachments = file_frame.state().get('selection').toArray();
                var attachment_ids = [];

                $(previewContainerId).empty();

                attachments.forEach(function(attachment) {
                    if (attachment && attachment.attributes && attachment.attributes.sizes) {
                        var thumbnail_url = attachment.attributes.sizes.thumbnail ? attachment.attributes.sizes.thumbnail.url : attachment.attributes.url;
                        if (thumbnail_url) {
                            attachment_ids.push(attachment.id);
                            $(previewContainerId).append(
                                '<li data-id="' + attachment.id + '" style="position: relative; display: inline-block;">' +
                                '<img src="' + thumbnail_url + '" style="max-width: 150px; height: auto;" />' +
                                '<a href="#" class="remove-image" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; cursor: pointer;">Remove</a>' +
                                '</li>'
                            );
                        } else {
                            console.error('Thumbnail URL not found for attachment:', attachment);
                        }
                    } else {
                        console.error('Attachment data is missing expected properties:', attachment);
                    }
                });

                $(hiddenInputId).val(attachment_ids.join(','));
            });

            file_frame.open();
        });

        // Handle image removal
        $(document).on('click', '.remove-image', function(e) {
            e.preventDefault();
            var $li = $(this).closest('li');
            var id = $li.data('id');
            var ids = $(hiddenInputId).val().split(',');

            ids = ids.filter(function(item) {
                return item != id;
            });

            $(hiddenInputId).val(ids.join(','));
            $li.remove();
        });
    }

    // Call the function for different meta boxes
    setupImageMetaBox('#upload_images_button', '#image-preview', '#custom_image_ids');
    setupImageMetaBox('#vietnam-upload-images-button', '#vietnam-image-preview', '#vietnam-custom-image-ids');
});
