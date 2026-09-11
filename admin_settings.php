<?php
$pageTitle = 'Settings';
require_once 'config.php';
$activePage = 'settings';
require_once 'admin_sidebar.php';
?>

<!-- ─── Login Gate ─── -->
<div id="loginGate" style="display:none; max-width:400px; margin:80px auto; text-align:center;">
    <div style="background:var(--admin-white); border-radius:12px; padding:40px 32px; box-shadow:0 2px 12px rgba(0,0,0,.06);">
        <div style="width:60px;height:60px;border-radius:16px;background:var(--admin-black);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fas fa-lock" style="color:#fff;font-size:1.3rem;"></i>
        </div>
        <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:4px;">SHAHI Admin</h2>
        <p style="font-size:.85rem; color:var(--admin-grey); margin-bottom:24px;">Sign in to manage settings</p>
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

<!-- ─── Settings Content ─── -->
<div id="settingsContent" style="display:none;">
    <div class="page-header">
        <h1>Settings</h1>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; max-width:900px;">

        <!-- WhatsApp Settings -->
        <div style="background:var(--admin-white); border-radius:12px; padding:28px; box-shadow:0 1px 4px rgba(0,0,0,.04);">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
                <div style="width:42px; height:42px; border-radius:10px; background:#e6fff9; display:flex; align-items:center; justify-content:center; color:#25d366; font-size:1.2rem;">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <h3 style="font-size:1rem; font-weight:700;">WhatsApp</h3>
                    <p style="font-size:.78rem; color:var(--admin-grey);">Floating button contact number</p>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:.8rem; font-weight:600; color:var(--admin-grey); margin-bottom:6px;">Phone Number (with country code)</label>
                <input type="tel" id="waNumber" placeholder="923001234567"
                       style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem;">
                <p style="font-size:.72rem; color:var(--admin-grey); margin-top:6px;">
                    Format: 92XXXXXXXXXX (no + or spaces). Example: 923001234567
                </p>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:.8rem; font-weight:600; color:var(--admin-grey); margin-bottom:6px;">Bubble Message</label>
                <input type="text" id="waMessage" placeholder="Need help? Chat with us!"
                       style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:.8rem; font-weight:600; color:var(--admin-grey); margin-bottom:6px;">Pre-filled Message (when user clicks)</label>
                <input type="text" id="waPreFilled" placeholder="Hi Shahi Peshawari! I need help."
                       style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem;">
            </div>

            <button onclick="saveWhatsApp()" id="saveWaBtn"
                    style="width:100%; padding:12px; background:var(--admin-black); color:#fff; border-radius:8px; font-size:.9rem; font-weight:600; transition:all .2s;">
                Save WhatsApp Settings
            </button>
            <p id="waStatus" style="font-size:.78rem; margin-top:10px; display:none;"></p>
        </div>

        <!-- Store Info Settings -->
        <div style="background:var(--admin-white); border-radius:12px; padding:28px; box-shadow:0 1px 4px rgba(0,0,0,.04);">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
                <div style="width:42px; height:42px; border-radius:10px; background:#e8f4fd; display:flex; align-items:center; justify-content:center; color:var(--admin-blue); font-size:1.2rem;">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <h3 style="font-size:1rem; font-weight:700;">Store Info</h3>
                    <p style="font-size:.78rem; color:var(--admin-grey);">Basic store contact details</p>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:.8rem; font-weight:600; color:var(--admin-grey); margin-bottom:6px;">Store Phone</label>
                <input type="tel" id="storePhone" placeholder="+92 300 1234567"
                       style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:.8rem; font-weight:600; color:var(--admin-grey); margin-bottom:6px;">Store Email</label>
                <input type="email" id="storeEmail" placeholder="info@shahichappal.com"
                       style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:.8rem; font-weight:600; color:var(--admin-grey); margin-bottom:6px;">Store Address</label>
                <input type="text" id="storeAddress" placeholder="Peshawar, KPK, Pakistan"
                       style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:.8rem; font-weight:600; color:var(--admin-grey); margin-bottom:6px;">Delivery Fee (Rs.)</label>
                <input type="number" id="deliveryFee" placeholder="150"
                       style="width:100%; padding:12px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.9rem;">
            </div>

            <button onclick="saveStoreInfo()" id="saveStoreBtn"
                    style="width:100%; padding:12px; background:var(--admin-black); color:#fff; border-radius:8px; font-size:.9rem; font-weight:600; transition:all .2s;">
                Save Store Info
            </button>
            <p id="storeStatus" style="font-size:.78rem; margin-top:10px; display:none;"></p>
        </div>

    </div>

    <!-- Payment Methods Settings -->
    <div style="margin-top:28px; background:var(--admin-white); border-radius:12px; padding:28px; box-shadow:0 1px 4px rgba(0,0,0,.04); max-width:900px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
            <div style="width:42px; height:42px; border-radius:10px; background:#fef3c7; display:flex; align-items:center; justify-content:center; color:#d97706; font-size:1.2rem;">
                <i class="fas fa-credit-card"></i>
            </div>
            <div>
                <h3 style="font-size:1rem; font-weight:700;">Payment Methods</h3>
                <p style="font-size:.78rem; color:var(--admin-grey);">Manage payment accounts and QR codes shown at checkout</p>
            </div>
        </div>

        <div id="paymentMethodsList"></div>

        <button onclick="addPaymentMethod()" style="width:100%; padding:12px; border:2px dashed var(--admin-border); border-radius:8px; font-size:.85rem; font-weight:600; cursor:pointer; background:transparent; color:var(--admin-blue); margin-top:12px; transition:all .2s;">
            <i class="fas fa-plus"></i> Add Payment Method
        </button>

        <button onclick="savePaymentMethods()" id="savePayBtn" style="width:100%; padding:12px; background:var(--admin-blue); color:#fff; border:none; border-radius:8px; font-size:.9rem; font-weight:600; cursor:pointer; margin-top:12px; transition:all .2s;">
            <i class="fas fa-save"></i> Save Payment Methods
        </button>
        <p id="payStatus" style="font-size:.78rem; margin-top:10px; display:none;"></p>
    </div>

    <!-- Current Preview -->
    <div style="margin-top:28px; background:var(--admin-white); border-radius:12px; padding:28px; box-shadow:0 1px 4px rgba(0,0,0,.04); max-width:900px;">
        <h3 style="font-size:1rem; font-weight:700; margin-bottom:16px;">Current WhatsApp Link Preview</h3>
        <div id="waPreview" style="padding:14px; background:var(--admin-grey-bg); border-radius:8px; font-size:.85rem; word-break:break-all; color:var(--admin-grey);">
            Loading...
        </div>
    </div>
</div>

<script>
/* ─── Login Gate ─── */
function checkAdminAuth() {
    if (localStorage.getItem('admin_logged_in') === 'true') {
        document.getElementById('loginGate').style.display = 'none';
        document.getElementById('settingsContent').style.display = 'block';
        document.body.classList.add('admin-authed');
        loadSettings();
    } else {
        document.getElementById('loginGate').style.display = 'block';
        document.getElementById('settingsContent').style.display = 'none';
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

/* ─── Load Settings from Firebase ─── */
function loadSettings() {
    db.ref('settings').on('value', snap => {
        const s = snap.val() || {};

        document.getElementById('waNumber').value = s.waNumber || '';
        document.getElementById('waMessage').value = s.waMessage || 'Need help? Chat with us!';
        document.getElementById('waPreFilled').value = s.waPreFilled || 'Hi Shahi Peshawari! I need help.';
        document.getElementById('storePhone').value = s.storePhone || '';
        document.getElementById('storeEmail').value = s.storeEmail || '';
        document.getElementById('storeAddress').value = s.storeAddress || '';
        document.getElementById('deliveryFee').value = s.deliveryFee || '';

        updatePreview(s.waNumber, s.waPreFilled);
    });
    loadPaymentMethods();
}

function updatePreview(num, msg) {
    const el = document.getElementById('waPreview');
    if (!num) {
        el.innerHTML = '<span style="color:var(--admin-red);">No WhatsApp number set yet.</span>';
        return;
    }
    const url = 'https://wa.me/' + num + '?text=' + encodeURIComponent(msg || 'Hi Shahi Peshawari!');
    el.innerHTML = '<a href="' + url + '" target="_blank" style="color:var(--admin-blue);text-decoration:underline;">' + url + '</a>';
}

/* ─── Save WhatsApp ─── */
function saveWhatsApp() {
    const btn = document.getElementById('saveWaBtn');
    const status = document.getElementById('waStatus');
    const num = document.getElementById('waNumber').value.trim().replace(/[^0-9]/g, '');
    const msg = document.getElementById('waMessage').value.trim();
    const pre = document.getElementById('waPreFilled').value.trim();

    if (!num) { showStatus(status, 'Enter a phone number', false); return; }

    btn.textContent = 'Saving...';
    btn.disabled = true;

    db.ref('settings').update({
        waNumber: num,
        waMessage: msg || 'Need help? Chat with us!',
        waPreFilled: pre || 'Hi Shahi Peshawari! I need help.'
    }).then(() => {
        showStatus(status, 'WhatsApp settings saved!', true);
        btn.textContent = 'Save WhatsApp Settings';
        btn.disabled = false;
        updatePreview(num, pre);
    }).catch(err => {
        showStatus(status, 'Error: ' + err.message, false);
        btn.textContent = 'Save WhatsApp Settings';
        btn.disabled = false;
    });
}

/* ─── Save Store Info ─── */
function saveStoreInfo() {
    const btn = document.getElementById('saveStoreBtn');
    const status = document.getElementById('storeStatus');

    btn.textContent = 'Saving...';
    btn.disabled = true;

    db.ref('settings').update({
        storePhone: document.getElementById('storePhone').value.trim(),
        storeEmail: document.getElementById('storeEmail').value.trim(),
        storeAddress: document.getElementById('storeAddress').value.trim(),
        deliveryFee: Number(document.getElementById('deliveryFee').value) || 150
    }).then(() => {
        showStatus(status, 'Store info saved!', true);
        btn.textContent = 'Save Store Info';
        btn.disabled = false;
    }).catch(err => {
        showStatus(status, 'Error: ' + err.message, false);
        btn.textContent = 'Save Store Info';
        btn.disabled = false;
    });
}

function showStatus(el, msg, success) {
    el.textContent = msg;
    el.style.color = success ? 'var(--admin-green)' : 'var(--admin-red)';
    el.style.display = 'block';
    setTimeout(() => { el.style.display = 'none'; }, 3000);
}

/* ─── Payment Methods ─── */
let paymentMethods = [];

function loadPaymentMethods() {
    db.ref('settings/paymentMethods').on('value', snap => {
        paymentMethods = [];
        const data = snap.val();
        if (data) {
            if (Array.isArray(data)) {
                paymentMethods = data;
            } else {
                Object.keys(data).forEach(k => paymentMethods.push(data[k]));
            }
        }
        if (paymentMethods.length === 0) {
            paymentMethods = [
                { id: 'pm1', name: 'EasyPaisa', type: 'mobile', account: '', iban: '', qrUrl: '' },
                { id: 'pm2', name: 'JazzCash', type: 'mobile', account: '', iban: '', qrUrl: '' },
                { id: 'pm3', name: 'Bank Transfer', type: 'bank', account: '', iban: '', qrUrl: '' }
            ];
        }
        renderPaymentMethods();
    });
}

function renderPaymentMethods() {
    const container = document.getElementById('paymentMethodsList');
    container.innerHTML = paymentMethods.map((pm, i) => {
        const icon = pm.type === 'bank' ? 'fa-university' : 'fa-mobile-alt';
        const iconBg = pm.type === 'bank' ? '#e8f4fd' : '#f0fdf4';
        const iconColor = pm.type === 'bank' ? 'var(--admin-blue)' : 'var(--admin-green)';
        return '<div style="border:1px solid var(--admin-border); border-radius:10px; padding:16px; margin-bottom:12px; background:var(--admin-grey-bg);" id="pm_' + i + '">'
            + '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">'
            + '<div style="display:flex; align-items:center; gap:10px;">'
            + '<div style="width:36px; height:36px; border-radius:8px; background:' + iconBg + '; display:flex; align-items:center; justify-content:center; color:' + iconColor + ';"><i class="fas ' + icon + '"></i></div>'
            + '<input type="text" value="' + (pm.name || '') + '" onchange="paymentMethods[' + i + '].name=this.value" placeholder="Method Name" style="border:none; background:transparent; font-weight:700; font-size:.95rem; outline:none; width:160px;">'
            + '</div>'
            + '<div style="display:flex; gap:6px; align-items:center;">'
            + '<select onchange="paymentMethods[' + i + '].type=this.value; renderPaymentMethods()" style="padding:5px 10px; border:1px solid var(--admin-border); border-radius:6px; font-size:.78rem; background:#fff;">'
            + '<option value="mobile" ' + (pm.type === 'mobile' ? 'selected' : '') + '>Mobile</option>'
            + '<option value="bank" ' + (pm.type === 'bank' ? 'selected' : '') + '>Bank</option>'
            + '</select>'
            + '<button onclick="removePaymentMethod(' + i + ')" style="width:30px; height:30px; border-radius:6px; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.8rem;"><i class="fas fa-trash"></i></button>'
            + '</div>'
            + '</div>'
            + '<div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">'
            + '<div><label style="display:block; font-size:.72rem; font-weight:600; color:var(--admin-grey); margin-bottom:4px;">Account / Mobile Number</label>'
            + '<input type="text" value="' + (pm.account || '') + '" onchange="paymentMethods[' + i + '].account=this.value" placeholder="0300-1234567" style="width:100%; padding:9px 12px; border:1px solid var(--admin-border); border-radius:8px; font-size:.82rem; box-sizing:border-box;"></div>'
            + '<div><label style="display:block; font-size:.72rem; font-weight:600; color:var(--admin-grey); margin-bottom:4px;">IBAN (optional)</label>'
            + '<input type="text" value="' + (pm.iban || '') + '" onchange="paymentMethods[' + i + '].iban=this.value" placeholder="PK36SCBL0000001123456702" style="width:100%; padding:9px 12px; border:1px solid var(--admin-border); border-radius:8px; font-size:.82rem; box-sizing:border-box;"></div>'
            + '</div>'
            + '<div style="margin-bottom:12px;"><label style="display:block; font-size:.72rem; font-weight:600; color:var(--admin-grey); margin-bottom:4px;">Account Title</label>'
            + '<input type="text" value="' + (pm.title || '') + '" onchange="paymentMethods[' + i + '].title=this.value" placeholder="Shahi Peshawari" style="width:100%; padding:9px 12px; border:1px solid var(--admin-border); border-radius:8px; font-size:.82rem; box-sizing:border-box;"></div>'
            + '<div><label style="display:block; font-size:.72rem; font-weight:600; color:var(--admin-grey); margin-bottom:4px;">QR Code</label>'
            + '<div style="display:flex; gap:10px; align-items:flex-start;">'
            + '<div style="flex:1;"><input type="text" value="' + (pm.qrUrl || '') + '" onchange="paymentMethods[' + i + '].qrUrl=this.value; renderPaymentMethods()" placeholder="https://... or uploads/..." style="width:100%; padding:9px 12px; border:1px solid var(--admin-border); border-radius:8px; font-size:.82rem; box-sizing:border-box;"></div>'
            + '<div style="flex-shrink:0;"><input type="file" accept="image/*" onchange="uploadQR(' + i + ', this.files[0])" id="qrFile_' + i + '" style="display:none">'
            + '<button onclick="document.getElementById(\'qrFile_' + i + '\').click()" style="padding:9px 14px; border:1px solid var(--admin-border); border-radius:8px; font-size:.82rem; cursor:pointer; background:#fff; white-space:nowrap;"><i class="fas fa-upload"></i> Upload</button></div>'
            + '</div>'
            + (pm.qrUrl ? '<div style="margin-top:8px; text-align:center;"><img src="' + pm.qrUrl + '" style="max-width:120px; max-height:120px; border-radius:8px; border:2px solid var(--admin-border); background:#fff; padding:4px;" onerror="this.style.display=\'none\'"></div>' : '')
            + '</div>'
            + '</div>';
    }).join('');
}

function addPaymentMethod() {
    paymentMethods.push({
        id: 'pm' + Date.now(),
        name: 'New Method',
        type: 'mobile',
        account: '',
        iban: '',
        title: '',
        qrUrl: ''
    });
    renderPaymentMethods();
}

function removePaymentMethod(index) {
    if (!confirm('Remove this payment method?')) return;
    paymentMethods.splice(index, 1);
    renderPaymentMethods();
}

function uploadQR(index, file) {
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { showStatus(document.getElementById('payStatus'), 'File too large (max 5MB)', false); return; }

    const formData = new FormData();
    formData.append('images[]', file);

    fetch('upload_product_image.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.ok && data.uploaded && data.uploaded[0]) {
                paymentMethods[index].qrUrl = data.uploaded[0];
                renderPaymentMethods();
                showStatus(document.getElementById('payStatus'), 'QR uploaded!', true);
            } else {
                showStatus(document.getElementById('payStatus'), 'Upload failed', false);
            }
        })
        .catch(err => showStatus(document.getElementById('payStatus'), 'Error: ' + err.message, false));
}

function savePaymentMethods() {
    const btn = document.getElementById('savePayBtn');
    const status = document.getElementById('payStatus');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    btn.disabled = true;

    db.ref('settings/paymentMethods').set(paymentMethods).then(() => {
        showStatus(status, 'Payment methods saved!', true);
        btn.innerHTML = '<i class="fas fa-save"></i> Save Payment Methods';
        btn.disabled = false;
    }).catch(err => {
        showStatus(status, 'Error: ' + err.message, false);
        btn.innerHTML = '<i class="fas fa-save"></i> Save Payment Methods';
        btn.disabled = false;
    });
}

checkAdminAuth();
</script>

</main>
</div>
</body>
</html>
