<?php
$pageTitle = 'Order Confirmed';
require_once 'config.php';
require_once 'header.php';
?>

<div class="container">
    <div class="success-box" id="successBox">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Order Placed Successfully!</h1>
        <p class="success-msg">Thank you for your order. We'll send you a confirmation shortly.</p>
        <div class="order-id-display">
            <span class="order-id-label">Your Order ID</span>
            <span class="order-id-value" id="displayOrderId">---</span>
            <button class="copy-btn" onclick="copyOrderId()" title="Copy Order ID"><i class="fas fa-copy"></i></button>
        </div>
    </div>

    <!-- Receipt -->
    <div class="receipt" id="receipt">
        <div class="receipt-header">
            <div class="receipt-brand">SHA<span>HI</span></div>
            <div class="receipt-title">Order Receipt</div>
        </div>
        <div class="receipt-info">
            <div><strong>Order ID:</strong> <span id="rOrderId">---</span></div>
            <div><strong>Date:</strong> <span id="rDate">---</span></div>
            <div><strong>Payment:</strong> <span id="rPayment">---</span></div>
        </div>
        <div class="receipt-customer">
            <div><strong>Customer:</strong> <span id="rName">---</span></div>
            <div><strong>Phone:</strong> <span id="rPhone">---</span></div>
            <div><strong>Address:</strong> <span id="rAddress">---</span></div>
        </div>
        <table class="receipt-table">
            <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
            <tbody id="rItems"></tbody>
        </table>
        <div class="receipt-totals">
            <div class="receipt-row"><span>Subtotal</span><span id="rSubtotal">---</span></div>
            <div class="receipt-row receipt-total"><span>Total</span><span id="rTotal">---</span></div>
        </div>
        <div class="receipt-footer">
            <p>Thank you for shopping with Shahi Peshawari!</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="success-actions">
        <button class="sa-btn sa-primary" onclick="downloadReceipt()"><i class="fas fa-download"></i> Download Receipt</button>
        <button class="sa-btn sa-green" onclick="shareReceipt()"><i class="fas fa-share-alt"></i> Share</button>
        <a href="track.php" class="sa-btn sa-outline"><i class="fas fa-truck"></i> Track Order</a>
        <a href="index.php" class="sa-btn sa-outline"><i class="fas fa-shopping-bag"></i> Continue Shopping</a>
    </div>
</div>

<!-- html2canvas + jsPDF for PDF download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
const CURRENCY = '<?= CURRENCY ?>';
const BASE = '<?= BASE_URL ?>/';

/* ─── Load Order from URL or sessionStorage ─── */
const orderId = new URLSearchParams(window.location.search).get('id');
let orderData = null;

if (orderId) {
    db.ref('orders/' + orderId).once('value', snap => {
        orderData = snap.val();
        if (orderData) renderReceipt(orderData);
        else showError();
    });
} else {
    /* Try sessionStorage fallback */
    const last = sessionStorage.getItem('shahi_last_order');
    if (last) {
        orderData = JSON.parse(last);
        renderReceipt(orderData);
    } else {
        showError();
    }
}

function showError() {
    document.getElementById('successBox').innerHTML = '<div class="success-icon" style="color:var(--red)"><i class="fas fa-exclamation-circle"></i></div><h1>Order Not Found</h1><p>Please check your order ID or track by phone number.</p><a href="track.php" class="sa-btn sa-primary" style="margin-top:16px;display:inline-flex">Track Order</a>';
    document.getElementById('receipt').style.display = 'none';
    document.querySelector('.success-actions').style.display = 'none';
}

function renderReceipt(o) {
    document.getElementById('displayOrderId').textContent = o.orderId;
    document.getElementById('rOrderId').textContent = o.orderId;
    document.getElementById('rDate').textContent = new Date(o.date).toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit' });
    document.getElementById('rPayment').textContent = (o.paymentMethod || 'cod').toUpperCase();
    document.getElementById('rName').textContent = o.customer?.name || '---';
    document.getElementById('rPhone').textContent = o.customer?.phone || '---';
    document.getElementById('rAddress').textContent = (o.customer?.address || '') + (o.customer?.city ? ', ' + o.customer.city : '');

    const tbody = document.getElementById('rItems');
    tbody.innerHTML = (o.items || []).map(it => `<tr><td>${it.name}${it.size ? '<br><span style="font-size:.72rem;color:#888">Size: ' + it.size + '</span>' : ''}</td><td>${it.qty}</td><td>${CURRENCY}${it.price}</td><td><strong>${CURRENCY}${it.price * it.qty}</strong></td></tr>`).join('');

    document.getElementById('rSubtotal').textContent = CURRENCY + o.subtotal;
    document.getElementById('rTotal').textContent = CURRENCY + o.total;
}

function copyOrderId() {
    const id = document.getElementById('displayOrderId').textContent;
    navigator.clipboard.writeText(id).then(() => showToast('Order ID copied!'));
}

/* ─── Download Receipt as PDF ─── */
function downloadReceipt() {
    const el = document.getElementById('receipt');
    html2canvas(el, { scale: 2, useCORS: true }).then(canvas => {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const img = canvas.toDataURL('image/png');
        const w = pdf.internal.pageSize.getWidth();
        const h = (canvas.height * w) / canvas.width;
        pdf.addImage(img, 'PNG', 0, 10, w, h);
        pdf.save('Shahi-Receipt-' + (orderData?.orderId || 'order') + '.pdf');
        showToast('Receipt downloaded!');
    });
}

/* ─── Share Receipt ─── */
function shareReceipt() {
    const text = `Order Confirmed!\nOrder ID: ${orderData?.orderId}\nTotal: ${CURRENCY}${orderData?.total}\nTrack: ${window.location.origin}/track.php?id=${orderData?.orderId}`;
    if (navigator.share) {
        navigator.share({ title: 'Shahi Peshawari Order', text });
    } else {
        window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
    }
}

/* ─── Flower Confetti Celebration ─── */
function launchConfetti() {
    const canvas = document.createElement('canvas');
    canvas.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;z-index:99999;pointer-events:none;';
    document.body.appendChild(canvas);
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const colors = ['#ff6b6b','#feca57','#48dbfb','#ff9ff3','#54a0ff','#00d2d3','#ff9f43','#ee5a24','#a29bfe','#55efc4','#fd79a8','#e17055'];
    const petals = [];

    for (let i = 0; i < 120; i++) {
        petals.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height - canvas.height,
            w: Math.random() * 14 + 8,
            h: Math.random() * 10 + 6,
            color: colors[Math.floor(Math.random() * colors.length)],
            speed: Math.random() * 3 + 2,
            angle: Math.random() * Math.PI * 2,
            spin: (Math.random() - 0.5) * 0.08,
            drift: (Math.random() - 0.5) * 1.5,
            opacity: Math.random() * 0.5 + 0.5
        });
    }

    function drawPetal(p) {
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.angle);
        ctx.globalAlpha = p.opacity;
        ctx.fillStyle = p.color;
        ctx.beginPath();
        ctx.ellipse(0, 0, p.w / 2, p.h / 2, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.beginPath();
        ctx.ellipse(p.w * 0.25, 0, p.w / 3, p.h / 3, 0.3, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }

    let frame = 0;
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        petals.forEach(p => {
            p.y += p.speed;
            p.x += p.drift + Math.sin(p.y * 0.01) * 0.8;
            p.angle += p.spin;
            if (p.y > canvas.height + 30) {
                p.y = -30;
                p.x = Math.random() * canvas.width;
            }
            drawPetal(p);
        });
        frame++;
        if (frame < 300) requestAnimationFrame(animate);
        else { ctx.clearRect(0, 0, canvas.width, canvas.height); canvas.remove(); }
    }
    animate();
}
launchConfetti();
</script>

<?php require_once 'footer.php'; ?>
