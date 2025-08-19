<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package mintokumobile
 */

get_header();
?>

	<div id="primary" class="site-main">

        <?php
        if (in_array(get_post_type(), ['vietnam', 'laos', 'cambodia', 'indonesia'])) {
            include get_template_directory() . '/single-job.php';
        } else {
            // Đối với các post type khác hoặc post
            get_template_part('template-parts/content', get_post_type());
        }
        ?>

    </div>

<?php
get_footer();