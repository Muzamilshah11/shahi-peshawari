<?php
$activePage = 'users';
require_once 'config.php';
require_once 'admin_sidebar.php';
?>

<style>
.users-table { width: 100%; border-collapse: collapse; background: var(--admin-white); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.users-table th { background: var(--admin-black); color: var(--admin-white); padding: 14px 16px; font-size: .8rem; font-weight: 600; text-align: left; text-transform: uppercase; letter-spacing: .5px; }
.users-table td { padding: 12px 16px; border-bottom: 1px solid var(--admin-grey-light); font-size: .85rem; }
.users-table tr:hover td { background: var(--admin-grey-bg); }
.user-avatar-sm { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; background: var(--admin-grey-light); }
.role-badge { padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
.role-admin { background: #fef3c7; color: #b45309; }
.role-customer { background: #dbeafe; color: #1d4ed8; }
.btn-sm { padding: 5px 12px; border-radius: 6px; font-size: .78rem; font-weight: 500; border: none; cursor: pointer; transition: var(--transition); }
.btn-sm-danger { background: #fef2f2; color: var(--admin-red); }
.btn-sm-danger:hover { background: var(--admin-red); color: white; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card { background: var(--admin-white); border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.06); text-align: center; }
.stat-card .stat-num { font-size: 2rem; font-weight: 700; }
.stat-card .stat-label { font-size: .78rem; color: var(--admin-grey); margin-top: 4px; }
.search-bar { display: flex; gap: 10px; margin-bottom: 20px; }
.search-bar input { flex: 1; padding: 10px 14px; border: 1px solid var(--admin-border); border-radius: 8px; font-size: .85rem; }
.search-bar input:focus { outline: none; border-color: var(--admin-blue); }
</style>

<div class="admin-content">
    <div class="content-header">
        <h1><i class="fas fa-user-shield"></i> Users (Firebase Auth)</h1>
        <p>Manage registered users and their roles</p>
    </div>

    <div class="stats-grid" id="userStats">
        <div class="stat-card"><div class="stat-num" id="totalUsers">-</div><div class="stat-label">Total Users</div></div>
        <div class="stat-card"><div class="stat-num" id="adminCount">-</div><div class="stat-label">Admins</div></div>
        <div class="stat-card"><div class="stat-num" id="customerCount">-</div><div class="stat-label">Customers</div></div>
        <div class="stat-card"><div class="stat-num" id="googleCount">-</div><div class="stat-label">Google Sign-in</div></div>
    </div>

    <div style="display:flex;gap:12px;align-items:center;margin-bottom:20px;flex-wrap:wrap">
        <div class="search-bar" style="flex:1;min-width:200px;margin:0">
            <input type="text" id="userSearch" placeholder="Search by name, email, or phone..." oninput="filterUsers()">
        </div>
        <button class="btn btn-primary" onclick="loadUsers()" style="white-space:nowrap"><i class="fas fa-sync-alt"></i> Refresh</button>
    </div>

    <div style="overflow-x:auto">
        <table class="users-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Provider</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-grey)"><i class="fas fa-spinner fa-spin"></i> Loading users...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
var allUsers = [];
function loadUsers() {
    document.getElementById('usersTableBody').innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-grey)"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';
    db.ref('users').once('value').then(function(snap) {
        allUsers = [];
        var data = snap.val();
        if (data) {
            Object.keys(data).forEach(function(uid) {
                allUsers.push({ uid: uid, ...data[uid] });
            });
        }
        renderUsers(allUsers);
        updateStats(allUsers);
    }).catch(function(err) {
        document.getElementById('usersTableBody').innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-red)"><i class="fas fa-exclamation-triangle"></i> Error: ' + err.message + '</td></tr>';
    });
}

function renderUsers(users) {
    if (!users.length) {
        document.getElementById('usersTableBody').innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-grey)"><i class="fas fa-users" style="font-size:2rem;margin-bottom:8px;display:block"></i> No users found</td></tr>';
        return;
    }
    var html = '';
    users.forEach(function(u) {
        var role = u.role || 'customer';
        var provider = u.provider || (u.email ? 'email' : 'unknown');
        var date = u.createdAt ? new Date(u.createdAt).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' }) : '-';
        var photo = u.photo ? '<img src="' + u.photo + '" class="user-avatar-sm" onerror="this.style.display=\'none\'">' : '<div class="user-avatar-sm" style="display:flex;align-items:center;justify-content:center;background:var(--admin-blue);color:white;font-weight:600;font-size:.9rem">' + (u.name || u.email || '?')[0].toUpperCase() + '</div>';
        html += '<tr data-uid="' + u.uid + '" data-name="' + (u.name||'').toLowerCase() + '" data-email="' + (u.email||'').toLowerCase() + '" data-phone="' + (u.phone||'').toLowerCase() + '">'
            + '<td data-label="User"><div style="display:flex;align-items:center;gap:10px">' + photo + '<div><div style="font-weight:600">' + (u.name || 'Unnamed') + '</div><div style="font-size:.75rem;color:var(--admin-grey)">' + u.uid.substring(0, 12) + '...</div></div></div></td>'
            + '<td data-label="Email">' + (u.email || '-') + '</td>'
            + '<td data-label="Phone">' + (u.phone || '-') + '</td>'
            + '<td data-label="Role"><span class="role-badge role-' + role + '">' + role.toUpperCase() + '</span></td>'
            + '<td data-label="Provider">' + provider + '</td>'
            + '<td data-label="Joined">' + date + '</td>'
            + '<td data-label="Action"><button class="btn-sm btn-sm-danger" onclick="deleteUser(\'' + u.uid + '\')" title="Delete"><i class="fas fa-trash"></i></button></td>'
            + '</tr>';
    });
    document.getElementById('usersTableBody').innerHTML = html;
}

function updateStats(users) {
    document.getElementById('totalUsers').textContent = users.length;
    document.getElementById('adminCount').textContent = users.filter(function(u) { return u.role === 'admin'; }).length;
    document.getElementById('customerCount').textContent = users.filter(function(u) { return u.role !== 'admin'; }).length;
    document.getElementById('googleCount').textContent = users.filter(function(u) { return u.provider === 'google'; }).length;
}

function filterUsers() {
    var q = document.getElementById('userSearch').value.toLowerCase();
    var filtered = allUsers.filter(function(u) {
        return (u.name || '').toLowerCase().includes(q) || (u.email || '').toLowerCase().includes(q) || (u.phone || '').includes(q);
    });
    renderUsers(filtered);
}

function deleteUser(uid) {
    if (!confirm('Delete this user? This cannot be undone.')) return;
    db.ref('users/' + uid).remove().then(function() {
        loadUsers();
    }).catch(function(err) {
        alert('Error: ' + err.message);
    });
}

loadUsers();
</script>

</main>
</div>
</body>
</html>
