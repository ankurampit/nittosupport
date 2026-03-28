<?php
add_action('woocommerce_review_order_before_order_total', 'pm_wallet_row');

function pm_wallet_row()
{

    if (!is_user_logged_in()) return;

    $wallet = get_user_meta(get_current_user_id(), 'wallet_balance', true);

    if (!$wallet) $wallet = 0;

?>

    <tr class="pm-wallet-row">


        <td class="pm-wallet-amount">

            ₹<?php echo esc_html($wallet); ?> available

        </td>

        <th class="pm-wallet-label">

            <label class="pm-wallet-checkbox">

                <input type="checkbox" name="use_wallet" id="use_wallet"
                    <?php checked(WC()->session->get('use_wallet'), true); ?>>

                <span>Use Wallet Balance</span>

            </label>

        </th>



    </tr>

<?php

}

add_action('woocommerce_checkout_update_order_review', 'pm_save_wallet_choice');

function pm_save_wallet_choice($posted_data)
{

    parse_str($posted_data, $output);

    if (isset($output['use_wallet'])) {
        WC()->session->set('use_wallet', true);
    } else {
        WC()->session->set('use_wallet', false);
    }
}

add_action('woocommerce_cart_calculate_fees', 'pm_apply_wallet_discount');

function pm_apply_wallet_discount($cart)
{

    if (is_admin() && !defined('DOING_AJAX')) return;

    if (!is_user_logged_in()) return;

    $use_wallet = WC()->session->get('use_wallet');

    if (!$use_wallet) return;

    $wallet = get_user_meta(get_current_user_id(), 'wallet_balance', true);

    if (!$wallet || $wallet <= 0) return;

    $subtotal = $cart->get_subtotal();

    $discount = min($wallet, $subtotal);

    $cart->add_fee('Wallet Discount', -$discount);
}

add_action('wp_footer', 'pm_wallet_script');

function pm_wallet_script()
{

    if (!is_checkout()) return;
?>

    <script>
        jQuery(function($) {

            $(document.body).on('change', '#use_wallet', function() {

                $('body').trigger('update_checkout');

            });

        });
    </script>

<?php
}

add_action('woocommerce_checkout_order_processed', 'pm_deduct_wallet');

function pm_deduct_wallet_old($order_id)
{

    global $wpdb;

    if (!WC()->session->get('use_wallet')) return;

    $user_id = get_current_user_id();

    $wallet = get_user_meta($user_id, 'wallet_balance', true);

    $order = wc_get_order($order_id);

    $discount = 0;

    foreach ($order->get_fees() as $fee) {

        if ($fee->get_name() === 'Wallet Discount') {
            $discount = abs($fee->get_total());
        }
    }

    if ($discount > 0) {

        $new_wallet = $wallet - $discount;
        $data = [
            'user_id'            => $user_id,
            'date'               => current_time('mysql'),
            'description' => 'Purchase',
            'amount'             => $discount,
            'amount_real'        => 0.00,
            'grand_total_amount' => 0.00,
            'transaction_id'     => '',
            'is_deleted'         => 0
        ];

        update_user_meta($user_id, 'wallet_balance', $new_wallet);
    }
}

function pm_deduct_wallet($order_id)
{
    global $wpdb;
    $table = 'wpntts_wallet_transactions';

    if (!WC()->session->get('use_wallet')) return;

    $user_id = get_current_user_id();
    $wallet  = get_user_meta($user_id, 'wallet_balance', true);
    $order   = wc_get_order($order_id);

    $discount = 0;

    foreach ($order->get_fees() as $fee) {
        if ($fee->get_name() === 'Wallet Discount') {
            $discount = abs($fee->get_total());
        }
    }

    if ($discount > 0) {

        $subtotal = $order->get_subtotal();
        $total    = $order->get_total();

        $new_wallet = $wallet - $discount;

        $data = [
            'user_id'            => $user_id,
            'date'               => current_time('mysql'),
            'description'        => 'Purchase',
            'amount'             => $discount,
            'amount_real'        => $subtotal,
            'grand_total_amount' => $total,
            'transaction_id'     => $order_id,
            'is_deleted'         => 0
        ];


        $request = $wpdb->insert(
            $table,
            $data,
            [
                '%d',
                '%s',
                '%s',
                '%f',
                '%f',
                '%f',
                '%s',
                '%d'
            ]
        );

        update_user_meta($user_id, 'wallet_balance', $new_wallet);
    }
}

function pm_test_wallet_deduction()
{

    if (!current_user_can('manage_options')) return;

    $order_id = 840;
    pm_deduct_wallet($order_id);

    echo "Wallet deduction function executed.";
}

add_action('init', function () {
    if (isset($_GET['test_wallet'])) {
        pm_test_wallet_deduction();
        exit;
    }
});


// Refund Functionality
add_action('woocommerce_order_status_changed', 'my_order_status_change_function', 10, 4);

function my_order_status_change_function($order_id, $old_status, $new_status, $order)
{

global $wpdb;
    // Your custom logic here
    $user_id = $order->get_user_id();
    $transaction_id = $order->get_transaction_id();


    $discount = 0;

    foreach ($order->get_fees() as $fee) {
        if ($fee->get_name() === 'Wallet Discount') {
            $discount = abs($fee->get_total());
        }
    }

    // Example: run something when order becomes completed
    if ($new_status == 'failed' || $new_status == 'refunded' || $new_status == 'cancelled') {

        $table = 'wpntts_wallet_transactions';

        $data = [
            'user_id'            => $user_id,
            'date'               => current_time('mysql'),
            'description'        => 'Refund',
            'amount'             => $discount,
            'amount_real'        => 0.00,
            'grand_total_amount' => 0.00,
            'transaction_id'     => $order_id,
            'is_deleted'         => 0
        ];

        $wpdb->insert(
            $table,
            $data,
            [
                '%d',
                '%s',
                '%s',
                '%f',
                '%f',
                '%f',
                '%s',
                '%d'
            ]
        );
    }
}


