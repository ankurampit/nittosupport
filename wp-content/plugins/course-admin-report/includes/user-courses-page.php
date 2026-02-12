<?php
if (!defined('ABSPATH')) exit;
global $wpdb;

if (isset($_GET['user_id'])) {
    $user_id = absint($_GET['user_id']);
}

function car_render_user_course_page()
{ ?>

    <style>
        .course-details-wrapper {
            background: #fff;
            padding: 20px;
        }

        .course-title-heading {
            text-align: center;
            font-weight: 800;
            font-size: 28px;
            margin-bottom: 25px;
        }

        .course-details-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            font-family: Arial, sans-serif;
        }

        .course-details-table thead th {
            background: #f4f4f4;
            padding: 12px 10px;
            border: 1px solid #ddd;
            font-weight: 600;
            text-align: left;
        }

        .course-details-table tbody td {
            padding: 15px 10px;
            border: 1px solid #eee;
            vertical-align: middle;
        }

        /* Course titles */
        .course-lines .line-en,
        .course-lines .line-fr {
            font-size: 15px;
            line-height: 20px;
        }

        .course-lines .line-fr {
            color: #444;
        }

        /* Badge images */
        .course-badge img {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
        }

        /* Completion date two-line structure */
        .completion-date div {
            font-size: 14px;
            color: #333;
            line-height: 18px;
        }

        /* Progress Bar */
        .progress-bar {
            background: #e6e6e6;
            height: 14px;
            border-radius: 20px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .progress-label {
            position: absolute;
            left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: bold;
            color: #e9e6e6ff;
            pointer-events: none;
            vertical-align: middle;
        }
        .progress-fill {
            height: 100%;
            background: #2d9cdb;
            /* blue like your screenshot */
            border-radius: 20px;
        }

        .progress-text {
            margin-top: 5px;
            font-size: 14px;
            font-weight: 600;
        }

        /* Toyo Dollar */
        .toyo-dollar {
            font-weight: 600;
            color: #444;
            white-space: nowrap;
        }

        .actions-control {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 10px;
            text-align: center
        }

        .actions-cell {
            width: 100px;
        }

        /* Curiculumn Pop up */

        .course-popup {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .course-popup-content {
            background: #fff;
            padding: 20px;
            width: 60%;
            margin: 80px auto;
            border-radius: 8px;
            position: relative;
        }

        .course-popup-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 22px;
            cursor: pointer;
        }
    </style>

    <div class="course-details-wrapper">

        <h2 class="course-title-heading">COURSE DETAILS</h2>

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
                ?>
                    <tr>
                        <td class="course-name">
                            <div class="course-lines">
                                <div class="line-en"><strong><?php echo $course_title ?></strong></div>
                                <!-- <div class="line-fr">Pneus Pour Conditions Variables - <strong>fr</strong></div> -->
                            </div>
                        </td>

                        <td class="course-badge">
                            <img src="badge.png" alt="Badge">
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
                            $0 Toyo Dollar
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
    </script>

<?php
}
