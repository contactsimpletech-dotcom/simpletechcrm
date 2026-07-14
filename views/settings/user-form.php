<?php $page_title = $user ? 'Edit User' : 'Add User'; ?>
<div class="page-title-area">
    <div>
        <a href="<?= BH_APP_URL ?>/settings/users" style="font-size:13px;color:#9ca3af;text-decoration:none;display:block;margin-bottom:4px;">&larr; Users</a>
        <h1 style="margin:0;font-size:20px;font-weight:700;"><?php echo $user ? 'Edit User' : 'Add User'; ?></h1>
    </div>
</div>

<div style="max-width:600px;">
<div class="card">
    <div class="card-body">
    <form method="POST" action="<?php echo BH_APP_URL . ($user ? '/settings/users/'.$user->id.'/edit' : '/settings/users/add'); ?>">
        <?php echo Csrf::field(); ?>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" class="form-control" required value="<?php echo View::e($user->name ?? ''); ?>" placeholder="John Smith" readonly onfocus="this.removeAttribute('readonly')" autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address <span style="color:#ef4444;">*</span></label>
                <input type="email" name="email" class="form-control" required
                       value="<?php echo View::e($user->email ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Password <?php echo $user ? '(leave blank to keep current)' : '<span style="color:#ef4444;">*</span>'; ?></label>
                <input type="password" name="password" class="form-control" <?php echo $user ? '' : 'required'; ?>
                       placeholder="Minimum 8 characters" minlength="8">
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control">
                    <?php foreach (['admin'=>'Administrator','staff'=>'Staff','client'=>'Client'] as $val=>$lbl): ?>
                    <option value="<?php echo $val; ?>" <?php echo ($user->role??'staff')===$val?'selected':''; ?>><?php echo $lbl; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Job Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo View::e($user->title ?? ''); ?>" placeholder="e.g. Technician" readonly onfocus="this.removeAttribute('readonly')" autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-control"
                       value="<?php echo View::e($user->phone ?? ''); ?>">
            </div>
            <?php if ($user): ?>
            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="active" value="1" <?php echo $user->active?'checked':''; ?>>
                    Active (can log in)
                </label>
            </div>
            <?php endif; ?>
            <div style="display:flex;gap:8px;margin-top:4px;">
                <button type="submit" class="btn btn-primary"><?php echo $user ? 'Save Changes' : 'Create User'; ?></button>
                <a href="<?= BH_APP_URL ?>/settings/users" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
    </div>
</div>
</div>
