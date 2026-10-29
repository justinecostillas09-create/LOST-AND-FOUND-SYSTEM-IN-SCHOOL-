<?php
// Simple session-based & JSON storage (No SQL required)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DATA_DIR', __DIR__ . '/../data');

// Initialize sample data files if they do not exist
function init_data() {
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0777, true);
    }

    $users_file = DATA_DIR . '/users.json';
    if (!file_exists($users_file)) {
        $default_users = [
            [
                'id' => 1,
                'name' => 'Campus Administrator',
                'email' => 'admin@campus.edu',
                'password' => 'admin123',
                'role' => 'Admin'
            ],
            [
                'id' => 2,
                'name' => 'Security Office Staff',
                'email' => 'staff@campus.edu',
                'password' => 'staff123',
                'role' => 'Staff'
            ],
            [
                'id' => 3,
                'name' => 'Alex Johnson',
                'email' => 'student@campus.edu',
                'password' => 'student123',
                'role' => 'User'
            ]
        ];
        file_put_contents($users_file, json_encode($default_users, JSON_PRETTY_PRINT));
    }

    $items_file = DATA_DIR . '/items.json';
    if (!file_exists($items_file)) {
        $default_items = [
            [
                'id' => 101,
                'type' => 'Lost',
                'name' => 'MacBook Air M2 (Silver)',
                'category' => 'Electronics',
                'description' => 'Silver 13-inch MacBook inside a dark grey felt sleeve. Has a small NASA sticker on the bottom lid.',
                'date' => '2026-09-28',
                'location' => 'Main Library - 2nd Floor Quiet Zone',
                'photo' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&auto=format&fit=crop&q=60',
                'status' => 'Pending Verification',
                'reported_by' => 'student@campus.edu',
                'created_at' => '2026-09-28 14:20'
            ],
            [
                'id' => 102,
                'type' => 'Found',
                'name' => 'Casio Scientific Calculator fx-991EX',
                'category' => 'Electronics',
                'description' => 'Black and white casing with slide-on cover. Left on desk row 4 after calculus class.',
                'date' => '2026-09-30',
                'location' => 'Science Complex - Lecture Hall 3',
                'photo' => 'https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?w=600&auto=format&fit=crop&q=60',
                'status' => 'Available for Claim',
                'reported_by' => 'staff@campus.edu',
                'created_at' => '2026-09-30 16:45'
            ],
            [
                'id' => 103,
                'type' => 'Lost',
                'name' => 'Student ID & Dorm Key Card',
                'category' => 'IDs & Cards',
                'description' => 'Blue University lanyard holding Student ID (Alex Johnson) and a white RFID dorm door access card.',
                'date' => '2026-10-01',
                'location' => 'Campus Dining Hall / Cafeteria',
                'photo' => 'https://images.unsplash.com/photo-1578873375972-014495cfa3e3?w=600&auto=format&fit=crop&q=60',
                'status' => 'Pending Verification',
                'reported_by' => 'student@campus.edu',
                'created_at' => '2026-10-01 12:10'
            ],
            [
                'id' => 104,
                'type' => 'Found',
                'name' => 'Navy Blue Hydro Flask (32oz)',
                'category' => 'Other',
                'description' => 'Dark blue insulated water bottle with national park stickers and a black flex cap.',
                'date' => '2026-10-02',
                'location' => 'Campus Recreation Center Gym',
                'photo' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=600&auto=format&fit=crop&q=60',
                'status' => 'Claim Approved',
                'reported_by' => 'staff@campus.edu',
                'created_at' => '2026-10-02 09:30'
            ],
            [
                'id' => 105,
                'type' => 'Found',
                'name' => 'AirPods Pro (2nd Gen) in Black Case',
                'category' => 'Electronics',
                'description' => 'Apple AirPods inside a matte black silicone protective case with small clip.',
                'date' => '2026-10-02',
                'location' => 'Student Union Lounge Benches',
                'photo' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=600&auto=format&fit=crop&q=60',
                'status' => 'Available for Claim',
                'reported_by' => 'staff@campus.edu',
                'created_at' => '2026-10-02 17:15'
            ],
            [
                'id' => 106,
                'type' => 'Lost',
                'name' => 'Organic Chemistry 8th Ed. Textbook',
                'category' => 'Books & Stationery',
                'description' => 'Hardcover textbook with sticky notes on chapters 4 and 5. Yellow highlighter on edge.',
                'date' => '2026-09-29',
                'location' => 'Engineering Hall - Study Room 204',
                'photo' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&auto=format&fit=crop&q=60',
                'status' => 'Pending Verification',
                'reported_by' => 'student@campus.edu',
                'created_at' => '2026-09-29 18:00'
            ]
        ];
        file_put_contents($items_file, json_encode($default_items, JSON_PRETTY_PRINT));
    }

    $claims_file = DATA_DIR . '/claims.json';
    if (!file_exists($claims_file)) {
        $default_claims = [
            [
                'id' => 201,
                'item_id' => 104,
                'item_name' => 'Navy Blue Hydro Flask (32oz)',
                'claimant_email' => 'student@campus.edu',
                'claimant_name' => 'Alex Johnson',
                'proof_details' => 'Bottle has a Yosemite National Park sticker with a tiny dent on the lower rim. I can identify all stickers.',
                'status' => 'Approved',
                'admin_notes' => 'Verified sticker description with item at Security Desk. Ready for pickup.',
                'created_at' => '2026-10-02 11:20'
            ],
            [
                'id' => 202,
                'item_id' => 105,
                'item_name' => 'AirPods Pro (2nd Gen) in Black Case',
                'claimant_email' => 'student@campus.edu',
                'claimant_name' => 'Alex Johnson',
                'proof_details' => 'My iPhone name is "Alex Johnson Airpods" and pairs automatically. Case has carabiner ring.',
                'status' => 'Pending',
                'admin_notes' => 'Under review by staff. Awaiting Bluetooth pairing confirmation.',
                'created_at' => '2026-10-02 18:40'
            ]
        ];
        file_put_contents($claims_file, json_encode($default_claims, JSON_PRETTY_PRINT));
    }

    $notifs_file = DATA_DIR . '/notifications.json';
    if (!file_exists($notifs_file)) {
        $default_notifs = [
            [
                'id' => 1,
                'user_email' => 'student@campus.edu',
                'title' => 'Claim Approved!',
                'message' => 'Your claim for "Navy Blue Hydro Flask (32oz)" was approved. Please collect it from the Campus Security Office.',
                'type' => 'success',
                'created_at' => '2026-10-02 14:15'
            ],
            [
                'id' => 2,
                'user_email' => 'student@campus.edu',
                'title' => 'Claim Under Review',
                'message' => 'Staff is currently verifying your claim for "AirPods Pro (2nd Gen)".',
                'type' => 'info',
                'created_at' => '2026-10-02 18:45'
            ],
            [
                'id' => 3,
                'user_email' => 'student@campus.edu',
                'title' => 'Lost Item Published',
                'message' => 'Your report for "MacBook Air M2" has been published to the campus search board.',
                'type' => 'primary',
                'created_at' => '2026-09-28 14:30'
            ]
        ];
        file_put_contents($notifs_file, json_encode($default_notifs, JSON_PRETTY_PRINT));
    }
}

// Helpers for reading/writing JSON
function read_json($filename) {
    init_data();
    $file = DATA_DIR . '/' . $filename;
    if (!file_exists($file)) return [];
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

function write_json($filename, $data) {
    init_data();
    $file = DATA_DIR . '/' . $filename;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// User Management
function get_users() {
    return read_json('users.json');
}

function find_user_by_email($email) {
    $users = get_users();
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

function register_user($name, $email, $password, $role = 'User') {
    $users = get_users();
    if (find_user_by_email($email)) {
        return ['success' => false, 'message' => 'Email is already registered.'];
    }
    $new_user = [
        'id' => time(),
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'role' => in_array($role, ['Admin', 'Staff', 'User']) ? $role : 'User'
    ];
    $users[] = $new_user;
    write_json('users.json', $users);
    return ['success' => true, 'user' => $new_user];
}

function authenticate_user($email, $password) {
    $user = find_user_by_email($email);
    if ($user && $user['password'] === $password) {
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function is_logged_in() {
    return !empty($_SESSION['user']);
}

function logout_user() {
    unset($_SESSION['user']);
    session_destroy();
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php?msg=login_required');
        exit;
    }
}

function require_admin() {
    require_login();
    $user = current_user();
    if ($user['role'] !== 'Admin') {
        header('Location: dashboard.php?msg=unauthorized');
        exit;
    }
}

function require_staff_or_admin() {
    require_login();
    $user = current_user();
    if (!in_array($user['role'], ['Admin', 'Staff'])) {
        header('Location: dashboard.php?msg=unauthorized');
        exit;
    }
}

// Item Management
function get_items($filters = []) {
    $items = read_json('items.json');
    if (empty($filters)) return $items;

    return array_filter($items, function($item) use ($filters) {
        if (!empty($filters['type']) && $filters['type'] !== 'All' && strcasecmp($item['type'], $filters['type']) !== 0) {
            return false;
        }
        if (!empty($filters['category']) && $filters['category'] !== 'All' && strcasecmp($item['category'], $filters['category']) !== 0) {
            return false;
        }
        if (!empty($filters['location']) && $filters['location'] !== 'All' && stripos($item['location'], $filters['location']) === false) {
            return false;
        }
        if (!empty($filters['keyword'])) {
            $kw = strtolower($filters['keyword']);
            $match_name = stripos(strtolower($item['name']), $kw) !== false;
            $match_desc = stripos(strtolower($item['description']), $kw) !== false;
            $match_loc = stripos(strtolower($item['location']), $kw) !== false;
            if (!$match_name && !$match_desc && !$match_loc) {
                return false;
            }
        }
        return true;
    });
}

function get_item_by_id($id) {
    $items = read_json('items.json');
    foreach ($items as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    return null;
}

function save_item($type, $name, $category, $description, $date, $location, $photo_url = '') {
    $items = read_json('items.json');
    $user = current_user();
    $user_email = $user ? $user['email'] : 'anonymous@campus.edu';

    if (empty($photo_url)) {
        // Fallback default placeholder based on category
        $photo_url = 'https://images.unsplash.com/photo-1586769852044-692d6e3703f0?w=600&auto=format&fit=crop&q=60';
    }

    $new_item = [
        'id' => time(),
        'type' => $type,
        'name' => $name,
        'category' => $category,
        'description' => $description,
        'date' => $date,
        'location' => $location,
        'photo' => $photo_url,
        'status' => ($type === 'Found') ? 'Available for Claim' : 'Open (Searching)',
        'reported_by' => $user_email,
        'created_at' => date('Y-m-d H:i')
    ];

    array_unshift($items, $new_item);
    write_json('items.json', $items);
    return $new_item;
}

function update_item_status($id, $new_status) {
    $items = read_json('items.json');
    $updated = false;
    foreach ($items as &$item) {
        if ($item['id'] == $id) {
            $item['status'] = $new_status;
            $updated = true;
            break;
        }
    }
    if ($updated) {
        write_json('items.json', $items);
    }
    return $updated;
}

// Claims Management
function get_claims() {
    return read_json('claims.json');
}

function get_claims_by_user($email) {
    $claims = get_claims();
    return array_values(array_filter($claims, function($c) use ($email) {
        return strtolower($c['claimant_email']) === strtolower($email);
    }));
}

function save_claim($item_id, $proof_details) {
    $item = get_item_by_id($item_id);
    if (!$item) return null;

    $user = current_user();
    $claims = get_claims();

    $new_claim = [
        'id' => time(),
        'item_id' => (int)$item_id,
        'item_name' => $item['name'],
        'claimant_email' => $user ? $user['email'] : 'student@campus.edu',
        'claimant_name' => $user ? $user['name'] : 'Alex Johnson',
        'proof_details' => $proof_details,
        'status' => 'Pending',
        'admin_notes' => 'Awaiting admin/staff review.',
        'created_at' => date('Y-m-d H:i')
    ];

    array_unshift($claims, $new_claim);
    write_json('claims.json', $claims);

    // Notify user
    add_notification(
        $new_claim['claimant_email'],
        'Claim Submitted',
        'Your claim for "' . $item['name'] . '" has been submitted for review.',
        'info'
    );

    return $new_claim;
}

function update_claim_status($claim_id, $status, $admin_notes = '') {
    $claims = get_claims();
    $updated = false;
    $claimant_email = '';
    $item_name = '';

    foreach ($claims as &$c) {
        if ($c['id'] == $claim_id) {
            $c['status'] = $status;
            if (!empty($admin_notes)) {
                $c['admin_notes'] = $admin_notes;
            }
            $claimant_email = $c['claimant_email'];
            $item_name = $c['item_name'];
            $updated = true;
            break;
        }
    }

    if ($updated) {
        write_json('claims.json', $claims);

        // Add user notification
        if (!empty($claimant_email)) {
            $notif_type = ($status === 'Approved') ? 'success' : (($status === 'Rejected') ? 'danger' : 'info');
            add_notification(
                $claimant_email,
                'Claim ' . $status,
                'Your claim for "' . $item_name . '" status is now: ' . $status . '. ' . $admin_notes,
                $notif_type
            );
        }
    }
    return $updated;
}

// Notifications
function get_user_notifications($email) {
    $notifs = read_json('notifications.json');
    return array_values(array_filter($notifs, function($n) use ($email) {
        return strtolower($n['user_email']) === strtolower($email);
    }));
}

function add_notification($email, $title, $message, $type = 'info') {
    $notifs = read_json('notifications.json');
    $new_n = [
        'id' => time() + rand(1, 999),
        'user_email' => $email,
        'title' => $title,
        'message' => $message,
        'type' => $type,
        'created_at' => date('Y-m-d H:i')
    ];
    array_unshift($notifs, $new_n);
    write_json('notifications.json', $notifs);
}

// Initial trigger to ensure JSON files are ready
init_data();
