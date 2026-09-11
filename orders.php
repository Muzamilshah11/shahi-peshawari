<?php
$pageTitle = 'My Orders';
require_once 'config.php';
require_once 'header.php';
?>

<div class="container">
    <h2 class="section-title"><i class="fas fa-box"></i> My Orders</h2>

    <!-- ─── Phone Lookup Form ─── -->
    <div id="lookupSection" style="max-width:450px;margin-bottom:28px;">
        <p style="font-size:.85rem;color:var(--grey-text);margin-bottom:12px;">
            Enter the phone number you used during checkout to track your orders.
        </p>
        <div style="display:flex;gap:8px;">
            <input type="tel" id="lookupPhone" placeholder="03XX-XXXXXXX"
                   style="flex:1;padding:12px 14px;border:2px solid var(--grey-border);border-radius:var(--radius-sm);font-size:.9rem;">
            <button class="btn-outline" onclick="lookupOrders()">
                <i class="fas fa-search"></i> Track
            </button>
        </div>
    </div>

    <!-- ─── Orders List ─── -->
    <div id="ordersList"></div>

    <!-- ─── Empty ─── -->
    <div class="empty-state" id="emptyOrders" style="display:none">
        <i class="fas fa-inbox"></i>
        <h3>No orders found</h3>
        <p>You haven't placed any orders yet with this number.</p>
        <a href="index.php" class="btn-outline" style="margin-top:16px;display:inline-block">Shop Now</a>
    </div>
</div>

<script>
const STATUS_MAP = {
    placed:     { step: 0, label: 'Placed',     icon: 'fa-check',  class: 'status-placed' },
    processing: { step: 1, label: 'Processing', icon: 'fa-cog',    class: 'status-processing' },
    shipped:    { step: 2, label: 'Shipped',    icon: 'fa-truck',  class: 'status-shipped' },
    delivered:  { step: 3, label: 'Delivered',  icon: 'fa-box-open', class: 'status-delivered' }
};

const STEP_LABELS = ['Placed', 'Processing', 'Shipped'];

function lookupOrders() {
    const phone = document.getElementById('lookupPhone').value.trim().replace(/\s+/g, '');
    if (!phone) { showToast('Enter a phone number'); return; }

    const list = document.getElementById('ordersList');
    const empty = document.getElementById('emptyOrders');
    list.innerHTML = '<p style="color:var(--grey-text);text-align:center;padding:40px;"><i class="fas fa-spinner fa-spin"></i> Loading orders...</p>';
    empty.style.display = 'none';

    /* Listen for orders matching this phone */
    db.ref('orders').orderByChild('customer/phone').equalTo(phone)
        .on('value', snap => {
            list.innerHTML = '';
            if (!snap.exists()) {
                list.innerHTML = '';
                empty.style.display = 'block';
                return;
            }
            empty.style.display = 'none';
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
                        <span class="order-status ${st.class}">${st.label}</span>
                    </div>

                    <!-- 3-Step Progress Tracker -->
                    <div class="progress-tracker">
                        ${STEP_LABELS.map((lbl, i) => {
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
                                <span style="font-weight:600;"><?= CURRENCY ?>${it.price * it.qty}</span>
                            </div>
                        `).join('')}
                        <div class="order-item-row" style="border-top:1px solid var(--grey-border);margin-top:8px;padding-top:8px;font-weight:700;">
                            <span>Total</span>
                            <span style="color:var(--blue);"><?= CURRENCY ?>${order.total}</span>
                        </div>
                    </div>
                </div>`;
            });
        });
}

/* Auto-lookup if phone stored */
const storedPhone = localStorage.getItem('shahi_phone');
if (storedPhone) {
    document.getElementById('lookupPhone').value = storedPhone;
    lookupOrders();
}
</script>

<?php require_once 'footer.php'; ?>
