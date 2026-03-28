<?php

/**
 * Template Name: MISC Documents
 * Template Post Type: page
 */

acf_form_head();
wp_enqueue_media();
get_header();

$edit_post_id     = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$current_page_url = get_permalink();
?>

<?php
require_once get_stylesheet_directory() . '/header-inner.php';
require_once get_stylesheet_directory() . '/templates/dealer-resources/dealer-navigation.php';
?>
<section class="catalogues-wrapper">
    <div class="catalogues-section">

        <div class="section-header">
            <h2>MISC Documents</h2>
            <button class="insert-btn" onclick="toggleForm()">INSERT</button>
        </div>

        <!-- ================= ADD NEW FORM ================= -->
        <div id="new-catalogue-form" style="display:none; margin-bottom:30px;">
            <?php
            acf_form([
                'post_id'      => 'new_post',
                'new_post'     => [
                    'post_type'   => 'misc_documents',
                    'post_status' => 'publish',
                ],
                // NOTE: This reuses the same ACF field group as catalogues.
                // If you have a dedicated field group for MISC Documents,
                // replace the ID below with that group's key.
                'field_groups' => ['group_699c4078c8bb4'],
                'submit_value' => 'Create Document',
                'return'       => $current_page_url,
                'uploader'     => 'wp',
            ]);
            ?>
        </div>

        <?php if ($edit_post_id) : ?>

            <!-- ================= EDIT FORM ================= -->
            <div class="acf-edit-form">
                <?php
                acf_form([
                    'post_id'      => $edit_post_id,
                    'field_groups' => ['group_699c4078c8bb4'],
                    'submit_value' => 'Update Document',
                    'return'       => $current_page_url,
                    'uploader'     => 'wp',
                ]);
                ?>
            </div>

        <?php else : ?>

            <!-- ================= TABLE ================= -->
            <table class="catalogue-table">
                <thead>
                    <tr>
                        <th>Images</th>
                        <th>Doc Name</th>
                        <th>PDF Version</th>
                        <th>JPG/PNG</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $query = new WP_Query([
                        'post_type'      => 'misc_documents',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ]);

                    if ($query->have_posts()) :

                        while ($query->have_posts()) :
                            $query->the_post();
                            $post_id = get_the_ID();

                            /* ===== NORMAL FIELDS ===== */
                            $desc_en = get_post_meta($post_id, 'title_english', true);
                            $desc_fr = get_post_meta($post_id, 'title_french', true);

                            /* ===== GROUP FIELD ===== */
                            $thumb_en = get_image_by_id(get_post_meta($post_id, 'misc_document_english_thumbnail', true));
                            $thumb_fr = get_image_by_id(get_post_meta($post_id, 'misc_document_french_thumbnail', true));
                            $pdf_en   = get_image_by_id(get_post_meta($post_id, 'misc_document_english_pdf', true));
                            $pdf_fr   = get_image_by_id(get_post_meta($post_id, 'misc_document_pdf_french', true));

                            // /* ===== HANDLE ARRAY RETURN FORMAT ===== */
                            // if (is_array($thumb_en) && isset($thumb_en['ID'])) {
                            //     $thumb_en = $thumb_en['ID'];
                            // }
                            // if (is_array($thumb_fr) && isset($thumb_fr['ID'])) {
                            //     $thumb_fr = $thumb_fr['ID'];
                            // }
                            // if (is_array($pdf_en) && isset($pdf_en['ID'])) {
                            //     $pdf_en = $pdf_en['ID'];
                            // }
                            // if (is_array($pdf_fr) && isset($pdf_fr['ID'])) {
                            //     $pdf_fr = $pdf_fr['ID'];
                            // }

                            // /* ===== CONVERT TO URL ===== */
                            // $thumb_en_url = is_numeric($thumb_en) ? wp_get_attachment_url($thumb_en) : '';
                            // $thumb_fr_url = is_numeric($thumb_fr) ? wp_get_attachment_url($thumb_fr) : '';
                            // $pdf_en_url   = is_numeric($pdf_en)   ? wp_get_attachment_url($pdf_en)   : '';
                            // $pdf_fr_url   = is_numeric($pdf_fr)   ? wp_get_attachment_url($pdf_fr)   : '';
                    ?>

                            <tr>

                                <!-- IMAGES -->
                                <td>
                                    <?php if ($thumb_en) : ?>
                                        <img src="<?php echo esc_url($thumb_en); ?>" width="90" alt="English">
                                    <?php endif; ?>

                                    <?php if ($thumb_fr) : ?>
                                        <img src="<?php echo esc_url($thumb_fr); ?>" width="90" alt="French">
                                    <?php endif; ?>
                                </td>

                                <!-- DESCRIPTION -->
                                <td>
                                    <?php if ($desc_en) : ?>
                                         <p><strong>Eng:</strong><strong><?php echo esc_html($desc_en); ?></strong></p>
                                        <p>1 page sell sheet.</p>
                                    <?php endif; ?>

                                    <br>

                                    <?php if ($desc_fr) : ?>
                                        <p><strong>Fr:</strong> <strong><?php echo esc_html($desc_fr); ?></strong></p>
                                        <p>1 page sell sheet.</p>
                                    <?php endif; ?>
                                </td>

                             
                                <td>
                                    <?php if ($pdf_en) : ?>
                                        <a href="<?php echo esc_url($pdf_en); ?>" target="_blank" download>
                                            <i class="fa fa-download" style="font-size:36px"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($pdf_fr) : ?>
                                        <a href="<?php echo esc_url($pdf_fr); ?>" target="_blank" download>
                                            <i class="fa fa-download" style="font-size:36px"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <!-- JPG -->
                                <td>
                                    <?php if ($thumb_en) : ?>
                                        <a href="<?php echo esc_url($thumb_en); ?>" target="_blank" downlaod>
                                            <i class="fa fa-download" style="font-size:36px"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($thumb_fr) : ?>
                                        <a href="<?php echo esc_url($thumb_fr); ?>" target="_blank" download>
                                            <i class="fa fa-download" style="font-size:36px"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <!-- ACTION -->
                                <td>
                                    <a href="<?php echo esc_url(add_query_arg('edit', $post_id, $current_page_url)); ?>" class="update-btn">
                                        UPDATE
                                    </a>
                                    <br><br>
                                    <a href="javascript:void(0);"
                                        onclick="openDeleteModal(<?php echo esc_js($post_id); ?>)"
                                        class="delete-btn">
                                        DELETE
                                    </a>
                                </td>

                            </tr>

                    <?php
                        endwhile;
                        wp_reset_postdata();

                    else :
                    ?>

                        <tr>
                            <td colspan="5" style="text-align:center;">No MISC Documents Found</td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>

        <?php endif; ?>

    </div>
    <div id="delete-modal" class="delete-modal">
        <div class="delete-modal-content">
            <p>Are you sure you want to delete this document?</p>
            <div>
                <button type="button" onclick="confirmDelete()">Yes, Delete</button>
                <button type="button" onclick="closeDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>
    </div>

    <?php require_once get_stylesheet_directory() . '/templates/admaterials/features-menu.php'; ?>

</section>

    <script>
        function toggleForm() {
            const form = document.getElementById('new-catalogue-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        /* ================= DELETE SYSTEM ================= */

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
                        action: 'delete_misc_document_post',
                        post_id: postIdToDelete,
                        nonce: '<?php echo wp_create_nonce('delete_misc_document_nonce'); ?>'
                    })
                })
                .then(res => res.json())
                .then(res => {

                    if (res.success) {
                        location.reload();
                    } else {
                        alert('Delete failed.');
                    }
                });
        }
    </script>

<?php get_footer();