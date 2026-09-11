<?php
$pageTitle = 'Home';
require_once 'config.php';
require_once 'product_data.php';
require_once 'header.php';
?>

<div class="container">

    <!-- Hero Banner with Video -->
    <section class="hero" style="position:relative;overflow:hidden;padding:0;background:#000;border-radius:var(--radius);margin-bottom:28px;height:340px;">
        <video autoplay muted loop playsinline style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:0;">
            <source src="footer.mp4" type="video/mp4">
        </video>
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(0,0,0,.65) 0%,rgba(0,0,0,.35) 100%);z-index:1;"></div>
        <div style="position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;padding:48px 36px;text-align:center;color:var(--white);">
            <h1 style="font-size:2.2rem;font-weight:800;margin-bottom:10px;text-shadow:0 2px 12px rgba(0,0,0,.3);">Premium Peshawari Chappal</h1>
            <p style="font-size:.95rem;opacity:.9;max-width:500px;line-height:1.7;text-shadow:0 1px 6px rgba(0,0,0,.2);">Handcrafted with tradition. Elevate your style with our premium collection of <?= count($PRODUCTS) ?>+ authentic Peshawari footwear designs.</p>
            <a href="shop.php" class="hero-btn" style="margin-top:20px;">Shop Now <i class="fas fa-arrow-right" style="margin-left:8px"></i></a>
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         CUSTOMER REVIEWS STRIP (Immediately After Hero)
         ═══════════════════════════════════════ -->
    <div style="background:linear-gradient(135deg,#0984e3,#0769b5);border-radius:12px;padding:18px 24px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;box-shadow:0 4px 16px rgba(9,132,227,.2);">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="display:flex;">
                <?php $emojis = ['😊','😄','🤩','😍','🥰']; foreach($emojis as $idx => $emo): ?>
                <div style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.25);border:2px solid rgba(255,255,255,.6);display:flex;align-items:center;justify-content:center;margin-left:<?= $idx>0?'-8px':'0' ?>;font-size:.92rem;">
                    <?= $emo ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="color:#fff;">
                <div style="font-weight:700;font-size:.95rem;letter-spacing:.2px;">2,500+ Happy Customers</div>
                <div style="font-size:.78rem;opacity:.9;margin-top:2px;">
                    <i class="fas fa-star" style="color:#ffd700;"></i>
                    <i class="fas fa-star" style="color:#ffd700;"></i>
                    <i class="fas fa-star" style="color:#ffd700;"></i>
                    <i class="fas fa-star" style="color:#ffd700;"></i>
                    <i class="fas fa-star" style="color:#ffd700;"></i>
                    &nbsp;4.9/5 Rating across Pakistan
                </div>
            </div>
        </div>
        <div style="color:#fff;font-size:.82rem;opacity:.95;display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);padding:8px 16px;border-radius:50px;">
            <i class="fas fa-award" style="font-size:1.15rem;color:#ffd700;"></i>
            <span>100% Authentic Peshawari Craftsmanship</span>
        </div>
    </div>

    <!-- Categories -->
    <h2 class="section-title">Categories</h2>
    <div class="categories-scroll" id="catScroll">
        <button class="cat-chip active" data-cat="all">All (<?= count($PRODUCTS) ?>)</button>
        <button class="cat-chip" data-cat="classic">Classic</button>
        <button class="cat-chip" data-cat="premium">Premium</button>
        <button class="cat-chip" data-cat="casual">Casual</button>
        <button class="cat-chip" data-cat="modern">Modern</button>
    </div>

    <!-- Products by Category (Pre-rendered for 100% guaranteed display on load) -->
    <div id="categorySections">
        <?php
        $catsDef = [
            ['key' => 'classic', 'label' => 'Classic Collection', 'icon' => 'fa-shoe-prints'],
            ['key' => 'premium', 'label' => 'Premium Collection', 'icon' => 'fa-gem'],
            ['key' => 'casual',  'label' => 'Casual Collection',  'icon' => 'fa-cloud-sun'],
            ['key' => 'modern',  'label' => 'Modern Collection',  'icon' => 'fa-wand-magic-sparkles']
        ];
        foreach ($catsDef as $cDef):
            $catItems = array_values(array_filter($PRODUCTS, function($p) use ($cDef) { return ($p['cat'] ?? '') === $cDef['key']; }));
            $catItems = array_slice($catItems, 0, 4);
            if (empty($catItems)) continue;
        ?>
        <div class="category-section" data-cat-section="<?= $cDef['key'] ?>">
            <div class="cat-section-header">
                <h2 class="section-title"><i class="fas <?= $cDef['icon'] ?>" style="color:var(--blue);margin-right:8px"></i><?= $cDef['label'] ?></h2>
                <a href="shop.php?cat=<?= $cDef['key'] ?>" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="product-grid">
                <?php foreach ($catItems as $p):
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
                <div class="product-card" data-id="<?= $p['id'] ?>" data-name="<?= $pName ?>" data-cat="<?= $pCat ?>">
                    <div class="img-wrap" style="position:relative">
                        <img src="<?= $src1 ?>" alt="<?= $pName ?>" loading="lazy" data-src2="<?= $src2 ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\' fill=\'%23e0e0e0\'%3E%3Crect width=\'300\' height=\'300\'/%3E%3Ctext x=\'150\' y=\'160\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%23999\' text-anchor=\'middle\'%3ENo Image%3C/text%3E%3C/svg%3E'">
                        <button class="heart-btn" data-id="<?= $p['id'] ?>" data-name="<?= $pName ?>" data-price="<?= $price ?>" data-img="<?= $src1 ?>" title="Add to Cart"><i class="fas fa-heart"></i></button>
                    </div>
                    <div class="card-body">
                        <span class="card-cat"><?= $pCat ?></span>
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
        <?php endforeach; ?>
    </div>

    <!-- ═══════════════════════════════════════
         TRUST SECTION (Placed Below Products)
         ═══════════════════════════════════════ -->
    <section style="margin-top:36px;margin-bottom:28px;">
        <div style="text-align:center;margin-bottom:20px;">
            <h2 style="font-size:1.3rem;font-weight:700;color:var(--black);">Why Choose Shahi Peshawari?</h2>
            <p style="font-size:.84rem;color:var(--grey-text);margin-top:4px;">Handcrafted luxury with authentic heritage & customer satisfaction</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;" class="trust-grid">

            <div style="background:#fff;border-radius:12px;padding:18px 14px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 10px rgba(0,0,0,.06);border:1px solid #f0f0f0;transition:transform .25s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#e8f4fd,#b8e0ff);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-shield-alt" style="color:#0984e3;font-size:1.2rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.88rem;color:#1a1a2e;">100% Secure</div>
                    <div style="font-size:.74rem;color:#636e72;margin-top:1px;">Safe & verified payments</div>
                </div>
            </div>

            <div style="background:#fff;border-radius:12px;padding:18px 14px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 10px rgba(0,0,0,.06);border:1px solid #f0f0f0;transition:transform .25s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#e6fff9,#b2f0e4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-truck" style="color:#00b894;font-size:1.2rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.88rem;color:#1a1a2e;">Fast Delivery</div>
                    <div style="font-size:.74rem;color:#636e72;margin-top:1px;">Pakistan-wide shipping</div>
                </div>
            </div>

            <div style="background:#fff;border-radius:12px;padding:18px 14px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 10px rgba(0,0,0,.06);border:1px solid #f0f0f0;transition:transform .25s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#fff8e6,#ffe9a0);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-star" style="color:#d4a017;font-size:1.2rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.88rem;color:#1a1a2e;">5-Star Rated</div>
                    <div style="font-size:.74rem;color:#636e72;margin-top:1px;">Thousands of happy customers</div>
                </div>
            </div>

            <div style="background:#fff;border-radius:12px;padding:18px 14px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 10px rgba(0,0,0,.06);border:1px solid #f0f0f0;transition:transform .25s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#fde8e8,#ffb3b3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-handshake" style="color:#d63031;font-size:1.2rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.88rem;color:#1a1a2e;">Authentic Handcraft</div>
                    <div style="font-size:.74rem;color:#636e72;margin-top:1px;">Traditional Peshawari quality</div>
                </div>
            </div>

            <div style="background:#fff;border-radius:12px;padding:18px 14px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 10px rgba(0,0,0,.06);border:1px solid #f0f0f0;transition:transform .25s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#e8e0ff,#c8b8ff);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-undo-alt" style="color:#6c5ce7;font-size:1.2rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.88rem;color:#1a1a2e;">Easy Returns</div>
                    <div style="font-size:.74rem;color:#636e72;margin-top:1px;">Hassle-free return policy</div>
                </div>
            </div>

            <div style="background:#fff;border-radius:12px;padding:18px 14px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 10px rgba(0,0,0,.06);border:1px solid #f0f0f0;transition:transform .25s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#e0f7fa,#b2ebf2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-headset" style="color:#0097a7;font-size:1.2rem;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.88rem;color:#1a1a2e;">24/7 Support</div>
                    <div style="font-size:.74rem;color:#636e72;margin-top:1px;">Always here to help you</div>
                </div>
            </div>

        </div>
    </section>

</div>

<script>
const BASE = '<?= BASE_URL ?>/';
let ALL_PRODUCTS = <?= json_encode($PRODUCTS) ?>;
let staticProducts = ALL_PRODUCTS.slice();
const ITEMS_PER_CATEGORY = 4;
const PLACEHOLDER = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" fill="#e0e0e0"><rect width="300" height="300"/><text x="150" y="160" font-family="sans-serif" font-size="14" fill="#999" text-anchor="middle">No Image</text></svg>');
let activeCategory = 'all';

function toArr(val) {
    if (!val) return [];
    if (Array.isArray(val)) return val;
    if (typeof val === 'object') return Object.values(val);
    if (typeof val === 'string') return [val];
    return [];
}

function imgURL(path) {
    if (!path) return '';
    var p = String(path).trim();
    if (p.startsWith('http')) return p;
    return BASE + p.replace(/^\//, '');
}

function updateCategoryCounts() {
    var allBtn = document.querySelector('.cat-chip[data-cat="all"]');
    if (allBtn) allBtn.textContent = 'All (' + ALL_PRODUCTS.length + ')';
}

function renderCard(p) {
    var imgs = toArr(p.images);
    var src1 = imgURL(imgs[0]) || PLACEHOLDER;
    var src2 = imgURL(imgs[1]) || src1;
    var detail = p.shortDetail || '';
    var safeName = (p.name||'').replace(/"/g,'&quot;');
    var rating = p.rating || 0;
    var starsHtml = '';
    if (rating > 0) {
        starsHtml = '<div style="margin:4px 0">';
        for (var s = 1; s <= 5; s++) {
            starsHtml += '<i class="fas fa-star" style="font-size:.7rem;color:' + (s <= rating ? '#f59e0b' : '#d1d5db') + '"></i>';
        }
        starsHtml += '</div>';
    }
    return '<div class="product-card" data-id="' + p.id + '" data-name="' + safeName + '" data-cat="' + (p.cat||'') + '">'
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

function renderProducts() {
    var container = document.getElementById('categorySections');
    if (!container) return;
    var categories = [
        { key: 'classic', label: 'Classic Collection', icon: 'fa-shoe-prints' },
        { key: 'premium', label: 'Premium Collection', icon: 'fa-gem' },
        { key: 'casual', label: 'Casual Collection', icon: 'fa-cloud-sun' },
        { key: 'modern', label: 'Modern Collection', icon: 'fa-wand-magic-sparkles' }
    ];
    var html = '';
    categories.forEach(function(cat) {
        var products = ALL_PRODUCTS.filter(function(p) { return p.cat === cat.key; }).slice(0, ITEMS_PER_CATEGORY);
        if (products.length === 0) return;
        html += '<div class="category-section" data-cat-section="' + cat.key + '">'
            + '<div class="cat-section-header">'
            + '<h2 class="section-title"><i class="fas ' + cat.icon + '" style="color:var(--blue);margin-right:8px"></i>' + cat.label + '</h2>'
            + '<a href="shop.php?cat=' + cat.key + '" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>'
            + '</div>'
            + '<div class="product-grid">' + products.map(renderCard).join('') + '</div>'
            + '</div>';
    });
    if (html) container.innerHTML = html;
}

function renderFilteredProducts() {
    var container = document.getElementById('categorySections');
    if (!container) return;
    if (activeCategory === 'all') { renderProducts(); return; }
    var filtered = ALL_PRODUCTS.filter(function(p) { return p.cat === activeCategory; });
    if (filtered.length === 0) {
        container.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><h3>No products found</h3><p>Try a different category</p></div>';
        return;
    }
    container.innerHTML = '<div class="product-grid">' + filtered.map(renderCard).join('') + '</div>';
}

function filterByCategory(cat) {
    document.querySelectorAll('.cat-chip').forEach(function(c) { c.classList.remove('active'); });
    var chip = document.querySelector('.cat-chip[data-cat="' + cat + '"]');
    if (chip) chip.classList.add('active');
    activeCategory = cat;
    renderFilteredProducts();
}

document.getElementById('catScroll').addEventListener('click', function(e) {
    var chip = e.target.closest('.cat-chip');
    if (!chip) return;
    document.querySelectorAll('.cat-chip').forEach(function(c) { c.classList.remove('active'); });
    chip.classList.add('active');
    activeCategory = chip.dataset.cat;
    renderFilteredProducts();
});

updateCategoryCounts();

/* ─── Hover Image Swap ─── */
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

/* ─── Card Click (go to product page) ─── */
document.addEventListener('click', function(e) {
    if (e.target.closest('.heart-btn')) return;
    if (e.target.closest('.btn-buy-card')) return;
    var card = e.target.closest('.product-card');
    if (card && card.dataset.id) {
        window.location.href = 'product.php?id=' + card.dataset.id;
    }
});

/* ─── Heart Button (Quick Add to Cart) ─── */
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.heart-btn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    addToCart(btn.dataset.id, btn.dataset.name, Number(btn.dataset.price), btn.dataset.img);
    btn.classList.add('added');
    setTimeout(function() { btn.classList.remove('added'); }, 800);
});

/* ─── Buy Now Button (open modal with slider) ─── */
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-buy-card');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    var images = (btn.dataset.imgs || '').split('|').filter(Boolean);
    openQuickBuy(btn.dataset.id, btn.dataset.name, Number(btn.dataset.price), images);
});

if (typeof db !== 'undefined') {
    db.ref('products').on('value', function(snap) {
        try {
            var fbProducts = snap.val();
            if (fbProducts && typeof fbProducts === 'object') {
                var loaded = Object.keys(fbProducts).map(function(id) {
                    var raw = fbProducts[id];
                    var imgs = raw.images || [];
                    if (imgs && typeof imgs === 'object' && !Array.isArray(imgs)) {
                        imgs = Object.values(imgs);
                    }
                    return {
                        id: id, name: raw.name || '', cat: raw.cat || 'classic',
                        price: raw.price || 0, oldPrice: raw.oldPrice || 0,
                        stock: raw.stock !== undefined ? raw.stock : 999,
                        rating: raw.rating || 0,
                        images: imgs, shortDetail: raw.shortDetail || ''
                    };
                }).filter(function(p) { return p.name && p.price > 0; });
                if (loaded.length > 0) {
                    var mergedMap = {};
                    staticProducts.forEach(function(p) { mergedMap[p.id] = p; });
                    loaded.forEach(function(p) { mergedMap[p.id] = p; });
                    ALL_PRODUCTS = Object.values(mergedMap);
                    if (activeCategory === 'all') renderProducts();
                    else renderFilteredProducts();
                    updateCategoryCounts();
                }
            }
        } catch(err) {
            console.error('Firebase load error:', err);
        }
    });
}

/* ─── Quick Buy Modal ─── */
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

    /* Build image slider */
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
            addToCart(quickBuyProduct.id, quickBuyProduct.name, quickBuyProduct.price, (quickBuyProduct.images && quickBuyProduct.images[0]) || '', quickBuyProduct.stock || 999, selectedSize);
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

/* URL-based category filter */
(function() {
    const params = new URLSearchParams(window.location.search);
    const cat = params.get('cat');
    if (cat) {
        activeCategory = cat;
        document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
        const chip = document.querySelector('.cat-chip[data-cat="' + cat + '"]');
        if (chip) chip.classList.add('active');
        renderFilteredProducts();
    }
})();
</script>

<!-- ═══════════════════════════════════════
     QUICK BUY MODAL (Home Page)
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
/* Payment method toggle - attached AFTER modal HTML exists in DOM */
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
</script>

<?php require_once 'footer.php'; ?>
