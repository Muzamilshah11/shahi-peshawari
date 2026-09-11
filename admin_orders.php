<?php
$pageTitle = 'Manage Orders';
require_once 'config.php';
$activePage = 'orders';
require_once 'admin_sidebar.php';
?>

<!-- ─── Login Gate ─── -->
<div id="loginGate" style="display:none; max-width:400px; margin:80px auto; text-align:center;">
    <div style="background:var(--admin-white); border-radius:12px; padding:40px 32px; box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div style="width:60px;height:60px;border-radius:16px;background:var(--admin-black);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fas fa-lock" style="color:#fff;font-size:1.3rem;"></i>
        </div>
        <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:4px;">SHAHI Admin</h2>
        <p style="font-size:.85rem; color:var(--admin-grey); margin-bottom:24px;">Sign in to manage orders</p>
        <input type="email" id="adminEmail" value="<?= ADMIN_EMAIL ?>" placeholder="Admin Email"
               style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem; margin-bottom:12px;">
        <input type="password" id="adminPass" placeholder="Password"
               style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem; margin-bottom:12px;">
        <div id="loginError" style="color:var(--admin-red); font-size:.8rem; margin-bottom:12px; display:none;"></div>
        <button onclick="adminLogin()" id="loginBtn"
                style="width:100%; padding:12px; background:var(--admin-black); color:#fff; border-radius:8px; font-size:.9rem; font-weight:600;">
            Sign In
        </button>
        <p style="font-size:.72rem; color:var(--admin-grey); margin-top:16px;">Default: <?= ADMIN_EMAIL ?> / <?= ADMIN_PASS ?></p>
    </div>
</div>

<!-- ─── Orders Content ─── -->
<div id="ordersContent" style="display:none;">
    <div class="page-header">
        <h1>Orders</h1>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <select id="filterDate" onchange="loadOrders()" style="padding:8px 12px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;">
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
            <select id="filterStatus" onchange="loadOrders()" style="padding:8px 12px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;">
                <option value="all">All Status</option>
                <option value="placed">Placed</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
            </select>
            <button onclick="downloadOrdersPDF()" style="padding:8px 16px;background:var(--admin-blue);color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="admin-table-wrap" style="display:block;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Items</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody">
                <tr><td colspan="10" style="text-align:center;padding:40px;color:var(--admin-grey);">Loading orders...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Order Detail Modal -->
    <div id="orderModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:2000; align-items:center; justify-content:center;">
        <div style="background:var(--admin-white); border-radius:12px; max-width:550px; width:95%; max-height:80vh; overflow-y:auto; padding:28px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3 style="font-size:1.1rem; font-weight:700;">Order Details</h3>
                <button onclick="closeModal()" style="background:none; font-size:1.2rem; color:var(--admin-grey);">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
/* ─── Login Gate ─── */
function checkAdminAuth() {
    if (localStorage.getItem('admin_logged_in') === 'true') {
        document.getElementById('loginGate').style.display = 'none';
        document.getElementById('ordersContent').style.display = 'block';
        document.body.classList.add('admin-authed');
        loadOrders();
    } else {
        document.getElementById('loginGate').style.display = 'block';
        document.getElementById('ordersContent').style.display = 'none';
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
    .then(() => { localStorage.setItem('admin_logged_in', 'true'); checkAdminAuth(); })
    .catch(err => {
        errEl.textContent = err.message || 'Login failed. Try again.';
        errEl.style.display = 'block';
        btn.textContent = 'Sign In';
        btn.disabled = false;
    });
}

/* ─── Load Orders ─── */
function loadOrders() {
    const filterStatus = document.getElementById('filterStatus').value;
    const filterDate = document.getElementById('filterDate').value;
    const tbody = document.getElementById('ordersTableBody');
    const now = Date.now();

    const dateRanges = {
        all: 0,
        today: now - (24 * 60 * 60 * 1000),
        week: now - (7 * 24 * 60 * 60 * 1000),
        month: now - (30 * 24 * 60 * 60 * 1000)
    };
    const minTimestamp = dateRanges[filterDate] || 0;

    db.ref('orders').on('value', snap => {
        const orders = [];
        snap.forEach(child => {
            const o = child.val();
            if (filterStatus !== 'all' && o.status !== filterStatus) return;
            if (minTimestamp > 0 && (o.timestamp || 0) < minTimestamp) return;
            orders.push(o);
        });

        orders.sort((a, b) => (b.timestamp || 0) - (a.timestamp || 0));

        if (orders.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;padding:40px;color:var(--admin-grey);">No orders found</td></tr>';
            return;
        }

        // Payment method icon + label helper
        function payBadge(pm) {
            const map = {
                cod:       { icon: 'fa-money-bill-wave', label: 'COD',       color: '#00b894' },
                easypaisa: { icon: 'fa-mobile-alt',      label: 'EasyPaisa', color: '#6c5ce7' },
                jazzcash:  { icon: 'fa-mobile-alt',      label: 'JazzCash',  color: '#e17055' },
                bank:      { icon: 'fa-university',      label: 'Bank',      color: '#0984e3' }
            };
            const m = map[(pm||'cod').toLowerCase()] || map.cod;
            return `<span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:50px;background:${m.color}18;color:${m.color};font-size:.72rem;font-weight:600;white-space:nowrap;">
                <i class="fas ${m.icon}"></i>${m.label}</span>`;
        }

        tbody.innerHTML = orders.map(o => {
            const d = new Date(o.date);
            const dateStr = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            const itemCount = (o.items || []).reduce((s, i) => s + i.qty, 0);
            const statusClass = 'badge-' + (o.status || 'placed');
            const orderIdEsc = o.orderId.replace(/'/g, "\\'");

            return `<tr>
                <td data-label="#"><strong>${o.orderId}</strong></td>
                <td data-label="Customer">${o.customer?.name || '-'}</td>
                <td data-label="Phone">${o.customer?.phone || '-'}</td>
                <td data-label="Email" style="font-size:.8rem;">${o.customer?.email || '-'}</td>
                <td data-label="Items">${itemCount}</td>
                <td data-label="Payment">${payBadge(o.paymentMethod)}</td>
                <td data-label="Total"><strong><?= CURRENCY ?>${o.total}</strong></td>
                <td data-label="Status"><span class="badge ${statusClass}">${(o.status || 'placed').toUpperCase()}</span></td>
                <td data-label="Date" style="font-size:.8rem;">${dateStr}</td>
                <td data-label="Action">
                    <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                        <select onchange="updateStatus('${orderIdEsc}', this.value)" style="padding:5px 8px;border:1px solid var(--admin-border);border-radius:6px;font-size:.75rem;">
                            <option value="placed" ${o.status==='placed'?'selected':''}>Placed</option>
                            <option value="processing" ${o.status==='processing'?'selected':''}>Processing</option>
                            <option value="shipped" ${o.status==='shipped'?'selected':''}>Shipped</option>
                            <option value="delivered" ${o.status==='delivered'?'selected':''}>Delivered</option>
                        </select>
                        <button onclick="viewOrder('${orderIdEsc}')" title="View Details"
                                style="padding:5px 8px;background:var(--admin-grey-bg);border:1px solid var(--admin-border);border-radius:6px;font-size:.75rem;">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="deleteOrder('${orderIdEsc}')" title="Delete Order"
                                style="padding:5px 8px;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;font-size:.75rem;color:#dc2626;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
        }).join('');
    });
}

/* ─── Update Status ─── */
function updateStatus(orderId, status) {
    db.ref('orders/' + orderId + '/status').set(status)
        .then(() => {
            showToast('Status updated to ' + status.toUpperCase());
        });
}

/* ─── Delete Order ─── */
function deleteOrder(orderId) {
    if (!confirm('Are you sure you want to delete order ' + orderId + '? This cannot be undone.')) return;
    db.ref('orders/' + orderId).remove()
        .then(() => showToast('Order deleted!'));
}

/* ─── View Order Modal ─── */
function viewOrder(orderId) {
    db.ref('orders/' + orderId).once('value', snap => {
        const o = snap.val();
        if (!o) return;

        const d = new Date(o.date);
        const dateStr = d.toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit' });
        const statusClass = 'badge-' + (o.status || 'placed');

        document.getElementById('modalBody').innerHTML = `
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                <div>
                    <p style="font-size:.75rem;color:var(--admin-grey);margin-bottom:2px;">Order ID</p>
                    <p style="font-weight:600;">${o.orderId}</p>
                </div>
                <div>
                    <p style="font-size:.75rem;color:var(--admin-grey);margin-bottom:2px;">Date</p>
                    <p style="font-size:.85rem;">${dateStr}</p>
                </div>
                <div>
                    <p style="font-size:.75rem;color:var(--admin-grey);margin-bottom:2px;">Customer</p>
                    <p style="font-weight:600;">${o.customer?.name || '-'}</p>
                    <p style="font-size:.85rem;">${o.customer?.phone || '-'}</p>
                    <p style="font-size:.8rem;color:var(--admin-grey);">${o.customer?.email || ''}</p>
                </div>
                <div>
                    <p style="font-size:.75rem;color:var(--admin-grey);margin-bottom:2px;">Delivery</p>
                    <p style="font-size:.85rem;">${o.customer?.address || '-'}</p>
                    <p style="font-size:.85rem;">${o.customer?.city || ''}</p>
                </div>
            </div>
            ${o.customer?.note ? `<div style="margin-bottom:16px;padding:12px;background:var(--admin-grey-bg);border-radius:8px;"><p style="font-size:.75rem;color:var(--admin-grey);margin-bottom:4px;">Order Note</p><p style="font-size:.85rem;">${o.customer.note}</p></div>` : ''}
            <h4 style="font-size:.9rem;font-weight:600;margin-bottom:8px;">Items</h4>
            ${(o.items||[]).map(it => {
                const img = it.image ? (it.image.startsWith('http') ? it.image : it.image) : '';
                return `
                <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--admin-border);font-size:.85rem;">
                    ${img ? `<img src="${img}" style="width:48px;height:48px;border-radius:8px;object-fit:cover;background:var(--admin-grey-bg)" onerror="this.style.display='none'">` : ''}
                    <div style="flex:1">
                        <div style="font-weight:600">${it.name}</div>
                        <div style="font-size:.78rem;color:var(--admin-grey)">Qty: ${it.qty} &times; Rs.${it.price}${it.size ? ' &middot; Size: ' + it.size : ''}</div>
                    </div>
                    <strong style="color:var(--admin-blue)">Rs.${it.price * it.qty}</strong>
                </div>`;
            }).join('')}
            <div style="display:flex;justify-content:space-between;padding:10px 0;font-size:.9rem;">
                <span>Subtotal</span><span><?= CURRENCY ?>${o.subtotal}</span>
            </div>
            ${o.discount > 0 ? `<div style="display:flex;justify-content:space-between;padding:4px 0;font-size:.85rem;color:var(--admin-green);">
                <span>Discount (${o.coupon})</span><span>-<?= CURRENCY ?>${o.discount}</span>
            </div>` : ''}
            <div style="display:flex;justify-content:space-between;padding:12px 0 0;font-weight:700;font-size:1.05rem;border-top:2px solid var(--admin-border);margin-top:8px;">
                <span>Total</span><span style="color:var(--admin-blue);"><?= CURRENCY ?>${o.total}</span>
            </div>
            <div style="margin-top:16px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span class="badge ${statusClass}" style="font-size:.8rem;padding:6px 14px;">${(o.status||'placed').toUpperCase()}</span>
                ${(function(){
                    const pm = o.paymentMethod || 'cod';
                    const map = {
                        cod:       { icon: 'fa-money-bill-wave', label: 'Cash on Delivery', color: '#00b894' },
                        easypaisa: { icon: 'fa-mobile-alt',      label: 'EasyPaisa',        color: '#6c5ce7' },
                        jazzcash:  { icon: 'fa-mobile-alt',      label: 'JazzCash',         color: '#e17055' },
                        bank:      { icon: 'fa-university',      label: 'Bank Transfer',    color: '#0984e3' }
                    };
                    const m = map[pm.toLowerCase()] || map.cod;
                    return `<span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:50px;background:${m.color}15;color:${m.color};font-size:.8rem;font-weight:600;border:1px solid ${m.color}30;">
                        <i class="fas ${m.icon}"></i>${m.label}</span>`;
                })()}
            </div>
        `;
        document.getElementById('orderModal').style.display = 'flex';
    });
}

function closeModal() {
    document.getElementById('orderModal').style.display = 'none';
}

document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

/* ─── Toast ─── */
function showToast(msg) {
    let t = document.getElementById('adminToast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'adminToast';
        t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#1a1a1a;color:#fff;padding:12px 24px;border-radius:50px;font-size:.85rem;z-index:9999;opacity:0;transition:opacity .3s;pointer-events:none;font-family:Poppins,sans-serif;';
        document.body.appendChild(t);
    }
    t.textContent = msg;
    t.style.opacity = '1';
    setTimeout(() => { t.style.opacity = '0'; }, 2500);
}

checkAdminAuth();

/* ─── Download Orders PDF ─── */
function downloadOrdersPDF() {
    var filterDate = document.getElementById('filterDate').value;
    var filterStatus = document.getElementById('filterStatus').value;
    var now = Date.now();
    var dateRanges = {
        all: 0,
        today: now - (24 * 60 * 60 * 1000),
        week: now - (7 * 24 * 60 * 60 * 1000),
        month: now - (30 * 24 * 60 * 60 * 1000)
    };
    var minTimestamp = dateRanges[filterDate] || 0;
    var filterLabels = { all: 'All Time', today: 'Today', week: 'This Week', month: 'This Month' };
    var statusLabels = { all: 'All Status', placed: 'Placed', processing: 'Processing', shipped: 'Shipped', delivered: 'Delivered' };
    var payMap = { cod:'COD', easypaisa:'EasyPaisa', jazzcash:'JazzCash', bank:'Bank' };
    var statusColors = { placed:'#f59e0b', processing:'#3b82f6', shipped:'#8b5cf6', delivered:'#10b981' };

    if (typeof html2canvas === 'undefined' || typeof window.jspdf === 'undefined') {
        showToast('PDF libraries loading... Click again in 3 seconds.');
        return;
    }

    showToast('Generating PDF with images...');

    db.ref('orders').once('value', function(snap) {
        var orders = [];
        snap.forEach(function(child) {
            var o = child.val();
            if (filterStatus !== 'all' && o.status !== filterStatus) return;
            if (minTimestamp > 0 && (o.timestamp || 0) < minTimestamp) return;
            orders.push(o);
        });

        if (orders.length === 0) { showToast('No orders to download'); return; }
        orders.sort(function(a, b) { return (b.timestamp || 0) - (a.timestamp || 0); });

        var totalRevenue = 0, totalItems = 0;
        orders.forEach(function(o) {
            totalRevenue += o.total || 0;
            (o.items || []).forEach(function(it) { totalItems += it.qty || 1; });
        });

        var BASE = '<?= BASE_URL ?>/';
        var imgURL = function(p) {
            if (!p) return '';
            p = String(p).trim();
            if (p.startsWith('http')) return p;
            return BASE + p.replace(/^\//, '');
        };

        var orderCards = '';
        orders.forEach(function(o, i) {
            var d = new Date(o.date);
            var dateStr = d.toLocaleDateString('en-US', { month:'short', day:'numeric' }) + ' ' + d.toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' });
            var st = (o.status || 'placed').toUpperCase();
            var stColor = statusColors[o.status] || '#f59e0b';

            var itemsHtml = (o.items || []).map(function(it) {
                var imgSrc = imgURL(it.image);
                var imgTag = imgSrc ? '<img src="' + imgSrc + '" style="width:40px;height:40px;border-radius:6px;object-fit:cover;border:1px solid #e5e7eb" onerror="this.style.display=\'none\'">' : '';
                return '<div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:1px solid #f3f4f6">'
                    + imgTag
                    + '<div style="flex:1;font-size:11px">'
                    + '<div style="font-weight:600">' + it.name + '</div>'
                    + '<div style="color:#6b7280;font-size:10px">Qty: ' + it.qty + (it.size ? ' | Size: ' + it.size : '') + ' | Rs.' + it.price + '</div>'
                    + '</div>'
                    + '<div style="font-weight:700;font-size:11px;color:#0984e3">Rs.' + (it.price * it.qty) + '</div>'
                    + '</div>';
            }).join('');

            orderCards += '<div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:12px;page-break-inside:avoid">'
                + '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid #f3f4f6">'
                + '<div><span style="font-weight:700;font-size:13px;color:#0984e3">' + o.orderId + '</span>'
                + '<span style="margin-left:8px;font-size:10px;background:' + stColor + '22;color:' + stColor + ';padding:2px 8px;border-radius:50px;font-weight:600">' + st + '</span></div>'
                + '<div style="text-align:right"><div style="font-size:10px;color:#6b7280">' + dateStr + '</div>'
                + '<div style="font-weight:700;font-size:13px">' + payMap[o.paymentMethod] + '</div></div>'
                + '</div>'
                + '<div style="display:flex;gap:16px;font-size:11px;margin-bottom:10px;color:#374151">'
                + '<div><strong>' + (o.customer && o.customer.name ? o.customer.name : '-') + '</strong><br>' + (o.customer && o.customer.phone ? o.customer.phone : '-') + '</div>'
                + '<div style="text-align:right;flex-shrink:0"><strong style="font-size:14px;color:#0984e3">Rs.' + (o.total || 0) + '</strong></div>'
                + '</div>'
                + itemsHtml
                + '</div>';
        });

        var reportHtml = '<div id="pdfReport" style="width:800px;font-family:Segoe UI,Arial,sans-serif;color:#1f2937;background:#fff;padding:0">'
            + '<div style="background:linear-gradient(135deg,#0984e3,#0769b5);color:#fff;padding:28px 30px;border-radius:0 0 12px 12px">'
            + '<h1 style="margin:0;font-size:22px;letter-spacing:1px">SHAHI PESHAWARI</h1>'
            + '<p style="margin:4px 0 0;font-size:12px;opacity:.85">Orders Report | ' + filterLabels[filterDate] + ' | ' + statusLabels[filterStatus] + ' | ' + new Date().toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' }) + '</p>'
            + '</div>'
            + '<div style="display:flex;gap:16px;padding:20px 30px;flex-wrap:wrap">'
            + '<div style="flex:1;min-width:120px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:12px 16px;text-align:center"><div style="font-size:10px;color:#6b7280;text-transform:uppercase">Orders</div><div style="font-size:20px;font-weight:700;color:#0984e3">' + orders.length + '</div></div>'
            + '<div style="flex:1;min-width:120px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:12px 16px;text-align:center"><div style="font-size:10px;color:#6b7280;text-transform:uppercase">Items</div><div style="font-size:20px;font-weight:700;color:#0984e3">' + totalItems + '</div></div>'
            + '<div style="flex:1;min-width:120px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:12px 16px;text-align:center"><div style="font-size:10px;color:#6b7280;text-transform:uppercase">Revenue</div><div style="font-size:20px;font-weight:700;color:#0984e3">Rs.' + totalRevenue.toLocaleString() + '</div></div>'
            + '</div>'
            + '<div style="padding:0 30px 30px">' + orderCards + '</div>'
            + '</div>';

        var container = document.createElement('div');
        container.style.cssText = 'position:fixed;left:-9999px;top:0;z-index:-1';
        container.innerHTML = reportHtml;
        document.body.appendChild(container);

        var el = document.getElementById('pdfReport');
        html2canvas(el, { scale: 2, useCORS: true, logging: false, backgroundColor: '#ffffff' }).then(function(canvas) {
            var jsPDF = window.jspdf.jsPDF;
            var pdf = new jsPDF('p', 'mm', 'a4');
            var img = canvas.toDataURL('image/png');
            var w = pdf.internal.pageSize.getWidth();
            var h = (canvas.height * w) / canvas.width;

            var pageCount = Math.ceil(h / 277);
            if (pageCount > 1) {
                for (var p = 0; p < pageCount; p++) {
                    if (p > 0) pdf.addPage();
                    var srcY = (p * 277 * canvas.width) / w;
                    var srcH = Math.min(277, h - p * 277);
                    var tmpCanvas = document.createElement('canvas');
                    tmpCanvas.width = canvas.width;
                    tmpCanvas.height = (srcH * canvas.width) / w;
                    var ctx = tmpCanvas.getContext('2d');
                    ctx.drawImage(canvas, 0, srcY, canvas.width, tmpCanvas.height, 0, 0, canvas.width, tmpCanvas.height);
                    var pageImg = tmpCanvas.toDataURL('image/png');
                    pdf.addImage(pageImg, 'PNG', 0, 10, w, srcH);
                }
            } else {
                pdf.addImage(img, 'PNG', 0, 10, w, h);
            }

            var fileName = 'Shahi_Orders_' + filterLabels[filterDate].replace(/\s+/g, '_') + '_' + new Date().toISOString().slice(0, 10) + '.pdf';
            pdf.save(fileName);
            document.body.removeChild(container);
            showToast('PDF downloaded! (' + orders.length + ' orders)');
        }).catch(function(e) {
            document.body.removeChild(container);
            showToast('PDF error: ' + e.message);
        });
    });
}
</script>

</main>
</div>
</body>
</html>
