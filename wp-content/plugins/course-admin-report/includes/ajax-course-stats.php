<?php

add_action('wp_ajax_get_course_stats_popup', 'get_course_stats_popup_callback');

function get_course_stats_popup_callback() {

    $course_id = intval($_POST['course_id']);
    $user_id   = intval($_POST['user_id']);

    $curriculum = bp_course_get_curriculum($course_id);

    echo "<h3>Course Curriculum Stats</h3>";
    echo "<table class='widefat striped'>";
    echo "<thead><tr><th>Unit</th><th>Status</th><th>Completed On</th></tr></thead><tbody>";

    foreach ($curriculum as $item) {

        if (is_numeric($item)) {

            $unit_id = $item;
            $title   = get_the_title($unit_id);

            $completed = get_user_meta($user_id, 'complete_unit_'.$unit_id.'_'.$course_id, true);
            $completed_on = $completed ? date('d M Y', $completed) : 'Not Completed';

            $status = $completed ? '<span style="color:green;font-weight:600;">Completed</span>'
                                 : '<span style="color:red;">Pending</span>';

            echo "<tr>
                    <td>{$title}</td>
                    <td>{$status}</td>
                    <td>{$completed_on}</td>
                  </tr>";
        }
    }

    echo "</tbody></table>";
    wp_die();
}
