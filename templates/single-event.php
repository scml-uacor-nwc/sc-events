<?php
/**
 * The template for displaying a single event.
 * This template simply calls the shortcode, making it compatible with theme builders.
 * @package SCEvents
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php echo do_shortcode('[sc_event_details]'); ?>
    </main>
</div>

<?php get_footer();