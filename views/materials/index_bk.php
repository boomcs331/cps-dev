<?php
header('Content-Type: text/html; charset=utf-8');
$materials = $materials ?? [];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>à¸ˆà¸±à¸”à¸à¸²à¸£à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š - CPS</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>lib/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/dashboard-shared.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/pagination.css">
    <style>
        /* Modern tab styling */
        .tab-card {
            margin-bottom: 24px;
            border: none;
            border-radius: 1.5rem;
            background: var(--bs-card-bg);
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.09);
        }

        .tab-card .card-body {
            padding: 1.75rem;
        }

        .material-tabs {
            border: none;
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .material-tabs .nav-item {
            margin-bottom: 0;
        }

        .material-tabs .nav-link {
            border: none;
            border-radius: 1.2rem;
            padding: 1.25rem 1.4rem;
            background: rgba(var(--bs-primary-rgb, 90, 141, 238), 0.08);
            color: var(--bs-body-color);
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
            align-items: center;
            box-shadow: inset 0 0 0 1px rgba(var(--bs-primary-rgb, 90, 141, 238), 0.14);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
        }

        .material-tabs .nav-link .tab-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: rgba(var(--bs-primary-rgb, 90, 141, 238), 0.15);
            display: grid;
            place-items: center;
            font-size: 1.15rem;
            color: var(--bs-primary);
        }

        .material-tabs .nav-link .tab-copy {
            text-align: left;
        }

        .material-tabs .nav-link .tab-title {
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .material-tabs .nav-link .tab-meta {
            display: block;
            margin-top: 0.1rem;
            font-size: 0.86rem;
            color: var(--bs-secondary-color, #6c757d);
            letter-spacing: 0.01em;
        }

        .material-tabs .nav-link .tab-arrow {
            color: rgba(var(--bs-primary-rgb, 90, 141, 238), 0.7);
            transition: transform 0.2s ease;
        }

        .material-tabs .nav-link:hover .tab-arrow,
        .material-tabs .nav-link:focus-visible .tab-arrow,
        .material-tabs .nav-link.active .tab-arrow {
            transform: translateX(4px);
        }

        .material-tabs .nav-link:hover,
        .material-tabs .nav-link:focus-visible {
            background: rgba(var(--bs-primary-rgb, 90, 141, 238), 0.16);
            box-shadow: 0 15px 40px rgba(var(--bs-primary-rgb, 90, 141, 238), 0.25);
            transform: translateY(-2px);
        }

        .material-tabs .nav-link:hover .tab-meta,
        .material-tabs .nav-link:focus-visible .tab-meta {
            color: rgba(var(--bs-primary-rgb, 90, 141, 238), 0.9);
        }

        .material-tabs .nav-link.active {
            background: linear-gradient(120deg, var(--bs-primary), #8b5cf6);
            color: #fff;
            box-shadow: 0 20px 45px rgba(var(--bs-primary-rgb, 90, 141, 238), 0.35);
        }

        .material-tabs .nav-link.active .tab-icon {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
        }

        .material-tabs .nav-link.active .tab-meta {
            color: rgba(255, 255, 255, 0.86);
        }

        @media (max-width: 575px) {
            .material-tabs {
                grid-template-columns: 1fr;
            }
        }

        [data-bs-theme="dark"] .material-tabs .nav-link {
            background: rgba(255, 255, 255, 0.05);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
        }

        [data-bs-theme="dark"] .material-tabs .nav-link:hover,
        [data-bs-theme="dark"] .material-tabs .nav-link:focus-visible {
            background: rgba(255, 255, 255, 0.12);
        }

        [data-bs-theme="dark"] .tab-card {
            box-shadow: 0 20px 55px rgba(5, 7, 15, 0.75);
        }
    </style>

</head>

<body>
    <script src="<?= BASE_URL ?>lib/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="dashboard-container">
            <!-- Tab Navigation -->
            <div class="card tab-card">
                <div class="card-body">
                    <ul class="nav nav-tabs material-tabs" id="materialTabs" role="tablist">
                                                <li class="nav-item" role="presentation">
                            <button class="nav-link active material-tab" id="materials-tab" data-bs-toggle="tab" data-bs-target="#materials" type="button" role="tab">
                                <span class="tab-icon">
                                    <i class="fas fa-boxes"></i>
                                </span>
                                <span class="tab-copy">
                                    <span class="tab-title">วัสดุทั้งหมด</span>
                                    <span class="tab-meta">ดูภาพรวมสต็อก &bull; <?= number_format(count($materials)) ?> รายการ</span>
                                </span>
                                <span class="tab-arrow">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </button>
                        </li>
                                                <li class="nav-item" role="presentation">
                            <button class="nav-link material-tab" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab">
                                <span class="tab-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </span>
                                <span class="tab-copy">
                                    <span class="tab-title">บันทึกการเคลื่อนไหว</span>
                                    <span class="tab-meta">ติดตามการรับเข้า-จ่ายออกล่าสุด &bull; <?= number_format(count($transactions ?? [])) ?> รายการ</span>
                                </span>
                                <span class="tab-arrow">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="materialTabContent">
                <!-- Materials Management Tab -->
                <div class="tab-pane fade show active" id="materials" role="tabpanel">
                    <!-- Header Section -->
                    <section class="card">
                        <header class="dashboard-hero">
                            <h1><i class="fas fa-boxes"></i> à¸ˆà¸±à¸”à¸à¸²à¸£à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š</h1>
                            <p>à¸ˆà¸±à¸”à¸à¸²à¸£à¸‚à¹‰à¸­à¸¡à¸¹à¸¥à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¹à¸¥à¸°à¸„à¸¥à¸±à¸‡à¸ªà¸´à¸™à¸„à¹‰à¸²</p>
                            <div class="actions-bar">
                                <button type="button" class="button-link" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                                    <i class="fas fa-plus"></i> à¹€à¸žà¸´à¹ˆà¸¡à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¹ƒà¸«à¸¡à¹ˆ
                                </button>
                            </div>
                        </header>
                    </section>
                    
                    <!-- Stats Cards -->
                    <section class="kpi-grid">
                <article class="kpi-card">
                    <h3>à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¸—à¸±à¹‰à¸‡à¸«à¸¡à¸”</h3>
                    <strong><?= count($materials) ?></strong>
                    <span class="kpi-trend neutral">à¸£à¸²à¸¢à¸à¸²à¸£</span>
                </article>
                <article class="kpi-card">
                    <h3>à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¸—à¸µà¹ˆà¹ƒà¸Šà¹‰à¸‡à¸²à¸™</h3>
                    <strong><?= count(array_filter($materials, fn($m) => $m['is_active'] == 1)) ?></strong>
                    <span class="kpi-trend up">à¸žà¸£à¹‰à¸­à¸¡à¹ƒà¸Šà¹‰à¸‡à¸²à¸™</span>
                </article>
                <article class="kpi-card">
                    <h3>à¸„à¸¥à¸±à¸‡à¸—à¸±à¹‰à¸‡à¸«à¸¡à¸”</h3>
                    <strong><?= count($locations ?? []) ?></strong>
                    <span class="kpi-trend neutral">à¸ªà¸–à¸²à¸™à¸—à¸µà¹ˆ</span>
                </article>
                <article class="kpi-card">
                    <h3>à¸«à¸™à¹ˆà¸§à¸¢à¸™à¸±à¸š</h3>
                    <strong><?= count($units ?? []) ?></strong>
                    <span class="kpi-trend neutral">à¸›à¸£à¸°à¹€à¸ à¸—</span>
                </article>
            </section>

            <!-- Materials Table -->
            <section class="card">
                <div class="table-responsive">
                    <table class="table" id="materialsTable">
                        <thead>
                            <tr>
                                <th>à¸£à¸«à¸±à¸ª</th>
                                <th>à¸Šà¸·à¹ˆà¸­à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š</th>
                                <th>à¸«à¸™à¹ˆà¸§à¸¢</th>
                                <th>à¸„à¸¥à¸±à¸‡</th>
                                <th>à¸ªà¸–à¸²à¸™à¸°</th>
                                <th>à¸ˆà¸±à¸”à¸à¸²à¸£</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr class="material-item">
                                    <td><code class="code-badge"><?= htmlspecialchars($material['material_code']) ?></code></td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($material['material_name']) ?></strong><br>
                                            <?php if (!empty($material['description'])): ?>
                                                <small class="text-muted"><?= htmlspecialchars($material['description']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($material['unit_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($material['location_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge <?= $material['is_active'] === 'à¹ƒà¸Šà¹‰à¸‡à¸²à¸™' ? 'info' : 'warning' ?>">
                                            <?= htmlspecialchars($material['is_active']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 4px;">
                                            <button class="btn btn-warning btn-sm" onclick="editMaterial(<?= $material['id'] ?>)" title="à¹à¸à¹‰à¹„à¸‚">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteMaterial(<?= $material['id'] ?>)" title="à¸¥à¸š">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card" style="margin-top: 24px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-muted">
                                à¹à¸ªà¸”à¸‡ <?= (($currentPage - 1) * $perPage) + 1 ?> - <?= min($currentPage * $perPage, $totalRecords) ?> à¸ˆà¸²à¸ <?= number_format($totalRecords) ?> à¸£à¸²à¸¢à¸à¸²à¸£
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted">à¹à¸ªà¸”à¸‡à¸•à¹ˆà¸­à¸«à¸™à¹‰à¸²:</span>
                                <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                                    <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                                    <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                                    <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                                    <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                                </select>
                            </div>
                        </div>
                        
                    <?php 
                    $totalPages = ceil($totalRecords / $perPage);
                    if ($totalPages > 1): 
                    ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <?php if ($currentPage > 2): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?url=materials&page=1&per_page=<?= $perPage ?>" title="à¸«à¸™à¹‰à¸²à¹à¸£à¸">
                                        <i class="fas fa-angle-double-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?url=materials&page=<?= $currentPage - 1 ?>&per_page=<?= $perPage ?>" title="à¸«à¸™à¹‰à¸²à¸à¹ˆà¸­à¸™à¸«à¸™à¹‰à¸²">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            $start = max(1, $currentPage - 2);
                            $end = min($totalPages, $currentPage + 2);
                            
                            for ($i = $start; $i <= $end; $i++):
                            ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?url=materials&page=<?= $i ?>&per_page=<?= $perPage ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?url=materials&page=<?= $currentPage + 1 ?>&per_page=<?= $perPage ?>" title="à¸«à¸™à¹‰à¸²à¸–à¸±à¸”à¹„à¸›">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if ($currentPage < $totalPages - 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?url=materials&page=<?= $totalPages ?>&per_page=<?= $perPage ?>" title="à¸«à¸™à¹‰à¸²à¸ªà¸¸à¸”à¸—à¹‰à¸²à¸¢">
                                        <i class="fas fa-angle-double-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                    </div>
                </div>
            </section>
                </div>
                
                <!-- Transactions Tab -->
                <div class="tab-pane fade" id="transactions" role="tabpanel">
                    <section class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">à¸£à¸²à¸¢à¸à¸²à¸£à¸£à¸±à¸šà¹€à¸‚à¹‰à¸²à¸ˆà¹ˆà¸²à¸¢à¸­à¸­à¸</h5>
                            <p class="text-muted">à¸à¸³à¸¥à¸±à¸‡à¸žà¸±à¸’à¸™à¸²à¸Ÿà¸µà¹€à¸ˆà¸­à¸£à¹Œà¸™à¸µà¹‰</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>



    <!-- Add Material Modal -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">à¹€à¸žà¸´à¹ˆà¸¡à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¹ƒà¸«à¸¡à¹ˆ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addMaterialForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">à¸£à¸«à¸±à¸ªà¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="material_code" placeholder="à¹€à¸Šà¹ˆà¸™ MAT-001" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">à¸Šà¸·à¹ˆà¸­à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="material_name" placeholder="à¹€à¸Šà¹ˆà¸™ à¹€à¸«à¸¥à¹‡à¸à¹à¸œà¹ˆà¸™" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">à¸«à¸™à¹ˆà¸§à¸¢à¸™à¸±à¸š <span class="text-danger">*</span></label>
                                    <select class="form-select" name="default_unit" required>
                                        <option value="">à¹€à¸¥à¸·à¸­à¸à¸«à¸™à¹ˆà¸§à¸¢à¸™à¸±à¸š</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">à¸„à¸¥à¸±à¸‡à¸ˆà¸±à¸”à¹€à¸à¹‡à¸š <span class="text-danger">*</span></label>
                                    <select class="form-select" name="location_id" required>
                                        <option value="">à¹€à¸¥à¸·à¸­à¸à¸„à¸¥à¸±à¸‡</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?= $location['location_id'] ?>"><?= htmlspecialchars($location['location_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">à¸«à¸¡à¸²à¸¢à¹€à¸«à¸•à¸¸</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="à¸£à¸²à¸¢à¸¥à¸°à¹€à¸­à¸µà¸¢à¸”à¹€à¸žà¸´à¹ˆà¸¡à¹€à¸•à¸´à¸¡à¹€à¸à¸µà¹ˆà¸¢à¸§à¸à¸±à¸šà¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š (à¸–à¹‰à¸²à¸¡à¸µ)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">à¸¢à¸à¹€à¸¥à¸´à¸</button>
                        <button type="submit" class="btn btn-primary">à¸šà¸±à¸™à¸—à¸¶à¸à¸‚à¹‰à¸­à¸¡à¸¹à¸¥</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Material Modal -->
    <div class="modal fade" id="editMaterialModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">à¹à¸à¹‰à¹„à¸‚à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editMaterialForm">
                    <input type="hidden" name="material_id" id="edit_material_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">à¸£à¸«à¸±à¸ªà¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="material_code" id="edit_material_code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">à¸Šà¸·à¹ˆà¸­à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸š <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="material_name" id="edit_material_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">à¸«à¸™à¹ˆà¸§à¸¢à¸™à¸±à¸š <span class="text-danger">*</span></label>
                                    <select class="form-select" name="default_unit" id="edit_default_unit" required>
                                        <option value="">à¹€à¸¥à¸·à¸­à¸à¸«à¸™à¹ˆà¸§à¸¢à¸™à¸±à¸š</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['unit_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">à¸„à¸¥à¸±à¸‡à¸ˆà¸±à¸”à¹€à¸à¹‡à¸š <span class="text-danger">*</span></label>
                                    <select class="form-select" name="location_id" id="edit_location_id" required>
                                        <option value="">à¹€à¸¥à¸·à¸­à¸à¸„à¸¥à¸±à¸‡</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?= $location['location_id'] ?>"><?= htmlspecialchars($location['location_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">à¸«à¸¡à¸²à¸¢à¹€à¸«à¸•à¸¸</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active">
                                <label class="form-check-label">à¹ƒà¸Šà¹‰à¸‡à¸²à¸™</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">à¸¢à¸à¹€à¸¥à¸´à¸</button>
                        <button type="submit" class="btn btn-primary">à¸šà¸±à¸™à¸—à¸¶à¸à¸à¸²à¸£à¹à¸à¹‰à¹„à¸‚</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>lib/compiled/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('addMaterialForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('<?= BASE_URL ?>?url=materials/store', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'à¸ªà¸³à¹€à¸£à¹‡à¸ˆ!',
                            text: 'à¹€à¸žà¸´à¹ˆà¸¡à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¹€à¸£à¸µà¸¢à¸šà¸£à¹‰à¸­à¸¢à¹à¸¥à¹‰à¸§',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'à¹€à¸à¸´à¸”à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”',
                            text: data.message || 'à¸à¸£à¸¸à¸“à¸²à¸¥à¸­à¸‡à¹ƒà¸«à¸¡à¹ˆ'
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”',
                    text: 'à¹€à¸à¸´à¸”à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”à¹ƒà¸™à¸à¸²à¸£à¹€à¸Šà¸·à¹ˆà¸­à¸¡à¸•à¹ˆà¸­'
                }));
        });

        function editMaterial(id) {
            fetch('<?= BASE_URL ?>?url=materials/get&id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const material = data.material;
                        document.getElementById('edit_material_id').value = material.material_id;
                        document.getElementById('edit_material_code').value = material.material_code;
                        document.getElementById('edit_material_name').value = material.material_name || '';
                        document.getElementById('edit_default_unit').value = material.default_unit;
                        document.getElementById('edit_location_id').value = material.location_id;
                        document.getElementById('edit_description').value = material.description || '';
                        document.getElementById('edit_is_active').checked = material.is_active == 1;

                        new bootstrap.Modal(document.getElementById('editMaterialModal')).show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'à¹„à¸¡à¹ˆà¸žà¸šà¸‚à¹‰à¸­à¸¡à¸¹à¸¥',
                            text: data.message || 'à¸à¸£à¸¸à¸“à¸²à¸¥à¸­à¸‡à¹ƒà¸«à¸¡à¹ˆ'
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”',
                    text: 'à¹€à¸à¸´à¸”à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”à¹ƒà¸™à¸à¸²à¸£à¹€à¸Šà¸·à¹ˆà¸­à¸¡à¸•à¹ˆà¸­'
                }));
        }

        document.getElementById('editMaterialForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('<?= BASE_URL ?>?url=materials/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'à¸ªà¸³à¹€à¸£à¹‡à¸ˆ!',
                            text: 'à¹à¸à¹‰à¹„à¸‚à¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¹€à¸£à¸µà¸¢à¸šà¸£à¹‰à¸­à¸¢à¹à¸¥à¹‰à¸§',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'à¹€à¸à¸´à¸”à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”',
                            text: data.message || 'à¸à¸£à¸¸à¸“à¸²à¸¥à¸­à¸‡à¹ƒà¸«à¸¡à¹ˆ'
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”',
                    text: 'à¹€à¸à¸´à¸”à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”à¹ƒà¸™à¸à¸²à¸£à¹€à¸Šà¸·à¹ˆà¸­à¸¡à¸•à¹ˆà¸­'
                }));
        });

        function deleteMaterial(id) {
            Swal.fire({
                title: 'à¸¢à¸·à¸™à¸¢à¸±à¸™à¸à¸²à¸£à¸¥à¸š?',
                text: 'à¸„à¸¸à¸“à¸•à¹‰à¸­à¸‡à¸à¸²à¸£à¸¥à¸šà¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¸™à¸µà¹‰à¸«à¸£à¸·à¸­à¹„à¸¡à¹ˆ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'à¸¥à¸š',
                cancelButtonText: 'à¸¢à¸à¹€à¸¥à¸´à¸'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('material_id', id);

                    fetch('<?= BASE_URL ?>?url=materials/delete', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'à¸¥à¸šà¹€à¸£à¸µà¸¢à¸šà¸£à¹‰à¸­à¸¢!',
                                    text: 'à¸¥à¸šà¸§à¸±à¸•à¸–à¸¸à¸”à¸´à¸šà¹€à¸£à¸µà¸¢à¸šà¸£à¹‰à¸­à¸¢à¹à¸¥à¹‰à¸§',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'à¹€à¸à¸´à¸”à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”',
                                    text: data.message || 'à¸à¸£à¸¸à¸“à¸²à¸¥à¸­à¸‡à¹ƒà¸«à¸¡à¹ˆ'
                                });
                            }
                        })
                        .catch(() => Swal.fire({
                            icon: 'error',
                            title: 'à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”',
                            text: 'à¹€à¸à¸´à¸”à¸‚à¹‰à¸­à¸œà¸´à¸”à¸žà¸¥à¸²à¸”à¹ƒà¸™à¸à¸²à¸£à¹€à¸Šà¸·à¹ˆà¸­à¸¡à¸•à¹ˆà¸­'
                        }));
                }
            });
        }
        
        // Change per page function
        function changePerPage(perPage) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }
    </script>
</body>

</html>

