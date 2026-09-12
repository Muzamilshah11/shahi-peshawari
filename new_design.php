<?php
$pageTitle = 'New Design & Modern Collection';
require_once 'config.php';
require_once 'product_data.php';
require_once 'header.php';

// Filter modern & new design products
$modernProducts = array_values(array_filter($PRODUCTS, function($p) {
    return ($p['cat'] ?? '') === 'modern';
}));
?>

<div class="container">

    <!-- Breadcrumb -->
    <div class="breadcrumb" style="margin-bottom:14px;">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>New Design Collection</span>
    </div>

    <!-- Modern Luxury Hero Banner -->
    <div style="background:linear-gradient(135deg,#0d1117 0%,#161b22 50%,#1f2937 100%);border-radius:var(--radius);padding:40px 32px;color:#fff;margin-bottom:28px;position:relative;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.25);border:1px solid rgba(255,255,255,.08);">
        <div style="position:absolute;right:-15px;top:-25px;opacity:.05;font-size:14rem;pointer-events:none;"><i class="fas fa-wand-magic-sparkles"></i></div>
        <div style="max-width:620px;position:relative;z-index:1;">
            <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;background:rgba(212,160,23,.18);border:1px solid rgba(212,160,23,.4);color:#ffd700;border-radius:50px;font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;margin-bottom:14px;">
                <i class="fas fa-sparkles"></i> 2026 Trendsetter Edition
            </div>
            <h1 style="font-size:2.2rem;font-weight:800;margin-bottom:10px;line-height:1.2;background:linear-gradient(135deg,#ffffff 0%,#dcdde1 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Contemporary Artistry & Modern Silhouettes</h1>
            <p style="font-size:.9rem;color:#9ca3af;line-height:1.7;">Step into the future of ethnic footwear. Redefining the legendary Peshawari Chappal with bold contemporary hues—Forest Green, Indigo, Midnight Blue, Slate Grey, Burgundy, and Two-Tone finishes.</p>
        </div>
    </div>

    <!-- Modern Design Innovation Pillars -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:32px;" class="trust-grid">
        <div style="background:#fff;border-radius:var(--radius);padding:20px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow);border:1px solid var(--grey-border);">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#2d3436,#636e72);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;flex-shrink:0;">
                <i class="fas fa-palette"></i>
            </div>
            <div>
                <h4 style="font-size:.92rem;font-weight:700;color:var(--black);">Bold New Palettes</h4>
                <p style="font-size:.76rem;color:var(--grey-text);margin-top:2px;">Distinctive rich dyes & dual tones</p>
            </div>
        </div>
        <div style="background:#fff;border-radius:var(--radius);padding:20px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow);border:1px solid var(--grey-border);">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0984e3,#00cec9);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;flex-shrink:0;">
                <i class="fas fa-feather-pointed"></i>
            </div>
            <div>
                <h4 style="font-size:.92rem;font-weight:700;color:var(--black);">Lightweight Memory Sole</h4>
                <p style="font-size:.76rem;color:var(--grey-text);margin-top:2px;">Ergonomic comfort for active wear</p>
            </div>
        </div>
        <div style="background:#fff;border-radius:var(--radius);padding:20px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow);border:1px solid var(--grey-border);">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#d63031,#e17055);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;flex-shrink:0;">
                <i class="fas fa-cut"></i>
            </div>
            <div>
                <h4 style="font-size:.92rem;font-weight:700;color:var(--black);">Precision Craftsmanship</h4>
                <p style="font-size:.76rem;color:var(--grey-text);margin-top:2px;">Hand-buffed edges & sleek cutouts</p>
            </div>
        </div>
    </div>

    <!-- Section Heading -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
        <div>
            <h2 style="font-size:1.35rem;font-weight:800;color:var(--black);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-bolt" style="color:var(--blue);"></i> Available Modern Designs
            </h2>
            <p style="font-size:.82rem;color:var(--grey-text);margin-top:2px;">Exclusive limited handcrafted batches (<?= count($modernProducts) ?> models)</p>
        </div>
        <div style="font-size:.8rem;color:var(--blue);font-weight:600;background:var(--blue-light);padding:6px 14px;border-radius:50px;">
            <i class="fas fa-fire"></i> High Demand
        </div>
    </div>

    <!-- Products Grid -->
    <div class="product-grid" style="margin-bottom:48px;">
        <?php foreach ($modernProducts as $p):
            $imgs = is_array($p['images']) ? $p['images'] : [$p['images']];
            $src1 = !empty($imgs[0]) ? (str_starts_with($imgs[0], 'http') ? $imgs[0] : BASE_URL . '/' . ltrim($imgs[0], '/')) : '';
            $src2 = !empty($imgs[1]) ? (str_starts_with($imgs[1], 'http') ? $imgs[1] : BASE_URL . '/' . ltrim($imgs[1], '/')) : $src1;
            $allImgs = implode('|', array_map(function($im) { return str_starts_with($im, 'http') ? $im : BASE_URL . '/' . ltrim($im, '/'); }, $imgs));
            $pName = htmlspecialchars($p['name'] ?? '', ENT_QUOTES);
            $pDetail = htmlspecialchars($p['shortDetail'] ?? '', ENT_QUOTES);
            $price = (int)($p['price'] ?? 0);
            $oldPrice = !empty($p['oldPrice']) ? (int)$p['oldPrice'] : 0;
        ?>
        <div class="product-card" data-id="<?= $p['id'] ?>" data-name="<?= $pName ?>" data-cat="modern" style="border:1px solid rgba(9,132,227,.15);position:relative;">
            
            <!-- Modern Badge -->
            <div style="position:absolute;top:10px;left:10px;z-index:5;background:linear-gradient(135deg,#0984e3,#00cec9);color:#fff;font-size:.66rem;font-weight:700;padding:3px 9px;border-radius:50px;letter-spacing:.5px;box-shadow:0 2px 6px rgba(0,0,0,.15);">
                NEW DESIGN
            </div>

            <div class="img-wrap" style="position:relative">
                <img src="<?= $src1 ?>" alt="<?= $pName ?>" loading="lazy" data-src2="<?= $src2 ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\' fill=\'%23e0e0e0\'%3E%3Crect width=\'300\' height=\'300\'/%3E%3Ctext x=\'150\' y=\'160\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%23999\' text-anchor=\'middle\'%3ENo Image%3C/text%3E%3C/svg%3E'">
                <button class="heart-btn" data-id="<?= $p['id'] ?>" data-name="<?= $pName ?>" data-price="<?= $price ?>" data-img="<?= $src1 ?>" title="Add to Cart"><i class="fas fa-heart"></i></button>
            </div>
            <div class="card-body">
                <span class="card-cat" style="color:#0984e3;font-weight:700;">Modern Series</span>
                <h3 class="card-title" title="<?= $pName ?>"><?= $pName ?></h3>
                <p style="font-size:.72rem;color:var(--grey-text);margin:4px 0 6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= $pDetail ?></p>
                <div class="card-price">Rs.<?= number_format($price) ?>
                    <?php if ($oldPrice > 0): ?><span class="old-price">Rs.<?= number_format($oldPrice) ?></span><?php endif; ?>
                </div>
                <button class="btn-buy-card" data-id="<?= $p['id'] ?>" data-name="<?= $pName ?>" data-price="<?= $price ?>" data-imgs="<?= $allImgs ?>"><i class="fas fa-bolt"></i> Buy Now</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- Quick Buy Modal -->
<div class="modal-overlay" id="quickBuyModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-shopping-bag"></i> Quick Order - Modern Collection</h2>
            <button class="modal-close" onclick="closeQuickBuy()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="modal-slider" id="qbSlider"></div>
            <div style="margin-bottom:16px">
                <div class="modal-pname" id="qbName" style="font-size:1rem;font-weight:700;margin-bottom:4px"></div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="modal-pprice"><span id="qbPrice"></span> x <span id="qbQty">1</span></div>
                    <div class="modal-total" style="border:none;padding:0"><span>Total</span><span id="qbTotal"></span></div>
                </div>
            </div>
            <div class="pd-qty-controls" style="margin-bottom:16px">
                <button class="qty-btn" onclick="changeQbQty(-1)"><i class="fas fa-minus"></i></button>
                <span class="qty-val" id="qbQty2">1</span>
                <button class="qty-btn" onclick="changeQbQty(1)"><i class="fas fa-plus"></i></button>
            </div>
            <form onsubmit="return false;">
                <div class="size-selector">
                    <label>Select Size <span class="required">*</span></label>
                    <div class="size-grid" id="qbSizeGrid">
                        <label class="size-option"><input type="radio" name="qbSize" value="7/36"><div class="size-box"><span class="size-uk">7</span><span class="size-eu">EU 36</span></div></label>
                        <label class="size-option"><input type="radio" name="qbSize" value="8/37"><div class="size-box"><span class="size-uk">8</span><span class="size-eu">EU 37</span></div></label>
                        <label class="size-option"><input type="radio" name="qbSize" value="9/38"><div class="size-box"><span class="size-uk">9</span><span class="size-eu">EU 38</span></div></label>
                        <label class="size-option"><input type="radio" name="qbSize" value="9.5/39"><div class="size-box"><span class="size-uk">9.5</span><span class="size-eu">EU 39</span></div></label>
                        <label class="size-option"><input type="radio" name="qbSize" value="10/40"><div class="size-box"><span class="size-uk">10</span><span class="size-eu">EU 40</span></div></label>
                        <label class="size-option"><input type="radio" name="qbSize" value="10.5/41"><div class="size-box"><span class="size-uk">10.5</span><span class="size-eu">EU 41</span></div></label>
                        <label class="size-option"><input type="radio" name="qbSize" value="11/42"><div class="size-box"><span class="size-uk">11</span><span class="size-eu">EU 42</span></div></label>
                        <label class="size-option"><input type="radio" name="qbSize" value="11.5/43"><div class="size-box"><span class="size-uk">11.5</span><span class="size-eu">EU 43</span></div></label>
                        <label class="size-option"><input type="radio" name="qbSize" value="12/44"><div class="size-box"><span class="size-uk">12</span><span class="size-eu">EU 44</span></div></label>
                    </div>
                    <div class="size-error" id="qbSizeError">Please select a size</div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Full Name *</label><input type="text" id="qbOName" placeholder="Ahmad Shah" required></div>
                    <div class="form-group"><label>Phone *</label><input type="tel" id="qbOPhone" placeholder="03XX-XXXXXXX" required></div>
                </div>
                <div class="form-group"><label>Email (optional)</label><input type="email" id="qbOEmail" placeholder="your@email.com"></div>
                <div class="form-group"><label><i class="fas fa-map-marker-alt" style="color:var(--blue)"></i> Address</label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input type="text" id="qbAddress" placeholder="Enter address or tap Map" style="flex:1">
                        <button type="button" onclick="openMapPicker('qbAddress','qbCity')" style="padding:10px 14px;border:2px solid var(--blue);border-radius:10px;background:var(--blue);color:#fff;cursor:pointer;white-space:nowrap;font-size:.85rem;display:flex;align-items:center;gap:6px;" title="Pick on map"><i class="fas fa-map"></i> <span>Map</span></button>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>City</label><input type="text" id="qbCity" placeholder="City"></div>
                    <div class="form-group"><label>Note (optional)</label><input type="text" id="qbONote" placeholder="Instructions..."></div>
                </div>
                <div class="form-group">
                    <label>Payment Method *</label>
                    <div class="payment-methods" id="qbPayMethods" style="grid-template-columns:1fr 1fr;gap:10px;">
                        <div class="pay-option active" data-method="cod">
                            <div class="pay-box" style="padding:14px 8px;">
                                <i class="fas fa-money-bill-wave" style="color:var(--blue);font-size:1.5rem;margin-bottom:6px;"></i>
                                <span style="font-weight:600;display:block;">Cash on Delivery</span>
                                <span style="font-size:.72rem;color:var(--grey-text);">Pay when delivered</span>
                            </div>
                        </div>
                        <div class="pay-option" data-method="easypaisa">
                            <div class="pay-box" style="padding:14px 8px;">
                                <i class="fas fa-mobile-alt" style="font-size:1.5rem;margin-bottom:6px;"></i>
                                <span style="font-weight:600;display:block;">EasyPaisa</span>
                                <span style="font-size:.72rem;color:var(--grey-text);">Mobile Payment</span>
                            </div>
                        </div>
                        <div class="pay-option" data-method="jazzcash">
                            <div class="pay-box" style="padding:14px 8px;">
                                <i class="fas fa-mobile-alt" style="font-size:1.5rem;margin-bottom:6px;"></i>
                                <span style="font-weight:600;display:block;">JazzCash</span>
                                <span style="font-size:.72rem;color:var(--grey-text);">Mobile Payment</span>
                            </div>
                        </div>
                        <div class="pay-option" data-method="bank">
                            <div class="pay-box" style="padding:14px 8px;">
                                <i class="fas fa-university" style="font-size:1.5rem;margin-bottom:6px;"></i>
                                <span style="font-weight:600;display:block;">Bank Transfer</span>
                                <span style="font-size:.72rem;color:var(--grey-text);">Direct Transfer</span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="payMethod" id="qbPayMethod" value="cod">
                </div>
                <div class="payment-details" id="qbPayDetails" style="display:none"><div class="pay-info-box" id="qbPayInfo"></div></div>
                <button class="pd-btn-confirm" id="qbConfirmBtn" onclick="confirmQuickBuy()"><i class="fas fa-check-circle"></i> Confirm Order</button>
            </form>
        </div>
    </div>
</div>

<script>
const BASE = '<?= BASE_URL ?>/';

// Card click & hover handlers
document.addEventListener('mouseenter', function(e) {
    var card = e.target.closest('.product-card');
    if (!card) return;
    var img = card.querySelector('.img-wrap img');
    var s2 = img && img.dataset.src2;
    if (img && s2 && s2 !== img.src) { img.style.opacity = '0'; setTimeout(function() { img.src = s2; img.style.opacity = '1'; }, 150); }
}, true);

document.addEventListener('mouseleave', function(e) {
    var card = e.target.closest('.product-card');
    if (!card) return;
    var img = card.querySelector('.img-wrap img');
    var s1 = img && img.src;
    var s2 = img && img.dataset.src2;
    if (img && s2 && s1 !== s2) { img.style.opacity = '0'; setTimeout(function() { img.style.opacity = '1'; }, 150); }
}, true);

document.addEventListener('click', function(e) {
    if (e.target.closest('.heart-btn') || e.target.closest('.btn-buy-card')) return;
    var card = e.target.closest('.product-card');
    if (card && card.dataset.id) {
        window.location.href = 'product.php?id=' + card.dataset.id;
    }
});

document.addEventListener('click', function(e) {
    var btn = e.target.closest('.heart-btn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    addToCart(btn.dataset.id, btn.dataset.name, Number(btn.dataset.price), btn.dataset.img);
    btn.classList.add('added');
    setTimeout(function() { btn.classList.remove('added'); }, 800);
});

document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-buy-card');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    var images = (btn.dataset.imgs || '').split('|').filter(Boolean);
    openQuickBuy(btn.dataset.id, btn.dataset.name, Number(btn.dataset.price), images);
});

let quickBuyProduct = null;
let qbSliderIndex = 0;
let qbSliderTimer = null;

function openQuickBuy(id, name, price, images) {
    quickBuyProduct = { id, name, price, images };
    document.getElementById('qbName').textContent = name;
    document.getElementById('qbPrice').textContent = '<?= CURRENCY ?>' + price.toLocaleString();
    document.getElementById('qbQty').textContent = '1';
    document.getElementById('qbQty2').textContent = '1';
    document.getElementById('qbTotal').textContent = '<?= CURRENCY ?>' + price.toLocaleString();

    const slider = document.getElementById('qbSlider');
    if (!images.length) images = [''];
    slider.innerHTML = images.map((src, i) =>
        `<img src="${src}" alt="${name}" class="${i===0?'active':''}" onerror="this.style.display='none'">`
    ).join('') +
    (images.length > 1 ? `
        <button class="slider-arrow prev" onclick="qbSlide(-1)"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-arrow next" onclick="qbSlide(1)"><i class="fas fa-chevron-right"></i></button>
        <div class="slider-dots">${images.map((_,i) => `<button class="slider-dot ${i===0?'active':''}" onclick="qbGoSlide(${i})"></button>`).join('')}</div>
    ` : '');

    qbSliderIndex = 0;
    clearInterval(qbSliderTimer);
    if (images.length > 1) {
        qbSliderTimer = setInterval(() => qbSlide(1), 3500);
    }

    document.getElementById('quickBuyModal').classList.add('show');
    document.body.style.overflow = 'hidden';

    /* User picks location via Map button */
}

function qbSlide(dir) {
    const imgs = document.querySelectorAll('#qbSlider img');
    const dots = document.querySelectorAll('#qbSlider .slider-dot');
    if (!imgs.length) return;
    qbSliderIndex = (qbSliderIndex + dir + imgs.length) % imgs.length;
    imgs.forEach((im, i) => im.classList.toggle('active', i === qbSliderIndex));
    dots.forEach((d, i) => d.classList.toggle('active', i === qbSliderIndex));
}
function qbGoSlide(i) {
    const imgs = document.querySelectorAll('#qbSlider img');
    const dots = document.querySelectorAll('#qbSlider .slider-dot');
    qbSliderIndex = i;
    imgs.forEach((im, j) => im.classList.toggle('active', j === i));
    dots.forEach((d, j) => d.classList.toggle('active', j === i));
}

function closeQuickBuy() {
    document.getElementById('quickBuyModal').classList.remove('show');
    document.body.style.overflow = '';
    clearInterval(qbSliderTimer);
}

let qbQty = 1;
function changeQbQty(delta) {
    qbQty = Math.max(1, Math.min(99, qbQty + delta));
    document.getElementById('qbQty').textContent = qbQty;
    document.getElementById('qbQty2').textContent = qbQty;
    if (quickBuyProduct) {
        document.getElementById('qbTotal').textContent = '<?= CURRENCY ?>' + (quickBuyProduct.price * qbQty).toLocaleString();
    }
}

document.getElementById('qbPayMethods').addEventListener('click', function(e) {
    var opt = e.target.closest('.pay-option');
    if (!opt) return;
    var val = opt.dataset.method;
    document.querySelectorAll('#qbPayMethods .pay-option').forEach(function(o) {
        o.classList.remove('active');
        var icon = o.querySelector('.pay-box i');
        if (icon) icon.style.color = '';
    });
    opt.classList.add('active');
    var selIcon = opt.querySelector('.pay-box i');
    if (selIcon) selIcon.style.color = 'var(--blue)';
    document.getElementById('qbPayMethod').value = val;
    var details = document.getElementById('qbPayDetails');
    var box = document.getElementById('qbPayInfo');
    if (val === 'cod') { details.style.display = 'none'; }
    else {
        details.style.display = 'block';
        var qrSize = window.innerWidth <= 480 ? 200 : 180;
        var info = {
            easypaisa: '<div style="text-align:center"><strong style="font-size:1rem">EasyPaisa</strong><br><img src="https://api.qrserver.com/v1/create-qr-code/?size=' + qrSize + 'x' + qrSize + '&data=upaisa://0300-1234567?amount=" alt="EasyPaisa QR" style="width:' + qrSize + 'px;height:' + qrSize + 'px;border-radius:12px;margin:12px auto;border:2px solid #eee"><br>Send to: <b style="font-size:1.05rem">0300-1234567</b><br>Account: Shahi Peshawari<br><span style="font-size:.78rem;color:#888">Scan QR or send on above number</span></div>',
            jazzcash: '<div style="text-align:center"><strong style="font-size:1rem">JazzCash</strong><br><img src="https://api.qrserver.com/v1/create-qr-code/?size=' + qrSize + 'x' + qrSize + '&data=jazzcash://0300-7654321?amount=" alt="JazzCash QR" style="width:' + qrSize + 'px;height:' + qrSize + 'px;border-radius:12px;margin:12px auto;border:2px solid #eee"><br>Send to: <b style="font-size:1.05rem">0300-7654321</b><br>Account: Shahi Peshawari<br><span style="font-size:.78rem;color:#888">Scan QR or send on above number</span></div>',
            bank: '<div style="text-align:center"><strong style="font-size:1rem">Bank Transfer</strong><br><img src="https://api.qrserver.com/v1/create-qr-code/?size=' + qrSize + 'x' + qrSize + '&data=bank://HBL/1234-5678-9012?amount=" alt="Bank QR" style="width:' + qrSize + 'px;height:' + qrSize + 'px;border-radius:12px;margin:12px auto;border:2px solid #eee"><br>Bank: HBL<br>Account: <b>1234-5678-9012</b><br>Title: Shahi Peshawari<br><span style="font-size:.78rem;color:#888">Scan QR or transfer to above account</span></div>'
        };
        box.innerHTML = info[val] || '';
    }
});

function confirmQuickBuy() {
    const name = document.getElementById('qbOName').value.trim();
    const phone = document.getElementById('qbOPhone').value.trim();
    if (!name || !phone) { showToast('Please fill Name and Phone'); return; }
    if (!quickBuyProduct) return;

    const sizeInput = document.querySelector('#quickBuyModal input[name="qbSize"]:checked');
    const sizeError = document.getElementById('qbSizeError');
    if (!sizeInput) {
        sizeError.style.display = 'block';
        document.getElementById('qbSizeGrid').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    sizeError.style.display = 'none';
    const selectedSize = sizeInput.value;

    const btn = document.getElementById('qbConfirmBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    const payMethod = document.getElementById('qbPayMethod').value;
    const total = quickBuyProduct.price * qbQty;
    const orderId = 'shahi' + Math.floor(1000 + Math.random() * 9000);

    const orderData = {
        orderId,
        customer: {
            name, phone,
            email: document.getElementById('qbOEmail').value.trim(),
            address: document.getElementById('qbAddress').value.trim(),
            city: document.getElementById('qbCity').value.trim(),
            note: document.getElementById('qbONote').value.trim()
        },
        items: [{ id: quickBuyProduct.id, name: quickBuyProduct.name, price: quickBuyProduct.price, qty: qbQty, size: selectedSize, image: (quickBuyProduct.images && quickBuyProduct.images[0]) || '' }],
        subtotal: total, deliveryFee: 0, coupon: null, discount: 0,
        total: total,
        paymentMethod: payMethod, status: 'placed', timestamp: Date.now(), date: new Date().toISOString()
    };

    db.ref('orders/' + orderId).set(orderData)
        .then(() => {
            addToCart(quickBuyProduct.id, quickBuyProduct.name, quickBuyProduct.price, (quickBuyProduct.images && quickBuyProduct.images[0]) || '');
            closeQuickBuy();
            showToast('Order placed successfully!');
            setTimeout(() => { window.location.href = 'order_success.php?id=' + orderId; }, 1200);
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Confirm Order';
            showToast('Error. Try again.');
        });
}
</script>

<?php require_once 'footer.php'; ?>
