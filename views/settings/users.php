<?php $page_title = 'Users'; ?>
<div class="page-title-area">
    <h1 style="margin:0;font-size:20px;font-weight:700;">
        <i class="fa-solid fa-users" style="color:var(--pfx-primary);margin-right:8px;"></i>Users
    </h1>
    <a href="<?= BH_APP_URL ?>/settings/users/add" class="btn btn-primary">
        <i class="fa-solid fa-plus" style="margin-right:5px;"></i>Add User
    </a>
</div>

<div class="card">
<table class="perfex-table">
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>MFA</th><th>Last Login</th><th>Status</th><th style="text-align:right;">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
    <tr>
        <td>
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1d4ed8;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <?php echo strtoupper(substr($u->name,0,1)); ?>
                </div>
                <div>
                    <div style="font-weight:600;font-size:13px;color:#111827;"><?php echo View::e($u->name); ?></div>
                    <?php if ($u->title): ?><div style="font-size:12px;color:#9ca3af;"><?php echo View::e($u->title); ?></div><?php endif; ?>
                </div>
            </div>
        </td>
        <td style="font-size:13px;color:#6b7280;"><?php echo View::e($u->email); ?></td>
        <td>
            <?php $rc = ['admin'=>'purple','staff'=>'info','client'=>'secondary']; ?>
            <span class="badge badge-<?php echo $rc[$u->role]??'secondary'; ?>"><?php echo ucfirst($u->role); ?></span>
        </td>
        <td>
            <?php if ($u->mfa_secret): ?>
            <span class="badge badge-success"><i class="fa-solid fa-check" style="margin-right:3px;"></i>On</span>
            <?php else: ?>
            <span class="badge badge-secondary">Off</span>
            <?php endif; ?>
        </td>
        <td style="font-size:12px;color:#9ca3af;"><?php echo $u->last_login ? View::ago($u->last_login) : 'Never'; ?></td>
        <td><?php echo $u->active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; ?></td>
        <td style="text-align:right;">
            <a href="<?= BH_APP_URL ?>/settings/users/<?php echo $u->id; ?>/edit" class="btn btn-secondary btn-sm">Edit</a>
            <?php if ($u->id !== Auth::id()): ?>
            <form method="POST" action="<?= BH_APP_URL ?>/settings/users/<?php echo $u->id; ?>/delete" style="display:inline;" onsubmit="return confirm('Delete this user? This cannot be undone.')">
                <?= Csrf::field() ?>
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
