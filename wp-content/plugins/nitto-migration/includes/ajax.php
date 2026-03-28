<?php
add_action('wp_ajax_nitto_fetch_users', 'nitto_fetch_users');

function nitto_fetch_users()
{
    global $wpdb;

    $external_db = nitto_laravel_db();

    $users = $external_db->get_results("SELECT * FROM entry");
    if (!$users) {
        wp_die('<p>No users found</p>');
    }

    ob_start();
?>

    <div class="nitto-table-container">
        <button id="nitto-start-migration" class="button button-primary">
            Start User Migration
        </button>
        <div id="nitto-migration-status"></div>
        <table class="nitto-modern-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>randomString</th>
                    <th>emailVerified</th>
                    <th>active_link</th>
                    <th>Name</th>
                    <th>NameMid</th>
                    <th>NameLast</th>
                    <th>Password</th>
                    <th>Temporerypassword</th>
                    <th>EmailAddress</th>
                    <th>Company</th>
                    <th>DealerNumber</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>DealerProv</th>
                    <th>PostalCode</th>
                    <th>Country_code</th>
                    <th>User_phone</th>
                    <th>Fax</th>
                    <th>Nittorep</th>
                    <th>NittoRepid</th>
                    <th>EnteredBy</th>
                    <th>Date</th>
                    <th>Lastlogindate</th>
                    <th>user_language</th>
                    <th>Ttrain</th>
                    <th>user_test_type</th>
                    <th>Status1</th>
                    <th>delaraccess</th>
                    <th>Level</th>
                    <th>Plist_0</th>
                    <th>Plist_G</th>
                    <th>gCode</th>
                    <th>Esalespromo</th>
                    <th>Etireinfo</th>
                    <th>Esurveys</th>
                    <th>Emisc</th>
                    <th>taccess</th>
                    <th>naccess</th>
                    <th>username</th>
                    <th>status</th>
                    <th>accesstob2b</th>
                    <th>accesstocopsubmsion</th>
                    <th>accesstoprderpromomaterial</th>
                    <th>accesstopointofpurchase</th>
                    <th>accesstoadmanage</th>
                    <th>accesstofeaturmanage</th>
                    <th>accessotbannermanage</th>
                    <th>accesstoweekvideomanage</th>
                    <th>accesstobuetinmanage</th>
                    <th>accesstoeventmange</th>
                    <th>dealerresourcesmanage</th>
                    <th>accesspointofsalemanage</th>
                    <th>Usergroup</th>
                    <th>old_user_chng_pass</th>
                    <th>rcvdemail</th>
                    <th>vrfyemail</th>
                    <th>advanceaccessrequeststatus</th>
                    <th>ccopsubmisiongroup</th>
                    <th>lngprefer</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo esc_html($user->ID); ?></td>
                        <td><?php echo esc_html($user->randomString); ?></td>
                        <td><?php echo esc_html($user->emailVerified); ?></td>
                        <td><?php echo esc_html($user->active_link); ?></td>
                        <td><?php echo esc_html($user->Name); ?></td>
                        <td><?php echo esc_html($user->NameMid); ?></td>
                        <td><?php echo esc_html($user->NameLast); ?></td>
                        <td><?php echo esc_html($user->Password); ?></td>
                        <td><?php echo esc_html($user->Temporerypassword); ?></td>
                        <td><?php echo esc_html($user->EmailAddress); ?></td>
                        <td><?php echo esc_html($user->Company); ?></td>
                        <td><?php echo esc_html($user->DealerNumber); ?></td>
                        <td><?php echo esc_html($user->Phone); ?></td>
                        <td><?php echo esc_html($user->Address); ?></td>
                        <td><?php echo esc_html($user->City); ?></td>
                        <td><?php echo esc_html($user->DealerProv); ?></td>
                        <td><?php echo esc_html($user->PostalCode); ?></td>
                        <td><?php echo esc_html($user->Country_code); ?></td>
                        <td><?php echo esc_html($user->User_phone); ?></td>
                        <td><?php echo esc_html($user->Fax); ?></td>
                        <td><?php echo esc_html($user->Nittorep); ?></td>
                        <td><?php echo esc_html($user->NittoRepid); ?></td>
                        <td><?php echo esc_html($user->EnteredBy); ?></td>
                        <td><?php echo esc_html($user->Date); ?></td>
                        <td><?php echo esc_html($user->Lastlogindate); ?></td>
                        <td><?php echo esc_html($user->user_language); ?></td>
                        <td><?php echo esc_html($user->Ttrain); ?></td>
                        <td><?php echo esc_html($user->user_test_type); ?></td>
                        <td><?php echo esc_html($user->Status1); ?></td>
                        <td><?php echo esc_html($user->delaraccess); ?></td>
                        <td><?php echo esc_html($user->Level); ?></td>
                        <td><?php echo esc_html($user->Plist_0); ?></td>
                        <td><?php echo esc_html($user->Plist_G); ?></td>
                        <td><?php echo esc_html($user->gCode); ?></td>
                        <td><?php echo esc_html($user->Esalespromo); ?></td>
                        <td><?php echo esc_html($user->Etireinfo); ?></td>
                        <td><?php echo esc_html($user->Esurveys); ?></td>
                        <td><?php echo esc_html($user->Emisc); ?></td>
                        <td><?php echo esc_html($user->taccess); ?></td>
                        <td><?php echo esc_html($user->naccess); ?></td>
                        <td><?php echo esc_html($user->username); ?></td>
                        <td><?php echo esc_html($user->status); ?></td>
                        <td><?php echo esc_html($user->accesstob2b); ?></td>
                        <td><?php echo esc_html($user->accesstocopsubmsion); ?></td>
                        <td><?php echo esc_html($user->accesstoprderpromomaterial); ?></td>
                        <td><?php echo esc_html($user->accesstopointofpurchase); ?></td>
                        <td><?php echo esc_html($user->accesstoadmanage); ?></td>
                        <td><?php echo esc_html($user->accesstofeaturmanage); ?></td>
                        <td><?php echo esc_html($user->accessotbannermanage); ?></td>
                        <td><?php echo esc_html($user->accesstoweekvideomanage); ?></td>
                        <td><?php echo esc_html($user->accesstobuetinmanage); ?></td>
                        <td><?php echo esc_html($user->accesstoeventmange); ?></td>
                        <td><?php echo esc_html($user->dealerresourcesmanage); ?></td>
                        <td><?php echo esc_html($user->accesspointofsalemanage); ?></td>
                        <td><?php echo esc_html($user->Usergroup); ?></td>
                        <td><?php echo esc_html($user->old_user_chng_pass); ?></td>
                        <td><?php echo esc_html($user->rcvdemail); ?></td>
                        <td><?php echo esc_html($user->vrfyemail); ?></td>
                        <td><?php echo esc_html($user->advanceaccessrequeststatus); ?></td>
                        <td><?php echo esc_html($user->ccopsubmisiongroup); ?></td>
                        <td><?php echo esc_html($user->lngprefer); ?></td>
                    </tr>
                <?php
                    $i++;
                endforeach; ?>
            </tbody>
        </table>
    </div>

<?php

    $html = ob_get_clean();
    echo $html;

    wp_die();
}


add_action('wp_ajax_nitto_batch_migrate_users', 'nitto_batch_migrate_users');

function nitto_batch_migrate_users()
{

    $offset = intval($_POST['offset']);
    $limit  = intval($_POST['limit']);
    // echo $limit . ' - ' . $offset;
    // die;

    $external_db = nitto_laravel_db();

     $total = (int) $external_db->get_var("SELECT COUNT(*) FROM entry");

    $users = $external_db->get_results(
        $external_db->prepare(
            "SELECT * FROM entry LIMIT %d OFFSET %d",
            $limit,
            $offset
        )
    );

    if (!$users) {
        wp_send_json_success([
            'migrated' => 0,
            'remaining' => 0
        ]);
    }

    $migrated = 0;

    foreach ($users as $user) {

        // echo sanitize_user($user->username);
        // die;

        $email = sanitize_email($user->EmailAddress);

        if (email_exists($email)) {
            continue;
        }

        $username = sanitize_user($user->username);
        // Fallback if username is empty
        if (empty($username)) {
            $username = sanitize_user($user->EmailAddress);
        }

        // Still empty? (rare but possible)
        if (empty($username)) {
            $username = 'user_' . $user->ID;
        }

        // if (username_exists($username)) {
        //     $username = $username . '_' . time();
        // }
        // ensure unique
        if (username_exists($username)) {
            $username .= '_' . wp_generate_password(4, false);
        }

        $role_map = [
            0 => 'normal_user',
            1 => 'advanced_user',
            2 => 'field_employee',
            3 => 'inside_employee',
            4 => 'um_super_user',
        ];

        $role = isset($role_map[$user->Level]) ? $role_map[$user->Level] : 'subscriber';
        $password = wp_generate_password();

        // $new_user_id = wp_create_user(
        //     $username,
        //     $password,
        //     $email
        // );
        $new_user_id = wp_insert_user([
            'user_login' => $username,
            'user_pass'  => $password,
            'user_email' => $email,
            'role'       => $role
        ]);

        if (!is_wp_error($new_user_id)) {

            wp_update_user([
                'ID' => $new_user_id,
                'first_name' => $user->Name,
                'last_name'  => $user->NameLast
            ]);

            $migrated++;
        };

        // echo $new_user_id;
        update_user_meta($new_user_id, 'original_laravel_id', $user->ID);
        update_user_meta($new_user_id, 'migration_status', 'migrated');
        update_user_meta($new_user_id, 'migration_timestamp', current_time('Y-m-d H:i:s'));
        update_user_meta($new_user_id, 'migration_notes', 'Migrated from Laravel on ' . current_time('Y-m-d H:i:s'));
        update_user_meta($new_user_id, 'randomString', $user->randomString);
        update_user_meta($new_user_id, 'emailVerified', $user->emailVerified);
        update_user_meta($new_user_id, 'active_link', $user->active_link);
        update_user_meta($new_user_id, 'name_mid', $user->NameMid);
        update_user_meta($new_user_id, 'temporerypassword', $user->Temporerypassword);
        update_user_meta($new_user_id, 'companyname', $user->Company);
        update_user_meta($new_user_id, 'dealernumber', $user->DealerNumber);
        update_user_meta($new_user_id, 'phone', $user->Phone);
        update_user_meta($new_user_id, 'address', $user->Address);
        update_user_meta($new_user_id, 'city', $user->City);
        update_user_meta($new_user_id, 'dealer_prov', $user->DealerProv);
        update_user_meta($new_user_id, 'postalcode', $user->PostalCode);
        update_user_meta($new_user_id, 'country', $user->Country_code);
        update_user_meta($new_user_id, 'fax', $user->Fax);
        update_user_meta($new_user_id, 'nittorep', $user->Nittorep);
        update_user_meta($new_user_id, 'nittorep_id', $user->NittoRepid);
        update_user_meta($new_user_id, 'entered_by', $user->EnteredBy);
        update_user_meta($new_user_id, 'date', $user->Date);
        update_user_meta($new_user_id, 'lastLoginDate', $user->Lastlogindate);
        update_user_meta($new_user_id, 'user_language', $user->user_language);
        update_user_meta($new_user_id, 'ttrain', $user->Ttrain);
        update_user_meta($new_user_id, 'user_test_type', $user->user_test_type);
        update_user_meta($new_user_id, 'status1', $user->Status1);
        update_user_meta($new_user_id, 'delaraccess', $user->delaraccess);
        update_user_meta($new_user_id, 'level', $user->Level);

        update_user_meta($new_user_id, 'Plist_0', $user->Plist_0);
        update_user_meta($new_user_id, 'Plist_G', $user->Plist_G);
        update_user_meta($new_user_id, 'gCode', $user->gCode);


        update_user_meta($new_user_id, 'esalespromo', $user->Esalespromo);
        update_user_meta($new_user_id, 'etireinfo', $user->Etireinfo);
        update_user_meta($new_user_id, 'esurveys', $user->Esurveys);
        update_user_meta($new_user_id, 'emisc', $user->Emisc);
        update_user_meta($new_user_id, 'taccess', $user->taccess);
        update_user_meta($new_user_id, 'naccess', $user->naccess);
        update_user_meta($new_user_id, 'username', $user->username);
        update_user_meta($new_user_id, 'status', $user->status);
        update_user_meta($new_user_id, 'accesstob2b', $user->accesstob2b);
        update_user_meta($new_user_id, 'accesstocopsubmsion', $user->accesstocopsubmsion);
        update_user_meta($new_user_id, 'accesstoprderpromomaterial', $user->accesstoprderpromomaterial);
        update_user_meta($new_user_id, 'accesstopointofpurchase', $user->accesstopointofpurchase);
        update_user_meta($new_user_id, 'accesstoadmanage', $user->accesstoadmanage);
        update_user_meta($new_user_id, 'accesstofeaturmanage', $user->accesstofeaturmanage);
        update_user_meta($new_user_id, 'accessotbannermanage', $user->accessotbannermanage);
        update_user_meta($new_user_id, 'accesstoweekvideomanage', $user->accesstoweekvideomanage);
        update_user_meta($new_user_id, 'accesstobuetinmanage', $user->accesstobuetinmanage);
        update_user_meta($new_user_id, 'accesstoeventmange', $user->accesstoeventmange);
        update_user_meta($new_user_id, 'dealerresourcesmanage', $user->dealerresourcesmanage);
        update_user_meta($new_user_id, 'accesspointofsalemanage', $user->accesspointofsalemanage);
        update_user_meta($new_user_id, 'usergroup', $user->Usergroup);
        update_user_meta($new_user_id, 'old_user_chng_pass', $user->old_user_chng_pass);
        update_user_meta($new_user_id, 'rcvdemail', $user->rcvdemail);
        update_user_meta($new_user_id, 'vrfyemail', $user->vrfyemail);
        update_user_meta($new_user_id, 'advanceaccessrequeststatus', $user->advanceaccessrequeststatus);
        update_user_meta($new_user_id, 'ccopsubmisiongroup', $user->ccopsubmisiongroup);
        update_user_meta($new_user_id, 'language_preference', $user->lngprefer);

        if (!is_wp_error($new_user_id)) {

            $user_obj = new WP_User($new_user_id);

            $cap_map = [
                'accesstoadmanage'        => 'edit_admaterials',
                'accesstofeaturmanage'    => 'edit_featured_items',
                'accessotbannermanage'    => 'edit_page_banners',
                'accesstoweekvideomanage' => 'edit_video_of_the_week',
                'accesstobuetinmanage'    => 'edit_bulletin_board_posts',
                'accesstoeventmange'      => 'edit_current_events',
                'dealerresourcesmanage'   => 'edit_dealer_resources',
                'accesspointofsalemanage' => 'edit_point_of_purchase',
            ];

            foreach ($cap_map as $db_field => $capability) {
                if (!empty($user->$db_field) && $user->$db_field == 1) {
                    $user_obj->add_cap($capability);
                }
            }
        }
    }

    $processed = min($offset + $limit, $total);
    // $remaining = count($users) == $limit ? 1 : 0;
    $remaining = ($processed < $total) ? 1 : 0;

    wp_send_json_success([
        'migrated'  => $migrated,
        'processed' => $processed,
        'total'     => $total,
        'remaining' => $remaining
    ]);
}



// For products
// add_action('wp_ajax_nitto_fetch_products', 'nitto_fetch_products');
