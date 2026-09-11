<?php
$pageTitle = 'Track Order';
require_once 'config.php';
require_once 'header.php';
?>

<div class="container">
    <h2 class="section-title"><i class="fas fa-truck"></i> Track Your Order</h2>

    <!-- Search Form -->
    <div class="track-search">
        <div class="track-tabs">
            <button class="track-tab active" onclick="switchTab('phone')"><i class="fas fa-phone"></i> Phone Number</button>
            <button class="track-tab" onclick="switchTab('id')"><i class="fas fa-hashtag"></i> Order ID</button>
        </div>
        <div class="track-form" id="phoneForm">
            <input type="tel" id="trackPhone" placeholder="03XX-XXXXXXX">
            <button class="btn-track" onclick="trackByPhone()"><i class="fas fa-search"></i> Track</button>
        </div>
        <div class="track-form" id="idForm" style="display:none">
            <input type="text" id="trackOrderId" placeholder="e.g. shahi0123">
            <button class="btn-track" onclick="trackById()"><i class="fas fa-search"></i> Track</button>
        </div>
    </div>

    <!-- Results -->
    <div id="trackResults"></div>
    <div class="empty-state" id="trackEmpty" style="display:none">
        <i class="fas fa-search"></i>
        <h3>No orders found</h3>
        <p>Try a different phone number or order ID.</p>
    </div>
</div>

<script>
const CURRENCY = '<?= CURRENCY ?>';

const STATUS_MAP = {
    placed:     { step: 0, label: 'Placed',     icon: 'fa-check',      color: '#d4a017' },
    processing: { step: 1, label: 'Processing', icon: 'fa-cog',        color: '#636e72' },
    shipped:    { step: 2, label: 'Shipped',    icon: 'fa-truck',      color: '#0984e3' },
    delivered:  { step: 3, label: 'Delivered',  icon: 'fa-box-open',   color: '#00b894' }
};
const STEPS = ['Placed', 'Processing', 'Shipped', 'Delivered'];

function switchTab(tab) {
    document.querySelectorAll('.track-tab').forEach(t => t.classList.remove('active'));
    if (tab === 'phone') {
        document.getElementById('phoneForm').style.display = 'flex';
        document.getElementById('idForm').style.display = 'none';
        document.querySelectorAll('.track-tab')[0].classList.add('active');
    } else {
        document.getElementById('phoneForm').style.display = 'none';
        document.getElementById('idForm').style.display = 'flex';
        document.querySelectorAll('.track-tab')[1].classList.add('active');
    }
}

function trackByPhone() {
    const phone = document.getElementById('trackPhone').value.trim().replace(/\s+/g, '');
    if (!phone) { showToast('Enter a phone number'); return; }
    localStorage.setItem('shahi_phone', phone);
    showLoading();
    db.ref('orders').orderByChild('customer/phone').equalTo(phone).on('value', snap => {
        renderOrders(snap);
    });
}

function trackById() {
    const id = document.getElementById('trackOrderId').value.trim();
    if (!id) { showToast('Enter an order ID'); return; }
    showLoading();
    db.ref('orders/' + id).once('value', snap => {
        if (!snap.exists()) {
            document.getElementById('trackResults').innerHTML = '';
            document.getElementById('trackEmpty').style.display = 'block';
            return;
        }
        const wrapper = { forEach: fn => fn({ val: () => snap.val(), key: id }) };
        renderOrders({ exists: () => true, forEach: fn => fn({ val: () => snap.val(), key: id }) });
    });
}

function showLoading() {
    document.getElementById('trackResults').innerHTML = '<p style="color:var(--grey-text);text-align:center;padding:40px;"><i class="fas fa-spinner fa-spin"></i> Searching...</p>';
    document.getElementById('trackEmpty').style.display = 'none';
}

function renderOrders(snap) {
    const list = document.getElementById('trackResults');
    const empty = document.getElementById('trackEmpty');
    list.innerHTML = '';
    empty.style.display = 'none';

    if (!snap.exists()) { empty.style.display = 'block'; return; }

    const orders = [];
    snap.forEach(child => orders.push(child.val()));
    orders.sort((a, b) => (b.timestamp || 0) - (a.timestamp || 0));

    orders.forEach(order => {
        const st = STATUS_MAP[order.status] || STATUS_MAP.placed;
        const currentStep = st.step;
        const d = new Date(order.date);
        const dateStr = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

        list.innerHTML += `
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-id">${order.orderId}</span>
                    <span class="order-date" style="margin-left:12px;">${dateStr}</span>
                </div>
                <span class="order-status" style="background:${st.color}20;color:${st.color}">${st.label}</span>
            </div>

            <!-- 4-Step Progress Tracker -->
            <div class="progress-tracker">
                ${STEPS.map((lbl, i) => {
                    let cls = '';
                    if (i < currentStep) cls = 'done';
                    else if (i === currentStep) cls = 'active';
                    return '<div class="progress-step">' +
                        '<div class="step-circle ' + cls + '">' +
                        (i < currentStep ? '<i class="fas fa-check"></i>' : (i + 1)) +
                        '</div>' +
                        '<span class="step-label ' + (cls ? 'active' : '') + '">' + lbl + '</span>' +
                        '</div>';
                }).join('')}
            </div>

            <!-- Order Items -->
            <div class="order-items">
                ${(order.items || []).map(it => `
                    <div class="order-item-row">
                        <span>${it.name} × ${it.qty}</span>
                        <span style="font-weight:600;">${CURRENCY}${it.price * it.qty}</span>
                    </div>
                `).join('')}
                <div class="order-item-row" style="border-top:1px solid var(--grey-border);margin-top:8px;padding-top:8px;font-weight:700;">
                    <span>Total</span>
                    <span style="color:var(--blue);">${CURRENCY}${order.total}</span>
                </div>
            </div>

            <!-- Delivery Info -->
            <div style="margin-top:12px;padding:12px;background:var(--grey-bg);border-radius:var(--radius-sm);font-size:.82rem;color:var(--grey-text);">
                <i class="fas fa-map-marker-alt" style="color:var(--blue)"></i>
                ${order.customer?.address || 'N/A'}${order.customer?.city ? ', ' + order.customer.city : ''}
                ${order.paymentMethod ? '<br><i class="fas fa-credit-card" style="color:var(--blue)"></i> Payment: ' + order.paymentMethod.toUpperCase() : ''}
            </div>
        </div>`;
    });
}

/* Auto-load from stored phone */
const storedPhone = localStorage.getItem('shahi_phone');
if (storedPhone) {
    document.getElementById('trackPhone').value = storedPhone;
    trackByPhone();
}
</script>

<?php require_once 'footer.php'; ?>
