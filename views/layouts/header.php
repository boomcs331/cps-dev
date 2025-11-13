<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CPS</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>lib/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>lib/css/dashboard-shared.css">
    <style>
        /* Receipt Detail Styles */
        .receipt-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .info-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
        }
        
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .qr-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: white;
            transition: all 0.2s ease;
        }
        
        .qr-card:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0,123,255,0.15);
        }
        
        .qr-code-container {
            width: 120px;
            height: 120px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        
        .qr-loading {
            color: #6c757d;
            font-size: 0.875rem;
        }
        
        .qr-loading i {
            animation: spin 1s linear infinite;
            margin-bottom: 5px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .qr-info {
            text-align: center;
        }
        
        .qr-box-number {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .qr-quantity {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 8px;
        }
        
        .qr-code-text {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.75rem;
            color: #495057;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        
        .qr-code-text:hover {
            background: #e9ecef;
        }
        
        .qr-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .qr-status.available {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .qr-status.used {
            background: #f8d7da;
            color: #721c24;
        }
        
        .qr-status.reserved {
            background: #fff3cd;
            color: #856404;
        }
        
        @media print {
            .btn, .card-header .btn {
                display: none !important;
            }
            
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            
            .qr-code-container canvas {
                max-width: 80px !important;
                max-height: 80px !important;
            }
            
            .qr-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
            
            .qr-card {
                break-inside: avoid;
                margin-bottom: 10px;
            }
        }
        
        @media (max-width: 768px) {
            .receipt-info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .qr-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>