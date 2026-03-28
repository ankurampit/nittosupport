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

function get_total_earned_dollars($user_id)
{
    global $wpdb;

    $courses = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT meta_key, meta_value
             FROM {$wpdb->usermeta}
             WHERE user_id = %d
             AND meta_key LIKE %s",
            $user_id,
            'course_status%'
        )
    );

    $course_ids = [];

    foreach ($courses as $row) {
        $course_id = str_replace('course_status', '', $row->meta_key);
        $course_ids[] = (int) $course_id;
    }

    $total_earned_dollar = 0;

    foreach ($course_ids as $course_id) {
        $progress = get_user_meta($user_id, 'progress' . $course_id, true);
        if ($progress == 100) {
            $toyo_dollars = get_post_meta($course_id, 'toyo_dollars', true);
            $total_earned_dollar += floatval($toyo_dollars);
        }
    }

    return number_format($total_earned_dollar, 2);
}

function get_earned_dollar_records($user_id)
{
    global $wpdb;

    $records = [];

    $courses = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT meta_key
             FROM {$wpdb->usermeta}
             WHERE user_id = %d
             AND meta_key LIKE %s",
            $user_id,
            'course_status%'
        )
    );

    foreach ($courses as $row) {

        $course_id = str_replace('course_status', '', $row->meta_key);

        $progress = get_user_meta($user_id, 'progress' . $course_id, true);

        if ($progress == 100) {

            $toyo_dollars = get_post_meta($course_id, 'toyo_dollars', true);

            // completion date
            $completion_date = get_user_meta($user_id, 'course_completed_date_' . $course_id, true);

            if (!$completion_date) {
                $completion_date = current_time('Y-m-d');
            }

            $records[] = [
                'date' => $completion_date,
                'description' => 'Course Earned',
                'amount' => floatval($toyo_dollars),
                'type' => 'credit'
            ];
        }
    }

    return $records;
}

function get_user_total_courses($user_id)
{
    global $wpdb;

    $count = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) 
             FROM {$wpdb->usermeta}
             WHERE user_id = %d
             AND meta_key LIKE %s",
            $user_id,
            'course_status%'
        )
    );

    return (int) $count;
}

function get_all_transactions_for_user($user_id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'wallet_transactions';

    $transactions = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$table} WHERE user_id = %d AND is_deleted = %d ORDER BY date ASC",
            $user_id,
            0
        )
    );

    return $transactions;
}

function get_available_balance($user_id, $total_earned)
{
    $transactions = get_all_transactions_for_user($user_id);
    $total_addition = 0;
    $total_deduction = 0;
    $toal_refund = 0;
    $total_dollar_used = 0;

    foreach ($transactions as $transaction) {
        if ($transaction->description == 'addition') {
            $total_addition += $transaction->amount;
        }

        if ($transaction->description == 'deduction') {
            $total_deduction += $transaction->amount;
        }

        if ($transaction->description == 'Refund') {
            $toal_refund += $transaction->amount;
        }

        if ($transaction->description == 'Purchase') {
            $total_dollar_used += $transaction->amount;
        }
    }


    $adjusted_balance = floatval($total_addition) - floatval($total_deduction);
    $available_balance = (floatval($total_earned) + floatval($toal_refund) + floatval($adjusted_balance)) - floatval($total_dollar_used);
    update_user_meta($user_id, 'wallet_balance', $available_balance);

    return number_format($available_balance, 2);
}