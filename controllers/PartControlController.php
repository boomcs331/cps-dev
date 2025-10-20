<?php

class PartControlController extends Controller
{
    public function index()
    {
        SessionManager::checkSession();
        SessionManager::extendSession();

        $summaryCards = [
            [
                'label' => 'Stock On Hand',
                'value' => 12850,
                'unit' => 'pcs',
                'change' => 4.6,
                'trend' => 'up'
            ],
            [
                'label' => 'Incoming This Week',
                'value' => 36,
                'unit' => 'lots',
                'change' => 2.1,
                'trend' => 'up'
            ],
            [
                'label' => 'Critical Alerts',
                'value' => 5,
                'unit' => 'items',
                'change' => 1.2,
                'trend' => 'down'
            ],
            [
                'label' => 'Kanban Cards',
                'value' => 92,
                'unit' => 'open',
                'change' => 3.4,
                'trend' => 'up'
            ],
        ];

        $inventoryHealth = [
            [
                'name' => 'Raw Material',
                'level' => 78,
                'status' => 'Stable',
                'color' => 'success'
            ],
            [
                'name' => 'WIP',
                'level' => 62,
                'status' => 'Monitor',
                'color' => 'warning'
            ],
            [
                'name' => 'Finished Goods',
                'level' => 88,
                'status' => 'Healthy',
                'color' => 'primary'
            ],
        ];

        $materialBreakdown = [
            'labels' => ['Metal', 'Plastic', 'Electronic', 'Packaging'],
            'series' => [38, 27, 19, 16],
        ];

        $inventoryTrend = [
            'categories' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'series' => [
                [
                    'name' => 'Raw Material',
                    'data' => [70, 74, 72, 75, 78, 80],
                ],
                [
                    'name' => 'WIP',
                    'data' => [54, 57, 59, 60, 62, 65],
                ],
                [
                    'name' => 'Finished Goods',
                    'data' => [82, 84, 83, 85, 88, 90],
                ],
            ],
        ];

        $incomingShipments = [
            [
                'eta' => 'Today 14:30',
                'supplier' => 'THAI STEEL CO., LTD.',
                'po' => 'PO-15248',
                'items' => 12,
                'status' => 'Unloading',
                'status_color' => 'primary'
            ],
            [
                'eta' => 'Tomorrow 09:00',
                'supplier' => 'NIPPON PARTS',
                'po' => 'PO-15277',
                'items' => 8,
                'status' => 'On Route',
                'status_color' => 'info'
            ],
            [
                'eta' => 'Wed 16:00',
                'supplier' => 'SIAM ELECTRONICS',
                'po' => 'PO-15291',
                'items' => 15,
                'status' => 'Confirmed',
                'status_color' => 'success'
            ],
        ];

        $stockAlerts = [
            [
                'part' => 'PCM-2145',
                'description' => 'Harness Bracket',
                'on_hand' => 120,
                'safety_stock' => 200,
                'coverage_days' => 1.6,
                'priority' => 'Critical',
                'priority_color' => 'danger'
            ],
            [
                'part' => 'PCM-5022',
                'description' => 'ABS Sensor Kit',
                'on_hand' => 340,
                'safety_stock' => 300,
                'coverage_days' => 4.2,
                'priority' => 'Warning',
                'priority_color' => 'warning'
            ],
            [
                'part' => 'PCM-3310',
                'description' => 'Battery Terminal',
                'on_hand' => 980,
                'safety_stock' => 700,
                'coverage_days' => 6.4,
                'priority' => 'Watch',
                'priority_color' => 'info'
            ],
        ];

        $agingLots = [
            [
                'part' => 'PCM-2201',
                'lot' => 'LOT-124-AX',
                'age_days' => 42,
                'location' => 'Rack B-4',
                'owner' => 'QC',
            ],
            [
                'part' => 'PCM-4488',
                'lot' => 'LOT-091-KL',
                'age_days' => 35,
                'location' => 'Cold Room',
                'owner' => 'PC',
            ],
            [
                'part' => 'PCM-1210',
                'lot' => 'LOT-076-BT',
                'age_days' => 29,
                'location' => 'Rack D-2',
                'owner' => 'PC',
            ],
        ];

        $qualityIssues = [
            [
                'date' => '2025-10-12',
                'part' => 'PCM-5521',
                'issue' => 'Surface rust detected on 3 pcs',
                'owner' => 'QC',
                'status' => 'Containment',
                'status_color' => 'danger'
            ],
            [
                'date' => '2025-10-11',
                'part' => 'PCM-1120',
                'issue' => 'Supplier label missing',
                'owner' => 'PC',
                'status' => 'Follow-up',
                'status_color' => 'warning'
            ],
            [
                'date' => '2025-10-09',
                'part' => 'PCM-7715',
                'issue' => 'Barcode reprint required',
                'owner' => 'WH',
                'status' => 'Closed',
                'status_color' => 'success'
            ],
        ];

        $lineReadiness = [
            [
                'line' => 'Assembly A1',
                'today' => 96,
                'tomorrow' => 92,
                'status' => 'Ready',
                'status_color' => 'success'
            ],
            [
                'line' => 'Assembly B3',
                'today' => 84,
                'tomorrow' => 78,
                'status' => 'Watch',
                'status_color' => 'warning'
            ],
            [
                'line' => 'Module C2',
                'today' => 71,
                'tomorrow' => 66,
                'status' => 'Risk',
                'status_color' => 'danger'
            ],
        ];

        $kanbanBoard = [
            'waiting' => [
                ['title' => 'PCM-5521 replenish', 'owner' => 'Preecha', 'due' => 'Today'],
                ['title' => 'Update supplier matrix', 'owner' => 'Somjit', 'due' => 'Tomorrow'],
            ],
            'in_process' => [
                ['title' => 'Cycle count zone D', 'owner' => 'Anan', 'due' => '14:00'],
                ['title' => 'Lot traceability audit', 'owner' => 'Jariya', 'due' => '16:30'],
            ],
            'completed' => [
                ['title' => 'Weekly shortage review', 'owner' => 'Supaporn', 'due' => '08:30'],
                ['title' => 'Material release plan', 'owner' => 'Tawan', 'due' => 'Yesterday'],
            ],
        ];

        $data = [
            'summaryCards' => $summaryCards,
            'inventoryHealth' => $inventoryHealth,
            'materialBreakdown' => $materialBreakdown,
            'inventoryTrend' => $inventoryTrend,
            'incomingShipments' => $incomingShipments,
            'stockAlerts' => $stockAlerts,
            'agingLots' => $agingLots,
            'qualityIssues' => $qualityIssues,
            'lineReadiness' => $lineReadiness,
            'kanbanBoard' => $kanbanBoard,
        ];

        $this->view('dashboard/pc', $data);
    }
}
