<?php
require_once 'config.php';
require_once 'product_data.php';

$productId = $_GET['id'] ?? '';
$product = null;
foreach ($PRODUCTS as $p) {
    if ($p['id'] === $productId) { $product = $p; break; }
}
if (!$product) { header('Location: index.php'); exit; }

$pageTitle = $product['name'];
require_once 'header.php';
?>

<div class="container">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="shop.php?cat=<?= $product['cat'] ?>"><?= ucfirst($product['cat']) ?></a>
        <i class="fas fa-chevron-right"></i>
        <span><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <!-- Product Detail -->
    <div class="pd-grid">

        <!-- Left: Images -->
        <div class="pd-images">
            <div class="pd-main-img" id="pdMainImg">
                <img src="<?= BASE_URL ?>/<?= ltrim($product['images'][0], '/') ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="pdMainImgTag"
                     onerror="this.onerror=null;this.src='data:image/svg+xml,<?= urlencode('<svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" fill="#e0e0e0"><rect width="500" height="500"/><text x="250" y="260" font-family="sans-serif" font-size="18" fill="#999" text-anchor="middle">No Image</text></svg>') ?>'">
            </div>
            <?php if (count($product['images']) > 1): ?>
            <div class="pd-thumbs">
                <?php foreach ($product['images'] as $i => $img): ?>
                <button class="pd-thumb <?= $i === 0 ? 'active' : '' ?>" onclick="changeImg('<?= BASE_URL ?>/<?= ltrim($img, '/') ?>', this)">
                    <img src="<?= BASE_URL ?>/<?= ltrim($img, '/') ?>" alt="View <?= $i + 1 ?>"
                         onerror="this.onerror=null;this.src='data:image/svg+xml,<?= urlencode('<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#e0e0e0"><rect width="80" height="80"/><text x="40" y="44" font-family="sans-serif" font-size="10" fill="#999" text-anchor="middle">N/A</text></svg>') ?>'">
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right: Info -->
        <div class="pd-info">
            <span class="pd-cat"><?= ucfirst($product['cat']) ?> Collection</span>
            <h1 class="pd-title"><?= htmlspecialchars($product['name']) ?></h1>

            <!-- Rating -->
            <?php $rating = (int)($product['rating'] ?? 0); ?>
            <div class="pd-rating">
                <div class="stars">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                        <i class="fas fa-star" style="color:<?= $s <= $rating ? '#f59e0b' : '#d1d5db' ?>"></i>
                    <?php endfor; ?>
                </div>
                <span class="rating-text"><?= $rating > 0 ? $rating . '.0' : 'No rating' ?></span>
            </div>

            <!-- Price -->
            <div class="pd-price">
                <?= CURRENCY ?><?= number_format($product['price']) ?>
                <?php if ($product['oldPrice']): ?>
                <span class="pd-old-price"><?= CURRENCY ?><?= number_format($product['oldPrice']) ?></span>
                <span class="pd-discount">-<?= round((1 - $product['price'] / $product['oldPrice']) * 100) ?>%</span>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <div class="pd-desc">
                <h3>Description</h3>
                <p><?= htmlspecialchars($product['shortDetail']) ?></p>
            </div>

            <!-- Features -->
            <div class="pd-features">
                <div class="pd-feature"><i class="fas fa-check-circle"></i> Premium Quality</div>
                <div class="pd-feature"><i class="fas fa-check-circle"></i> Handcrafted</div>
                <div class="pd-feature"><i class="fas fa-check-circle"></i> Free Delivery over <?= CURRENCY ?>5000</div>
                <div class="pd-feature"><i class="fas fa-check-circle"></i> 7-Day Return</div>
            </div>

            <!-- Quantity -->
            <div class="pd-qty-section">
                <h3>Quantity</h3>
                <div class="pd-qty-controls">
                    <button class="qty-btn" onclick="changePdQty(-1)"><i class="fas fa-minus"></i></button>
                    <span class="qty-val" id="pdQty">1</span>
                    <button class="qty-btn" onclick="changePdQty(1)"><i class="fas fa-plus"></i></button>
                </div>
                <span class="pd-stock" id="pdStock"><i class="fas fa-check-circle"></i> In Stock</span>
            </div>

            <!-- Size Selector -->
            <div class="size-selector" style="margin-top:16px">
                <label>Select Size <span class="required">*</span></label>
                <div class="size-grid" id="pdSizeGrid">
                    <label class="size-option"><input type="radio" name="pdSize" value="7/36"><div class="size-box"><span class="size-uk">7</span><span class="size-eu">EU 36</span></div></label>
                    <label class="size-option"><input type="radio" name="pdSize" value="8/37"><div class="size-box"><span class="size-uk">8</span><span class="size-eu">EU 37</span></div></label>
                    <label class="size-option"><input type="radio" name="pdSize" value="9/38"><div class="size-box"><span class="size-uk">9</span><span class="size-eu">EU 38</span></div></label>
                    <label class="size-option"><input type="radio" name="pdSize" value="9.5/39"><div class="size-box"><span class="size-uk">9.5</span><span class="size-eu">EU 39</span></div></label>
                    <label class="size-option"><input type="radio" name="pdSize" value="10/40"><div class="size-box"><span class="size-uk">10</span><span class="size-eu">EU 40</span></div></label>
                    <label class="size-option"><input type="radio" name="pdSize" value="10.5/41"><div class="size-box"><span class="size-uk">10.5</span><span class="size-eu">EU 41</span></div></label>
                    <label class="size-option"><input type="radio" name="pdSize" value="11/42"><div class="size-box"><span class="size-uk">11</span><span class="size-eu">EU 42</span></div></label>
                    <label class="size-option"><input type="radio" name="pdSize" value="11.5/43"><div class="size-box"><span class="size-uk">11.5</span><span class="size-eu">EU 43</span></div></label>
                    <label class="size-option"><input type="radio" name="pdSize" value="12/44"><div class="size-box"><span class="size-uk">12</span><span class="size-eu">EU 44</span></div></label>
                </div>
                <div class="size-error" id="pdSizeError">Please select a size</div>
            </div>

            <!-- Action Buttons -->
            <div class="pd-actions" style="margin-top:16px">
                <button class="pd-btn-cart" onclick="addToCartWithSize()">
                    <i class="fas fa-cart-plus"></i> Add to Cart
                </button>
                <button class="pd-btn-buy" onclick="openBuyModal()">
                    <i class="fas fa-bolt"></i> Buy Now
                </button>
            </div>
        </div>
    </div>

    <!-- Similar Products -->
    <div class="pd-similar">
        <h2 class="section-title"><i class="fas fa-thumbs-up" style="color:var(--blue);margin-right:8px"></i>Similar Products</h2>
        <div class="product-grid" id="similarGrid"></div>
    </div>

</div>

<!-- ═══════════════════════════════════════
     BUY NOW MODAL
     ═══════════════════════════════════════ -->
<div class="modal-overlay" id="buyModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-shopping-bag"></i> Complete Your Order</h2>
            <button class="modal-close" onclick="closeBuyModal()"><i class="fas fa-times"></i></button>
        </div>

        <div class="modal-body">
            <!-- Order Summary -->
            <div class="modal-summary">
                <div class="modal-product-info">
                    <img src="<?= BASE_URL ?>/<?= ltrim($product['images'][0], '/') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="modal-thumb"
                         onerror="this.onerror=null;this.src='data:image/svg+xml,<?= urlencode('<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#e0e0e0"><rect width="60" height="60"/></svg>') ?>'">
                    <div>
                        <div class="modal-pname"><?= htmlspecialchars($product['name']) ?></div>
                        <div class="modal-pprice"><?= CURRENCY ?><?= number_format($product['price']) ?> x <span id="modalQty">1</span></div>
                    </div>
                </div>
                <div class="modal-total">
                    <span>Total</span>
                    <span id="modalTotal"><?= CURRENCY ?><?= number_format($product['price']) ?></span>
                </div>
            </div>

            <!-- Customer Form -->
            <form id="orderForm" onsubmit="return false;">

                <!-- Size Selector -->
                <div class="size-selector">
                    <label>Select Size <span class="required">*</span></label>
                    <div class="size-grid" id="sizeGrid">
                        <label class="size-option">
                            <input type="radio" name="size" value="7/36">
                            <div class="size-box"><span class="size-uk">7</span><span class="size-eu">EU 36</span></div>
                        </label>
                        <label class="size-option">
                            <input type="radio" name="size" value="8/37">
                            <div class="size-box"><span class="size-uk">8</span><span class="size-eu">EU 37</span></div>
                        </label>
                        <label class="size-option">
                            <input type="radio" name="size" value="9/38">
                            <div class="size-box"><span class="size-uk">9</span><span class="size-eu">EU 38</span></div>
                        </label>
                        <label class="size-option">
                            <input type="radio" name="size" value="9.5/39">
                            <div class="size-box"><span class="size-uk">9.5</span><span class="size-eu">EU 39</span></div>
                        </label>
                        <label class="size-option">
                            <input type="radio" name="size" value="10/40">
                            <div class="size-box"><span class="size-uk">10</span><span class="size-eu">EU 40</span></div>
                        </label>
                        <label class="size-option">
                            <input type="radio" name="size" value="10.5/41">
                            <div class="size-box"><span class="size-uk">10.5</span><span class="size-eu">EU 41</span></div>
                        </label>
                        <label class="size-option">
                            <input type="radio" name="size" value="11/42">
                            <div class="size-box"><span class="size-uk">11</span><span class="size-eu">EU 42</span></div>
                        </label>
                        <label class="size-option">
                            <input type="radio" name="size" value="11.5/43">
                            <div class="size-box"><span class="size-uk">11.5</span><span class="size-eu">EU 43</span></div>
                        </label>
                        <label class="size-option">
                            <input type="radio" name="size" value="12/44">
                            <div class="size-box"><span class="size-uk">12</span><span class="size-eu">EU 44</span></div>
                        </label>
                    </div>
                    <div class="size-error" id="sizeError">Please select a size</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" id="oName" placeholder="Ahmad Shah" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="tel" id="oPhone" placeholder="03XX-XXXXXXX" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email (optional)</label>
                    <input type="email" id="oEmail" placeholder="your@email.com">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt" style="color:var(--blue)"></i> Delivery Address</label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input type="text" id="oAddress" placeholder="Enter address or tap Map" style="flex:1">
                        <button type="button" onclick="openMapPicker('oAddress','oCity')" style="padding:10px 14px;border:2px solid var(--blue);border-radius:10px;background:var(--blue);color:#fff;cursor:pointer;white-space:nowrap;font-size:.85rem;display:flex;align-items:center;gap:6px;" title="Pick on map"><i class="fas fa-map"></i> <span>Map</span></button>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" id="oCity" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label>Order Note (optional)</label>
                        <input type="text" id="oNote" placeholder="Any special instructions...">
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label>Payment Method *</label>
                    <div class="payment-methods">
                        <label class="pay-option active">
                            <input type="radio" name="payMethod" value="cod" checked>
                            <div class="pay-box">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Cash on Delivery</span>
                            </div>
                        </label>
                        <label class="pay-option">
                            <input type="radio" name="payMethod" value="easypaisa">
                            <div class="pay-box">
                                <i class="fas fa-mobile-alt"></i>
                                <span>EasyPaisa</span>
                            </div>
                        </label>
                        <label class="pay-option">
                            <input type="radio" name="payMethod" value="jazzcash">
                            <div class="pay-box">
                                <i class="fas fa-mobile-alt"></i>
                                <span>JazzCash</span>
                            </div>
                        </label>
                        <label class="pay-option">
                            <input type="radio" name="payMethod" value="bank">
                            <div class="pay-box">
                                <i class="fas fa-university"></i>
                                <span>Bank Transfer</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Payment details (shown for non-COD) -->
                <div class="payment-details" id="paymentDetails" style="display:none">
                    <div class="pay-info-box" id="payInfoBox"></div>
                </div>

                <button class="pd-btn-confirm" id="confirmBtn" onclick="confirmOrder()">
                    <i class="fas fa-check-circle"></i> Confirm Order
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Map (hidden, for geolocation) -->
<div id="map" style="display:none"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const PRODUCT_STATIC = <?= json_encode($product) ?>;
let PRODUCT = PRODUCT_STATIC;

/* Load from Firebase (override static if available) */
db.ref('products/' + PRODUCT_STATIC.id).once('value', snap => {
    const fb = snap.val();
    if (fb) {
        PRODUCT = {
            id: PRODUCT_STATIC.id,
            name: fb.name || PRODUCT_STATIC.name,
            cat: fb.cat || PRODUCT_STATIC.cat,
            price: fb.price || PRODUCT_STATIC.price,
            oldPrice: fb.oldPrice || PRODUCT_STATIC.oldPrice,
            stock: fb.stock !== undefined ? fb.stock : 999,
            images: fb.images && fb.images.length ? fb.images : PRODUCT_STATIC.images,
            shortDetail: fb.shortDetail || PRODUCT_STATIC.shortDetail
        };
    }
});
const ALL_PRODUCTS = <?= json_encode($PRODUCTS) ?>;
const BASE = '<?= BASE_URL ?>/';
const CURRENCY = '<?= CURRENCY ?>';

/* ─── Image Gallery ─── */
function changeImg(src, btn) {
    document.getElementById('pdMainImgTag').src = src;
    document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
}

/* ─── Update stock display ─── */
function updateStockDisplay() {
    const el = document.getElementById('pdStock');
    if (!el || !PRODUCT) return;
    const stock = PRODUCT.stock !== undefined ? PRODUCT.stock : 999;
    if (stock === 0) {
        el.innerHTML = '<i class="fas fa-times-circle"></i> Out of Stock';
        el.style.color = 'var(--red)';
    } else if (stock <= 5) {
        el.innerHTML = '<i class="fas fa-exclamation-circle"></i> Only ' + stock + ' left';
        el.style.color = '#d4a017';
    } else {
        el.innerHTML = '<i class="fas fa-check-circle"></i> In Stock';
        el.style.color = 'var(--green)';
    }
}
setTimeout(updateStockDisplay, 500);

/* ─── Quantity ─── */
let qty = 1;
function changePdQty(delta) {
    qty = Math.max(1, Math.min(99, qty + delta));
    document.getElementById('pdQty').textContent = qty;
    document.getElementById('modalQty').textContent = qty;
    document.getElementById('modalTotal').textContent = CURRENCY + (PRODUCT.price * qty).toLocaleString();
}

/* ─── Buy Modal ─── */
function addToCartWithSize() {
    const sizeInput = document.querySelector('input[name="pdSize"]:checked');
    const sizeError = document.getElementById('pdSizeError');
    if (!sizeInput) {
        sizeError.style.display = 'block';
        document.getElementById('pdSizeGrid').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    sizeError.style.display = 'none';
    addToCart(PRODUCT.id, PRODUCT.name, PRODUCT.price, PRODUCT.images[0] ? BASE + PRODUCT.images[0] : '', PRODUCT.stock || 999, sizeInput.value);
}
function openBuyModal() {
    document.getElementById('buyModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeBuyModal() {
    document.getElementById('buyModal').classList.remove('show');
    document.body.style.overflow = '';
}

/* ─── Payment Method Toggle ─── */
document.getElementById('buyModal').addEventListener('click', function(e) {
    const opt = e.target.closest('.pay-option');
    if (!opt) return;
    const input = opt.querySelector('input[type="radio"]');
    if (!input) return;
    input.checked = true;
    document.querySelectorAll('#buyModal .pay-option').forEach(o => o.classList.remove('active'));
    opt.classList.add('active');
    const val = input.value;
    const details = document.getElementById('paymentDetails');
    const box = document.getElementById('payInfoBox');
    if (val === 'cod') {
        details.style.display = 'none';
    } else {
        details.style.display = 'block';
        box.innerHTML = '<div style="text-align:center;color:#888;padding:10px"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
        if (typeof getPaymentMethods === 'function') {
            getPaymentMethods().then(function() {
                box.innerHTML = buildPayInfoHTML(val);
            }).catch(function() {
                box.innerHTML = '<div style="text-align:center;color:#888">Payment info not available</div>';
            });
        } else {
            box.innerHTML = '<div style="text-align:center;color:#888">Payment info not configured</div>';
        }
    }
});

/* ─── User picks location via Map button ─── */

/* ─── Confirm Order ─── */
function confirmOrder() {
    const name = document.getElementById('oName').value.trim();
    const phone = document.getElementById('oPhone').value.trim();
    if (!name || !phone) { showToast('Please fill Name and Phone'); return; }

    const sizeInput = document.querySelector('input[name="size"]:checked');
    const sizeError = document.getElementById('sizeError');
    if (!sizeInput) {
        sizeError.style.display = 'block';
        document.getElementById('sizeGrid').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    sizeError.style.display = 'none';
    const selectedSize = sizeInput.value;

    const btn = document.getElementById('confirmBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    const payMethod = document.querySelector('input[name="payMethod"]:checked').value;
    const total = PRODUCT.price * qty;
    const orderId = 'shahi' + Math.floor(1000 + Math.random() * 9000);

    const orderData = {
        orderId,
        customer: {
            name, phone,
            email: document.getElementById('oEmail').value.trim(),
            address: document.getElementById('oAddress').value.trim(),
            city: document.getElementById('oCity').value.trim(),
            note: document.getElementById('oNote').value.trim()
        },
        items: [{ id: PRODUCT.id, name: PRODUCT.name, price: PRODUCT.price, qty: qty, size: selectedSize, image: PRODUCT.images[0] || '' }],
        subtotal: total,
        deliveryFee: 0,
        coupon: null,
        discount: 0,
        total: total,
        paymentMethod: payMethod,
        status: 'placed',
        timestamp: Date.now(),
        date: new Date().toISOString()
    };

    db.ref('orders/' + orderId).set(orderData)
        .then(() => {
            const email = document.getElementById('oEmail').value.trim();
            if (email) {
                fetch('send_order_email.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(orderData)
                }).catch(() => {});
            }
            addToCart(PRODUCT.id, PRODUCT.name, PRODUCT.price, PRODUCT.images[0] ? BASE + PRODUCT.images[0] : '', PRODUCT.stock || 999, selectedSize);
            showToast('Order placed successfully!');
            setTimeout(() => { window.location.href = 'order_success.php?id=' + orderId; }, 1200);
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Confirm Order';
            showToast('Error placing order. Try again.');
        });
}

/* ─── Similar Products ─── */
(function() {
    const similar = ALL_PRODUCTS.filter(p => p.cat === PRODUCT.cat && p.id !== PRODUCT.id).slice(0, 4);
    const section = document.querySelector('.pd-similar');
    if (similar.length === 0) {
        if (section) section.style.display = 'none';
        return;
    }
    const placeholder = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" fill="%23e0e0e0"><rect width="300" height="300"/><text x="150" y="160" font-family="sans-serif" font-size="14" fill="%23999" text-anchor="middle">No Image</text></svg>');
    document.getElementById('similarGrid').innerHTML = similar.map(p => {
        var imgs = p.images || [];
        if (imgs && typeof imgs === 'object' && !Array.isArray(imgs)) imgs = Object.values(imgs);
        var src = (imgs[0] && imgs[0].startsWith('http')) ? imgs[0] : (imgs[0] ? BASE + imgs[0].replace(/^\//, '') : placeholder);
        return '<div class="product-card" onclick="window.location.href=\'product.php?id=' + p.id + '\'" style="cursor:pointer">'
            + '<div class="img-wrap" style="position:relative">'
            + '<img src="' + src + '" alt="' + p.name + '" loading="lazy" onerror="this.onerror=null;this.src=\'' + placeholder + '\'">'
            + '<button class="heart-btn" data-id="' + p.id + '" data-name="' + (p.name||'').replace(/"/g,'&quot;') + '" data-price="' + p.price + '" data-img="' + src + '" title="Add to Cart"><i class="fas fa-heart"></i></button>'
            + '</div>'
            + '<div class="card-body">'
            + '<span class="card-cat">' + p.cat + '</span>'
            + '<h3 class="card-title">' + p.name + '</h3>'
            + '<div class="card-price">' + CURRENCY + p.price.toLocaleString()
            + (p.oldPrice ? ' <span class="old-price">' + CURRENCY + p.oldPrice.toLocaleString() + '</span>' : '')
            + '</div></div></div>';
    }).join('');
})();
</script>

<?php require_once 'footer.php'; ?>
