<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPLMS Adapter
 * Hooks into your plugin to provide:
 * - Enrolled courses
 * - Course progress %
 */

add_filter( 'car_get_course_progress', function( $progress, $user_id, $course_id ) {

    // If another handler already provided progress, skip
    if ( $progress !== null ) {
        return $progress;
    }

    // Verify user is enrolled first (optional but recommended)
    if ( function_exists( 'is_user_enrolled' ) ) {
        $is_enrolled = is_user_enrolled( $user_id, $course_id );
        if ( ! $is_enrolled ) {
            return 0; // not enrolled = 0%
        }
    }

    // Get progress using WPLMS core function
    if ( function_exists( 'bp_course_get_user_progress' ) ) {
        $p = bp_course_get_user_progress( $user_id, $course_id ); // returns int 0..100
        if ( is_numeric( $p ) ) {
            return intval( $p );
        }
    }

    // Fallback to user meta (WPLMS stores progress here)
    $meta = get_user_meta( $user_id, 'course_progress_' . $course_id, true );
    if ( $meta !== '' ) {
        return intval( $meta );
    }

    return 0;
}, 10, 3 );
