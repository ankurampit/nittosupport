jQuery(document).ready(function($){

    console.log("Nitto Migration Loaded");

});

jQuery(document).ready(function ($) {

    $('#nitto-load-users').click(function () {

        if (!confirm("Are you sure you want to fetch users from the external database?")) {
            return;
        }

        $('#nitto-users-list').html('<p>Loading users...</p>');

        $.ajax({
            url: nitto_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'nitto_fetch_users'
            },
            success: function (response) {

                $('#nitto-users-list').html(response);

            }
        });

    });

});

// Batch processing for user migations
// jQuery(document).ready(function ($) {

//     let offset = 0;
//     let limit = 2;

//     $('#nitto-start-migration').on('click', function () {

//         $('#nitto-migration-status').html('Starting migration...');
//         console.log("Starting migration...");

//         migrateBatch();

//     });

//     function migrateBatch() {

//         $.ajax({
//             url: ajaxurl,
//             type: 'POST',
//             data: {
//                 action: 'nitto_batch_migrate_users',
//                 offset: offset,
//                 limit: limit
//             },
//             success: function (response) {

//                 if (response.success) {

//                     offset += limit;

//                     $('#nitto-migration-status').html(
//                         'Migrated ' + response.data.migrated + ' users...'
//                     );

//                     if (response.data.remaining > 0) {

//                         migrateBatch(); // process next batch

//                     } else {

//                         $('#nitto-migration-status').html(
//                             'Migration completed!'
//                         );

//                     }

//                 } else {

//                     $('#nitto-migration-status').html('Migration failed');

//                 }

//             }
//         });

//     }

// });

jQuery(document).ready(function ($) {

    let offset = 0;
    let limit = 10;

    $(document).on('click', '#nitto-start-migration', function () {

        $('#nitto-migration-status').html('Starting migration...');
        console.log("Starting migration...");

        migrateBatch();

    });

    function migrateBatch() {

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'nitto_batch_migrate_users',
                offset: offset,
                limit: limit
            },
            success: function (response) {

                if (response.success) {

                    offset += limit;

                    let processed = response.data.processed;
                    let total = response.data.total;

                    let percent = total > 0 ? Math.round((processed / total) * 100) : 0;

                    $('#nitto-migration-status').html(
                        `Migrated ${processed} / ${total} users (${percent}%)`
                    );

                    // Optional: update progress bar if you added one
                    $('#progress-fill').css('width', percent + '%');

                    if (response.data.remaining > 0) {
                        migrateBatch();
                    } else {
                        $('#nitto-migration-status').html(
                            `Migration completed! (${total} users)`
                        );

                        $('#progress-fill').css('width', '100%');
                    }

                } else {
                    $('#nitto-migration-status').html('Migration failed');
                }

            }
        });

    }

});

