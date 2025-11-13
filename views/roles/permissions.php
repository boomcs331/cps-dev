<div id="permissions-content">
    <h6 class="mb-3">เลือกสิทธิ์สำหรับบทบาท: <?= htmlspecialchars($role['display_name']) ?></h6>

    <?php
    $groupedPermissions = [];
    foreach ($permissions as $permission) {
        $groupedPermissions[$permission['module']][] = $permission;
    }
    ?>

    <?php foreach ($groupedPermissions as $module => $modulePermissions): ?>
        <div class="mb-4">
            <h6 class="text-primary mb-2">
                <i class="fas fa-folder"></i> <?= ucfirst($module) ?> Module
            </h6>
            <div class="row">
                <?php foreach ($modulePermissions as $permission): ?>
                    <div class="col-md-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input"
                                type="checkbox"
                                name="permissions[]"
                                value="<?= $permission['id'] ?>"
                                id="perm_<?= $permission['id'] ?>"
                                <?= in_array($permission['id'], $rolePermissions) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="perm_<?= $permission['id'] ?>">
                                <strong><?= htmlspecialchars($permission['display_name']) ?></strong>
                                <br><small class="text-muted"><?= htmlspecialchars($permission['description']) ?></small>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="mt-3">
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPermissions()">
            <i class="fas fa-check-square"></i> เลือกทั้งหมด
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllPermissions()">
            <i class="fas fa-square"></i> ยกเลิกทั้งหมด
        </button>
    </div>
</div>