<div class="table-container">
    <table>
        <h3>Total $ Deduction</h3>
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
            $total_deduction = 0;
            foreach ($transactions as $transaction) {

                if ($transaction->description == 'deduction') {
                    $total_deduction += $transaction->amount;
                } else {
                    continue;
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

        <div style="text-align: right;">
            <div style="font-size: 0.8rem; color: var(--text-muted);">Total Deduction: $</div>
            <div style="font-size: 1.2rem; font-weight: 700; color: var(--danger);">$<?php echo number_format($total_deduction, 2); ?></div>
        </div>
    </div>
</div>

<!-- Total Addition -->
<div class="table-container">
    <table>
        <h3>Total $ Addtion</h3>
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
            $total_addittion = 0;
            foreach ($transactions as $transaction) {

                if ($transaction->description == 'addition') {
                    $total_addittion += $transaction->amount;
                } else {
                    continue;
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
            <?php } ?>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: 700;">Total Addition:</td>
                <td style="font-weight: 700; color: var(--primary);">+<?php echo number_format($total_addittion, 2); ?></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Ladger Views -->
<div class="table-container">
    <table>

        <h3>Ledger View</h3>

        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Credit ($)</th>
                <th>Debit ($)</th>
                <th>Balance ($)</th>
            </tr>
        </thead>

        <tbody>

            <?php

            $transactions = get_all_transactions_for_user($user_id);
            $earned_records = get_earned_dollar_records($user_id);

            /* Normalize wallet transactions */
            $wallet_records = [];

            foreach ($transactions as $transaction) {

                $wallet_records[] = [
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'amount' => $transaction->amount,
                    'type' => in_array($transaction->description, ['addition', 'Refund']) ? 'credit' : 'debit'
                ];
            }

            /* Merge both */
            $all_records = array_merge($wallet_records, $earned_records);

            /* Sort by date */
            usort($all_records, function ($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            $balance = 0;

            foreach ($all_records as $record) {

                $credit = 0;
                $debit = 0;

                if ($record['type'] == 'credit') {
                    $credit = $record['amount'];
                    $balance += $credit;
                } else {
                    $debit = $record['amount'];
                    $balance -= $debit;
                }

            ?>

                <tr>

                    <td><?php echo date('Y-m-d', strtotime($record['date'])); ?></td>

                    <td>
                        <?php
                        if ($record['description'] == 'Course Earned') {
                            echo 'Course Completion Reward';
                        } else {
                            echo ucfirst($record['description']);
                        }
                        ?>
                    </td>

                    <td class="amt-pos">
                        <?php echo $credit ? '+' . number_format($credit, 2) : '--'; ?>
                    </td>

                    <td class="amt-neg">
                        <?php echo $debit ? '-' . number_format($debit, 2) : '--'; ?>
                    </td>

                    <td style="font-weight:600;">
                        <?php echo number_format($balance, 2); ?>
                    </td>

                </tr>

            <?php } ?>

        </tbody>
    </table>
</div>