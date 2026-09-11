<?php
$activePage = 'subscribers';
require_once 'config.php';
require_once 'admin_sidebar.php';
?>

<div class="page-header">
    <h1><i class="fas fa-envelope-open-text" style="color:var(--admin-blue);margin-right:8px"></i> Subscribers</h1>
</div>

<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-envelope"></i></div>
        <div class="stat-info"><div class="stat-value" id="totalSubs">0</div><div class="stat-label">Total Subscribers</div></div>
    </div>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Email</th>
                <th>Name</th>
                <th>Subscribed On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="subsBody">
            <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--admin-grey)">Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
db.ref('subscribers').on('value', snap => {
    const data = snap.val() || {};
    const keys = Object.keys(data);
    document.getElementById('totalSubs').textContent = keys.length;
    const tbody = document.getElementById('subsBody');
    let html = '';

    keys.forEach((id, i) => {
        const s = data[id];
        const date = s.date ? new Date(s.date).toLocaleDateString('en-PK', { year:'numeric', month:'short', day:'numeric' }) : '-';
        html += `<tr>
            <td>${i + 1}</td>
            <td><strong>${s.email || '-'}</strong></td>
            <td>${s.name || '-'}</td>
            <td>${date}</td>
            <td><button class="btn btn-sm btn-delete" onclick="removeSubscriber('${id}')"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    });

    if (keys.length === 0) {
        html = '<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--admin-grey)"><i class="fas fa-envelope-open" style="font-size:2rem;margin-bottom:8px;display:block"></i>No subscribers yet</td></tr>';
    }
    tbody.innerHTML = html;
});

function removeSubscriber(id) {
    if (!confirm('Remove this subscriber?')) return;
    db.ref('subscribers/' + id).remove().then(() => showToast('Subscriber removed'));
}
</script>

</main>
</div>
</body>
</html>
