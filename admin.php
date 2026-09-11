<?php
$pageTitle = 'Dashboard';
require_once 'config.php';
$activePage = 'dashboard';
require_once 'admin_sidebar.php';
?>

<!-- ─── Login Gate ─── -->
<div id="loginGate" style="display:none; max-width:400px; margin:80px auto; text-align:center;">
    <div style="background:var(--admin-white); border-radius:12px; padding:40px 32px; box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div style="width:60px;height:60px;border-radius:16px;background:var(--admin-black);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fas fa-lock" style="color:#fff;font-size:1.3rem;"></i>
        </div>
        <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:4px;">SHAHI Admin</h2>
        <p style="font-size:.85rem; color:var(--admin-grey); margin-bottom:24px;">Sign in to manage your store</p>
        <div style="margin-bottom:16px;">
            <input type="email" id="adminEmail" value="<?= ADMIN_EMAIL ?>" placeholder="Admin Email"
                   style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem; margin-bottom:12px;">
            <input type="password" id="adminPass" placeholder="Password"
                   style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem;">
        </div>
        <div id="loginError" style="color:var(--admin-red); font-size:.8rem; margin-bottom:12px; display:none;"></div>
        <button onclick="adminLogin()" id="loginBtn"
                style="width:100%; padding:12px; background:var(--admin-black); color:#fff; border-radius:8px; font-size:.9rem; font-weight:600;">
            Sign In
        </button>
        <p style="font-size:.72rem; color:var(--admin-grey); margin-top:16px;">
            Default: <?= ADMIN_EMAIL ?> / <?= ADMIN_PASS ?>
        </p>
    </div>
</div>

<!-- ─── Dashboard Content ─── -->
<div id="dashboardContent" style="display:none;">
    <div class="page-header">
        <h1>Dashboard</h1>
        <span style="font-size:.85rem; color:var(--admin-grey);" id="todayDate"></span>
    </div>

    <!-- Stat Cards -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statRevenue"><?= CURRENCY ?>0</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statOrders">0</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statPending">0</div>
                <div class="stat-label">Pending Orders</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="statDelivered">0</div>
                <div class="stat-label">Delivered</div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:16px;">Recent Orders</h2>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="recentOrdersBody">
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--admin-grey);">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
/* ─── Admin Login Gate ─── */
function checkAdminAuth() {
    const logged = localStorage.getItem('admin_logged_in');
    if (logged === 'true') {
        document.getElementById('loginGate').style.display = 'none';
        document.getElementById('dashboardContent').style.display = 'block';
        document.body.classList.add('admin-authed');
        loadDashboard();
    } else {
        document.getElementById('loginGate').style.display = 'block';
        document.getElementById('dashboardContent').style.display = 'none';
    }
}

function adminLogin() {
    const email = document.getElementById('adminEmail').value.trim();
    const pass = document.getElementById('adminPass').value;
    const errEl = document.getElementById('loginError');
    const btn = document.getElementById('loginBtn');

    if (!email || !pass) { errEl.textContent = 'Fill in all fields'; errEl.style.display = 'block'; return; }

    btn.textContent = 'Signing in...';
    btn.disabled = true;
    errEl.style.display = 'none';

    fetch('admin_auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password: pass })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok && data.idToken) {
            const credential = firebase.auth.EmailAuthProvider.credential(email, pass);
            return firebase.auth().signInWithCredential(credential);
        } else {
            throw new Error(data.error || 'Login failed');
        }
    })
    .then(() => {
        localStorage.setItem('admin_logged_in', 'true');
        checkAdminAuth();
    })
    .catch(err => {
        errEl.textContent = err.message || 'Login failed. Try again.';
        errEl.style.display = 'block';
        btn.textContent = 'Sign In';
        btn.disabled = false;
    });
}

/* ─── Dashboard Data ─── */
function loadDashboard() {
    document.getElementById('todayDate').textContent = new Date().toLocaleDateString('en-US', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });

    db.ref('orders').on('value', snap => {
        let totalRevenue = 0, totalOrders = 0, pending = 0, delivered = 0;
        const allOrders = [];

        snap.forEach(child => {
            const o = child.val();
            totalOrders++;
            totalRevenue += o.total || 0;
            if (o.status === 'placed' || o.status === 'processing') pending++;
            if (o.status === 'delivered') delivered++;
            allOrders.push(o);
        });

        document.getElementById('statRevenue').textContent = '<?= CURRENCY ?>' + totalRevenue.toLocaleString();
        document.getElementById('statOrders').textContent = totalOrders;
        document.getElementById('statPending').textContent = pending;
        document.getElementById('statDelivered').textContent = delivered;

        /* Recent 10 */
        allOrders.sort((a, b) => (b.timestamp || 0) - (a.timestamp || 0));
        const recent = allOrders.slice(0, 10);
        const tbody = document.getElementById('recentOrdersBody');

        if (recent.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--admin-grey);">No orders yet</td></tr>';
            return;
        }

        tbody.innerHTML = recent.map(o => {
            const d = new Date(o.date);
            const dateStr = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const statusClass = 'badge-' + (o.status || 'placed');
            return `<tr>
                <td><strong>${o.orderId}</strong></td>
                <td>${o.customer?.name || '-'}</td>
                <td>${o.customer?.phone || '-'}</td>
                <td><strong><?= CURRENCY ?>${o.total}</strong></td>
                <td><span class="badge ${statusClass}">${(o.status || 'placed').toUpperCase()}</span></td>
                <td>${dateStr}</td>
            </tr>`;
        }).join('');
    });
}

checkAdminAuth();
</script>

</main>
</div>
</body>
</html>
