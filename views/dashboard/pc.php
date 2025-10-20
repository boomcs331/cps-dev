<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Part Control Material Dashboard</title>
    <link rel="shortcut icon" href="./lib/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="./lib/compiled/css/app.css">
    <link rel="stylesheet" href="./lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./lib/compiled/css/iconly.css">
    <link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./lib/css/pc-dashboard.css">
</head>

<body>
    <script src="lib/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'views/layouts/navbar-mazer.php'; ?>

        <div class="container-fluid mt-3 pc-dashboard">
            <div class="page-heading mb-3">
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-2">
                    <div>
                        <h3 class="mb-1">Part Control Material Dashboard</h3>
                        <p class="text-muted mb-0">Real-time visibility on material availability, critical alerts, and inbound flow.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">Updated: <?= date('d M Y H:i') ?></span>
                        <a href="#" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrows-rotate me-1"></i> Sync Now
                        </a>
                    </div>
                </div>
            </div>

            <div class="page-content">
                <section class="row gy-4">
                    <div class="col-12">
                        <div class="row g-3">
                            <?php
                            $summaryIcons = [
                                'Stock On Hand' => 'fa-boxes-stacked',
                                'Incoming This Week' => 'fa-truck-loading',
                                'Critical Alerts' => 'fa-radiation',
                                'Kanban Cards' => 'fa-id-card-clip'
                            ];
                            foreach ($summaryCards as $card):
                                $icon = $summaryIcons[$card['label']] ?? 'fa-chart-bar';
                                $isUp = $card['trend'] === 'up';
                            ?>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card summary-card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="summary-icon bg-light text-primary">
                                                <i class="fas <?= $icon ?>"></i>
                                            </div>
                                            <span class="badge bg-light text-primary">
                                                <i class="fas <?= $isUp ? 'fa-arrow-up' : 'fa-arrow-down' ?> me-1"></i>
                                                <?= number_format($card['change'], 1) ?>%
                                            </span>
                                        </div>
                                        <p class="text-muted mt-3 mb-1 text-uppercase small"><?= htmlspecialchars($card['label']) ?></p>
                                        <h2 class="fw-bold mb-0">
                                            <?= number_format($card['value']) ?>
                                            <span class="text-muted fs-6"><?= htmlspecialchars($card['unit']) ?></span>
                                        </h2>
                                        <span class="text-muted small">vs last 7 days</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg-8">
                        <div class="card shadow-sm h-100">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">Inventory Trend</h5>
                                        <small class="text-muted">Utilization index across major material states</small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary active" disabled>Last 6 Days</button>
                                        <button class="btn btn-outline-primary" disabled>Last 4 Weeks</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="inventoryTrendChart" style="min-height: 320px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header">
                                <h5 class="mb-0">Inventory Health</h5>
                                <small class="text-muted">Coverage against plan</small>
                            </div>
                            <div class="card-body">
                                <?php foreach ($inventoryHealth as $line): ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold"><?= htmlspecialchars($line['name']) ?></span>
                                        <span class="text-muted small"><?= $line['status'] ?></span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-<?= $line['color'] ?>" role="progressbar" style="width: <?= $line['level'] ?>%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted mt-1">
                                        <span>Level</span>
                                        <span><?= $line['level'] ?>%</span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <hr>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="bullet-dot bg-success"></div><span class="small text-muted">Healthy &gt; 80%</span>
                                    <div class="bullet-dot bg-warning"></div><span class="small text-muted">Monitor 60-80%</span>
                                    <div class="bullet-dot bg-danger"></div><span class="small text-muted">Critical &lt; 60%</span>
                                </div>
                                <hr>
                                <h6 class="text-muted text-uppercase small mb-2">Material Mix</h6>
                                <div id="materialBreakdownChart" style="min-height: 220px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">Inbound Shipments</h5>
                                    <small class="text-muted">Planned receiving in the next 72 hours</small>
                                </div>
                                <a href="#" class="btn btn-outline-primary btn-sm disabled">
                                    <i class="fas fa-download me-1"></i> Export
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle">
                                        <thead class="text-muted small">
                                            <tr>
                                                <th scope="col">ETA</th>
                                                <th scope="col">Supplier</th>
                                                <th scope="col">PO</th>
                                                <th scope="col" class="text-center">Items</th>
                                                <th scope="col" class="text-end">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($incomingShipments as $shipment): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($shipment['eta']) ?></td>
                                                <td class="fw-semibold"><?= htmlspecialchars($shipment['supplier']) ?></td>
                                                <td><span class="badge bg-light text-dark"><?= htmlspecialchars($shipment['po']) ?></span></td>
                                                <td class="text-center"><?= $shipment['items'] ?></td>
                                                <td class="text-end">
                                                    <span class="badge bg-<?= $shipment['status_color'] ?>">
                                                        <?= htmlspecialchars($shipment['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">Quality & Issues</h5>
                                    <small class="text-muted">Latest containment status</small>
                                </div>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-flag me-1 text-danger"></i> Alerts: <?= count($qualityIssues) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle">
                                        <thead class="text-muted small">
                                            <tr>
                                                <th>Date</th>
                                                <th>Part</th>
                                                <th>Issue</th>
                                                <th class="text-end">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($qualityIssues as $issue): ?>
                                            <tr>
                                                <td><?= htmlspecialchars(date('d M', strtotime($issue['date']))) ?></td>
                                                <td class="fw-semibold"><?= htmlspecialchars($issue['part']) ?></td>
                                                <td><?= htmlspecialchars($issue['issue']) ?></td>
                                                <td class="text-end">
                                                    <span class="badge bg-<?= $issue['status_color'] ?>">
                                                        <?= htmlspecialchars($issue['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-8">
                        <div class="card shadow-sm h-100">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">Stock Alerts</h5>
                                        <small class="text-muted">Safety stock breaches and near misses</small>
                                    </div>
                                    <span class="badge bg-danger"><i class="fas fa-bolt me-1"></i> Action Needed</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle">
                                        <thead class="text-muted small">
                                            <tr>
                                                <th>Part No.</th>
                                                <th>Description</th>
                                                <th class="text-center">On Hand</th>
                                                <th class="text-center">Safety Stock</th>
                                                <th class="text-center">Coverage</th>
                                                <th class="text-end">Priority</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($stockAlerts as $alert): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= htmlspecialchars($alert['part']) ?></td>
                                                <td><?= htmlspecialchars($alert['description']) ?></td>
                                                <td class="text-center"><?= number_format($alert['on_hand']) ?></td>
                                                <td class="text-center"><?= number_format($alert['safety_stock']) ?></td>
                                                <td class="text-center"><?= number_format($alert['coverage_days'], 1) ?> days</td>
                                                <td class="text-end">
                                                    <span class="badge bg-<?= $alert['priority_color'] ?>">
                                                        <?= htmlspecialchars($alert['priority']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header">
                                <h5 class="mb-0">Aging Lots</h5>
                                <small class="text-muted">Lots over 21 days in storage</small>
                            </div>
                            <div class="card-body">
                                <?php foreach ($agingLots as $lot): ?>
                                <div class="kanban-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($lot['part']) ?></span>
                                        <span class="text-muted small"><?= $lot['age_days'] ?> days</span>
                                    </div>
                                    <p class="fw-semibold mb-1"><?= htmlspecialchars($lot['lot']) ?></p>
                                    <div class="text-muted small">
                                        <i class="fas fa-location-dot me-1 text-primary"></i><?= htmlspecialchars($lot['location']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-user-gear me-1 text-success"></i>Owner: <?= htmlspecialchars($lot['owner']) ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">Line Readiness & Kanban</h5>
                                    <small class="text-muted">Material coverage for assembly lines</small>
                                </div>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-industry me-1"></i> <?= count($lineReadiness) ?> Lines
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-lg-5">
                                        <div class="table-responsive">
                                            <table class="table table-borderless align-middle">
                                                <thead class="text-muted small">
                                                    <tr>
                                                        <th>Line</th>
                                                        <th class="text-center">Today</th>
                                                        <th class="text-center">Tomorrow</th>
                                                        <th class="text-end">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($lineReadiness as $line): ?>
                                                    <tr>
                                                        <td class="fw-semibold"><?= htmlspecialchars($line['line']) ?></td>
                                                        <td class="text-center"><?= $line['today'] ?>%</td>
                                                        <td class="text-center"><?= $line['tomorrow'] ?>%</td>
                                                        <td class="text-end">
                                                            <span class="badge bg-<?= $line['status_color'] ?>">
                                                                <?= htmlspecialchars($line['status']) ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-7">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <div class="kanban-column h-100">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="bullet-dot bg-warning me-2"></div>
                                                        <span class="fw-semibold">Waiting</span>
                                                    </div>
                                                    <?php foreach ($kanbanBoard['waiting'] as $task): ?>
                                                    <div class="kanban-card">
                                                        <p class="fw-semibold mb-1"><?= htmlspecialchars($task['title']) ?></p>
                                                        <div class="text-muted small"><i class="fas fa-user me-1"></i><?= htmlspecialchars($task['owner']) ?></div>
                                                        <div class="text-muted small"><i class="fas fa-clock me-1"></i><?= htmlspecialchars($task['due']) ?></div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="kanban-column h-100">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="bullet-dot bg-primary me-2"></div>
                                                        <span class="fw-semibold">In Process</span>
                                                    </div>
                                                    <?php foreach ($kanbanBoard['in_process'] as $task): ?>
                                                    <div class="kanban-card">
                                                        <p class="fw-semibold mb-1"><?= htmlspecialchars($task['title']) ?></p>
                                                        <div class="text-muted small"><i class="fas fa-user me-1"></i><?= htmlspecialchars($task['owner']) ?></div>
                                                        <div class="text-muted small"><i class="fas fa-clock me-1"></i><?= htmlspecialchars($task['due']) ?></div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="kanban-column h-100">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="bullet-dot bg-success me-2"></div>
                                                        <span class="fw-semibold">Completed</span>
                                                    </div>
                                                    <?php foreach ($kanbanBoard['completed'] as $task): ?>
                                                    <div class="kanban-card">
                                                        <p class="fw-semibold mb-1"><?= htmlspecialchars($task['title']) ?></p>
                                                        <div class="text-muted small"><i class="fas fa-user me-1"></i><?= htmlspecialchars($task['owner']) ?></div>
                                                        <div class="text-muted small"><i class="fas fa-clock me-1"></i><?= htmlspecialchars($task['due']) ?></div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p><?= date('Y') ?> &copy; CPS Dashboard</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted for Part Control Material Excellence</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="lib/static/js/components/dark.js"></script>
    <script src="lib/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="lib/compiled/js/app.js"></script>
    <script src="lib/extensions/apexcharts/apexcharts.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inventoryTrendOptions = {
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: { show: false }
                },
                colors: ['#435ebe', '#ff7976', '#5ddab4'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                series: <?= json_encode($inventoryTrend['series']) ?>,
                xaxis: {
                    categories: <?= json_encode($inventoryTrend['categories']) ?>,
                    labels: { style: { colors: '#6c757d' } }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#6c757d' },
                        formatter: function(value) {
                            return value + '%';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + '% of plan';
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                grid: {
                    strokeDashArray: 6,
                    borderColor: '#e9ecef'
                }
            };

            const trendChart = new ApexCharts(document.querySelector('#inventoryTrendChart'), inventoryTrendOptions);
            trendChart.render();

            const materialBreakdownOptions = {
                chart: {
                    type: 'donut',
                    height: 220
                },
                series: <?= json_encode($materialBreakdown['series']) ?>,
                labels: <?= json_encode($materialBreakdown['labels']) ?>,
                colors: ['#435ebe', '#ff7976', '#5ddab4', '#ffc107'],
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    formatter: function(value) {
                        return value.toFixed(1) + '%';
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '60%',
                            labels: {
                                show: true,
                                total: {
                                    label: 'Total',
                                    formatter: function() {
                                        return '100%';
                                    }
                                }
                            }
                        }
                    }
                }
            };

            const materialChart = new ApexCharts(document.querySelector('#materialBreakdownChart'), materialBreakdownOptions);
            materialChart.render();
        });
    </script>
</body>

</html>
