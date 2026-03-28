<?php

// Course point system for WPLMS courses
function add_course_point_metabox() {
    add_meta_box(
        'course_point_metabox',
        __('Coursesss Point', 'wplms'),
        'render_course_point_metabox',
        'course', 
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_course_point_metabox');

function render_course_point_metabox($post) {
    $course_point = get_post_meta($post->ID, '_course_point', true);
    wp_nonce_field('course_point_nonce_action', 'course_point_nonce');
    ?>
    <p>
        <label for="course_point"><?php _e('Select Point:', 'wplms'); ?></label><br>
        <select name="course_point" id="course_point">
            <option value=""><?php _e('Select a point', 'wplms'); ?></option>
            <?php
            $options = [
                '10' => '10 - $50',
                '9'  => '9 - $45',
                '8'  => '8 - $40',
                '7'  => '7 - $35',
                '6'  => '6 - $30',
                '5'  => '5 - $25',
                '4'  => '4 - $20',
                '3'  => '3 - $15',
                '2'  => '2 - $10',
                '1'  => '1 - $5',
            ];
            foreach ($options as $value => $label) {
                echo '<option value="' . esc_attr($value) . '" ' . selected($course_point, $value, false) . '>' . esc_html($label) . '</option>';
            }
            ?>
        </select>
    </p>
    <?php
}

//Save the selected value
function save_course_point_meta($post_id) {
    if (!isset($_POST['course_point_nonce']) || !wp_verify_nonce($_POST['course_point_nonce'], 'course_point_nonce_action')) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $options = [
        '10' => ['points' => 10, 'dollars' => 50],
        '9'  => ['points' => 9, 'dollars' => 45],
        '8'  => ['points' => 8, 'dollars' => 40],
        '7'  => ['points' => 7, 'dollars' => 35],
        '6'  => ['points' => 6, 'dollars' => 30],
        '5'  => ['points' => 5, 'dollars' => 25],
        '4'  => ['points' => 4, 'dollars' => 20],
        '3'  => ['points' => 3, 'dollars' => 15],
        '2'  => ['points' => 2, 'dollars' => 10],
        '1'  => ['points' => 1, 'dollars' => 5],
    ];

    if (isset($_POST['course_point'])) {
        $course_point = sanitize_text_field($_POST['course_point']);
        // Save the course_point meta
        update_post_meta($post_id, '_course_point', $course_point);
        // Save post_id to _sourse_point to match list code
        update_post_meta($post_id, '_sourse_point', $post_id);

        // Save points and dollars as ACF fields
        if (isset($options[$course_point])) {
            update_field('course_value', $options[$course_point]['points'], $post_id);
            update_field('toyo_dollars', $options[$course_point]['dollars'], $post_id);
        } else {
            update_field('course_value', 0, $post_id);
            update_field('toyo_dollars', 0, $post_id);
        }
    }
}
add_action('save_post', 'save_course_point_meta');
