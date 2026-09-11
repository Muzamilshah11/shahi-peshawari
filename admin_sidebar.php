<?php if (!isset($activePage)) $activePage = ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --admin-bg: #f5f5f5;
            --admin-white: #ffffff;
            --admin-black: #1a1a1a;
            --admin-grey: #6b7280;
            --admin-grey-light: #e5e7eb;
            --admin-grey-bg: #f9fafb;
            --admin-border: #e5e7eb;
            --admin-blue: #0984e3;
            --admin-red: #dc2626;
            --admin-gold: #b8860b;
            --admin-green: #16a34a;
            --sidebar-width: 280px;
            --transition: all .25s ease;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--admin-bg);
            color: var(--admin-black);
            line-height: 1.6;
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }
        button { font-family: inherit; cursor: pointer; border: none; outline: none; }
        input, select { font-family: inherit; outline: none; }

        /* ─── Admin Layout ─── */
        .admin-wrapper { display: flex; min-height: 100vh; }
        .admin-main { flex: 1; margin-left: var(--sidebar-width); padding: 28px; transition: margin-left .3s ease; }

        /* ─── Sidebar ─── */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width); background: var(--admin-white);
            border-right: 1px solid var(--admin-border);
            display: flex; flex-direction: column;
            z-index: 1100; transition: width .3s ease;
            overflow: hidden;
        }
        .sidebar.collapsed { width: 70px; }
        .sidebar.collapsed ~ .admin-main { margin-left: 70px; }
        .sidebar.collapsed .sidebar-brand-text,
        .sidebar.collapsed .sidebar-nav a span,
        .sidebar.collapsed .sidebar-bottom a span,
        .sidebar.collapsed .sidebar-toggle-btn span { display: none; }
        .sidebar.collapsed .sidebar-nav a { justify-content: center; padding: 12px; gap: 0; }
        .sidebar.collapsed .sidebar-bottom a { justify-content: center; padding: 12px; gap: 0; }
        .sidebar.collapsed .sidebar-toggle-btn { justify-content: center; }
        .sidebar.collapsed .sidebar-header { justify-content: center; padding: 20px 0; gap: 0; }
        .sidebar.collapsed .sidebar-close { display: none; }

        /* Sidebar Header */
        .sidebar-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px; flex-shrink: 0;
            border-bottom: 1px solid var(--admin-border);
        }
        .sidebar-brand {
            font-size: 1.3rem; font-weight: 800; color: var(--admin-black);
            letter-spacing: -0.5px;
        }
        .sidebar-close {
            width: 32px; height: 32px; border-radius: 8px;
            background: var(--admin-grey-bg); color: var(--admin-grey);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; transition: var(--transition);
        }
        .sidebar-close:hover { background: var(--admin-black); color: var(--admin-white); }

        /* Sidebar Nav */
        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
        .sidebar-nav ul { list-style: none; }
        .sidebar-nav li { margin-bottom: 2px; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 16px; border-radius: 10px;
            font-size: .875rem; font-weight: 500;
            color: var(--admin-grey); transition: var(--transition);
        }
        .sidebar-nav a i { font-size: 1rem; width: 20px; text-align: center; }
        .sidebar-nav a:hover { background: var(--admin-grey-bg); color: var(--admin-black); }

        /* Active Item */
        .sidebar-nav a.active {
            background: var(--admin-black); color: var(--admin-white);
        }
        .sidebar-nav a.active i { color: var(--admin-white); }

        /* Divider */
        .sidebar-divider {
            height: 1px; background: var(--admin-grey-light);
            margin: 8px 16px;
        }

        /* Bottom Items */
        .sidebar-bottom { padding: 12px 12px 12px; flex-shrink: 0; }
        .sidebar-bottom ul { list-style: none; }
        .sidebar-bottom li { margin-bottom: 2px; }
        .sidebar-bottom a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 16px; border-radius: 10px;
            font-size: .875rem; font-weight: 500;
            transition: var(--transition);
        }
        .sidebar-bottom a i { font-size: 1rem; width: 20px; text-align: center; }
        .sidebar-bottom .change-pass { color: var(--admin-gold); }
        .sidebar-bottom .change-pass:hover { background: #fef3c7; }
        .sidebar-bottom .signout { color: var(--admin-red); }
        .sidebar-bottom .signout:hover { background: #fef2f2; }

        /* Sidebar Toggle */
        .sidebar-toggle-btn {
            width: 32px; height: 32px; border-radius: 8px;
            background: var(--admin-grey-bg); color: var(--admin-grey);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; cursor: pointer; border: none;
            transition: transform .3s ease; flex-shrink: 0;
        }
        .sidebar-toggle-btn:hover { background: var(--admin-black); color: var(--admin-white); }
        .sidebar.collapsed .sidebar-toggle-btn i { transform: rotate(180deg); }

        /* ─── Mobile Toggle ─── */
        .admin-topbar {
            display: none; position: fixed; top: 0; left: 0; right: 0;
            height: 60px; background: var(--admin-white);
            border-bottom: 1px solid var(--admin-border);
            align-items: center; justify-content: space-between;
            padding: 0 20px; z-index: 1000;
        }
        body:not(.admin-authed) .admin-topbar { display: none !important; }
        body:not(.admin-authed) .sidebar { display: none !important; }
        body:not(.admin-authed) .admin-main { margin-left: 0 !important; }
        .admin-topbar .brand { font-weight: 800; font-size: 1.1rem; }
        .menu-toggle {
            width: 40px; height: 40px; border-radius: 8px;
            background: var(--admin-grey-bg);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: var(--admin-black);
        }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.4); z-index: 1050;
        }

        /* ─── Stat Cards ─── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px; margin-bottom: 28px;
        }
        .stat-card {
            background: var(--admin-white);
            border-radius: 12px; padding: 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
            display: flex; align-items: center; gap: 16px;
        }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .stat-icon.blue { background: #e8f4fd; color: var(--admin-blue); }
        .stat-icon.green { background: #e6fff9; color: var(--admin-green); }
        .stat-icon.gold { background: #fef3c7; color: var(--admin-gold); }
        .stat-icon.red { background: #fef2f2; color: var(--admin-red); }
        .stat-info .stat-value { font-size: 1.5rem; font-weight: 700; }
        .stat-info .stat-label { font-size: .8rem; color: var(--admin-grey); }

        /* ─── Table ─── */
        .admin-table-wrap {
            background: var(--admin-white);
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .admin-table {
            width: 100%; border-collapse: collapse;
        }
        .admin-table th {
            text-align: left; padding: 14px 16px;
            font-size: .75rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .5px;
            color: var(--admin-grey);
            background: var(--admin-grey-bg);
            border-bottom: 1px solid var(--admin-border);
        }
        .admin-table td {
            padding: 14px 16px; font-size: .85rem;
            border-bottom: 1px solid var(--admin-border);
            vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: var(--admin-grey-bg); }
        .admin-table select {
            padding: 6px 10px; border: 1px solid var(--admin-border);
            border-radius: 6px; font-size: .8rem; font-weight: 500;
            background: var(--admin-white); cursor: pointer;
        }
        .badge {
            padding: 4px 10px; border-radius: 50px;
            font-size: .7rem; font-weight: 600;
        }
        .badge-placed     { background: #fef3c7; color: #92400e; }
        .badge-processing  { background: #e5e7eb; color: #374151; }
        .badge-shipped     { background: #dbeafe; color: #1d4ed8; }
        .badge-delivered   { background: #d1fae5; color: #065f46; }

        /* ─── Page Header ─── */
        .page-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 24px;
        }
        .page-header h1 { font-size: 1.4rem; font-weight: 700; }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .admin-topbar { display: flex; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .admin-main { margin-left: 0; padding: 80px 12px 24px; }
            .stat-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .page-header { flex-direction: column; gap: 10px; align-items: flex-start; }
            .page-header h1 { font-size: 1.2rem; }
            .page-header div { width: 100%; }
            .page-header select, .page-header input { font-size: .8rem; padding: 7px 10px; }
            .admin-table-wrap { overflow-x: visible; }
            .admin-table { display: block; width: 100%; }
            .admin-table thead { display: none; }
            .admin-table tbody { display: block; width: 100%; }
            .admin-table tbody tr { display: flex; flex-direction: column; background: var(--admin-white); border: 1px solid var(--admin-border); border-radius: 10px; padding: 14px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
            .admin-table tbody td { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border: none; font-size: .82rem; }
            .admin-table tbody td::before { content: attr(data-label); font-weight: 600; color: var(--admin-grey); font-size: .72rem; text-transform: uppercase; min-width: 80px; }
            .admin-table tbody td:last-child { border-top: 1px solid var(--admin-border); margin-top: 6px; padding-top: 10px; }
            .modal { padding: 10px !important; }
            .modal > div { max-height: 85vh !important; padding: 20px !important; }
            .btn { padding: 8px 14px !important; font-size: .8rem !important; }
            .admin-table-wrap { overflow-x: visible !important; }
        }
        @media (max-width: 480px) {
            .stat-grid { grid-template-columns: 1fr; }
            .admin-main { padding: 80px 10px 24px; }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">

    <!-- ─── Mobile Topbar ─── -->
    <div class="admin-topbar" id="adminTopbar">
        <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <span class="brand">SHAHI <span style="font-weight:400;color:var(--admin-grey)">Admin</span></span>
        <div style="width:40px"></div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ─── Sidebar ─── -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-brand">SHA<span class="sidebar-brand-text">HI</span></span>
            <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()" title="Toggle Sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="admin.php" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-th-large"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="admin_categories.php" class="<?= $activePage === 'categories' ? 'active' : '' ?>">
                        <i class="fas fa-layer-group"></i> <span>Categories</span>
                    </a>
                </li>
                <li>
                    <a href="admin_products.php" class="<?= $activePage === 'inventory' ? 'active' : '' ?>">
                        <i class="fas fa-boxes-stacked"></i> <span>Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="admin_orders.php" class="<?= $activePage === 'orders' ? 'active' : '' ?>">
                        <i class="fas fa-shopping-bag"></i> <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="admin_customers.php" class="<?= $activePage === 'customers' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> <span>Customers</span>
                    </a>
                </li>
                <li>
                    <a href="admin_users.php" class="<?= $activePage === 'users' ? 'active' : '' ?>">
                        <i class="fas fa-user-shield"></i> <span>Users (Auth)</span>
                    </a>
                </li>
                <li>
                    <a href="admin_subscribers.php" class="<?= $activePage === 'subscribers' ? 'active' : '' ?>">
                        <i class="fas fa-envelope-open-text"></i> <span>Subscribers</span>
                    </a>
                </li>
                <li>
                    <a href="admin_reviews.php" class="<?= $activePage === 'reviews' ? 'active' : '' ?>">
                        <i class="fas fa-star"></i> <span>Reviews</span>
                    </a>
                </li>
                <li>
                    <a href="admin_settings.php" class="<?= $activePage === 'settings' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i> <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-divider"></div>

        <div class="sidebar-bottom">
            <ul>
                <li><a href="#" class="change-pass" onclick="changeAdminPass(); return false;"><i class="fas fa-key"></i> <span>Change Password</span></a></li>
                <li><a href="#" class="signout" onclick="adminSignOut()"><i class="fas fa-sign-out-alt"></i> <span>Sign Out</span></a></li>
            </ul>
        </div>
    </aside>

    <!-- ─── Main Content ─── -->
    <main class="admin-main">

<!-- Admin Firebase Init (loaded once) -->
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
<script>
if (!firebase.apps.length) {
    firebase.initializeApp({
        apiKey:            "<?= FIREBASE_API_KEY ?>",
        authDomain:        "<?= FIREBASE_AUTH_DOMAIN ?>",
        databaseURL:       "<?= FIREBASE_DATABASE_URL ?>",
        projectId:         "<?= FIREBASE_PROJECT_ID ?>",
        storageBucket:     "<?= FIREBASE_STORAGE_BUCKET ?>",
        messagingSenderId: "<?= FIREBASE_MESSAGING_SENDER_ID ?>",
        appId:             "<?= FIREBASE_APP_ID ?>"
    });
}
const db = firebase.database();

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

function showAdminPanel() {
    document.body.classList.add('admin-authed');
}

/* Auto-check login for pages without loginGate */
(function() {
    var hasLoginGate = document.getElementById('loginGate');
    if (!hasLoginGate) {
        if (localStorage.getItem('admin_logged_in') === 'true') {
            showAdminPanel();
        }
    }
})();

function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('admin_sidebar_collapsed', sidebar.classList.contains('collapsed'));
}

/* Restore collapsed state on load */
if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
    document.getElementById('sidebar').classList.add('collapsed');
}

function adminSignOut() {
    if (confirm('Sign out of admin panel?')) {
        localStorage.removeItem('admin_logged_in');
        document.body.classList.remove('admin-authed');
        firebase.auth().signOut().catch(function() {});
        window.location.href = 'admin.php';
    }
}

function changeAdminPass() {
    document.getElementById('changePassModal').style.display = 'flex';
}

function closeChangePassModal() {
    document.getElementById('changePassModal').style.display = 'none';
    document.getElementById('currentPass').value = '';
    document.getElementById('newPass').value = '';
    document.getElementById('confirmPass').value = '';
    document.getElementById('passError').style.display = 'none';
}

function submitChangePass() {
    var current = document.getElementById('currentPass').value;
    var newP = document.getElementById('newPass').value;
    var confirm = document.getElementById('confirmPass').value;
    var errEl = document.getElementById('passError');
    var btn = document.getElementById('changePassBtn');

    errEl.style.display = 'none';

    if (!current || !newP || !confirm) {
        errEl.textContent = 'Fill in all fields'; errEl.style.display = 'block'; return;
    }
    if (newP.length < 6) {
        errEl.textContent = 'New password must be at least 6 characters'; errEl.style.display = 'block'; return;
    }
    if (newP !== confirm) {
        errEl.textContent = 'New password and confirm do not match'; errEl.style.display = 'block'; return;
    }
    if (current === newP) {
        errEl.textContent = 'New password must be different from current'; errEl.style.display = 'block'; return;
    }

    var user = firebase.auth().currentUser;
    if (!user || !user.email) {
        errEl.textContent = 'No admin user logged in'; errEl.style.display = 'block'; return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

    var credential = firebase.auth.EmailAuthProvider.credential(user.email, current);
    user.reauthenticateWithCredential(credential).then(function() {
        return user.updatePassword(newP);
    }).then(function() {
        return db.ref('admin/password').set(newP);
    }).then(function() {
        showToast('Password updated! Logging out...');
        closeChangePassModal();
        setTimeout(function() {
            localStorage.removeItem('admin_logged_in');
            document.body.classList.remove('admin-authed');
            firebase.auth().signOut().catch(function() {});
            window.location.href = 'admin.php';
        }, 1500);
    }).catch(function(err) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Update Password';
        if (err.code === 'auth/wrong-password') {
            errEl.textContent = 'Current password is incorrect'; errEl.style.display = 'block';
        } else if (err.code === 'auth/weak-password') {
            errEl.textContent = 'New password is too weak'; errEl.style.display = 'block';
        } else {
            errEl.textContent = err.message; errEl.style.display = 'block';
        }
    });
}

function showToast(msg) {
    let t = document.getElementById('adminToast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'adminToast';
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1a1a1a;color:#fff;padding:12px 24px;border-radius:10px;font-size:.85rem;font-weight:500;z-index:9999;transition:all .3s ease;opacity:0;transform:translateY(10px);box-shadow:0 4px 20px rgba(0,0,0,.2)';
        document.body.appendChild(t);
    }
    t.textContent = msg;
    t.style.opacity = '1';
    t.style.transform = 'translateY(0)';
    setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(10px)'; }, 2500);
}
</script>

<!-- Change Password Modal -->
<div id="changePassModal" style="display:none;position:fixed;inset:0;z-index:3000;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--admin-white);border-radius:16px;width:100%;max-width:420px;padding:28px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <h3 style="font-size:1.1rem;font-weight:700"><i class="fas fa-key" style="color:var(--admin-blue);margin-right:8px"></i>Change Password</h3>
            <button onclick="closeChangePassModal()" style="width:32px;height:32px;border-radius:8px;background:var(--admin-grey-bg);display:flex;align-items:center;justify-content:center;font-size:1rem;cursor:pointer;border:none">&times;</button>
        </div>
        <div style="margin-bottom:14px">
            <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Current Password *</label>
            <input type="password" id="currentPass" placeholder="Enter current password" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;box-sizing:border-box">
        </div>
        <div style="margin-bottom:14px">
            <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">New Password *</label>
            <input type="password" id="newPass" placeholder="Min 6 characters" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;box-sizing:border-box">
        </div>
        <div style="margin-bottom:14px">
            <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Confirm New Password *</label>
            <input type="password" id="confirmPass" placeholder="Re-enter new password" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;box-sizing:border-box">
        </div>
        <div id="passError" style="color:var(--admin-red);font-size:.8rem;margin-bottom:12px;display:none;padding:8px 12px;background:#fef2f2;border-radius:8px"></div>
        <div style="display:flex;gap:10px;justify-content:flex-end">
            <button onclick="closeChangePassModal()" style="padding:10px 20px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;background:var(--admin-white)">Cancel</button>
            <button id="changePassBtn" onclick="submitChangePass()" style="padding:10px 20px;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;background:var(--admin-blue);color:#fff"><i class="fas fa-check"></i> Update Password</button>
        </div>
    </div>
</div>
