<?php
$activePage = 'categories';
require_once 'config.php';
require_once 'admin_sidebar.php';
?>

<div class="page-header">
    <h1><i class="fas fa-layer-group" style="color:var(--admin-blue);margin-right:8px"></i> Categories</h1>
    <button class="btn btn-primary" onclick="openCatModal()"><i class="fas fa-plus"></i> Add Category</button>
</div>

<!-- Stats -->
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-layer-group"></i></div>
        <div class="stat-info"><div class="stat-value" id="totalCats">0</div><div class="stat-label">Total Categories</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-box"></i></div>
        <div class="stat-info"><div class="stat-value" id="totalProducts">0</div><div class="stat-label">Total Products</div></div>
    </div>
</div>

<!-- Table -->
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Slug</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="catBody">
            <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--admin-grey)">Loading...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="catModal" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--admin-white);border-radius:16px;width:100%;max-width:420px;padding:28px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <h2 id="catModalTitle" style="font-size:1.2rem;font-weight:700">Add Category</h2>
            <button onclick="closeCatModal()" style="width:32px;height:32px;border-radius:8px;background:var(--admin-grey-bg);display:flex;align-items:center;justify-content:center;font-size:1rem;cursor:pointer;border:none">&times;</button>
        </div>
        <form id="catForm" onsubmit="saveCategory(event)">
            <input type="hidden" id="editCatId">
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Category Name *</label>
                <input type="text" id="catName" required placeholder="e.g. Classic" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem">
            </div>
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Slug (auto-generated)</label>
                <input type="text" id="catSlug" readonly style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;background:var(--admin-grey-bg)">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="closeCatModal()" style="padding:10px 24px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;background:var(--admin-white)">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
const DEFAULT_CATS = [
    { id: 'classic', name: 'Classic', slug: 'classic' },
    { id: 'premium', name: 'Premium', slug: 'premium' },
    { id: 'casual',  name: 'Casual',  slug: 'casual' },
    { id: 'modern',  name: 'Modern',  slug: 'modern' }
];

let categories = {};
let productCounts = {};

/* Auto-generate slug */
document.getElementById('catName').addEventListener('input', function() {
    document.getElementById('catSlug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
});

/* Load categories + count products per category */
db.ref('categories').on('value', snap => {
    const data = snap.val();
    if (data && Object.keys(data).length > 0) {
        categories = data;
    } else {
        /* Seed defaults if empty */
        DEFAULT_CATS.forEach(c => db.ref('categories/' + c.id).set(c));
        categories = {};
        DEFAULT_CATS.forEach(c => categories[c.id] = c);
    }
    renderCategories();
});

db.ref('products').on('value', snap => {
    const prods = snap.val() || {};
    productCounts = {};
    Object.values(prods).forEach(p => {
        const cat = (p.cat || 'other').toLowerCase();
        productCounts[cat] = (productCounts[cat] || 0) + 1;
    });
    renderCategories();
});

function renderCategories() {
    const tbody = document.getElementById('catBody');
    const keys = Object.keys(categories);
    document.getElementById('totalCats').textContent = keys.length;
    document.getElementById('totalProducts').textContent = Object.values(productCounts).reduce((a, b) => a + b, 0);
    let html = '';

    keys.forEach((id, i) => {
        const c = categories[id];
        const count = productCounts[id.toLowerCase()] || 0;
        html += `<tr>
            <td>${i + 1}</td>
            <td><strong>${c.name || id}</strong></td>
            <td><span style="font-family:monospace;font-size:.8rem;color:var(--admin-grey)">${c.slug || id}</span></td>
            <td><span class="badge badge-placed">${count} products</span></td>
            <td>
                <button class="btn btn-sm btn-edit" onclick="editCategory('${id}')"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-delete" onclick="deleteCategory('${id}')"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
    });

    if (keys.length === 0) {
        html = '<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--admin-grey)">No categories</td></tr>';
    }
    tbody.innerHTML = html;
}

function openCatModal() {
    document.getElementById('catModal').style.display = 'flex';
    document.getElementById('editCatId').value = '';
    document.getElementById('catModalTitle').textContent = 'Add Category';
    document.getElementById('catForm').reset();
}
function closeCatModal() { document.getElementById('catModal').style.display = 'none'; }

function editCategory(id) {
    const c = categories[id];
    if (!c) return;
    document.getElementById('catModal').style.display = 'flex';
    document.getElementById('editCatId').value = id;
    document.getElementById('catModalTitle').textContent = 'Edit Category';
    document.getElementById('catName').value = c.name || '';
    document.getElementById('catSlug').value = c.slug || id;
}

function saveCategory(e) {
    e.preventDefault();
    const id = document.getElementById('editCatId').value || document.getElementById('catSlug').value;
    const cat = {
        name: document.getElementById('catName').value.trim(),
        slug: document.getElementById('catSlug').value.trim()
    };
    db.ref('categories/' + id).set(cat).then(() => {
        showToast('Category saved!');
        closeCatModal();
    });
}

function deleteCategory(id) {
    const count = productCounts[id.toLowerCase()] || 0;
    if (count > 0) {
        if (!confirm(`"${id}" has ${count} products. Delete category anyway?`)) return;
    } else {
        if (!confirm('Delete this category?')) return;
    }
    db.ref('categories/' + id).remove().then(() => showToast('Category deleted'));
}
</script>

</main>
</div>
</body>
</html>
