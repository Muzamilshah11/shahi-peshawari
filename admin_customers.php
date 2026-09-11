<?php
$activePage = 'customers';
require_once 'config.php';
require_once 'admin_sidebar.php';
?>

<div class="page-header">
    <h1><i class="fas fa-users" style="color:var(--admin-blue);margin-right:8px"></i> Customers</h1>
</div>

<!-- Search -->
<div style="margin-bottom:20px">
    <input type="text" id="searchInput" placeholder="Search by name, phone, or email..." oninput="filterCustomers()" style="padding:10px 16px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;width:100%;max-width:400px">
</div>

<!-- Stats -->
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-info"><div class="stat-value" id="totalCustomers">0</div><div class="stat-label">Total Customers</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-shopping-bag"></i></div>
        <div class="stat-info"><div class="stat-value" id="totalOrders">0</div><div class="stat-label">Total Orders</div></div>
    </div>
</div>

<!-- Table -->
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Orders</th>
                <th>Total Spent</th>
            </tr>
        </thead>
        <tbody id="customersBody">
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-grey)">Loading customers...</td></tr>
        </tbody>
    </table>
</div>

<script>
let customers = {};

db.ref('orders').on('value', snap => {
    const orders = snap.val() || {};
    customers = {};

    Object.keys(orders).forEach(id => {
        const o = orders[id];
        const phone = (o.phone || '').trim();
        const name = (o.name || 'Unknown').trim();
        if (!phone && !name) return;

        const key = phone || name;
        if (!customers[key]) {
            customers[key] = { name, phone, email: o.email||'', address: o.address||'', orders: 0, totalSpent: 0 };
        }
        customers[key].orders++;
        customers[key].totalSpent += Number(o.total) || 0;
        if (o.email && !customers[key].email) customers[key].email = o.email;
        if (o.address && !customers[key].address) customers[key].address = o.address;
    });

    document.getElementById('totalCustomers').textContent = Object.keys(customers).length;
    document.getElementById('totalOrders').textContent = Object.keys(orders).length;
    filterCustomers();
});

function filterCustomers() {
    const search = (document.getElementById('searchInput').value || '').toLowerCase();
    const tbody = document.getElementById('customersBody');
    let html = '';
    let idx = 0;

    Object.keys(customers).forEach(key => {
        const c = customers[key];
        if (search && !(c.name.toLowerCase().includes(search) || c.phone.toLowerCase().includes(search) || c.email.toLowerCase().includes(search))) return;
        idx++;
        html += `<tr>
            <td>${idx}</td>
            <td><strong>${c.name}</strong></td>
            <td>${c.phone || '-'}</td>
            <td>${c.email || '-'}</td>
            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${c.address || '-'}</td>
            <td><span class="badge badge-placed">${c.orders}</span></td>
            <td style="font-weight:600;color:var(--admin-blue)">Rs.${c.totalSpent.toLocaleString()}</td>
        </tr>`;
    });

    if (idx === 0) {
        html = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-grey)"><i class="fas fa-users" style="font-size:2rem;margin-bottom:8px;display:block"></i>No customers yet</td></tr>';
    }
    tbody.innerHTML = html;
}
</script>

</main>
</div>
</body>
</html>
