<?php

/**
 * Template Name: Vehicle Picture 
 * Template Post Type: page
 */

get_header('header.php');


?>

<?php
require_once get_stylesheet_directory() . '/header-inner.php';
require_once get_stylesheet_directory() . '/templates/admaterials/top-navigation.php';
?>

<div class="main-table">
    <div class="table-header">
        <a class="header-ads">Vehicle Pictures</a>
        <table class="advertsing-material-table">
            <thead>
                <tr class="table-head">
                    <th class="tbl-heading">Picture</th>
                    <th class="tbl-heading">Picture Description</th>
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
                            'terms'    => 'vehicle-picture'
                        )
                    )
                ));

                if ($query->have_posts()):
                    while ($query->have_posts()):
                        $query->the_post();

                        $post_id = get_the_ID();

                        $img_en   = get_post_meta($post_id, 'picture_version_1__version_1_image_', true);
                        $img_fr   = get_post_meta($post_id, 'picture_version_2_version_2_image', true);
                        $title_en = get_post_meta($post_id, 'picture_name_:', true);
                        $start    = get_post_meta($post_id, 'do_not_use_before_:', true);
                        $PictureType   = get_post_meta($post_id, 'picture_type_:', true);
                        $PictureCategory   = get_post_meta($post_id, 'picture_category_:', true);


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
                            <td class="table-img">
                                <div class="img-wrapper">

                                    
                                    <?php if (!empty($img_en)) : ?>
                                        <div class="img-box">
                                            <img src="<?php echo esc_url($img_en); ?>" alt="English Image">
                                        </div>
                                    <?php else : ?>
                                        <div class="img-box placeholder"></div>
                                    <?php endif; ?>

                                    
                                    <?php if (!empty($img_fr)) : ?>
                                        <div class="img-box">
                                            <img src="<?php echo esc_url($img_fr); ?>" alt="French Image">
                                        </div>
                                    <?php else : ?>
                                        <div class="img-box placeholder"></div>
                                    <?php endif; ?>

                                </div>
                            </td>

                            <td>
                                <p><strong>Title</strong>: <?php echo esc_html($title_en); ?></p>
                                <p><strong>Start Date</strong>: <?php echo esc_html($start); ?></p>
                                <p><strong>Picture Type</strong>: <?php echo esc_html($PictureType); ?></p>
                                <p><strong>Picture Category</strong>: <?php echo esc_html($PictureCategory); ?></p>

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
                        <td colspan="5" style="text-align:center; opacity:0.7;">No Vehicle Picture found.</td>
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