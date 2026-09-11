<?php
$activePage = 'inventory';
require_once 'config.php';
require_once 'admin_sidebar.php';
?>

<div class="page-header">
    <h1><i class="fas fa-boxes-stacked" style="color:var(--admin-blue);margin-right:8px"></i> Products / Inventory</h1>
    <div style="display:flex;gap:8px">
        <button class="btn" onclick="syncToFirebase()" style="background:#e6fff9;color:var(--admin-green)"><i class="fas fa-sync"></i> Sync Static Products</button>
        <button class="btn btn-primary" onclick="openModal()"><i class="fas fa-plus"></i> Add Product</button>
    </div>
</div>

<!-- Filters -->
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center">
    <input type="text" id="searchInput" placeholder="Search products..." oninput="filterProducts()" style="padding:10px 16px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;flex:1;min-width:200px">
    <select id="catFilter" onchange="filterProducts()" style="padding:10px 16px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem">
        <option value="all">All Categories</option>
        <option value="classic">Classic</option>
        <option value="premium">Premium</option>
        <option value="casual">Casual</option>
        <option value="modern">Modern</option>
    </select>
</div>

<!-- Products Table -->
<div class="admin-table-wrap">
    <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
        <tbody id="productsTableBody">
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--admin-grey)">Loading products...</td></tr>
        </tbody>
    </table>
</div>

<!-- Product Modal -->
<div id="productModal" class="modal" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--admin-white);border-radius:16px;width:100%;max-width:600px;max-height:90vh;overflow-y:auto;padding:28px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <h2 id="modalTitle" style="font-size:1.2rem;font-weight:700">Add Product</h2>
            <button onclick="closeModal()" style="width:32px;height:32px;border-radius:8px;background:var(--admin-grey-bg);display:flex;align-items:center;justify-content:center;font-size:1rem;cursor:pointer;border:none">&times;</button>
        </div>
        <form id="productForm" onsubmit="saveProduct(event)">
            <input type="hidden" id="editId">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Product Name *</label>
                    <input type="text" id="pName" required style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem">
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Category *</label>
                    <select id="pCat" required style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem">
                        <option value="classic">Classic</option>
                        <option value="premium">Premium</option>
                        <option value="casual">Casual</option>
                        <option value="modern">Modern</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Price (Rs.) *</label>
                    <input type="number" id="pPrice" required min="0" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem">
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Old Price (Rs.)</label>
                    <input type="number" id="pOldPrice" min="0" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem">
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Stock Quantity</label>
                    <input type="number" id="pStock" min="0" placeholder="999 for unlimited" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem">
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Star Rating</label>
                    <div id="starRatingInput" style="display:flex;gap:4px;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;cursor:pointer">
                        <i class="fas fa-star" data-star="1" style="font-size:1.3rem;color:#d1d5db;transition:color .15s"></i>
                        <i class="fas fa-star" data-star="2" style="font-size:1.3rem;color:#d1d5db;transition:color .15s"></i>
                        <i class="fas fa-star" data-star="3" style="font-size:1.3rem;color:#d1d5db;transition:color .15s"></i>
                        <i class="fas fa-star" data-star="4" style="font-size:1.3rem;color:#d1d5db;transition:color .15s"></i>
                        <i class="fas fa-star" data-star="5" style="font-size:1.3rem;color:#d1d5db;transition:color .15s"></i>
                        <span id="starRatingVal" style="font-size:.8rem;color:var(--admin-grey);margin-left:8px;line-height:2">0</span>
                    </div>
                    <input type="hidden" id="pRating" value="0">
                </div>
            </div>
            <div style="margin-top:16px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Short Detail</label>
                <textarea id="pDetail" rows="3" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;resize:vertical"></textarea>
            </div>
            <div style="margin-top:16px">
                <label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:6px">Product Images</label>
                <!-- Upload Area -->
                <div id="uploadArea" style="border:2px dashed var(--admin-border);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all .2s;background:var(--admin-grey-bg)" onclick="document.getElementById('fileInput').click()" ondragover="event.preventDefault();this.style.borderColor='var(--admin-blue)';this.style.background='#f0f7ff'" ondragleave="this.style.borderColor='var(--admin-border)';this.style.background='var(--admin-grey-bg)'" ondrop="event.preventDefault();handleFileDrop(event)">
                    <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:var(--admin-blue);margin-bottom:8px;display:block"></i>
                    <p style="font-size:.85rem;font-weight:600;margin-bottom:4px">Click or drag images here</p>
                    <p style="font-size:.72rem;color:var(--admin-grey)">JPG, PNG, GIF, WebP - Max 5MB each</p>
                </div>
                <input type="file" id="fileInput" multiple accept="image/*" style="display:none" onchange="handleFileSelect(this.files)">
                <!-- Upload Progress -->
                <div id="uploadProgress" style="display:none;margin-top:8px;padding:8px 12px;background:#f0f7ff;border-radius:8px;font-size:.8rem;color:var(--admin-blue)">
                    <i class="fas fa-spinner fa-spin"></i> Uploading...
                </div>
                <!-- Image Preview -->
                <div id="imagePreview" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px"></div>
                <!-- URL fallback -->
                <details style="margin-top:10px">
                    <summary style="font-size:.75rem;color:var(--admin-grey);cursor:pointer">Or enter image URLs manually</summary>
                    <textarea id="pImages" rows="2" placeholder="uploads/products/1.1.png&#10;https://example.com/image.jpg" style="width:100%;padding:10px 14px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;resize:vertical;font-family:monospace;margin-top:6px"></textarea>
                </details>
            </div>
            <div style="display:flex;gap:10px;margin-top:24px;justify-content:flex-end">
                <button type="button" onclick="closeModal()" style="padding:10px 24px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;background:var(--admin-white)">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding:10px 24px;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;background:var(--admin-blue);color:#fff">Save Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirm Modal -->
<div id="deleteModal" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--admin-white);border-radius:16px;width:100%;max-width:400px;padding:28px;text-align:center">
        <div style="width:60px;height:60px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.5rem;color:var(--admin-red)"><i class="fas fa-trash"></i></div>
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:8px">Delete Product?</h3>
        <p style="font-size:.85rem;color:var(--admin-grey);margin-bottom:24px">This action cannot be undone.</p>
        <input type="hidden" id="deleteId">
        <div style="display:flex;gap:10px;justify-content:center">
            <button onclick="closeDeleteModal()" style="padding:10px 24px;border:1px solid var(--admin-border);border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;background:var(--admin-white)">Cancel</button>
            <button onclick="confirmDelete()" style="padding:10px 24px;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;background:var(--admin-red);color:#fff">Delete</button>
        </div>
    </div>
</div>

<style>
.btn { padding: 10px 20px; border-radius: 8px; font-size: .85rem; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 6px; transition: var(--transition); }
.btn-primary { background: var(--admin-blue); color: #fff; }
.btn-primary:hover { background: #0769b5; }
.btn-sm { padding: 6px 12px; font-size: .75rem; }
.btn-edit { background: #e8f4fd; color: var(--admin-blue); }
.btn-edit:hover { background: var(--admin-blue); color: #fff; }
.btn-delete { background: #fef2f2; color: var(--admin-red); }
.btn-delete:hover { background: var(--admin-red); color: #fff; }
.prod-thumb { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; background: var(--admin-grey-bg); }
</style>

<script>
let allProducts = {};

/* ─── Static Products (same as product_data.php) ─── */
const STATIC_PRODUCTS = [
    {id:'p1',name:'Khalid Classic Brown',cat:'classic',price:1800,oldPrice:2200,stock:999,images:['uploads/products/1.1.png','uploads/products/1.2.png','uploads/products/1.3.png'],shortDetail:'Hand-stitched pure leather with durable rubber sole.'},
    {id:'p2',name:'Shahi Beige Suede',cat:'casual',price:2200,oldPrice:2800,stock:999,images:['uploads/products/2.1.png','uploads/products/2.2.png','uploads/products/2.3.png'],shortDetail:'Soft suede leather with brass buckle strap.'},
    {id:'p3',name:'Royal Black Premium',cat:'premium',price:2800,oldPrice:3400,stock:999,images:['uploads/products/3.1.png','uploads/products/3.2.png','uploads/products/3.3.png'],shortDetail:'Glossy black leather with gold buckle.'},
    {id:'p4',name:'Peshawari Camel',cat:'classic',price:1600,oldPrice:2000,stock:999,images:['uploads/products/4.1.png','uploads/products/4.2.png','uploads/products/4.3.png'],shortDetail:'Traditional camel tone leather with sturdy rubber sole.'},
    {id:'p5',name:'Shahi Tan Classic',cat:'classic',price:1900,oldPrice:2400,stock:999,images:['uploads/products/5.1.png','uploads/products/5.2.png','uploads/products/5.3.png'],shortDetail:'Rich tan leather with cross-strap design.'},
    {id:'p6',name:'Khalid Dark Brown',cat:'classic',price:1700,oldPrice:2100,stock:999,images:['uploads/products/6.1.png','uploads/products/6.2.png','uploads/products/6.3.png'],shortDetail:'Deep chocolate brown leather with traditional cross pattern.'},
    {id:'p7',name:'Premium Brown Elite',cat:'premium',price:3200,oldPrice:3900,stock:999,images:['uploads/products/7.1.png','uploads/products/7.2.png','uploads/products/7.3.png'],shortDetail:'Hand-polished brown leather with silver buckle.'},
    {id:'p8',name:'Shahi Black Gold',cat:'premium',price:3500,oldPrice:4200,stock:999,images:['uploads/products/8.1.png','uploads/products/8.2.png','uploads/products/8.3.png'],shortDetail:'Matte black leather with gold-tone buckle accent.'},
    {id:'p9',name:'Peshawari Ebony',cat:'premium',price:3000,oldPrice:3600,stock:999,images:['uploads/products/9.1.png','uploads/products/9.2.png'],shortDetail:'Solid ebony black leather with classic cross-strap.'},
    {id:'p10',name:'Royal Gloss Black',cat:'premium',price:3800,oldPrice:4500,stock:999,images:['uploads/products/10.1.png','uploads/products/10.2.png','uploads/products/10.3.png'],shortDetail:'Mirror-finish black leather with silver buckle.'},
    {id:'p11',name:'Khalid Brown Classic',cat:'classic',price:1850,oldPrice:2300,stock:999,images:['uploads/products/11.1.png','uploads/products/11.2.png','uploads/products/11.3.png'],shortDetail:'Heritage brown leather with hand-carved details.'},
    {id:'p12',name:'Shahi Maroon Elite',cat:'premium',price:3400,oldPrice:4100,stock:999,images:['uploads/products/12.1.png','uploads/products/12.2.png','uploads/products/12.3.png'],shortDetail:'Deep maroon leather with gold buckle.'},
    {id:'p13',name:'Premium Tan Deluxe',cat:'premium',price:3100,oldPrice:3800,stock:999,images:['uploads/products/13.1.png','uploads/products/13.2.png','uploads/products/13.3.png'],shortDetail:'Double-stitched tan leather with comfort insole.'},
    {id:'p14',name:'Peshawari Copper',cat:'premium',price:3600,oldPrice:4300,stock:999,images:['uploads/products/14.1.png','uploads/products/14.2.png','uploads/products/14.3.png'],shortDetail:'Copper-toned leather with antique buckle finish.'},
    {id:'p15',name:'Shahi Honey Gold',cat:'classic',price:2100,oldPrice:2600,stock:999,images:['uploads/products/15.1.png','uploads/products/15.2.png','uploads/products/15.3.png'],shortDetail:'Warm honey-toned leather with brass buckle.'},
    {id:'p16',name:'Royal Walnut',cat:'premium',price:3300,oldPrice:4000,stock:999,images:['uploads/products/16.1.png','uploads/products/16.2.png','uploads/products/16.3.png'],shortDetail:'Rich walnut brown with hand-finished edges.'},
    {id:'p17',name:'Khalid Casual Black',cat:'casual',price:1500,oldPrice:1900,stock:999,images:['uploads/products/17.1.png','uploads/products/17.2.png','uploads/products/17.3.png'],shortDetail:'Everyday black leather with rubber sole.'},
    {id:'p18',name:'Shahi Sand Beige',cat:'casual',price:1650,oldPrice:2100,stock:999,images:['uploads/products/18.1.png','uploads/products/18.2.png','uploads/products/18.3.png'],shortDetail:'Lightweight beige leather with ankle strap.'},
    {id:'p19',name:'Peshawari Olive',cat:'casual',price:1800,oldPrice:2300,stock:999,images:['uploads/products/19.1.png','uploads/products/19.2.png','uploads/products/19.3.png'],shortDetail:'Unique olive green suede with leather trim.'},
    {id:'p20',name:'Shahi Textured Black',cat:'premium',price:2900,oldPrice:3500,stock:999,images:['uploads/products/20.1.png','uploads/products/20.2.png','uploads/products/20.3.png'],shortDetail:'Pebbled texture black leather with gold buckle.'},
    {id:'p21',name:'Classic Caramel',cat:'classic',price:1950,oldPrice:2500,stock:999,images:['uploads/products/21.1.png','uploads/products/21.2.png','uploads/products/21.3.png'],shortDetail:'Warm caramel leather with cross-strap design.'},
    {id:'p22',name:'Khalid Espresso',cat:'classic',price:1750,oldPrice:2200,stock:999,images:['uploads/products/22.1.png','uploads/products/22.2.png','uploads/products/22.3.png'],shortDetail:'Deep espresso brown leather with contrast stitching.'},
    {id:'p23',name:'Shahi Cognac',cat:'casual',price:1600,oldPrice:2000,stock:999,images:['uploads/products/23.1.png','uploads/products/23.2.png','uploads/products/23.3.png'],shortDetail:'Cognac-toned leather with cutout design.'},
    {id:'p24',name:'Premium Mocha',cat:'premium',price:2700,oldPrice:3300,stock:999,images:['uploads/products/24.1.png','uploads/products/24.2.png','uploads/products/24.3.png'],shortDetail:'Smooth mocha leather with dual-buckle strap.'},
    {id:'p25',name:'Shahi Forest Green',cat:'modern',price:2500,oldPrice:3100,stock:999,images:['uploads/products/25.1.png','uploads/products/25.2.png','uploads/products/25.3.png'],shortDetail:'Forest green suede with leather accents.'},
    {id:'p26',name:'Khalid Slate Grey',cat:'modern',price:2300,oldPrice:2900,stock:999,images:['uploads/products/26.1.png','uploads/products/26.2.png','uploads/products/26.3.png'],shortDetail:'Slate grey leather with minimalist design.'},
    {id:'p27',name:'Peshawari Indigo',cat:'modern',price:2600,oldPrice:3200,stock:999,images:['uploads/products/27.1.png','uploads/products/27.2.png','uploads/products/27.3.png'],shortDetail:'Deep indigo leather with contrast sole.'},
    {id:'p28',name:'Shahi Burgundy',cat:'modern',price:2800,oldPrice:3400,stock:999,images:['uploads/products/28.1.png','uploads/products/28.2.png','uploads/products/28.3.png'],shortDetail:'Rich burgundy leather with antique brass buckle.'},
    {id:'p29',name:'Premium Charcoal',cat:'modern',price:2400,oldPrice:3000,stock:999,images:['uploads/products/29.1.png','uploads/products/29.2.png','uploads/products/29.3.png'],shortDetail:'Charcoal matte leather with sleek profile.'},
    {id:'p30',name:'Classic Heritage Brown',cat:'classic',price:2000,oldPrice:2500,stock:999,images:['uploads/products/30.1.png','uploads/products/30.2.png','uploads/products/30.3.png'],shortDetail:'Heritage brown with brass buckle and embossed footbed.'},
    {id:'p32',name:'Khalid Midnight Blue',cat:'modern',price:2700,oldPrice:3300,stock:999,images:['uploads/products/32.1.png','uploads/products/32.2.png','uploads/products/32.3.png'],shortDetail:'Midnight blue leather with white contrast stitching.'},
    {id:'p33',name:'Shahi Two-Tone',cat:'modern',price:2900,oldPrice:3500,stock:999,images:['uploads/products/33.1.png','uploads/products/33.2.png','uploads/products/33.3.png'],shortDetail:'Dual-tone brown and tan leather.'},
    {id:'p34',name:'Peshawari Sand',cat:'casual',price:1400,oldPrice:1800,stock:999,images:['uploads/products/34.1.png','uploads/products/34.2.png','uploads/products/34.3.png'],shortDetail:'Light sand leather with open design.'},
    {id:'p35',name:'Khalid Cutout Tan',cat:'casual',price:1550,oldPrice:2000,stock:999,images:['uploads/products/35.1.png','uploads/products/35.2.png'],shortDetail:'Tan leather with artistic cutout pattern.'},
    {id:'p36',name:'Shahi Umber',cat:'classic',price:1900,oldPrice:2400,stock:999,images:['uploads/products/36.1.png','uploads/products/36.2.png'],shortDetail:'Deep umber brown leather with padded insole.'},
    {id:'p37',name:'Premium Espresso Duo',cat:'premium',price:3000,oldPrice:3700,stock:999,images:['uploads/products/37.1.png','uploads/products/37.2.png'],shortDetail:'Dual-shade espresso leather with handcrafted stitching.'},
    {id:'p38',name:'Khalid Rust',cat:'casual',price:1500,oldPrice:1900,stock:999,images:['uploads/products/38.1.png','uploads/products/38.2.png'],shortDetail:'Rust-toned leather with adjustable strap.'},
    {id:'p39',name:'Shahi Camel Luxe',cat:'premium',price:2600,oldPrice:3200,stock:999,images:['uploads/products/39.1.png','uploads/products/39.2.png'],shortDetail:'Luxurious camel leather with gold hardware.'},
    {id:'p40',name:'Classic Tan Slide',cat:'casual',price:1200,oldPrice:1600,stock:999,images:['uploads/products/40.1.png','uploads/products/40.2.png'],shortDetail:'Open-toe slide design in smooth tan leather.'},
    {id:'p41',name:'Shahi Caramel Open',cat:'casual',price:1350,oldPrice:1750,stock:999,images:['uploads/products/41.1.png','uploads/products/41.2.png'],shortDetail:'Caramel leather with open-toe and cutout design.'}
];

/* ─── Sync Static Products to Firebase ─── */
function syncToFirebase() {
    if (!confirm('This will push all ' + STATIC_PRODUCTS.length + ' static products to Firebase. Existing products will be updated. Continue?')) return;
    const btn = event.target.closest('button');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';
    btn.disabled = true;

    let synced = 0;
    const total = STATIC_PRODUCTS.length;
    const promises = STATIC_PRODUCTS.map(p => {
        return db.ref('products/' + p.id).set(p).then(() => {
            synced++;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing ' + synced + '/' + total;
        });
    });

    Promise.all(promises).then(() => {
        btn.innerHTML = '<i class="fas fa-check"></i> Synced!';
        showToast('All ' + total + ' products synced to Firebase!');
        setTimeout(() => { btn.innerHTML = '<i class="fas fa-sync"></i> Sync Static Products'; btn.disabled = false; }, 2000);
    }).catch(err => {
        showToast('Error: ' + err.message);
        btn.innerHTML = '<i class="fas fa-sync"></i> Sync Static Products';
        btn.disabled = false;
    });
}

/* ─── Load Products from Firebase ─── */
db.ref('products').on('value', snap => {
    allProducts = snap.val() || {};
    filterProducts();
});

function filterProducts() {
    const search = (document.getElementById('searchInput').value || '').toLowerCase();
    const cat = document.getElementById('catFilter').value;
    const tbody = document.getElementById('productsTableBody');
    let html = '';
    let count = 0;

    Object.keys(allProducts).forEach(id => {
        const p = allProducts[id];
        const matchSearch = !search || (p.name||'').toLowerCase().includes(search) || (p.cat||'').toLowerCase().includes(search);
        const matchCat = cat === 'all' || p.cat === cat;
        if (!matchSearch || !matchCat) return;
        count++;
        const img = (p.images && p.images[0]) ? p.images[0] : '';
        const stock = p.stock !== undefined ? p.stock : '∞';
        const stockColor = stock === 0 ? 'var(--admin-red)' : (stock <= 5 ? '#d4a017' : 'var(--admin-green)');
        html += `<tr>
            <td data-label="Image"><img src="${img}" class="prod-thumb" onerror="this.style.display='none'"></td>
            <td data-label="Name"><strong>${p.name||''}</strong><br><span style="font-size:.72rem;color:var(--admin-grey)">${(p.shortDetail||'').substring(0,50)}...</span></td>
            <td data-label="Category"><span class="badge badge-placed" style="text-transform:capitalize">${p.cat||''}</span></td>
            <td data-label="Price" style="font-weight:600;color:var(--admin-blue)">Rs.${p.price||0}</td>
            <td data-label="Stock" style="font-weight:600;color:${stockColor}">${stock}</td>
            <td data-label="Actions">
                <button class="btn btn-sm btn-edit" onclick="editProduct('${id}')"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-delete" onclick="deleteProduct('${id}')"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
    });

    if (count === 0) {
        html = '<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--admin-grey)"><i class="fas fa-box-open" style="font-size:2rem;margin-bottom:8px;display:block"></i>No products found</td></tr>';
    }
    tbody.innerHTML = html;
}

/* ─── Modal ─── */
function openModal(id) {
    document.getElementById('productModal').style.display = 'flex';
    document.getElementById('editId').value = '';
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('productForm').reset();
    uploadedImages = [];
    renderImagePreview();
    setStarRating(0);
}
function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

function editProduct(id) {
    const p = allProducts[id];
    if (!p) return;
    document.getElementById('productModal').style.display = 'flex';
    document.getElementById('editId').value = id;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('pName').value = p.name || '';
    document.getElementById('pCat').value = p.cat || 'classic';
    document.getElementById('pPrice').value = p.price || '';
    document.getElementById('pOldPrice').value = p.oldPrice || '';
    document.getElementById('pStock').value = p.stock !== undefined ? p.stock : '';
    document.getElementById('pDetail').value = p.shortDetail || '';
    setStarRating(p.rating || 0);
    document.getElementById('pImages').value = (p.images || []).join('\n');
}

function saveProduct(e) {
    e.preventDefault();
    const id = document.getElementById('editId').value || 'p' + Date.now();
    const images = getMergedImages();

    const product = {
        name: document.getElementById('pName').value.trim(),
        cat: document.getElementById('pCat').value,
        price: Number(document.getElementById('pPrice').value),
        oldPrice: Number(document.getElementById('pOldPrice').value) || 0,
        stock: document.getElementById('pStock').value !== '' ? Number(document.getElementById('pStock').value) : 999,
        rating: Number(document.getElementById('pRating').value) || 0,
        shortDetail: document.getElementById('pDetail').value.trim(),
        images: images
    };

    db.ref('products/' + id).set(product).then(() => {
        uploadedImages = [];
        showToast(document.getElementById('editId').value ? 'Product updated!' : 'Product added!');
        closeModal();
    }).catch(err => {
        showToast('Error: ' + err.message);
    });
}

/* ─── Delete ─── */
function deleteProduct(id) {
    document.getElementById('deleteModal').style.display = 'flex';
    document.getElementById('deleteId').value = id;
}
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
function confirmDelete() {
    const id = document.getElementById('deleteId').value;
    db.ref('products/' + id).remove().then(() => {
        showToast('Product deleted!');
        closeDeleteModal();
    });
}

/* ─── Star Rating ─── */
let currentRating = 0;

function setStarRating(val) {
    currentRating = val;
    document.getElementById('pRating').value = val;
    document.getElementById('starRatingVal').textContent = val > 0 ? val + '/5' : '0';
    var stars = document.querySelectorAll('#starRatingInput .fa-star');
    stars.forEach(function(s, i) {
        s.style.color = (i < val) ? '#f59e0b' : '#d1d5db';
    });
}

document.getElementById('starRatingInput').addEventListener('click', function(e) {
    var star = e.target.closest('.fa-star');
    if (!star) return;
    var val = Number(star.dataset.star);
    setStarRating(val === currentRating ? 0 : val);
});

document.getElementById('starRatingInput').addEventListener('mouseover', function(e) {
    var star = e.target.closest('.fa-star');
    if (!star) return;
    var hoverVal = Number(star.dataset.star);
    var stars = document.querySelectorAll('#starRatingInput .fa-star');
    stars.forEach(function(s, i) {
        s.style.color = (i < hoverVal) ? '#fbbf24' : '#d1d5db';
    });
});

document.getElementById('starRatingInput').addEventListener('mouseleave', function() {
    var stars = document.querySelectorAll('#starRatingInput .fa-star');
    stars.forEach(function(s, i) {
        s.style.color = (i < currentRating) ? '#f59e0b' : '#d1d5db';
    });
});

/* ─── Image Upload ─── */
let uploadedImages = [];

function handleFileSelect(files) {
    if (!files || files.length === 0) return;
    uploadFiles(files);
}

function handleFileDrop(e) {
    e.preventDefault();
    e.currentTarget.style.borderColor = 'var(--admin-border)';
    e.currentTarget.style.background = 'var(--admin-grey-bg)';
    uploadFiles(e.dataTransfer.files);
}

function uploadFiles(files) {
    var formData = new FormData();
    var validFiles = [];
    for (var i = 0; i < files.length; i++) {
        if (files[i].size > 5 * 1024 * 1024) {
            showToast(files[i].name + ' is too large (max 5MB)');
            continue;
        }
        formData.append('images[]', files[i]);
        validFiles.push(files[i]);
    }
    if (validFiles.length === 0) return;

    document.getElementById('uploadProgress').style.display = 'block';

    fetch('upload_product_image.php', { method: 'POST', body: formData })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('uploadProgress').style.display = 'none';
            if (data.ok && data.uploaded) {
                data.uploaded.forEach(function(path) { uploadedImages.push(path); });
                renderImagePreview();
                showToast(data.count + ' image(s) uploaded!');
            } else {
                showToast('Upload failed: ' + (data.errors || []).join(', '));
            }
        })
        .catch(function(err) {
            document.getElementById('uploadProgress').style.display = 'none';
            showToast('Upload error: ' + err.message);
        });
}

function renderImagePreview() {
    var container = document.getElementById('imagePreview');
    var allImages = getMergedImages();
    container.innerHTML = allImages.map(function(src, i) {
        return '<div style="position:relative;width:60px;height:60px;border-radius:8px;overflow:hidden;border:2px solid var(--admin-border)">' +
            '<img src="' + src + '" style="width:100%;height:100%;object-fit:cover" onerror="this.parentElement.style.display=\'none\'">' +
            '<button onclick="removeImage(' + i + ')" style="position:absolute;top:2px;right:2px;width:18px;height:18px;border-radius:50%;background:rgba(0,0,0,.6);color:#fff;border:none;font-size:.6rem;cursor:pointer;display:flex;align-items:center;justify-content:center">&times;</button>' +
            '</div>';
    }).join('');
}

function removeImage(index) {
    var allImages = getMergedImages();
    allImages.splice(index, 1);
    uploadedImages = [];
    document.getElementById('pImages').value = allImages.join('\n');
    renderImagePreview();
}

function getMergedImages() {
    var urlText = (document.getElementById('pImages').value || '').trim();
    var urlImages = urlText ? urlText.split('\n').map(function(s) { return s.trim(); }).filter(Boolean) : [];
    return uploadedImages.concat(urlImages);
}

/* Edit product - load existing images */
var origEditProduct = editProduct;
editProduct = function(id) {
    origEditProduct(id);
    uploadedImages = [];
    var p = allProducts[id];
    if (p && p.images) {
        uploadedImages = p.images.filter(function(img) { return img && img.indexOf('uploads/') === 0; });
        var urlOnly = p.images.filter(function(img) { return img && img.indexOf('uploads/') !== 0; });
        document.getElementById('pImages').value = urlOnly.join('\n');
    }
    renderImagePreview();
};
</script>

</main>
</div>
</body>
</html>
