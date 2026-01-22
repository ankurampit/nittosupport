<?php
$args = [
    'post_type'      => 'bulletins',
    'posts_per_page' => -1,
];

$bulletin_query = new WP_Query($args);
?>

<div class="col-md-4">
    <div class="bulletinBox">
        <h2>Bulletin Board <a class="bltnSeeMr"
                href="#">See More</a></h2>
        <ul>
            <?php
            if ($bulletin_query->have_posts()):
                while ($bulletin_query->have_posts()): $bulletin_query->the_post();
                    $post_id = get_the_ID();
                    $title = get_the_title($post_id);
                    $bulleting_short_description_english = get_post_meta($post_id, 'bulleting_short_description_english', true);
                    $bulleting_image_english = get_post_meta($post_id, 'bulletin_image_english', true);
                    $image_url = wp_get_attachment_url($bulleting_image_english);
            ?>
                    <li>
                        <a href="#">
                            <img src="<?php echo $image_url; ?>" alt="">
                        </a>
                        <h5><a href="#"><?php echo $title ?></a></h5>
                        <p><?php echo $bulleting_short_description_english ?></p>
                        <a href="#" class="fdetail">Details</a>
                    </li>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </ul>
        <a style="float:right;" href="#">See
            More</a>
    </div>
</div>