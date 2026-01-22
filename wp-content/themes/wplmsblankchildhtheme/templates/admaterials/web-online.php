<?php

/**
 * Template Name: Web and Online
 * Template Post Type: page
 */

get_header('header.php');
require_once get_stylesheet_directory() . '/header-inner.php';
require_once get_stylesheet_directory() . '/templates/admaterials/top-navigation.php';
?>

<div class="main-table">
    <div class="table-header">
        <a class="header-ads">Web advertising</a>
        <table class="advertsing-material-table">
            <thead>
                <tr class="table-head">
                    <th class="tbl-heading">Image English</th>
                    <th class="tbl-heading"> Description</th>
                    <th class="tbl-heading">Image French</th>
                    <th class="tbl-heading"> Description</th>
                    <th class="tbl-heading">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = new WP_Query(array(
                    'post_type'      => 'materials',
                    'posts_per_page' => -1,
                    'order'          => 'DESC',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'material_category',
                            'field'    => 'slug',
                            'terms'    => 'web-online'
                        )
                    )
                ));

                if ($query->have_posts()):
                    while ($query->have_posts()):
                        $query->the_post();

                        $post_id = get_the_ID();

                        $img_en   = get_post_meta($post_id, 'banner_image_english_:', true);
                        $img_fr   = get_post_meta($post_id, 'banner_image_french_:', true);
                        $title_en = get_post_meta($post_id, 'web_banner_name_english_:', true);
                        $title_fr = get_post_meta($post_id, 'web_banner_name_french_:', true);
                        $start    = get_post_meta($post_id, 'do_not_use_before_:', true);
                        $end      = get_post_meta($post_id, 'do_not_use_after_:', true);
                        $coop     = get_post_meta($post_id, 'coop_%_:', true);
                        $note     = get_post_meta($post_id, 'do_not_use_before_title_english_:', true);
                        $note_fr  = get_post_meta($post_id, 'do_not_use_before_title_french_:', true);


                        if (is_numeric($img_en)) {
                            $img_en = wp_get_attachment_url($img_en);
                        }
                        if (is_numeric($img_fr)) {
                            $img_fr = wp_get_attachment_url($img_fr);
                        }

                        if (empty($img_en) && empty($img_fr) && empty($title_en)) {
                            continue;
                        }
                ?>
                        <tr class="table-body">
                            <td class="table-img-1">
                                <?php if (!empty($img_en)): ?>
                                    <img class="img1" src="<?php echo esc_url($img_en); ?>" alt="">
                                <?php endif; ?>
                            </td>

                            <td>
                                <p><strong>Title</strong>: <?php echo esc_html($title_en); ?></p>
                                <p><strong>Start Date</strong>: <?php echo esc_html($start); ?></p>
                                <p><strong>End Date</strong>: <?php echo esc_html($end); ?></p>
                                <p><strong>Coop</strong>: <?php echo esc_html($coop); ?></p>
                                <p><strong>Note</strong>: <?php echo esc_html($note); ?></p>

                            </td>
                            <td class="table-img-2">
                                <?php if (!empty($img_fr)): ?>
                                    <img class="img2" src="<?php echo esc_url($img_fr); ?>" alt="">
                                <?php endif; ?>
                            </td>
                            <td>
                                <p><strong>Title</strong>: <?php echo esc_html($title_fr); ?></p>
                                <p><strong>Start Date</strong>: <?php echo esc_html($start); ?></p>
                                <p><strong>End Date</strong>: <?php echo esc_html($end); ?></p>
                                <p><strong>Coop</strong>: <?php echo esc_html($coop); ?></p>
                                <p><strong>Note</strong>: <?php echo esc_html($note_fr); ?></p>
                            </td>



                            <td>
                                <div class="action-icons"><i class="fa fa-edit"></i></div>
                                <div class="action-icons"><i class="fa fa-trash"></i></div>
                                <div class="action-icons"><i class="fa fa-language"></i></div>
                                <div class="action-icons"><i class="fa fa-eye"></i></div>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                else:
                    ?>

                    <tr>
                        <td colspan="5" style="text-align:center; opacity:0.7;">No Web advertising found.</td>
                    </tr>

                <?php
                endif;
                wp_reset_postdata();
                ?>
            </tbody>

        </table>
    </div>
    <?php require_once get_stylesheet_directory() . '/templates/admaterials/features-menu.php'; ?>
</div>


</div>

<?php
get_footer('footer.php');
?>