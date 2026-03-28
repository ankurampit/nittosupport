<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function car_render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'Insufficient permissions', 'course-admin-report' ) );
    }

    // CSV export handler (simple)
    if ( isset( $_GET['car_export'] ) && $_GET['car_export'] === '1' ) {
        check_admin_referer( 'car_export' );
        $rows = [];
        $rows[] = ['User ID','User','Course ID','Course Title','Progress (%)'];

        $users = get_users( ['role__in' => ['subscriber','student',''] ] ); // adjust roles as needed
        $courses = get_posts( ['post_type' => 'course', 'posts_per_page' => -1] );

        foreach ( $users as $user ) {
            foreach ( $courses as $course ) {
                $p = car_get_course_progress( $user->ID, $course->ID );
                $rows[] = [$user->ID, $user->user_login . ' (' . $user->display_name . ')', $course->ID, $course->post_title, $p];
            }
        }
        car_export_csv( $rows, 'course-report-' . date('Y-m-d') . '.csv' );
    }

    // Main page UI
    $users   = get_users( ['role__in' => ['subscriber','student',''] ] ); // adjust
    $courses = get_posts( ['post_type' => 'course', 'posts_per_page' => -1] );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Course Admin Report', 'course-admin-report' ); ?></h1>

        <p>
            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg('car_export','1'), 'car_export' ) ); ?>" class="button button-primary">
                <?php _e('Export CSV', 'course-admin-report'); ?>
            </a>
        </p>

        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th><?php _e('User', 'course-admin-report'); ?></th>
                    <?php foreach ( $courses as $course ): ?>
                        <th><?php echo esc_html( $course->post_title ); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ( $users as $user ): ?>
                <tr>
                    <td><?php echo esc_html( $user->display_name . ' (' . $user->user_login . ')' ); ?></td>
                    <?php foreach ( $courses as $course ): 
                        $p = car_get_course_progress( $user->ID, $course->ID );
                        ?>
                        <td><?php echo intval( $p ) . '%'; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
