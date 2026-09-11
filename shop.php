<?php
$pageTitle = 'Shop All Collections';
require_once 'config.php';
require_once 'product_data.php';
require_once 'header.php';

$selectedCat = $_GET['cat'] ?? 'all';
?>

<div class="container">

    <!-- Breadcrumb & Header -->
    <div class="breadcrumb" style="margin-bottom:14px;">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Shop All Collections</span>
    </div>

    <!-- Shop Banner -->
    <div style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);border-radius:var(--radius);padding:36px 30px;color:#fff;margin-bottom:28px;position:relative;overflow:hidden;box-shadow:var(--shadow-lg);">
        <div style="position:absolute;right:-20px;bottom:-30px;opacity:.08;font-size:12rem;pointer-events:none;"><i class="fas fa-shoe-prints"></i></div>
        <div style="max-width:600px;position:relative;z-index:1;">
            <span style="display:inline-block;padding:4px 12px;background:rgba(9,132,227,.25);border:1px solid rgba(9,132,227,.4);color:#74b9ff;border-radius:50px;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;">Official Online Store</span>
            <h1 style="font-size:2rem;font-weight:800;margin-bottom:8px;line-height:1.2;">Explore Our Authentic Peshawari Catalog</h1>
            <p style="font-size:.88rem;color:#b2bec3;line-height:1.6;">Browse over <?= count($PRODUCTS) ?> hand-stitched Peshawari Chappals made with 100% pure leather, double sole durability, and heritage artistry.</p>
        </div>
    </div>

    <!-- Shop Control Bar (Filters & Sorting) -->
    <div style="background:#fff;border-radius:var(--radius);padding:18px 20px;margin-bottom:24px;box-shadow:var(--shadow);border:1px solid var(--grey-border);">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
            
            <!-- Category Filter Pills -->
            <div style="display:flex;gap:8px;overflow-x:auto;scrollbar-width:none;padding-bottom:2px;" id="shopCatFilter">
                <button class="cat-chip <?= $selectedCat === 'all' ? 'active' : '' ?>" data-cat="all">All (<?= count($PRODUCTS) ?>)</button>
                <button class="cat-chip <?= $selectedCat === 'classic' ? 'active' : '' ?>" data-cat="classic">Classic</button>
                <button class="cat-chip <?= $selectedCat === 'premium' ? 'active' : '' ?>" data-cat="premium">Premium</button>
                <button class="cat-chip <?= $selectedCat === 'modern' ? 'active' : '' ?>" data-cat="modern">New Design</button>
                <button class="cat-chip <?= $selectedCat === 'casual' ? 'active' : '' ?>" data-cat="casual">Casual</button>
            </div>

            <!-- Search & Sort Controls -->
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;flex:1;justify-content:flex-end;">
                <div style="position:relative;min-width:200px;flex:1;max-width:280px;">
                    <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--grey-text);font-size:.82rem;"></i>
                    <input type="text" id="shopSearch" placeholder="Filter by name..." style="width:100%;padding:9px 12px 9px 34px;border:1px solid var(--grey-border);border-radius:50px;font-size:.82rem;">
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <label for="shopSort" style="font-size:.8rem;color:var(--grey-text);font-weight:600;white-space:nowrap;"><i class="fas fa-sort-amount-down"></i> Sort:</label>
                    <select id="shopSort" onchange="applyShopFilters()" style="padding:8px 14px;border:1px solid var(--grey-border);border-radius:8px;font-size:.82rem;background:#fff;cursor:pointer;">
                        <option value="featured">Featured</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="name-asc">Name: A to Z</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- Filter stats strip -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;padding-top:10px;border-top:1px solid #f0f0f0;font-size:.78rem;color:var(--grey-text);">
            <div>Showing <strong id="shopProductCount" style="color:var(--black);"><?= count($PRODUCTS) ?></strong> products</div>
            <div style="display:flex;gap:12px;">
                <span><i class="fas fa-check-circle" style="color:var(--green)"></i> In Stock & Ready to Ship</span>
                <span><i class="fas fa-shield-alt" style="color:var(--blue)"></i> Authentic Guarantee</span>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div id="shopGrid" class="product-grid" style="margin-bottom:40px;">
        <?php foreach ($PRODUCTS as $p):
            $imgs = is_array($p['images']) ? $p['images'] : [$p['images']];
            $src1 = !empty($imgs[0]) ? (str_starts_with($imgs[0], 'http') ? $imgs[0] : BASE_URL . '/' . ltrim($imgs[0], '/')) : '';
            $src2 = !empty($imgs[1]) ? (str_starts_with($imgs[1], 'http') ? $imgs[1] : BASE_URL . '/' . ltrim($imgs[1], '/')) : $src1;
            $allImgs = implode('|', array_map(function($im) { return str_starts_with($im, 'http') ? $im : BASE_URL . '/' . ltrim($im, '/'); }, $imgs));
            $pName = htmlspecialchars($p['name'] ?? '', ENT_QUOTES);
            $pCat = htmlspecialchars($p['cat'] ?? '', ENT_QUOTES);
            $pDetail = htmlspecialchars($p['shortDetail'] ?? '', ENT_QUOTES);
            $price = (int)($p['price'] ?? 0);
            $oldPrice = !empty($p['oldPrice']) ? (int)$p['oldPrice'] : 0;
        ?>
        <div class="product-card" data-id="<?= $p['id'] ?>" data-name="<?= $pName ?>" data-cat="<?= $pCat ?>" data-price="<?= $price ?>">
            <div class="img-wrap" style="position:relative">
                <img src="<?= $src1 ?>" alt="<?= $pName ?>" loading="lazy" data-src2="<?= $src2 ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\' fill=\'%23e0e0e0\'%3E%3Crect width=\'300\' height=\'300\'/%3E%3Ctext x=\'150\' y=\'160\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%23999\' text-anchor=\'middle\'%3ENo Image%3C/text%3E%3C/svg%3E'">
                <button class="heart-btn" data-id="<?= $p['id'] ?>" data-name="<?= $pName ?>" data-price="<?= $price ?>" data-img="<?= $src1 ?>" title="Add to Cart"><i class="fas fa-heart"></i></button>
            </div>
            <div class="card-body">
                <span class="card-cat"><?= $pCat ?></span>
                <h3 class="card-title" title="<?= $pName ?>"><?= $pName ?></h3>
                <?php $rating = (int)($p['rating'] ?? 0); if ($rating > 0): ?>
                <div style="margin:3px 0"><?php for ($s = 1; $s <= 5; $s++): ?><i class="fas fa-star" style="font-size:.65rem;color:<?= $s <= $rating ? '#f59e0b' : '#d1d5db' ?>"></i><?php endfor; ?></div>
                <?php endif; ?>
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

<!-- ═══════════════════════════════════════
     QUICK BUY MODAL (Shop Page)
     ═══════════════════════════════════════ -->
<div class="modal-overlay" id="quickBuyModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-shopping-bag"></i> Quick Order</h2>
            <button class="modal-close" onclick="closeQuickBuy()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <!-- Image Slider -->
            <div class="modal-slider" id="qbSlider"></div>

            <!-- Product Info -->
            <div style="margin-bottom:16px">
                <div class="modal-pname" id="qbName" style="font-size:1rem;font-weight:700;margin-bottom:4px"></div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="modal-pprice"><span id="qbPrice"></span> x <span id="qbQty">1</span></div>
                    <div class="modal-total" style="border:none;padding:0"><span>Total</span><span id="qbTotal"></span></div>
                </div>
            </div>

            <!-- Quantity -->
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
                <div class="form-group"><label><i class="fas fa-map-marker-alt" style="color:var(--blue)"></i> Address (auto-detected)</label><input type="text" id="qbAddress" placeholder="Detecting location..."></div>
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
let ALL_PRODUCTS = <?= json_encode($PRODUCTS) ?>;
let currentCat = '<?= $selectedCat ?>';
const PLACEHOLDER = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" fill="#e0e0e0"><rect width="300" height="300"/><text x="150" y="160" font-family="sans-serif" font-size="14" fill="#999" text-anchor="middle">No Image</text></svg>');

function imgURL(path) {
    if (!path) return '';
    var p = String(path).trim();
    if (p.startsWith('http')) return p;
    return BASE + p.replace(/^\//, '');
}

function renderShopCard(p) {
    var imgs = Array.isArray(p.images) ? p.images : [p.images];
    var src1 = imgURL(imgs[0]) || PLACEHOLDER;
    var src2 = imgURL(imgs[1]) || src1;
    var detail = p.shortDetail || '';
    var safeName = (p.name||'').replace(/"/g,'&quot;');
    var rating = p.rating || 0;
    var starsHtml = '';
    if (rating > 0) {
        starsHtml = '<div style="margin:3px 0">';
        for (var s = 1; s <= 5; s++) {
            starsHtml += '<i class="fas fa-star" style="font-size:.65rem;color:' + (s <= rating ? '#f59e0b' : '#d1d5db') + '"></i>';
        }
        starsHtml += '</div>';
    }
    return '<div class="product-card" data-id="' + p.id + '" data-name="' + safeName + '" data-cat="' + (p.cat||'') + '" data-price="' + p.price + '">'
        + '<div class="img-wrap" style="position:relative">'
        + '<img src="' + src1 + '" alt="' + safeName + '" loading="lazy" data-src2="' + src2 + '" onerror="this.onerror=null;this.src=\'' + PLACEHOLDER + '\'">'
        + '<button class="heart-btn" data-id="' + p.id + '" data-name="' + safeName + '" data-price="' + p.price + '" data-img="' + src1 + '" title="Add to Cart"><i class="fas fa-heart"></i></button>'
        + '</div>'
        + '<div class="card-body">'
        + '<span class="card-cat">' + (p.cat||'') + '</span>'
        + '<h3 class="card-title" title="' + safeName + '">' + safeName + '</h3>'
        + starsHtml
        + '<p style="font-size:.72rem;color:var(--grey-text);margin:4px 0 6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">' + detail + '</p>'
        + '<div class="card-price">Rs.' + Number(p.price).toLocaleString()
        + (p.oldPrice ? ' <span class="old-price">Rs.' + Number(p.oldPrice).toLocaleString() + '</span>' : '')
        + '</div>'
        + '<button class="btn-buy-card" data-id="' + p.id + '" data-name="' + safeName + '" data-price="' + p.price + '" data-imgs="' + src1 + '|' + src2 + '"><i class="fas fa-bolt"></i> Buy Now</button>'
        + '</div></div>';
}

function applyShopFilters() {
    var query = (document.getElementById('shopSearch').value || '').toLowerCase().trim();
    var sort = document.getElementById('shopSort').value;
    
    var filtered = ALL_PRODUCTS.filter(function(p) {
        var matchCat = (currentCat === 'all' || p.cat === currentCat);
        var matchQuery = !query || (p.name || '').toLowerCase().includes(query) || (p.shortDetail || '').toLowerCase().includes(query);
        return matchCat && matchQuery;
    });

    if (sort === 'price-asc') {
        filtered.sort((a,b) => (a.price || 0) - (b.price || 0));
    } else if (sort === 'price-desc') {
        filtered.sort((a,b) => (b.price || 0) - (a.price || 0));
    } else if (sort === 'name-asc') {
        filtered.sort((a,b) => (a.name || '').localeCompare(b.name || ''));
    }

    var grid = document.getElementById('shopGrid');
    var countEl = document.getElementById('shopProductCount');
    if (countEl) countEl.textContent = filtered.length;

    if (filtered.length === 0) {
        grid.innerHTML = '<div class="empty-state" style="grid-column:1/-1;"><i class="fas fa-search"></i><h3>No products found</h3><p>Try changing your search query or category filter</p></div>';
    } else {
        grid.innerHTML = filtered.map(renderShopCard).join('');
    }
}

// Category filter click listener
document.getElementById('shopCatFilter').addEventListener('click', function(e) {
    var chip = e.target.closest('.cat-chip');
    if (!chip) return;
    document.querySelectorAll('#shopCatFilter .cat-chip').forEach(c => c.classList.remove('active'));
    chip.classList.add('active');
    currentCat = chip.dataset.cat;
    applyShopFilters();
});

document.getElementById('shopSearch').addEventListener('input', applyShopFilters);

// Check if query params exist
(function() {
    const params = new URLSearchParams(window.location.search);
    const cat = params.get('cat');
    if (cat) {
        currentCat = cat;
        document.querySelectorAll('#shopCatFilter .cat-chip').forEach(c => c.classList.remove('active'));
        const chip = document.querySelector('#shopCatFilter .cat-chip[data-cat="' + cat + '"]');
        if (chip) chip.classList.add('active');
        applyShopFilters();
    }
})();

// Hover image swap
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

// Card Click
document.addEventListener('click', function(e) {
    if (e.target.closest('.heart-btn') || e.target.closest('.btn-buy-card')) return;
    var card = e.target.closest('.product-card');
    if (card && card.dataset.id) {
        window.location.href = 'product.php?id=' + card.dataset.id;
    }
});

// Heart Button
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.heart-btn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    addToCart(btn.dataset.id, btn.dataset.name, Number(btn.dataset.price), btn.dataset.img);
    btn.classList.add('added');
    setTimeout(function() { btn.classList.remove('added'); }, 800);
});

// Buy Now Button
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-buy-card');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    var images = (btn.dataset.imgs || '').split('|').filter(Boolean);
    openQuickBuy(btn.dataset.id, btn.dataset.name, Number(btn.dataset.price), images);
});

/* ─── Quick Buy Modal Logic ─── */
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

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${pos.coords.latitude}&longitude=${pos.coords.longitude}&localityLanguage=en`)
                .then(r => r.json()).then(data => {
                    document.getElementById('qbAddress').value = data.localityInfo?.formatted?.[0] || data.street || '';
                    document.getElementById('qbCity').value = [data.city, data.principalSubdivision].filter(Boolean).join(', ');
                }).catch(() => {});
        }, () => {});
    }
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

// Payment method toggle
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
            fetch('send_order_email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            }).catch(() => {});
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
