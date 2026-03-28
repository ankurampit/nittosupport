<?php
$featured_args = [
    'post_type'      => 'featured',
    'posts_per_page' => 2,
    'order'          => 'DESC',
];
$featured_query = new WP_Query($featured_args);
?>
<div class="featuredBox">
    <h2>Featured</h2>

    <ul>
        <?php if ($featured_query->have_posts()) : ?>
            <?php while ($featured_query->have_posts()) : $featured_query->the_post();
                $image_id = get_post_meta(get_the_ID(), 'image', true);
            ?>
                <li>
                    <a href="javascript:{}">
                        <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                    </a>
                    <h5><a href="javascript:{}"><?php the_title(); ?></a></h5>
                    <p><?php the_excerpt(); ?></p>
                    <a href="javascript:{}"
                        class="fdetail">Details</a>
                </li>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <li>No featured items available.</li>
        <?php endif; ?>
    </ul>
</div>