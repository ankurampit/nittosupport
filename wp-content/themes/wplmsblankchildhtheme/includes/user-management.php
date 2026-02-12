<?php
$global_priority_roles = [
    'um_super-user'   => 'Super User',
    'super_user'     => 'Super User',
    'administrator'   => 'Administrator',
    'field_employee'  => 'Field Employee',
    'inside_employee' => 'Inside Employee',
    'advanced_user'    => 'Advanced User',
    'normal_user'     => 'Normal User',
];


function global_roles($logedin_user_role){
    global $global_priority_roles;
    
    $role_permissions = [
        'um_super-user'   => [
            'um_super-user',
            'administrator' ,
            'field_employee' ,
            'inside_employee',
            'advanced_user',
            'normal_user',
        ],
        'super_user'     => [
            'super_user',
            'administrator' ,
            'field_employee' ,
            'inside_employee',
            'advanced_user',
            'normal_user',
        ],  
        'administrator'   => [
            'field_employee',
            'inside_employee',
            'advanced_user',
            'normal_user'
        ],
        'field_employee'  => [
            'advanced_user',
            'normal_user'
        ],
        'inside_employee' => [
            'advanced_user',
            'normal_user'
        ],
        'advanced_user'    => [],
        'normal_user'     => [],
    ];

    if (!isset($role_permissions[$logedin_user_role])) {
        return [];
    }
    return array_intersect_key($global_priority_roles, array_flip($role_permissions[$logedin_user_role]));
}

// add_action('init', function () {

//     $old_role = 'super_user';
//     $new_role = 'um_super-user';

//     // Get old role
//     $role = get_role($old_role);

//     if ($role && !get_role($new_role)) {

//         // 1️⃣ Create new role with same capabilities
//         add_role($new_role, 'UM Super User', $role->capabilities);

//         // 2️⃣ Get all users with old role
//         $users = get_users([
//             'role' => $old_role,
//             'fields' => 'all'
//         ]);

//         // 3️⃣ Assign new role to users
//         foreach ($users as $user) {
//             $user->remove_role($old_role);
//             $user->add_role($new_role);
//         }

//         // 4️⃣ Remove old role
//         remove_role($old_role);
//     }
// });
