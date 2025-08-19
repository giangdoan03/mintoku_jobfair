<?php
/**
 * Custom taxonomy meta box & admin columns
 * Use for university_* , company_* taxonomy and post types: vietnam, indonesia...
 */

// Regions dùng chung
$regions = ['vietnam','indonesia','laos','cambodia','thailand'];

/*------------------------
  Custom meta box taxonomy
-------------------------*/
function custom_taxonomy_remove_default_boxes(): void
{
    remove_meta_box('university_vietnamdiv','vietnam','side');
    remove_meta_box('company_vietnamdiv','vietnam','side');
}
add_action('admin_menu','custom_taxonomy_remove_default_boxes');

function custom_add_meta_boxes(): void
{
    add_custom_taxonomy_meta_box('university_vietnam',__('Chọn trường đại học','textdomain'));
    add_custom_taxonomy_meta_box('company_vietnam',__('Chọn công ty','textdomain'));
}
add_action('add_meta_boxes','custom_add_meta_boxes');

function add_custom_taxonomy_meta_box($taxonomy,$label): void
{
    add_meta_box(
        $taxonomy.'_box',
        $label,
        function($post) use($taxonomy){ render_custom_taxonomy_meta_box($post,$taxonomy); },
        'vietnam',
        'side'
    );
}

function render_custom_taxonomy_meta_box($post,$taxonomy){
    $terms = get_terms(['taxonomy'=>$taxonomy,'hide_empty'=>false]);
    $selected = wp_get_object_terms($post->ID,$taxonomy,['fields'=>'ids']);
    echo '<input type="text" id="search_'.$taxonomy.'" placeholder="Tìm..." style="width:100%;margin-bottom:10px">';
    echo '<div id="'.$taxonomy.'-list">';
    foreach($terms as $t){
        $chk = in_array($t->term_id,$selected)?'checked':'';
        echo '<div><input type="checkbox" name="selected_'.$taxonomy.'[]" value="'.$t->term_id.'" '.$chk.'> '.$t->name.'</div>';
    }
    echo '</div>';
    wp_nonce_field('save_'.$taxonomy.'_box',$taxonomy.'_nonce');
}

function save_custom_taxonomy_meta_boxes($post_id){
    foreach(['university_vietnam','company_vietnam'] as $tax){
        if(!isset($_POST[$tax.'_nonce']) || !wp_verify_nonce($_POST[$tax.'_nonce'],'save_'.$tax.'_box')) continue;
        $val = isset($_POST['selected_'.$tax]) ? array_map('intval',$_POST['selected_'.$tax]) : [];
        wp_set_object_terms($post_id,$val,$tax);
    }
}
add_action('save_post','save_custom_taxonomy_meta_boxes');

/*-------------------------
  Tìm kiếm term realtime
--------------------------*/
function custom_admin_footer(): void
{
    foreach(['university_vietnam','company_vietnam'] as $taxonomy){
        ?>
        <script>
            jQuery(function($){
                $('#search_<?php echo esc_js($taxonomy);?>').on('keyup',function(){
                    var key = $(this).val().toLowerCase();
                    $('#<?php echo esc_js($taxonomy);?>-list div').each(function(){
                        var txt = $(this).text().toLowerCase();
                        $(this).toggle(txt.indexOf(key)>-1);
                    });
                });
            });
        </script>
        <?php
    }
}
add_action('admin_footer','custom_admin_footer');

/*-------------------------
   Custom column for posts
-------------------------*/
function custom_admin_columns($columns){
    global $current_screen;
    $pt = $current_screen->post_type ?? '';
    if(!in_array($pt,['vietnam','indonesia','laos','cambodia','thailand'])) return $columns;

    $new=[];
    foreach($columns as $k=>$v){
        $new[$k]=$v;
        if($k==='title'){
            $new["province_{$pt}"]    =__('Province');
            $new["university_{$pt}"]  =__('University');
            $new["company_{$pt}"]     =__('Company');
            $new['recommended_work']  =__('Recommended Work');
        }
    }
    return $new;
}
add_filter('manage_vietnam_posts_columns','custom_admin_columns');
add_filter('manage_indonesia_posts_columns','custom_admin_columns');
add_filter('manage_laos_posts_columns','custom_admin_columns');
add_filter('manage_cambodia_posts_columns','custom_admin_columns');
add_filter('manage_thailand_posts_columns','custom_admin_columns');

function custom_admin_column_content($column,$post_id): void
{
    $pt = get_post_type($post_id);
    if(!in_array($pt,['vietnam','indonesia','laos','cambodia','thailand'])) return;

    if($column==="province_{$pt}"||$column==="company_{$pt}"||$column==="university_{$pt}"){
        $tax  = str_replace("{$pt}","",$column).'_'.$pt;
        $terms=get_the_terms($post_id,$tax);
        echo !empty($terms)?esc_html(implode(', ',wp_list_pluck($terms,'name'))):__('None');
    }
    if($column==='recommended_work'){
        $val=get_post_meta($post_id,'recommended_work',true);
        if(is_array($val)) echo implode(', ',$val);
        elseif($val==='recommended') echo '<span style="color:green">✔</span>';
        elseif($val) echo '<span>'.$val.'</span>';
        else echo '<span style="color:red">No</span>';
    }
}
add_action('manage_vietnam_posts_custom_column','custom_admin_column_content',10,2);
add_action('manage_indonesia_posts_custom_column','custom_admin_column_content',10,2);
add_action('manage_laos_posts_custom_column','custom_admin_column_content',10,2);
add_action('manage_cambodia_posts_custom_column','custom_admin_column_content',10,2);
add_action('manage_thailand_posts_custom_column','custom_admin_column_content',10,2);

function custom_sortable_cols($cols){
    global $current_screen;
    $pt=$current_screen->post_type??'';
    if(in_array($pt,get_my_regions())){
        $cols["province_{$pt}"]="province_{$pt}";
        $cols["company_{$pt}"]="company_{$pt}";
        $cols['recommended_work']='recommended_work';
    }
    return $cols;
}
add_filter('manage_edit-vietnam_sortable_columns','custom_sortable_cols');
add_filter('manage_edit-indonesia_sortable_columns','custom_sortable_cols');
add_filter('manage_edit-laos_sortable_columns','custom_sortable_cols');
add_filter('manage_edit-cambodia_sortable_columns','custom_sortable_cols');
add_filter('manage_edit-thailand_sortable_columns','custom_sortable_cols');

function order_recommended($query): void
{
    if(!is_admin()||!$query->is_main_query()) return;
    $pt=$_GET['post_type']??'';
    if (!function_exists('get_my_regions')) {
        function get_my_regions(): array
        {
            return ['vietnam', 'indonesia', 'laos', 'cambodia', 'thailand'];
        }
    }

}
add_action('pre_get_posts','order_recommended');
