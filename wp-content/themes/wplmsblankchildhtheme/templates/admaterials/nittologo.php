<?php

/**
 * Template Name: Nitto Logo
 * Template Post Type: page
 */

acf_form_head();
wp_enqueue_media();
get_header();

$edit_post_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$current_page_url = get_permalink();
$page = "nitto-logo";
get_header('header.php');
?>

<?php
require_once get_stylesheet_directory() . '/header-inner.php';
require_once get_stylesheet_directory() . '/templates/admaterials/top-navigation.php';
?>

<div class="main-table">
    <div class="table-header">
        <a class="header-ads">Nitto Logo</a>
        <button class="add-new-ad-button btn btn-primary" onclick="addNewAd('add_new')" id="print-ads-btn">Add New</button>
        <button class="add-new-ad-button btn btn-primary" onclick="addNewAd('back')" id="back-btn" style="display:none;">Back</button>

        <div class="clearfix" id="new-ad-form" style="display:none;">
            <?php
            add_filter('acf/load_field/name=material_term_slug', function ($field) {
                $field['value'] = 'nitto-logo';
                return $field;
            });

            acf_form([
                'post_id'      => 'new_post',
                'new_post'     => [
                    'post_type'   => 'materials',
                    'post_status' => 'publish',
                ],
                'field_groups' => ['group_6926a9a039b0f'],
                'submit_value' => 'Add New Nitto Logo',
                'return'       => $current_page_url,
                'uploader'     => 'wp',
            ]);
            ?>
        </div>

        <?php if ($edit_post_id) : ?>

            <div class="acf-edit-form" id="acf-edit-form">
                <?php
                acf_form([
                    'post_id'      => $edit_post_id,
                    'field_groups' => ['group_6926a9a039b0f'],
                    'submit_value' => 'Update',
                    'return'       => get_permalink($edit_post_id),
                    'uploader'     => 'wp',
                ]);
                ?>
            </div>

        <?php else : ?>

            <table class="advertising-material-table" id="print-ads-table">
                <thead>
                    <tr class="table-head">
                        <th class="tbl-heading">Image English</th>
                        <th class="tbl-heading">Description English</th>
                        <th class="tbl-heading">Image French</th>
                        <th class="tbl-heading">Description French</th>
                        <th class="tbl-heading">Action</th>
                    </tr>
                </thead>
                <tbody id="materials-sortable">
                    <?php
                    $query = new WP_Query(array(
                        'post_type'      => 'materials',
                        'posts_per_page' => -1,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'material_category',
                                'field'    => 'slug',
                                'terms'    => 'nitto-logo',
                            ),
                        ),
                    ));

                    if ($query->have_posts()) :
                        while ($query->have_posts()) :
                            $query->the_post();
                            $post_id = get_the_ID();

                            $img_en   = get_post_meta($post_id, 'logo_jpg_version_english', true);
                            $img_fr   = get_post_meta($post_id, 'logo_jpg_version_french', true);
                            $title_en = get_post_meta($post_id, 'logo_name_english', true);
                            $title_fr = get_post_meta($post_id, 'logo_name_french', true);
                            $start    = get_post_meta($post_id, 'do_not_use_before', true);
                            $end      = get_post_meta($post_id, 'do_not_use_after', true);
                            $coop     = get_post_meta($post_id, 'coop_%_', true);
                            $note_en  = get_post_meta($post_id, 'do_not_use_before_title_english_', true);
                            $note_fr  = get_post_meta($post_id, 'do_not_use_before_title_french_', true);

                            $start_date = covertDateToReadableFormat($start);
                            $end_date   = covertDateToReadableFormat($end);
                            if (is_numeric($img_en)) {
                                $logo_english = wp_get_attachment_url($img_en);
                            }
                            if (is_numeric($img_fr)) {
                                $logo_french = wp_get_attachment_url($img_fr);
                            }
                    ?>

                            <tr class="table-body" draggable="true" data-post-id="<?php echo esc_attr($post_id); ?>">
                                <td class=" table-img-1 file">
                                    <img src="<?php echo esc_url($logo_english); ?>" alt="Audio Placeholder" class="audio-placeholder">
                                </td>

                                <td>
                                    <p><strong>Title:</strong> <?php echo esc_html($title_en); ?></p>
                                    <p><strong>Start Date:</strong> <?php echo esc_html($start_date); ?></p>
                                    <p><strong>End Date:</strong> <?php echo esc_html($end_date); ?></p>
                                    <p><strong>Coop:</strong> <?php echo esc_html($coop); ?></p>
                                    <?php if ($note_en) : ?>
                                        <p><strong>Note:</strong> <?php echo esc_html($note_en); ?></p>
                                    <?php endif; ?>
                                </td>

                                <td class=" table-img-1 file">
                                    <img src="<?php echo esc_url($logo_french); ?>" alt="Audio Placeholder" class="audio-placeholder">
                                </td>

                                <td>
                                    <p><strong>Title:</strong> <?php echo esc_html($title_fr); ?></p>
                                    <p><strong>Start Date:</strong> <?php echo esc_html($start_date); ?></p>
                                    <p><strong>End Date:</strong> <?php echo esc_html($end_date); ?></p>
                                    <p><strong>Coop:</strong> <?php echo esc_html($coop); ?></p>
                                    <?php if ($note_fr) : ?>
                                        <p><strong>Note:</strong> <?php echo esc_html($note_fr); ?></p>
                                    <?php endif; ?>
                                </td>

                                <td class="actions">
                                    <?php if (current_user_can('edit_post', $post_id)) : ?>
                                        <a class="action-icons" href="<?php echo esc_url(add_query_arg('edit', $post_id, $current_page_url)); ?>" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <div class="action-icons delete-action">
                                        <a href="javascript:void(0);" onclick="openDeleteModal(<?php echo esc_js($post_id); ?>)"
                                            title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>

                                    <div class="action-icons" title="Preview (not implemented)">
                                        <i class="fa fa-eye"></i>
                                    </div>
                                </td>
                            </tr>

                        <?php
                        endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <tr>
                            <td colspan="5" style="text-align:center; opacity:0.7;">No Print Ads found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php endif; ?>
        <?php require_once get_stylesheet_directory() . '/templates/admaterials/admaterials.modal.php'; ?>
    </div>

    <script>
        let postIdToDelete = null;

        function openDeleteModal(postId) {
            postIdToDelete = postId;
            document.getElementById('delete-modal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.remove('active');
            postIdToDelete = null;
        }

        function confirmDelete() {
            if (!postIdToDelete) return;

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'delete_material_post',
                        post_id: postIdToDelete,
                        nonce: '<?php echo wp_create_nonce('delete_material_nonce'); ?>'
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        const row = document.querySelector(
                            `tr[data-post-id="${postIdToDelete}"]`
                        );
                        if (row) {
                            row.style.transition = 'opacity 0.3s ease';
                            row.style.opacity = '0';
                            setTimeout(() => row.remove(), 300);
                        }
                    }
                    closeDeleteModal();
                });
        }
    </script>



    <?php require_once get_stylesheet_directory() . '/templates/admaterials/features-menu.php'; ?>
</div>

</div>
<?php get_footer('footer.php'); ?>