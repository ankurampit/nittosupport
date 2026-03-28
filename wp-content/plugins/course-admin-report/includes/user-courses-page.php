<?php
function car_render_user_course_page()
{
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
?>

    <div class="course-details-wrapper">
        <header>
            <div><span class="badge-count"><?php echo get_user_total_courses($user_id); ?> COURSES TAKEN</span></div>
            <h1>Statement and Records</h1>
        </header>
        <div class="tabs">
            <div class="tab">
                <button class="btn-primary-new active" onclick="openTab('record-statement')"><i class='far fa-credit-card' style='color:white;'></i> Transactions and Lists</button>
            </div>
            <div class="tab">
                <button class="btn-primary-new" onclick="openTab('details-ladger-view')"><i class='fas fa-chart-line' style='color:white;'></i> Transactions Statements and Ledger View</button>
            </div>
        </div>
        <div id="record-statement" style="display:block;">

            <?php
            require_once CAR_PLUGIN_DIR . 'includes/transactions.php';
            ?>
            <table class="course-details-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Badge</th>
                        <th>Completion Date</th>
                        <th>Status</th>
                        <th>Toyo Dollar Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <!-- ONE COURSE ROW (duplicate dynamically) -->
                    <?php
                    global $wpdb, $user_id;
                    $results = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT meta_key, meta_value 
                        FROM {$wpdb->usermeta}
                        WHERE user_id = %d
                        AND meta_key LIKE %s",
                            $user_id,
                            '%course_status%'
                        )
                    );
                    foreach ($results as $course) {
                        $meta_key = $course->meta_key;
                        if (preg_match('/course_status(\d+)/', $meta_key, $match)) {
                            $course_id = $match[1];
                        }
                        $course_title = get_the_title($course_id);
                        $end_date = get_user_meta($user_id, 'course_end_date_' . $course_id, true);
                        $completion_timestamp = get_user_meta($user_id, $course_id, true);

                        if ($completion_timestamp) {
                            $completed_on = date('Y-m-d', $completion_timestamp);
                        } else {
                            $completed_on = 'Not completed';
                        }

                        $progress = get_user_meta($user_id, 'progress' . $course_id, true);
                        $toyo_dollars = get_post_meta($course_id, 'toyo_dollars', true);
                        $badge_url = get_the_post_thumbnail_url($course_id, 'vibe_course_badge');
                    ?>
                        <tr>
                            <td class="course-name">
                                <div class="course-lines">
                                    <div class="line-en"><strong><?php echo $course_title ?></strong></div>
                                    <!-- <div class="line-fr">Pneus Pour Conditions Variables - <strong>fr</strong></div> -->
                                </div>
                            </td>

                            <td class="course-badge">
                                <img src="<?php echo $badge_url; ?>" alt="Badge">
                            </td>

                            <td class="completion-date">
                                <div class="date-en"><?php echo $completed_on ?></div>
                                <!-- <div class="date-fr">Not completed</div> -->
                            </td>

                            <td class="course-status">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $progress; ?>%;"></div>
                                    <span class="progress-label"><?php echo $progress; ?>%</span>
                                </div>
                            </td>


                            <td class="toyo-dollar">
                                <span class="status-label <?php echo $progress == 100 ? 'is-earned' : 'is-pending'; ?>">
                                    <?php echo $progress == 100 ? 'Earned $' . number_format($toyo_dollars, 2) : 'Not Earned'; ?>
                                </span>
                            </td>

                            <td class="actions-cell">
                                <ul class="actions-control">
                                    <li><a class="tip reset_course_user" data-course="13027" data-user="1888" title="Reset Course for User"><i class="fas fa-sync-alt"></i></a></li>
                                    <li><a class="tip course_stats_user" onclick="openCourseStatsPopup(<?php echo $course_id; ?>, <?php echo $user_id; ?>)" title="See Course stats for User"><i class="fas fa-bars"></i></a></li>
                                    <li><a class="tip course_activity_user" data-course="13027" data-user="1888" title="See User Activity in Course"><i class="fas fa-atom"></i></a></li>
                                    <li><a class="tip remove_user_course" data-course="13027" data-user="1888" title="Remove User from this Course"><i class="fas fa-times"></i></a></li>
                                </ul>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>
                    <!-- END ROW -->

                </tbody>
            </table>
            <div id="course-stats-popup" class="course-popup">
                <div class="course-popup-content" onclick="closeCourseStatsPopup()">
                    <span class="course-popup-close">&times;</span>
                    <div id="course-stats-data">Loading...</div>
                </div>
            </div>
        </div>

        <div id="details-ladger-view" style="display:none;">
            <?php require_once CAR_PLUGIN_DIR . 'includes/ladger-view.php'; ?>
        </div>

    </div>

    <script>
        function openCourseStatsPopup(courseId, userId) {

            // Show popup
            document.getElementById("course-stats-popup").style.display = "block";
            document.getElementById("course-stats-data").innerHTML = "Loading...";

            // Prepare AJAX
            const formData = new FormData();
            formData.append("action", "get_course_stats_popup");
            formData.append("course_id", courseId);
            formData.append("user_id", userId);

            // AJAX call using fetch()
            fetch(ajaxurl, {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("course-stats-data").innerHTML = data;
                })
                .catch(err => {
                    document.getElementById("course-stats-data").innerHTML = "Error loading course data.";
                });
        }

        function closeCourseStatsPopup() {
            document.getElementById("course-stats-popup").style.display = "none";
        }

        document.addEventListener("DOMContentLoaded", function() {

            const confirmPopup = document.getElementById("confirm-popup");
            const popupContent = document.querySelector(".confirm-popup-content");

            const addBtn = document.getElementById("add-amount-btn");
            const deductBtn = document.getElementById("deduct-amount-btn");

            function openPopup(type, amount) {

                confirmPopup.style.display = "flex";

                const actionText = type === "add" ? "add" : "deduct";

                popupContent.innerHTML = `
                    <h3>Confirm Action</h3>
                    <p>Are you sure you want to ${actionText} $${amount} ?</p>
                    <button id="confirm-action" class="btn-primary">Yes</button>
                    <button id="cancel-action" class="btn-secondary">Cancel</button>
                `;

                document.getElementById("cancel-action").onclick = closePopup;

                document.getElementById("confirm-action").onclick = function() {
                    performAjax(type, amount);
                };
            }

            function closePopup() {
                confirmPopup.style.display = "none";
            }

            function performAjax(type, amount) {

                const userId = new URLSearchParams(window.location.search).get("user_id");

                const formData = new FormData();
                formData.append("action", "car_update_dollar");
                formData.append("user_id", userId);
                formData.append("amount", amount);
                formData.append("type", type);

                fetch(ajaxurl, {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {

                        if (data.success) {

                            const transaction = data.data;
                            console.log("Transaction added:", data);
                            console.log("Transaction added:", transaction);

                            addTransactionRow(transaction);
                            recalculateTotals();

                            popupContent.innerHTML =
                                "<h3 style='color:green'>✔ Transaction successful</h3>";

                            setTimeout(closePopup, 2000);

                        } else {

                            popupContent.innerHTML =
                                "<h3 style='color:red'>Error saving data</h3>";

                            setTimeout(closePopup, 2000);
                        }

                    });

            }


            /* ADD BUTTON */
            addBtn.addEventListener("click", function() {

                const amount = document.getElementById("add-amount").value;

                if (!amount) {
                    alert("Enter amount");
                    return;
                }

                openPopup("add", amount);
            });


            /* DEDUCT BUTTON */
            deductBtn.addEventListener("click", function() {

                const amount = document.getElementById("deduct-amount").value;

                if (!amount) {
                    alert("Enter amount");
                    return;
                }

                openPopup("deduct", amount);
            });


            /* CLOSE POPUP WHEN CLICK OUTSIDE */
            window.addEventListener("click", function(event) {

                if (event.target === confirmPopup) {
                    closePopup();
                }

            });

        });

        function addTransactionRow(transaction) {

            const tbody = document.getElementById("transactions-body");

            let amountHtml = "";
            const amount = parseFloat(transaction.amount).toFixed(2);

            if (transaction.description === "addition" || transaction.description === "add") {
                amountHtml = `<span class="amt-pos">+${amount}</span>`;
            }

            if (transaction.description === "deduction" || transaction.description === "deduct") {
                amountHtml = `<span class="amt-neg">-${amount}</span>`;
            }

            const row = `
        <tr data-type="${transaction.description}" data-amount="${transaction.amount}">
            <td><input type="checkbox" value="${transaction.id}"></td>
            <td>${transaction.date}</td>
            <td>Adjustment ${transaction.description}</td>
            <td>${amountHtml}</td>
            <td>--</td>
            <td>--</td>
        </tr>
    `;

            console.log("Adding row:", row);

            tbody.insertAdjacentHTML("beforeend", row);
        }

        function recalculateTotals() {

            let addition = 0;
            let deduction = 0;
            let refund = 0;

            document.querySelectorAll("#transactions-body tr").forEach(row => {

                const type = row.dataset.type;
                const amount = parseFloat(row.dataset.amount) || 0;

                if (type === "addition") addition += amount;
                if (type === "deduction") deduction += amount;
                if (type === "refund") refund += amount;

            });

            console.log("Totals - Addition:", addition, "Deduction:", deduction, "Refund:", refund);
            console.log("Total Addition/Deduction:", addition - deduction);
            document.getElementById("total-add-deduct").textContent =
                `Total Addition/Deduction($): $${(addition - deduction).toFixed(2)}`;

            document.getElementById("total-refund").textContent =
                `Total Dollars Refund($): $${refund.toFixed(2)}`;
        }

        // Delete transactions
        jQuery(document).ready(function($) {

            // Select all checkbox
            $('#select-all').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Delete selected
            $('.btn-danger').on('click', function() {

                let ids = [];
                let user_id = $('#wallet-user-id').text().trim();
                let total_earned = $('#total-earned').text().trim().replace(/[^0-9.]/g, '');
                let avaliable_balance = $('#available-balance').text().trim();
                console.log("User ID:", user_id, "Total Earned:", total_earned, "Available Balance:", avaliable_balance);

                $('.row-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length === 0) {
                    alert('Please select transactions to delete.');
                    return;
                }

                if (!confirm('Are you sure you want to delete selected transactions?')) {
                    return;
                }

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'delete_wallet_transactions',
                        ids: ids,
                        user_id: user_id,
                        total_earned: total_earned,

                    },
                    success: function(response) {
                        if (response.success) {

                            ids.forEach(function(id) {
                                $('input[value="' + id + '"]').closest('tr').remove();
                            });
                            console.log(response);
                            $('#available-balance').text('$' + response.data.available_balance);
                            $('#total-earned').text('$' + response.data.total_earned);
                            $('#available-balance-footer').text(
                                'Available Toyo Dollars: $' + response.data.available_balance
                            );

                            alert('Transactions deleted successfully');
                        } else {
                            alert('Delete failed');
                        }
                    }
                });

            });

        });
    </script>

<?php
}
