<!-- ═══════════════════════════════════════════════════════════════
     SITE FOOTER
     ═══════════════════════════════════════════════════════════════ -->
<footer class="site-footer">
    <div class="footer-container">

        <!-- Top Section -->
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-col footer-brand-col">
                <a href="index.php" class="footer-logo">SHA<span>HI</span></a>
                <p class="footer-tagline">Premium handcrafted Peshawari Chappal. Tradition meets elegance since generations.</p>
                <div class="footer-social">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4 class="footer-heading">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="shop.php"><i class="fas fa-chevron-right"></i> Shop All</a></li>
                    <li><a href="cart.php"><i class="fas fa-chevron-right"></i> Cart</a></li>
                    <li><a href="orders.php"><i class="fas fa-chevron-right"></i> My Orders</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="footer-col">
                <h4 class="footer-heading">Categories</h4>
                <ul class="footer-links">
                    <li><a href="shop.php?cat=classic"><i class="fas fa-chevron-right"></i> Classic</a></li>
                    <li><a href="premium.php"><i class="fas fa-chevron-right"></i> Premium</a></li>
                    <li><a href="new_design.php"><i class="fas fa-chevron-right"></i> New Design</a></li>
                    <li><a href="casual.php"><i class="fas fa-chevron-right"></i> Casual</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-col">
                <h4 class="footer-heading">Contact Us</h4>
                <ul class="footer-contact" id="footerContact">
                    <li><i class="fas fa-map-marker-alt"></i> <span id="fcAddress">Peshawar, Khyber Pakhtunkhwa, Pakistan</span></li>
                    <li><i class="fas fa-phone-alt"></i> <span id="fcPhone">+92 300 1234567</span></li>
                    <li><i class="fas fa-envelope"></i> <span id="fcEmail">info@shahichappal.com</span></li>
                    <li><i class="fas fa-clock"></i> Mon - Sat: 9:00 AM - 8:00 PM</li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Shahi Peshawari. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* ─── Site Footer ─── */
    .site-footer {
        background: var(--black);
        color: rgba(255,255,255,.75);
        margin-top: auto;
    }
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Footer Grid */
    .footer-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1.3fr;
        gap: 40px;
        padding: 52px 0 36px;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }

    /* Brand */
    .footer-logo {
        font-size: 1.6rem; font-weight: 800; color: var(--blue);
        letter-spacing: -0.5px; display: inline-block; margin-bottom: 12px;
    }
    .footer-logo span { color: var(--white); }
    .footer-tagline {
        font-size: .85rem; line-height: 1.7; margin-bottom: 20px;
        color: rgba(255,255,255,.55);
    }
    .footer-social { display: flex; gap: 10px; }
    .footer-social a {
        width: 38px; height: 38px; border-radius: 50%;
        background: rgba(255,255,255,.08);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,.6); font-size: .9rem;
        transition: var(--transition);
    }
    .footer-social a:hover {
        background: var(--blue); color: var(--white);
        transform: translateY(-2px);
    }

    /* Headings */
    .footer-heading {
        font-size: .9rem; font-weight: 600; color: var(--white);
        margin-bottom: 18px; letter-spacing: .3px;
    }

    /* Links */
    .footer-links { list-style: none; }
    .footer-links li { margin-bottom: 10px; }
    .footer-links a {
        font-size: .85rem; color: rgba(255,255,255,.55);
        display: flex; align-items: center; gap: 8px;
        transition: var(--transition);
    }
    .footer-links a i { font-size: .55rem; color: var(--blue); }
    .footer-links a:hover { color: var(--white); transform: translateX(4px); }

    /* Contact */
    .footer-contact { list-style: none; }
    .footer-contact li {
        font-size: .83rem; margin-bottom: 12px;
        display: flex; align-items: flex-start; gap: 10px;
        color: rgba(255,255,255,.55);
    }
    .footer-contact li i {
        color: var(--blue); font-size: .85rem;
        margin-top: 3px; flex-shrink: 0;
    }

    /* Bottom Bar */
    .footer-bottom {
        display: flex; justify-content: space-between; align-items: center;
        padding: 20px 0;
    }
    .footer-bottom p {
        font-size: .8rem; color: rgba(255,255,255,.4);
    }
    .footer-bottom-links { display: flex; gap: 20px; }
    .footer-bottom-links a {
        font-size: .8rem; color: rgba(255,255,255,.4);
        transition: var(--transition);
    }
    .footer-bottom-links a:hover { color: var(--blue); }

    /* ─── Footer Responsive ─── */
    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            padding: 36px 0 24px;
        }
        .footer-brand-col { grid-column: 1 / -1; }
        .footer-bottom {
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }
    }
    @media (max-width: 480px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 24px;
            padding: 32px 0 20px;
        }
        .footer-social { gap: 8px; }
        .footer-contact li { font-size: .78rem; }
        .footer-links a { font-size: .8rem; }
    }

    /* ═══════════════════════════════════════════════════════════════
       FLOATING WHATSAPP BUTTON
       ═══════════════════════════════════════════════════════════════ */
    .wa-float {
        position: fixed;
        bottom: 90px;
        right: 24px;
        z-index: 9990;
        display: flex;
        align-items: flex-end;
        gap: 0;
    }

    /* Main Button */
    .wa-btn {
        width: 60px;
        height: 60px;
        background: #25d366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 30px;
        box-shadow: 0 4px 20px rgba(37,211,102,.4);
        position: relative;
        transition: all .3s cubic-bezier(.4,0,.2,1);
        text-decoration: none;
        flex-shrink: 0;
    }
    .wa-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 28px rgba(37,211,102,.55);
    }

    /* Pulse Ripple */
    .wa-ripple {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(37,211,102,.35);
        animation: waPulse 2s ease-out infinite;
    }
    @keyframes waPulse {
        0%   { transform: scale(1);   opacity: .6; }
        70%  { transform: scale(1.6); opacity: 0; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    /* Chat Bubble */
    .wa-bubble {
        position: absolute;
        bottom: 12px;
        right: 74px;
        background: #fff;
        border-radius: 10px 10px 2px 10px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 4px 20px rgba(0,0,0,.12);
        opacity: 0;
        transform: translateX(12px) scale(.9);
        pointer-events: none;
        transition: all .35s cubic-bezier(.4,0,.2,1);
        text-decoration: none;
    }
    .wa-bubble.show {
        opacity: 1;
        transform: translateX(0) scale(1);
        pointer-events: auto;
    }
    .wa-bubble:hover {
        box-shadow: 0 6px 28px rgba(0,0,0,.18);
        transform: translateX(-4px) scale(1);
    }
    .wa-bubble-text {
        font-family: 'Poppins', sans-serif;
        font-size: .82rem;
        font-weight: 600;
        color: #333;
    }
    .wa-bubble-arrow {
        position: absolute;
        right: -7px;
        bottom: 14px;
        width: 0;
        height: 0;
        border-left: 8px solid #fff;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
    }

    /* ─── Responsive ─── */
    @media (max-width: 768px) {
        .wa-float { bottom: 80px; right: 16px; }
        .wa-btn { width: 52px; height: 52px; font-size: 26px; }
        .wa-bubble { right: 64px; bottom: 6px; padding: 8px 14px; }
        .wa-bubble-text { font-size: .75rem; }
    }
    @media (max-width: 480px) {
        .wa-float { bottom: 76px; right: 12px; }
        .wa-btn { width: 46px; height: 46px; font-size: 22px; }
        .wa-bubble { right: 56px; bottom: 4px; padding: 6px 10px; }
        .wa-bubble-text { font-size: .7rem; }
        .wa-bubble-arrow { border-left-width: 6px; }
    }
</style>

<!-- ═══════════════════════════════════════════════════════════════
     FLOATING WHATSAPP BUTTON
     ═══════════════════════════════════════════════════════════════ -->
<div class="wa-float" id="waFloat">
    <!-- Chat Bubble (appears after 4s) -->
    <a href="#" target="_blank" rel="noopener" class="wa-bubble" id="waBubble">
        <span class="wa-bubble-text" id="waBubbleText">Need help? Chat with us!</span>
        <span class="wa-bubble-arrow"></span>
    </a>
    <!-- Main Button -->
    <a href="#" target="_blank" rel="noopener" class="wa-btn" id="waBtn" title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="wa-ripple"></span>
    </a>
</div>

<!-- ─── Toast ─── -->
<div class="toast" id="toast"></div>

<script>

/* ─── Toast ─── */
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2500);
}

/* ─── Cart (localStorage) ─── */
const CART_KEY = 'shahi_cart';
let cart = [];

function loadCart() {
    try { cart = JSON.parse(localStorage.getItem(CART_KEY)) || []; }
    catch(e) { cart = []; }
}

function saveCart() {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateBadge();
}

function updateBadge() {
    const count = cart.reduce((s, i) => s + i.qty, 0);
    const badge = document.getElementById('cartBadge');
    const mBadge = document.getElementById('mobileCartBadge');
    if (badge) badge.textContent = count;
    if (mBadge) {
        mBadge.textContent = count;
        mBadge.style.display = count > 0 ? 'flex' : 'none';
    }
}

function addToCart(id, name, price, image, stock, size) {
    if (!id) return;
    loadCart();
    const inCart = cart.find(i => i.id === id);
    const currentQty = inCart ? inCart.qty : 0;
    if (stock !== undefined && stock !== null && stock !== 999 && currentQty >= stock) {
        showToast('Out of stock!');
        return;
    }
    const existing = cart.find(i => i.id === id);
    if (existing) { existing.qty++; }
    else { cart.push({ id, name: name || '', price: Number(price) || 0, image: image || '', qty: 1, size: size || '' }); }
    saveCart();
    showToast(name + ' added to cart');
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart();
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty < 1) { removeFromCart(id); return; }
    saveCart();
}

function getCartTotal() {
    return cart.reduce((s, i) => s + (i.price * i.qty), 0);
}

function clearCart() {
    cart = [];
    saveCart();
}

/* ─── Global Search ─── */
function setupSearch(inputId) {
    const el = document.getElementById(inputId);
    if (!el) return;
    el.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.product-card').forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            const cat = (card.dataset.cat || '').toLowerCase();
            card.style.display = (name.includes(q) || cat.includes(q) || !q) ? '' : 'none';
        });
    });
}
setupSearch('globalSearch');
setupSearch('globalSearchMobile');

/* ─── Init ─── */
loadCart();
updateBadge();

/* ─── WhatsApp — Load from Firebase ─── */
db.ref('settings').on('value', snap => {
    const s = snap.val() || {};
    const num = s.waNumber || '923001234567';
    const preFilled = s.waPreFilled || 'Hi Shahi Peshawari! I need help.';
    const bubbleMsg = s.waMessage || 'Need help? Chat with us!';
    const waUrl = 'https://wa.me/' + num + '?text=' + encodeURIComponent(preFilled);

    const btn = document.getElementById('waBtn');
    const bubble = document.getElementById('waBubble');
    const bubbleText = document.getElementById('waBubbleText');

    if (btn) btn.href = waUrl;
    if (bubble) bubble.href = waUrl;
    if (bubbleText) bubbleText.textContent = bubbleMsg;

    /* Update footer contact */
    if (s.storeAddress) { const el = document.getElementById('fcAddress'); if (el) el.textContent = s.storeAddress; }
    if (s.storePhone)   { const el = document.getElementById('fcPhone');   if (el) el.textContent = s.storePhone; }
    if (s.storeEmail)   { const el = document.getElementById('fcEmail');   if (el) el.textContent = s.storeEmail; }
});

/* ─── WhatsApp Bubble Auto-Show (2 seconds then hide) ─── */
function showWaBubble() {
    const bubble = document.getElementById('waBubble');
    if (!bubble) return;
    bubble.classList.add('show');
    setTimeout(() => { bubble.classList.remove('show'); }, 2000);
}

/* Show after 4.5s, then repeat every 20s */
setTimeout(() => {
    showWaBubble();
    setInterval(showWaBubble, 20000);
}, 4500);

/* Hide bubble on click */
document.getElementById('waBubble')?.addEventListener('click', function() {
    this.classList.remove('show');
});

/* ─── Payment Methods Loader ─── */
let _payMethodsData = null;
let _payMethodsReady = null;

function getPaymentMethods() {
    if (_payMethodsReady) return _payMethodsReady;
    _payMethodsReady = new Promise(function(resolve) {
        if (typeof db === 'undefined') { resolve([]); return; }
        db.ref('settings/paymentMethods').once('value', function(snap) {
            var data = snap.val();
            if (data) {
                _payMethodsData = Array.isArray(data) ? data : Object.values(data);
            }
            resolve(_payMethodsData || []);
        }).catch(function() {
            _payMethodsReady = null;
            resolve([]);
        });
    });
    return _payMethodsReady;
}

function resetPaymentMethodsCache() {
    _payMethodsData = null;
    _payMethodsReady = null;
}

function buildPayInfoHTML(method, qrSize) {
    qrSize = qrSize || (window.innerWidth <= 480 ? 200 : 180);
    if (!_payMethodsData) return '<div style="text-align:center;color:#888">Loading payment info...</div>';
    var pm = null;
    for (var i = 0; i < _payMethodsData.length; i++) {
        var m = _payMethodsData[i];
        if (m.name && m.name.toLowerCase().indexOf(method.toLowerCase()) !== -1) { pm = m; break; }
    }
    if (!pm) return '<div style="text-align:center;color:#888">Payment info not configured</div>';
    var html = '<div style="text-align:center">';
    html += '<strong style="font-size:1rem">' + (pm.name || method) + '</strong><br>';
    if (pm.qrUrl) {
        var qrSrc = pm.qrUrl;
        if (pm.qrUrl.indexOf('uploads/') === 0) {
            if (typeof BASE !== 'undefined') qrSrc = BASE + pm.qrUrl;
            else qrSrc = pm.qrUrl;
        }
        html += '<img src="' + qrSrc + '" alt="' + pm.name + ' QR" style="width:' + qrSize + 'px;height:' + qrSize + 'px;border-radius:12px;margin:12px auto;border:2px solid #eee;display:block">';
    }
    if (pm.account) html += 'Send to: <b style="font-size:1.05rem">' + pm.account + '</b><br>';
    if (pm.title) html += 'Account: ' + pm.title + '<br>';
    if (pm.iban) html += 'IBAN: <span style="font-size:.8rem">' + pm.iban + '</span><br>';
    html += '<span style="font-size:.78rem;color:#888">Scan QR or send on above number</span>';
    html += '</div>';
    return html;
}
</script>
</body>
</html>
