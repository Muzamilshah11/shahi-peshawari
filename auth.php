<?php
$pageTitle = 'Login / Register';
require_once 'config.php';
require_once 'header.php';
?>

<style>
.auth-wrapper { max-width:440px; margin:40px auto; }
.auth-card { background:var(--white); border-radius:var(--radius); box-shadow:var(--shadow-lg); overflow:hidden; }
.auth-tabs { display:flex; border-bottom:2px solid var(--grey-border); }
.auth-tab { flex:1; padding:14px; text-align:center; font-weight:600; font-size:.9rem; cursor:pointer; transition:var(--transition); color:var(--grey-text); border-bottom:2px solid transparent; margin-bottom:-2px; }
.auth-tab.active { color:var(--blue); border-bottom-color:var(--blue); }
.auth-body { padding:28px 24px; }
.auth-form { display:none; }
.auth-form.active { display:block; }
.auth-input { width:100%; padding:12px 14px; border:2px solid var(--grey-border); border-radius:var(--radius-sm); font-size:.88rem; margin-bottom:14px; transition:var(--transition); }
.auth-input:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(9,132,227,.1); }
.auth-btn { width:100%; padding:13px; border:none; border-radius:var(--radius-sm); font-size:.9rem; font-weight:600; cursor:pointer; transition:var(--transition); }
.auth-btn-primary { background:var(--blue); color:var(--white); }
.auth-btn-primary:hover { background:var(--blue-dark); }
.auth-btn-google { background:var(--white); color:var(--black); border:2px solid var(--grey-border); margin-top:12px; display:flex; align-items:center; justify-content:center; gap:10px; }
.auth-btn-google:hover { border-color:#4285f4; background:#f8f9fa; }
.auth-btn-google img { width:20px; height:20px; }
.auth-divider { display:flex; align-items:center; gap:12px; margin:18px 0; color:var(--grey-text); font-size:.8rem; }
.auth-divider::before, .auth-divider::after { content:''; flex:1; height:1px; background:var(--grey-border); }
.auth-msg { padding:10px 14px; border-radius:var(--radius-sm); font-size:.82rem; margin-bottom:14px; display:none; }
.auth-msg.error { display:block; background:#fef2f2; color:var(--red); border:1px solid #fecaca; }
.auth-msg.success { display:block; background:#f0fdf4; color:var(--green); border:1px solid #bbf7d0; }
.auth-avatar { width:80px; height:80px; border-radius:50%; background:var(--blue-light); display:flex; align-items:center; justify-content:center; margin:0 auto 20px; }
.auth-avatar i { font-size:2rem; color:var(--blue); }
.user-menu { position:relative; }
.user-menu-btn { display:flex; align-items:center; gap:8px; padding:8px 14px; border-radius:50px; background:var(--grey-bg); cursor:pointer; font-size:.82rem; font-weight:600; color:var(--black); transition:var(--transition); border:none; }
.user-menu-btn:hover { background:var(--blue-light); color:var(--blue); }
.user-menu-btn img { width:28px; height:28px; border-radius:50%; }
.user-dropdown { position:absolute; top:calc(100% + 8px); right:0; background:var(--white); border-radius:var(--radius); box-shadow:var(--shadow-lg); min-width:200px; display:none; z-index:100; overflow:hidden; }
.user-dropdown.show { display:block; }
.user-dropdown a, .user-dropdown button { display:flex; align-items:center; gap:10px; padding:12px 16px; font-size:.85rem; color:var(--black); transition:var(--transition); width:100%; text-align:left; background:none; border:none; cursor:pointer; }
.user-dropdown a:hover, .user-dropdown button:hover { background:var(--grey-bg); }
.user-dropdown .signout-link { color:var(--red); }
</style>

<div class="container">
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-tabs">
            <div class="auth-tab active" onclick="showAuthTab('login')">Login</div>
            <div class="auth-tab" onclick="showAuthTab('register')">Register</div>
        </div>
        <div class="auth-body">
            <div class="auth-avatar"><i class="fas fa-user"></i></div>

            <!-- Login Form -->
            <div class="auth-form active" id="loginForm">
                <div class="auth-msg" id="loginMsg"></div>
                <input type="email" class="auth-input" id="loginEmail" placeholder="Email address">
                <input type="password" class="auth-input" id="loginPass" placeholder="Password">
                <button class="auth-btn auth-btn-primary" onclick="doLogin()"><i class="fas fa-sign-in-alt"></i> Login</button>
                <div class="auth-divider">or</div>
                <button class="auth-btn auth-btn-google" onclick="doGoogleLogin()">
                    <img src="https://www.gstatic.com/firebasejs/24.7.0/firebaseui-en/images/google.svg" alt="Google"> Continue with Google
                </button>
            </div>

            <!-- Register Form -->
            <div class="auth-form" id="registerForm">
                <div class="auth-msg" id="registerMsg"></div>
                <input type="text" class="auth-input" id="regName" placeholder="Full Name">
                <input type="email" class="auth-input" id="regEmail" placeholder="Email address">
                <input type="tel" class="auth-input" id="regPhone" placeholder="Phone number">
                <input type="password" class="auth-input" id="regPass" placeholder="Password (min 6 chars)">
                <button class="auth-btn auth-btn-primary" onclick="doRegister()"><i class="fas fa-user-plus"></i> Create Account</button>
                <div class="auth-divider">or</div>
                <button class="auth-btn auth-btn-google" onclick="doGoogleLogin()">
                    <img src="https://www.gstatic.com/firebasejs/24.7.0/firebaseui-en/images/google.svg" alt="Google"> Sign up with Google
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function showAuthTab(tab) {
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
    document.querySelector('.auth-tab[onclick*="' + tab + '"]').classList.add('active');
    document.getElementById(tab === 'login' ? 'loginForm' : 'registerForm').classList.add('active');
}

function showMsg(id, msg, type) {
    var el = document.getElementById(id);
    el.textContent = msg;
    el.className = 'auth-msg ' + type;
}

function doLogin() {
    var email = document.getElementById('loginEmail').value.trim();
    var pass = document.getElementById('loginPass').value;
    if (!email || !pass) { showMsg('loginMsg', 'Fill in all fields', 'error'); return; }
    firebase.auth().signInWithEmailAndPassword(email, pass)
        .then(function(cred) {
            saveUserProfile(cred.user);
            showMsg('loginMsg', 'Login successful!', 'success');
            setTimeout(function() { window.location.href = 'index.php'; }, 800);
        })
        .catch(function(err) {
            showMsg('loginMsg', err.message, 'error');
        });
}

function doRegister() {
    var name = document.getElementById('regName').value.trim();
    var email = document.getElementById('regEmail').value.trim();
    var phone = document.getElementById('regPhone').value.trim();
    var pass = document.getElementById('regPass').value;
    if (!name || !email || !pass) { showMsg('registerMsg', 'Fill in all required fields', 'error'); return; }
    if (pass.length < 6) { showMsg('registerMsg', 'Password must be at least 6 characters', 'error'); return; }
    firebase.auth().createUserWithEmailAndPassword(email, pass)
        .then(function(cred) {
            return cred.user.updateProfile({ displayName: name }).then(function() {
                db.ref('users/' + cred.user.uid).set({
                    name: name, email: email, phone: phone,
                    role: 'customer', createdAt: Date.now()
                });
                saveUserProfile(cred.user);
                showMsg('registerMsg', 'Account created!', 'success');
                setTimeout(function() { window.location.href = 'index.php'; }, 800);
            });
        })
        .catch(function(err) {
            showMsg('registerMsg', err.message, 'error');
        });
}

function doGoogleLogin() {
    var provider = new firebase.auth.GoogleAuthProvider();
    firebase.auth().signInWithPopup(provider)
        .then(function(result) {
            var user = result.user;
            db.ref('users/' + user.uid).once('value', function(snap) {
                if (!snap.val()) {
                    db.ref('users/' + user.uid).set({
                        name: user.displayName || '', email: user.email || '',
                        phone: user.phoneNumber || '', photo: user.photoURL || '',
                        role: 'customer', createdAt: Date.now()
                    });
                }
            });
            saveUserProfile(user);
            setTimeout(function() { window.location.href = 'index.php'; }, 800);
        })
        .catch(function(err) {
            showMsg('loginMsg', err.message, 'error');
            showMsg('registerMsg', err.message, 'error');
        });
}

function saveUserProfile(user) {
    var profile = {
        uid: user.uid,
        name: user.displayName || '',
        email: user.email || '',
        photo: user.photoURL || ''
    };
    localStorage.setItem('shahi_user', JSON.stringify(profile));
}
</script>

<?php require_once 'footer.php'; ?>
