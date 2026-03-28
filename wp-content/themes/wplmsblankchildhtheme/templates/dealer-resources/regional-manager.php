<?php
 
/**
 * Template Name: Regional Manager
 * Template Post Type: page
 */
 
acf_form_head();
wp_enqueue_media();
get_header();
 
$edit_post_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$current_page_url = get_permalink();
?>
 
<?php
require_once get_stylesheet_directory() . '/header-inner.php';
require_once get_stylesheet_directory() . '/templates/dealer-resources/dealer-navigation.php';
?>
 
<section class="catalogues-wrapper">
    <div class="catalogues-section regional-manager">
 
        <div class="section-header">
            <h2>Regional Manager</h2>
            <button class="insert-btn" onclick="toggleForm()">INSERT</button>
        </div>
 
        <div id="new-catalogue-form" style="display:none; margin-bottom:30px;">
            <?php
            acf_form([
                'post_id'      => 'new_post',
                'new_post'     => [
                    'post_type'   => 'regional_manager',
                    'post_status' => 'publish',
                ],
                'field_groups' => ['group_69a18393679ae'],
                'submit_value' => 'Create Regional Manager',
                'return'       => $current_page_url,
                'uploader'     => 'wp',
            ]);
            ?>
        </div>
 
        <?php if ($edit_post_id) : ?>
            <div class="acf-edit-form">
                <?php
                acf_form([
                    'post_id'      => $edit_post_id,
                    'field_groups' => ['group_69a18393679ae'],
                    'submit_value' => 'Update Regional Manager',
                    'return'       => $current_page_url,
                    'uploader'     => 'wp',
                ]);
                ?>
            </div>
        <?php else : ?>
 
            <table class="catalogue-table regional-manager-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Cell/Mobile</th>
                        <th>Office Bureau</th>
                        <th>Extension</th>
                        <th>Territory</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $row_number = 0;
                    $get_manager_meta = function ($post_id, $keys) {
                        foreach ($keys as $key) {
                            $value = get_post_meta($post_id, $key, true);
                            if ($value !== '' && $value !== null) {
                                return $value;
                            }
                        }
                        return '';
                    };
 
                    $query = new WP_Query([
                        'post_type'      => 'regional_manager',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'ASC',
                    ]);
 
                    if ($query->have_posts()) :
                        while ($query->have_posts()) :
                            $query->the_post();
                            $post_id = get_the_ID();
                            $row_number++;
 
                            $first_name = $get_manager_meta($post_id, ['first_name']);
                            $last_name = $get_manager_meta($post_id, ['last_name']);
                            $cell_mobile = $get_manager_meta($post_id, ['cellmobile']);
                            $office_bureau = $get_manager_meta($post_id, ['office']);
                            $extension = $get_manager_meta($post_id, ['extensions']);
                            $territory = $get_manager_meta($post_id, ['territory']);
                            $email = $get_manager_meta($post_id, ['email']);
 
                            if (!$first_name || !$last_name) {
                                $title_parts = preg_split('/\s+/', trim(get_the_title($post_id)));
                                if (!$first_name && !empty($title_parts[0])) {
                                    $first_name = $title_parts[0];
                                }
                                if (!$last_name && !empty($title_parts[1])) {
                                    $last_name = $title_parts[1];
                                }
                            }
 
                            $first_name = $first_name ?: '--';
                            $last_name = $last_name ?: '--';
                            $cell_mobile = $cell_mobile ?: '--';
                            $office_bureau = $office_bureau ?: '--';
                            $extension = $extension ?: '--';
                            $territory = $territory ?: '--';
                            $email = $email ?: '--';
                    ?>
                            <tr>
                                <td><?php echo esc_html($row_number); ?></td>
                                <td><?php echo esc_html($first_name . ' ' . $last_name); ?></td>
                                <td><?php echo esc_html($cell_mobile); ?></td>
                                <td><?php echo esc_html($office_bureau); ?></td>
                                <td><?php echo esc_html($extension); ?></td>
                                <td><?php echo esc_html($territory); ?></td>
                                <td>
                                    <?php if ($email !== '--') : ?>
                                        <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                    <?php else : ?>
                                        --
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="regional-manager-actions">
                                        <a href="<?php echo esc_url(add_query_arg('edit', $post_id, $current_page_url)); ?>" class="update-btn">Update</a>
                                        <a href="javascript:void(0);" onclick="openDeleteModal(<?php echo esc_js($post_id); ?>)" class="delete-btn">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        ?>
                        <tr>
                            <td colspan="9" style="text-align:center;">No Regional Manager Entries Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
 
    </div>
 
    <div id="delete-modal" class="delete-modal">
        <div class="delete-modal-content">
            <p>Are you sure you want to delete this regional manager entry?</p>
            <div>
                <button type="button" onclick="confirmDelete()">Yes, Delete</button>
                <button type="button" onclick="closeDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>
</section>
 
 
 
<script>
    function toggleForm() {
        const form = document.getElementById('new-catalogue-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
 
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
                    action: 'delete_regional_manager_post',
                    post_id: postIdToDelete,
                    nonce: '<?php echo wp_create_nonce('delete_regional_manager_nonce'); ?>'
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
 