<?php
/* Template Name: Thỏa thuận sử dụng */

// Include WordPress header
get_header();

$placeholder_image = get_stylesheet_directory_uri() . '/images/mintokumesse_logo.png';
?>
<div id="page-term" class="page-term">
    <div class="banner">
        <div class="logo_job_term_page">
            <img src="<?php echo esc_url($placeholder_image); ?>" alt="">
        </div>
        <h2 class="title_term_page"><?php the_title(); ?></h2>
    </div>
    <div class="container">
        <?php
        // Start the loop to get the page content
        while ( have_posts() ) :
            the_post(); // Set up the post data
            ?>
            <div class="page-content">
                <?php the_content(); ?> <!-- Display the page content -->
            </div>
            <div class="back_page">
                <a href="https://mintoku.com/messe/jobfair/region/?region=indonesia" id="backButton_term">Kembali</a>
            </div>

        <?php
        endwhile; // End of the loop.
        ?>
    </div>
</div><!-- #main -->

<?php
// Include WordPress footer
get_footer();
?>