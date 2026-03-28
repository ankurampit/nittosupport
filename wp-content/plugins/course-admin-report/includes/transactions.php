<?php
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$total_earned = get_total_earned_dollars($user_id);
?>
<div class="dashboard-grid">
    <div class="card">
        <h3>User Details</h3>
        <div class="detail-row">
            <span class="detail-label">User Name:</span>
            <span class="detail-value"><?php echo $first_name . ' ' . $last_name; ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">User Email:</span>
            <span class="detail-value"><?php echo get_userdata($user_id)->user_email; ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">User ID:</span>
            <span class="detail-value" id="wallet-user-id"><?php echo $user_id; ?> </span>
        </div>
    </div>

    <div class="card">
        <h3>Course & Toyo Details</h3>
        <div class="detail-row">
            <span class="detail-label">Total Earned Dollars:</span>
            <span class="balance-highlight" id="total-earned">$<?php echo number_format($total_earned, 2); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Available Toyo Dollars:</span>
            <span class="balance-highlight" id="available-balance">$<?php echo get_available_balance($user_id, $total_earned) ?></span>
        </div>
        <div class="action-group">
            <input type="text" placeholder="Add amount..." id="add-amount">
            <button class="btn-primary" id="add-amount-btn">&nbsp; Add &nbsp;</button>
        </div>
        <div class="action-group">
            <input type="text" placeholder="Deduct amount..." id="deduct-amount">
            <button class="btn-primary" style="background:#64748b;" id="deduct-amount-btn">
                Deduct
            </button>
        </div>

        <div id="confirm-popup" class="confirm-popup">
            <div class="confirm-popup-content">
                <h3>Confirm Action</h3>
                <p>Are you sure you want to add this amount?</p>

                <button id="confirm-add" class="btn-primary">Yes</button>
                <button id="cancel-add" class="btn-secondary">Cancel</button>
            </div>
        </div>
    </div>
</div>

<button class="btn-danger" data-nonce="<?php echo wp_create_nonce('delete_wallet_nonce'); ?>">Delete Selected</button>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Date</th>
                <th>Description</th>
                <th>Amount ($)</th>
                <th>Discount ($)</th>
                <th>Grand Total</th>
            </tr>
        </thead>

        <tbody id="transactions-body">
            <?php
            $transactions = get_all_transactions_for_user($user_id);
            $total_addition = 0;
            $total_deduction = 0;
            $total_refund = 0;
            $total_discount = 0;
            $avaliable_balence = get_available_balance($user_id, $total_earned);
            foreach ($transactions as $transaction) {
                if ($transaction->description == 'addition') {
                    $total_addition += $transaction->amount;
                }

                if ($transaction->description == 'deduction') {
                    $total_deduction += $transaction->amount;
                }

                if ($transaction->description == 'Refund') {
                    $total_refund += $transaction->amount;
                }

                if($transaction->description == 'Purchase') {
                    $total_discount += $transaction->amount;
                }
            ?>
                <tr data-type="<?php echo $transaction->description; ?>" data-amount="<?php echo $transaction->amount; ?>">
                    <td><input type="checkbox" class="row-checkbox" value="<?php echo $transaction->id; ?>"></td>
                    <td><?php echo $transaction->date; ?></td>
                    <td><span style="display:flex; align-items:center; gap:5px;">Adjustment <?php echo $transaction->description; ?></span></td>
                    <td class="amt-pos">
                        <?php if ($transaction->description == 'addition'): ?>
                            <span class="amt-pos">+<?php echo number_format($transaction->amount, 2); ?></span>

                        <?php elseif ($transaction->description == 'deduction'): ?>
                            <span class="amt-neg">-<?php echo number_format($transaction->amount, 2); ?></span>

                        <?php elseif ($transaction->description == 'Purchase'): ?>
                            <span class="text-muted"><?php echo number_format($transaction->amount_real, 2); ?></span>
                        <?php elseif ($transaction->description == 'Refund'): ?>
                            <span class="amt-pos">+<?php echo number_format($transaction->amount, 2); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        if ($transaction->description == 'Purchase') { ?>
                            <span class="amt-neg">-<?php echo number_format($transaction->amount, 2); ?></span>
                        <?php
                        } else {
                            echo '--';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($transaction->description == 'Purchase') { ?>
                            <span class="text-muted"><?php echo number_format($transaction->grand_total_amount, 2); ?></span>
                        <?php
                        } else {
                            echo '--';
                        }
                        ?>
                    </td>
                </tr>
            <?php } 
            
            ?>

        </tbody>
    </table>

    <div class="table-footer">
        <div>
            <strong style="color: var(--success);" id="available-balance-footer">Available Toyo Dollars: $<?php echo number_format($avaliable_balence, 2); ?></strong>
        </div>
        <div>
            <strong style="color: var(--success);" id="total-add-deduct">Total Addition/Deduction($): $<?php echo number_format($total_addition - $total_deduction, 2); ?></strong><br>
            <strong style="color: var(--success);" id="total-refund">Total Dollars Refund($): $<?php echo number_format($total_refund, 2); ?></strong>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.8rem; color: var(--text-muted);">Total Discount Used</div>
            <div style="font-size: 1.2rem; font-weight: 700; color: var(--danger);">$<?php echo number_format($total_discount, 2); ?></div>
        </div>
    </div>
</div>