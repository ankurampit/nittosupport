<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get progress for a user on a course.
 * This function first applies the 'car_get_course_progress' filter so LMS adapters can return real values.
 * Expected return: integer 0..100
 */
function car_get_course_progress( $user_id, $course_id ) {
    // Allow LMS-specific code to override/provide progress.
    $progress = apply_filters( 'car_get_course_progress', null, $user_id, $course_id );

    if ( $progress !== null ) {
        return intval( $progress );
    }

    // Fallback: try user meta convention "course_progress_{course_id}"
    $meta_key = 'course_progress_' . intval( $course_id );
    $p = get_user_meta( $user_id, $meta_key, true );
    if ( $p !== '' ) {
        return intval( $p );
    }

    // Default: 0
    return 0;
}

/**
 * Helper to prepare CSV output (simple)
 */
function car_export_csv( $rows, $filename = 'course-report.csv' ) {
    if ( headers_sent() ) return false;
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=' . $filename );
    $output = fopen( 'php://output', 'w' );
    foreach ( $rows as $row ) {
        fputcsv( $output, $row );
    }
    fclose( $output );
    exit;
}
