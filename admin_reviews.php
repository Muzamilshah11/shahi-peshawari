<?php
$activePage = 'reviews';
require_once 'config.php';
require_once 'admin_sidebar.php';
?>

<div class="page-header">
    <h1><i class="fas fa-star" style="color:var(--admin-gold);margin-right:8px"></i> Reviews</h1>
</div>

<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-star"></i></div>
        <div class="stat-info"><div class="stat-value" id="avgRating">0</div><div class="stat-label">Average Rating</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-comments"></i></div>
        <div class="stat-info"><div class="stat-value" id="totalReviews">0</div><div class="stat-label">Total Reviews</div></div>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="reviewsBody">
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-grey)">Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
db.ref('reviews').on('value', snap => {
    const data = snap.val() || {};
    const keys = Object.keys(data);
    let totalRating = 0;
    const tbody = document.getElementById('reviewsBody');
    let html = '';

    keys.forEach((id, i) => {
        const r = data[id];
        const rating = Number(r.rating) || 0;
        totalRating += rating;
        const date = r.date ? new Date(r.date).toLocaleDateString('en-PK', { year:'numeric', month:'short', day:'numeric' }) : '-';
        const stars = '&#9733;'.repeat(rating) + '&#9734;'.repeat(5 - rating);

        html += `<tr>
            <td>${i + 1}</td>
            <td><strong>${r.customerName || r.name || 'Anonymous'}</strong></td>
            <td>${r.productName || r.product || '-'}</td>
            <td style="color:var(--admin-gold);font-size:1rem">${stars}</td>
            <td style="max-width:300px">${r.review || r.text || '-'}</td>
            <td>${date}</td>
            <td><button class="btn btn-sm btn-delete" onclick="deleteReview('${id}')"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    });

    document.getElementById('totalReviews').textContent = keys.length;
    document.getElementById('avgRating').textContent = keys.length ? (totalRating / keys.length).toFixed(1) : '0';

    if (keys.length === 0) {
        html = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-grey)"><i class="fas fa-star" style="font-size:2rem;margin-bottom:8px;display:block"></i>No reviews yet</td></tr>';
    }
    tbody.innerHTML = html;
});

function deleteReview(id) {
    if (!confirm('Delete this review?')) return;
    db.ref('reviews/' + id).remove().then(() => showToast('Review deleted'));
}
</script>

</main>
</div>
</body>
</html>
