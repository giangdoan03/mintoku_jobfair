<?php
/**
 * Custom fields + custom columns cho taxonomy university_{region}
 */

if (!function_exists('get_my_regions')) {
    function get_my_regions(): array {
        return ['vietnam','indonesia','laos','cambodia','thailand'];
    }
}

/**
 * Hiển thị custom field khi thêm term
 */
function uni_add_custom_fields($taxonomy) {
    if (!str_starts_with($taxonomy,'university_')) return;
    $region    = str_replace('university_','',$taxonomy);
    $provinces = get_terms(['taxonomy'=>"province_$region",'hide_empty'=>false]);
    ?>
    <div class="form-field term-group">
        <label><?php _e('Select Province');?></label>
        <select name="province_id">
            <option value=""><?php _e('Please select');?></option>
            <?php foreach($provinces as $p): ?>
                <option value="<?=$p->term_id?>"><?=$p->name?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-field term-group"><label>Address</label><input type="text" name="address"></div>
    <div class="form-field term-group"><label>Start</label><input type="date" name="start_date"></div>
    <div class="form-field term-group"><label>End</label><input type="date" name="end_date"></div>
    <div class="form-field term-group"><label><input type="checkbox" name="is_featured" value="1"/> Featured</label></div>
    <?php
}

/**
 * Field khi edit
 */
function uni_edit_custom_fields($term,$taxonomy) {
    if (!str_starts_with($taxonomy,'university_')) return;
    $region    = str_replace('university_','',$taxonomy);
    $provinces = get_terms(['taxonomy'=>"province_$region",'hide_empty'=>false]);

    $meta = [
            'province_id' => get_term_meta($term->term_id,'province_id',true),
            'address'     => get_term_meta($term->term_id,'address',true),
            'start'       => get_term_meta($term->term_id,'start_date',true),
            'end'         => get_term_meta($term->term_id,'end_date',true),
            'featured'    => get_term_meta($term->term_id,'is_featured',true),
    ];
    ?>
    <tr class="form-field">
        <th>Province</th>
        <td>
            <select name="province_id">
                <option value="">Select</option>
                <?php foreach($provinces as $p): ?>
                    <option value="<?=$p->term_id?>" <?=selected($meta['province_id'],$p->term_id,false)?>><?=$p->name?></option>
                <?php endforeach;?>
            </select>
        </td>
    </tr>
    <tr class="form-field"><th>Address</th><td><input type="text" name="address" value="<?=esc_attr($meta['address'])?>"></td></tr>
    <tr class="form-field"><th>Start</th><td><input type="date" name="start_date" value="<?=esc_attr($meta['start'])?>"></td></tr>
    <tr class="form-field"><th>End</th><td><input type="date" name="end_date" value="<?=esc_attr($meta['end'])?>"></td></tr>
    <tr class="form-field"><th>Featured</th><td><input type="checkbox" name="is_featured" value="1" <?=checked($meta['featured'],'1',false)?>> Yes</td></tr>
    <?php
}

// Hook add/edit
foreach(get_my_regions() as $r){
    add_action("university_{$r}_add_form_fields",'uni_add_custom_fields');
    add_action("university_{$r}_edit_form_fields",'uni_edit_custom_fields',10,2);
}

/**
 * Save meta
 */
function uni_save_meta($term_id,$tt_id,$taxonomy) {
    if(!str_starts_with($taxonomy,'university_')) return;
    update_term_meta($term_id,'province_id',sanitize_text_field($_POST['province_id']??''));
    update_term_meta($term_id,'address',sanitize_text_field($_POST['address']??''));
    update_term_meta($term_id,'start_date',sanitize_text_field($_POST['start_date']??''));
    update_term_meta($term_id,'end_date',sanitize_text_field($_POST['end_date']??''));
    update_term_meta($term_id,'is_featured',!empty($_POST['is_featured'])?'1':'0');
}
add_action('created_term','uni_save_meta',10,3);
add_action('edited_term','uni_save_meta',10,3);

/**
 * Custom column (Province + Featured)
 */
function uni_add_columns($cols){
    $cols['province']   = __('Province');
    $cols['is_featured']= __('Featured');
    return $cols;
}
function uni_show_column($out,$col,$term_id){
    if($col==='province'){
        $pid = get_term_meta($term_id,'province_id',true);
        return $pid ? get_term($pid)->name : '<em>None</em>';
    }
    if($col==='is_featured'){
        return get_term_meta($term_id,'is_featured',true) ? '✔':'';
    }
    return $out;
}
foreach(get_my_regions() as $r){
    add_filter("manage_edit-university_{$r}_columns",'uni_add_columns');
    add_filter("manage_university_{$r}_custom_column",'uni_show_column',10,3);
}

/**
 * Ẩn textarea Description
 */
add_action('admin_head',function(){
    $s = get_current_screen();
    if(str_starts_with($s->taxonomy,'university_')){
        echo '<style>.term-description-wrap{display:none!important;}</style>';
    }
});
