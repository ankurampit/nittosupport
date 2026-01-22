<?php

/**
 * Template Name: Tire Photo
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
        <a class="header-ads">Tire Photo</a>
        <div>
            <table class="advertsing-material-table">
                <thead>
                    
                    <tr class="table-head">
                        <th class="tbl-heading">Logo</th>
                        <th class="tbl-heading">Image</th>
                        <th class="tbl-heading">Description</th>
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
                                'terms'    => 'tire-photo'
                            )
                        )
                    ));

                    if ($query->have_posts()):
                        while ($query->have_posts()):
                            $query->the_post();

                            $post_id = get_the_ID();

                            $img_en  = get_post_meta($post_id, 'logo_jpeg_version_:', true);
                            $title_en = get_post_meta($post_id, 'tire_photo_name_:', true);
                            $img_fr = get_post_meta($post_id, 'image_icon_:_', true);
                            $en = get_post_meta($post_id, 'description_english_:', true);
                            $fr = get_post_meta($post_id, 'description_french_:', true);

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
                                    <p><strong>Title</strong>: <?php echo esc_html($title_en); ?></p>
                                </td>

                                <td class="table-img-2">
                                    <?php if (!empty($img_fr)): ?>
                                        <img class="img2" src="<?php echo esc_url($img_fr); ?>" alt="">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $plain_en = trim(wp_strip_all_tags(html_entity_decode($en), true));
                                    $plain_fr = trim(wp_strip_all_tags(html_entity_decode($fr), true));
                                    ?>

                                    <div class="desc-final">
                                        <p class="desc-label"><strong>En:</strong>
                                            <?php echo esc_html($plain_en); ?>
                                        </p>
                                        <p class="desc-label"><strong> Fr: </strong>
                                            <?php echo esc_html($plain_fr); ?>
                                        </p>
                                    </div>
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
                            <td colspan="5" style="text-align:center; opacity:0.7;">No Tire Photo found.</td>
                        </tr>

                    <?php
                    endif;
                    wp_reset_postdata();
                    ?>
                </tbody>

            </table>
        </div>
    </div>
    <?php require_once get_stylesheet_directory() . '/templates/admaterials/features-menu.php'; ?>
</div>

</div>

<?php
get_footer('footer.php');
?>