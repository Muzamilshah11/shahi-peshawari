<?php
$pageTitle = 'Cart & Checkout';
require_once 'config.php';
require_once 'header.php';
?>

<div class="container">

    <!-- ─── Cart Section ─── -->
    <div id="cartSection">
        <h2 class="section-title"><i class="fas fa-shopping-cart"></i> Your Cart</h2>
        <div id="cartList"></div>
    </div>

    <!-- ─── Checkout (hidden until cart has items) ─── -->
    <div id="checkoutSection" style="display:none">
        <div style="display:grid; grid-template-columns: 1fr 380px; gap:24px; align-items:start;" id="checkoutGrid">

            <!-- Left: Map + Form -->
            <div>
                <!-- Map -->
                <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Delivery Location</h2>
                <p style="font-size:.8rem;color:var(--grey-text);margin-bottom:8px;">
                    <i class="fas fa-map-pin" style="color:var(--blue)"></i>
                    Click on map to set delivery location
                </p>
                <div id="map"></div>

                <!-- Checkout Form -->
                <div class="checkout-form">
                    <h2 class="section-title"><i class="fas fa-user"></i> Checkout Details</h2>
                    <form id="checkoutForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name *</label>
                                <input type="text" id="cName" placeholder="e.g. Ahmad Shah" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number *</label>
                                <input type="tel" id="cPhone" placeholder="03XX-XXXXXXX" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email (optional - for order confirmation)</label>
                            <input type="email" id="cEmail" placeholder="your@email.com">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt" style="color:var(--blue)"></i> Address</label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="text" id="cAddress" placeholder="Enter address or tap Map" style="flex:1">
                                <button type="button" onclick="openMapPicker('cAddress','cCity')" style="padding:10px 14px;border:2px solid var(--blue);border-radius:10px;background:var(--blue);color:#fff;cursor:pointer;white-space:nowrap;font-size:.85rem;display:flex;align-items:center;gap:6px;" title="Pick on map"><i class="fas fa-map"></i> <span>Map</span></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>City / State</label>
                            <input type="text" id="cCity" placeholder="City or Province">
                        </div>
                        <div class="form-group">
                            <label>Order Note (optional)</label>
                            <textarea id="cNote" placeholder="Special instructions for your order..."></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Summary -->
            <div class="summary-box">
                <h3>Order Summary</h3>
                <div id="summaryItems"></div>
                <div class="coupon-row">
                    <input type="text" id="couponInput" placeholder="Coupon code">
                    <button class="btn-coupon" onclick="applyCoupon()">Apply</button>
                </div>
                <div class="coupon-msg" id="couponMsg"></div>
                <div class="summary-row"><span>Subtotal</span><span id="subtotalVal"></span></div>
                <div class="summary-row" id="discountRow" style="display:none;color:var(--green)">
                    <span>Discount</span><span id="discountVal"></span>
                </div>
                <div class="summary-row total"><span>Total</span><span id="totalVal"></span></div>
                <button class="btn-primary" id="placeOrderBtn" onclick="placeOrder()" style="margin-top:16px" disabled>
                    <i class="fas fa-lock"></i> Place Order
                </button>
            </div>
        </div>
    </div>

    <!-- ─── Empty State ─── -->
    <div class="empty-state" id="emptyCart" style="display:none">
        <i class="fas fa-shopping-cart"></i>
        <h3>Your cart is empty</h3>
        <p>Browse our collection and add some premium chappals!</p>
        <a href="index.php" class="btn-outline" style="margin-top:16px;display:inline-block">Shop Now</a>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ─── State ─── */
let appliedCoupon = null;
let discountAmount = 0;
let map, marker;
let userLat = 34.01, userLng = 71.58; // Default: Peshawar

/* ─── Render Cart ─── */
function renderCartPage() {
    const list = document.getElementById('cartList');
    const checkout = document.getElementById('checkoutSection');
    const empty = document.getElementById('emptyCart');
    const cartSection = document.getElementById('cartSection');

    if (cart.length === 0) {
        cartSection.style.display = 'none';
        checkout.style.display = 'none';
        empty.style.display = 'block';
        return;
    }
    cartSection.style.display = 'block';
    checkout.style.display = 'block';
    empty.style.display = 'none';

    list.innerHTML = cart.map(item => `
        <div class="cart-item">
            <img src="${item.image}" alt="${item.name}">
            <div class="item-info">
                <div class="item-name">${item.name}</div>
                ${item.size ? '<div class="item-size" style="font-size:.72rem;color:var(--grey-text);margin-top:2px">Size: ' + item.size + '</div>' : ''}
                <div class="item-price"><?= CURRENCY ?>${item.price}</div>
            </div>
            <div class="qty-controls">
                <button class="qty-btn" onclick="changeQty('${item.id}',-1); renderCartPage();">-</button>
                <span class="qty-val">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty('${item.id}',1); renderCartPage();">+</button>
            </div>
            <button class="btn-remove" onclick="removeFromCart('${item.id}'); renderCartPage();">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `).join('');

    updateSummary();
    validateForm();
}

/* ─── Update Summary ─── */
function updateSummary() {
    const subtotal = getCartTotal();
    const total = subtotal - discountAmount;

    document.getElementById('subtotalVal').textContent = '<?= CURRENCY ?>' + subtotal;
    document.getElementById('totalVal').textContent = '<?= CURRENCY ?>' + total;

    const dRow = document.getElementById('discountRow');
    if (discountAmount > 0) {
        dRow.style.display = 'flex';
        document.getElementById('discountVal').textContent = '-<?= CURRENCY ?>' + discountAmount;
    } else {
        dRow.style.display = 'none';
    }
}

/* ─── Coupon ─── */
function applyCoupon() {
    const code = document.getElementById('couponInput').value.trim().toUpperCase();
    const msg = document.getElementById('couponMsg');
    const subtotal = getCartTotal();
    const coupons = <?= json_encode(COUPONS) ?>;

    if (!code) { msg.className = 'coupon-msg error'; msg.textContent = 'Enter a coupon code'; return; }
    if (!coupons[code]) { msg.className = 'coupon-msg error'; msg.textContent = 'Invalid coupon code'; appliedCoupon = null; discountAmount = 0; updateSummary(); return; }

    const c = coupons[code];
    if (c.type === 'percent') { discountAmount = Math.round(subtotal * c.value / 100); }
    else { discountAmount = Math.min(c.value, subtotal); }
    appliedCoupon = code;
    msg.className = 'coupon-msg success';
    msg.textContent = c.label + ' applied! You save <?= CURRENCY ?>' + discountAmount;
    updateSummary();
}

/* ─── Form Validation ─── */
function validateForm() {
    const name = document.getElementById('cName').value.trim();
    const phone = document.getElementById('cPhone').value.trim();
    document.getElementById('placeOrderBtn').disabled = !(name && phone);
}

document.addEventListener('input', e => {
    if (['cName', 'cPhone'].includes(e.target.id)) validateForm();
});

/* ─── Leaflet Map + Geolocation ─── */
function initMap() {
    map = L.map('map').setView([userLat, userLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    marker = L.marker([userLat, userLng], { draggable: true }).addTo(map);

    marker.on('dragend', function () {
        const pos = marker.getLatLng();
        reverseGeo(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        reverseGeo(e.latlng.lat, e.latlng.lng);
    });

    /* Map ready — user picks location by clicking */
}

function reverseGeo(lat, lng) {
    /* bigdatacloud primary — better for Pakistan */
    fetch('https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=' + lat + '&longitude=' + lng + '&localityLanguage=en')
    .then(function(r) { return r.json(); })
    .then(function(d) {
        var addr = d.localityInfo?.formatted || [];
        var street = d.street || '';
        var city = d.city || d.locality || '';
        var state = d.principalSubdivision || '';

        var goodParts = [];
        for (var i = 0; i < addr.length; i++) {
            var part = addr[i];
            if (!part) continue;
            if (/^[A-Z]?\d{1,2}[A-Z]?$/.test(part.trim())) continue;
            if (/^\d+$/.test(part.trim())) continue;
            goodParts.push(part);
        }

        if (street) {
            if (/^[A-Z]?\d{1,2}[A-Z]?$/.test(street.trim()) || /^\d+$/.test(street.trim())) {
                document.getElementById('cAddress').value = goodParts.slice(0, 3).join(', ') || d.display_name || '';
            } else {
                document.getElementById('cAddress').value = street + (goodParts.length ? ', ' + goodParts.slice(0, 2).join(', ') : '');
            }
        } else {
            document.getElementById('cAddress').value = goodParts.slice(0, 3).join(', ') || d.display_name || '';
        }

        if (city && city.length > 2) {
            document.getElementById('cCity').value = state ? city + ', ' + state : city;
        } else {
            document.getElementById('cCity').value = state || 'Peshawar';
        }
    })
    .catch(function() {
        /* Nominatim fallback */
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1&accept-language=en', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data && data.address) {
                var a = data.address;
                var parts = [a.house_number, a.road, a.pedestrian, a.footway,
                    a.neighbourhood, a.suburb, a.residential, a.commercial,
                    a.quarter, a.hamlet, a.plot, a.village, a.town, a.city_district, a.county].filter(Boolean);
                var cleanParts = [];
                for (var i = 0; i < parts.length; i++) {
                    if (!/^[A-Z]?\d{1,2}[A-Z]?$/.test(parts[i].trim())) cleanParts.push(parts[i]);
                }
                document.getElementById('cAddress').value = cleanParts.slice(0, 3).join(', ') || data.display_name || '';
                var cp = [a.city || a.town || a.village, a.state].filter(Boolean);
                document.getElementById('cCity').value = cp.join(', ') || '';
            } else {
                document.getElementById('cAddress').value = data.display_name || '';
                document.getElementById('cCity').value = '';
            }
        })
        .catch(function() {
            document.getElementById('cAddress').value = '';
            document.getElementById('cCity').value = '';
        });
    });
}

/* ─── Place Order ─── */
function placeOrder() {
    if (cart.length === 0) return;
    const name = document.getElementById('cName').value.trim();
    const phone = document.getElementById('cPhone').value.trim();
    if (!name || !phone) { showToast('Please fill in Name and Phone'); return; }

    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    const subtotal = getCartTotal();
    const total = subtotal - discountAmount;
    const orderId = 'shahi' + Math.floor(1000 + Math.random() * 9000);

    const orderData = {
        orderId: orderId,
        customer: {
            name: name,
            phone: phone,
            email: document.getElementById('cEmail').value.trim(),
            address: document.getElementById('cAddress').value.trim(),
            city: document.getElementById('cCity').value.trim(),
            note: document.getElementById('cNote').value.trim()
        },
        items: cart.map(i => ({ id: i.id, name: i.name, price: i.price, qty: i.qty, size: i.size || '', image: i.image })),
        subtotal: subtotal,
        deliveryFee: 0,
        coupon: appliedCoupon,
        discount: discountAmount,
        total: total,
        status: 'placed',
        timestamp: Date.now(),
        date: new Date().toISOString()
    };

    /* Save to Firebase */
    db.ref('orders/' + orderId).set(orderData)
        .then(() => {
            /* Send email if provided */
            const email = document.getElementById('cEmail').value.trim();
            if (email) {
                fetch('send_order_email.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(orderData)
                }).catch(() => {});
            }

            clearCart();
            showToast('Order placed successfully!');
            setTimeout(() => { window.location.href = 'order_success.php?id=' + orderId; }, 1200);
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock"></i> Place Order';
            showToast('Error placing order. Try again.');
        });
}

/* ─── Init ─── */
document.addEventListener('DOMContentLoaded', function() {
    cart = JSON.parse(localStorage.getItem(CART_KEY) || '[]');
    updateBadge();
    renderCartPage();
    initMap();
});
</script>

<?php require_once 'footer.php'; ?>
