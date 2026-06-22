<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #F5F5F7;
            color: #333;
        }

        .admin-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            background: white;
            padding: 30px 20px;
            border-right: 1px solid #E0E0E0;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            text-decoration: none;
            color: #667eea;
            font-weight: 700;
            font-size: 16px;
        }

        .sidebar-logo i {
            font-size: 24px;
            background: #667eea;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-to-website {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #E0E0E0;
            padding-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-to-website:hover {
            gap: 12px;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 30px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #666;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 14px;
            width: 100%;
            text-align: left;
            font-family: inherit;
        }

        .nav-item:hover {
            background: #F5F5F7;
            color: #667eea;
        }

        .nav-item.active {
            background: #667eea;
            color: white;
            font-weight: 600;
        }

        .nav-item i {
            width: 20px;
            text-align: center;
        }

        .sidebar-divider {
            height: 1px;
            background: #E0E0E0;
            margin: 20px 0;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid #E0E0E0;
            background: white;
            width: 250px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: #F5F5F7;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .user-details {
            flex: 1;
        }

        .user-details p {
            margin: 0;
            font-size: 12px;
        }

        .user-details p:first-child {
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }

        .user-details p:last-child {
            color: #999;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 10px 15px;
            background: #FEE;
            color: #C33;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .logout-btn:hover {
            background: #FDD;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .page {
            display: none;
        }

        .page.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .page-subtitle {
            font-size: 14px;
            color: #999;
            margin-top: 5px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* STAT CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-bottom: 3px solid #667eea;
        }

        .stat-card.green {
            border-bottom-color: #10B981;
        }

        .stat-card.purple {
            border-bottom-color: #A78BFA;
        }

        .stat-card.pink {
            border-bottom-color: #F472B6;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-card:nth-child(1) .stat-icon {
            background: #667eea;
        }

        .stat-card:nth-child(2) .stat-icon {
            background: #10B981;
        }

        .stat-card:nth-child(3) .stat-icon {
            background: #A78BFA;
        }

        .stat-card:nth-child(4) .stat-icon {
            background: #F472B6;
        }

        .stat-content h3 {
            font-size: 12px;
            color: #999;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .stat-content .number {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .stat-content .change {
            font-size: 12px;
            color: #10B981;
            margin-top: 5px;
        }

        /* CHART CONTAINER */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .chart-container h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        /* TABLE */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #E0E0E0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .search-box {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-size: 13px;
            max-width: 400px;
        }

        .filter-btn {
            padding: 10px 15px;
            background: #F5F5F7;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            background: #E0E0E0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: #F5F5F7;
            border-bottom: 1px solid #E0E0E0;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #666;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #F0F0F0;
        }

        tbody tr:hover {
            background: #FAFAFA;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge.warning {
            background: #FEF3C7;
            color: #78350F;
        }

        .badge.danger {
            background: #FEE2E2;
            color: #7F1D1D;
        }

        .badge.info {
            background: #DBEAFE;
            color: #0C2340;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            color: #5568d3;
            transform: scale(1.2);
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: flex-start;
            justify-content: center;
            padding: 20px;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-cancel {
            padding: 10px 20px;
            background: #E0E0E0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #D0D0D0;
        }

        .btn-submit {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: #5568d3;
        }

        /* ENHANCED EDIT ROOM MODAL */
        .modal--edit-room {
            align-items: center;
            padding: 20px;
        }

        .modal-content--edit-room {
            max-width: 700px;
            width: 95%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            padding: 0;
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header--sticky {
            position: sticky;
            top: 0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 24px 28px 20px 28px;
            margin-bottom: 0;
            border-bottom: 1px solid #E5E7EB;
            background: white;
            border-radius: 16px 16px 0 0;
            flex-shrink: 0;
            z-index: 20;
        }

        .modal-header-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .modal-header-subtitle {
            font-size: 13px;
            color: #6B7280;
            font-weight: 400;
        }

        .modal-close-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            background: #F3F4F6;
            color: #6B7280;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .modal-close-btn:hover {
            background: #E5E7EB;
            color: #111827;
        }

        .modal-body--scrollable {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 24px 28px;
            -webkit-overflow-scrolling: touch;
            min-height: 0;
        }

        .modal-body--scrollable::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body--scrollable::-webkit-scrollbar-track {
            background: transparent;
        }

        .modal-body--scrollable::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 3px;
        }

        .modal-body--scrollable::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }

        /* FORM SECTIONS */
        .form-section {
            margin-bottom: 28px;
            padding-bottom: 28px;
            border-bottom: 1px solid #F3F4F6;
        }

        .form-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .form-section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 700;
            color: #374151;
        }

        .form-section-header i {
            width: 32px;
            height: 32px;
            background: #EEF2FF;
            color: #667eea;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* FORM GRID */
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .required-mark {
            color: #EF4444;
            font-weight: 700;
        }

        /* PHOTO UPLOAD AREA */
        .photo-upload-area {
            border: 2px dashed #D1D5DB;
            border-radius: 8px;
            padding: 16px;
            background: #FAFAFA;
            transition: border-color 0.2s;
        }

        .photo-upload-area:focus-within {
            border-color: #667eea;
            background: #F5F7FF;
        }

        .photo-upload-area input {
            border: 1px solid #E0E0E0;
            background: white;
        }

        /* STICKY FOOTER */
        .modal-footer--sticky {
            position: sticky;
            bottom: 0;
            padding: 16px 28px;
            border-top: 1px solid #E5E7EB;
            background: white;
            margin-top: 0;
            border-radius: 0 0 16px 16px;
            flex-shrink: 0;
            z-index: 20;
        }

        .modal-footer--sticky .btn-cancel,
        .modal-footer--sticky .btn-submit {
            padding: 10px 24px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            border-radius: 8px;
        }

        /* EDIT ROOM FORM */
        .edit-room-form {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
        }

        /* ========================================== */
        /* ADD ROOM MODAL */
        /* ========================================== */
        .modal--add-room {
            align-items: center;
            padding: 20px;
        }

        .modal-content--add-room {
            max-width: 1000px;
            width: 95%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            padding: 0;
            overflow: hidden;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .add-room-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* FORM SECTION CARD */
        .form-section-card {
            background: #FAFBFC;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: border-color 0.2s;
        }

        .form-section-card:hover {
            border-color: #D1D5DB;
        }

        /* PRICE INPUT */
        .input-price-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-price-wrapper:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-price-prefix {
            padding: 10px 14px;
            background: #F5F5F7;
            color: #666;
            font-weight: 600;
            font-size: 13px;
            border-right: 1px solid #E0E0E0;
            white-space: nowrap;
        }

        .input-price {
            flex: 1;
            border: none !important;
            padding: 10px 14px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            border-radius: 0 !important;
        }

        .input-price:focus {
            box-shadow: none !important;
            border-color: transparent !important;
        }

        /* STATUS RADIO CARDS */
        .status-radio-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .status-radio-card {
            display: block;
            cursor: pointer;
        }

        .status-radio-card input[type="radio"] {
            display: none;
        }

        .status-radio-content {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            transition: all 0.2s;
            background: white;
        }

        .status-radio-content i {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .status-radio-content strong {
            display: block;
            font-size: 13px;
            color: #374151;
        }

        .status-radio-content small {
            font-size: 11px;
            color: #9CA3AF;
        }

        .status-radio-card input[type="radio"]:checked + .status-radio-content {
            border-color: #667eea;
            background: #EEF2FF;
        }

        .status-radio-card:hover .status-radio-content {
            border-color: #D1D5DB;
        }

        /* PHOTO UPLOAD AREA - HOVER */
        .photo-upload-area:hover {
            border-color: #667eea;
            background: #F5F7FF;
        }

        /* FORM VALIDATION */
        .form-group input.is-invalid,
        .form-group select.is-invalid,
        .form-group textarea.is-invalid {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-error {
            color: #EF4444;
            font-size: 11px;
            margin-top: 4px;
            display: none;
        }

        .form-error.show {
            display: block;
        }

        /* ADD ROOM MODAL RESPONSIVE */
        @media (max-width: 900px) {
            .add-room-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .modal-content--add-room {
                max-width: 95vw;
                width: 95vw;
                max-height: 90vh;
                border-radius: 12px;
            }

            .modal--add-room {
                padding: 8px;
            }
        }

        /* TABLE RESPONSIVE */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* NEW GRID CLASSES */
        .dashboard-charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-activity-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* MOBILE HEADER & OVERLAY */
        .mobile-header {
            display: none;
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #E0E0E0;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .menu-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: #667eea;
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .menu-btn:hover {
            background: #F5F5F7;
        }

        .mobile-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
        }

        .mobile-logo i {
            font-size: 20px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1001;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* EDIT ROOM MODAL RESPONSIVE */
        @media (max-width: 768px) {
            .modal--edit-room {
                padding: 8px;
            }

            .modal-content--edit-room {
                max-width: 95vw;
                width: 95vw;
                max-height: 90vh;
                border-radius: 12px;
            }

            .modal-header--sticky {
                padding: 16px 20px 14px 20px;
            }

            .modal-header-title {
                font-size: 17px;
            }

            .modal-header-subtitle {
                font-size: 12px;
            }

            .modal-body--scrollable {
                padding: 16px 20px;
            }

            .modal-footer--sticky {
                padding: 12px 20px;
                flex-wrap: wrap;
            }

            .modal-footer--sticky .btn-cancel,
            .modal-footer--sticky .btn-submit {
                flex: 1;
                justify-content: center;
                padding: 10px 16px;
                font-size: 12px;
            }

            .form-grid-2 {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .form-section {
                margin-bottom: 20px;
                padding-bottom: 20px;
            }

            .form-section-header {
                font-size: 13px;
            }

            .form-section-header i {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .admin-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                width: 250px;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 1002;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding-top: 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .mobile-header {
                display: flex;
            }

            .dashboard-charts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .page-title {
                font-size: 22px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 10px;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .search-box {
                max-width: 100%;
                width: 100%;
            }

            .table-actions {
                width: 100%;
                display: flex;
                gap: 10px;
            }

            .dashboard-activity-grid {
                grid-template-columns: 1fr;
            }

            .btn-primary {
                padding: 8px 16px;
                font-size: 12px;
                border-radius: 6px;
                white-space: nowrap;
            }

            .page-header {
                flex-wrap: wrap;
                gap: 12px;
            }
        }
    </style>
</head>

<body>
    <!-- MOBILE HEADER -->
    <div class="mobile-header">
        <button class="menu-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="mobile-logo">
            <i class="fas fa-building"></i>
            <span>KosKita</span>
        </div>
        <div style="width: 32px;"></div>
    </div>

    <!-- SIDEBAR BACKDROP -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="{{ route('home') }}" class="sidebar-logo">
            <i class="fas fa-building"></i>
            <span>KosKita</span>
        </a>



        <div class="nav-menu">
            <button class="nav-item active" onclick="showPage('dashboard')">
                <i class="fas fa-th"></i>
                Dashboard
            </button>
            <button class="nav-item" onclick="showPage('manajemen-kamar')">
                <i class="fas fa-door-open"></i>
                Manajemen Kamar
            </button>
            <button class="nav-item" onclick="showPage('booking-review')">
                <i class="fas fa-clipboard-check"></i>
                Booking Baru
                @if($pendingReviewCount > 0)
                    <span style="background: #EF4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; margin-left: auto;">{{ $pendingReviewCount }}</span>
                @endif
            </button>
            <button class="nav-item" onclick="showPage('manajemen-penyewa')">
                <i class="fas fa-users"></i>
                Manajemen Penyewa
            </button>
            <button class="nav-item" onclick="showPage('laporan-keuangan')">
                <i class="fas fa-chart-line"></i>
                Laporan Keuangan
            </button>
            <button class="nav-item" onclick="showPage('inbox')">
                <i class="fas fa-inbox"></i>
                Inbox
                <span id="unreadBadge"
                    style="display: none; background: #EF4444; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; margin-left: auto;">0</span>
            </button>
        </div>

        <div class="sidebar-divider"></div>

        <button class="nav-item" onclick="showPage('pengaturan')">
            <i class="fas fa-cog"></i>
            Pengaturan
        </button>

        <div class="sidebar-footer">
            <div class="user-info" id="userInfo">
                <div class="user-avatar">A</div>
                <div class="user-details">
                    <p id="adminName">Admin User</p>
                    <p id="adminEmail">admin@koskita.com</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logoutForm" style="display: none;">
                @csrf
            </form>
            <button class="logout-btn"
                onclick="event.preventDefault(); localStorage.removeItem('currentUser'); document.getElementById('logoutForm').submit();">
                <i class="fas fa-sign-out-alt"></i>
                Keluar
            </button>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        @include('dashboard.admin.dashboard')
        @include('dashboard.admin.kamar')
        @include('dashboard.admin.penyewa')
        @include('dashboard.admin.keuangan')
        @include('dashboard.admin.pengaturan')
        @include('dashboard.admin.inbox')
        @include('dashboard.admin.booking-review')
    </div>

    @include('dashboard.admin.modals')

    <script>
        // Set currentUser in localStorage from Laravel Auth session
        @if(Auth::check())
            localStorage.setItem('currentUser', JSON.stringify({
                id: {{ Auth::user()->id }},
                name: "{{ Auth::user()->name }}",
                email: "{{ Auth::user()->email }}",
                role: "{{ Auth::user()->role }}",
                loginTime: new Date().toISOString()
            }));
        @endif
    </script>
    <script>
        // ============================================
        // AUTHENTICATION CHECK
        // ============================================
        const APP_ROUTES = {
            login: @json(route('login.page')),
            userDashboard: @json(route('dashboard.users')),
        };

        function checkAdminAuth() {
            const authData = localStorage.getItem('currentUser');
            if (!authData) {
                window.location.href = APP_ROUTES.login;
                return false;
            }

            const user = JSON.parse(authData);

            if (user.role !== 'admin') {
                window.location.href = user.role === 'user' ? APP_ROUTES.userDashboard : APP_ROUTES.login;
                return false;
            }

            return user;
        }

        function logoutAdmin() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                localStorage.removeItem('currentUser');
                localStorage.removeItem('adminAuth');
                window.location.href = APP_ROUTES.login;
            }
        }

        // ============================================
        // SIDEBAR TOGGLE
        // ============================================
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            if (sidebar.classList.contains('show')) {
                overlay.style.display = 'block';
                setTimeout(() => overlay.classList.add('show'), 10);
            } else {
                overlay.classList.remove('show');
                setTimeout(() => overlay.style.display = 'none', 300);
            }
        }

        // ============================================
        // PAGE NAVIGATION
        // ============================================
        function showPage(pageName) {
            const targetPage = document.getElementById(pageName);
            if (!targetPage) return;

            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });

            targetPage.classList.add('active');

            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });

            const activeNav = document.querySelector(`.nav-item[onclick*="${pageName}"]`);
            if (activeNav) {
                activeNav.classList.add('active');
            }

            // Close sidebar on mobile
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.classList.contains('show')) {
                toggleSidebar();
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
            if (window.location.hash !== `#${pageName}`) {
                history.replaceState(null, '', `#${pageName}`);
            }
        }

        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 20px;
                border-radius: 8px;
                background: ${type === 'success' ? '#10B981' : '#EF4444'};
                color: white;
                z-index: 9999;
                font-size: 14px;
                font-weight: 600;
                animation: slideIn 0.3s ease-out;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // ============================================
        // INITIALIZE
        // ============================================
        document.addEventListener('DOMContentLoaded', function () {
            const adminData = checkAdminAuth();
            if (!adminData) return;

            const adminNameEl = document.getElementById('adminName');
            const adminEmailEl = document.getElementById('adminEmail');
            if (adminNameEl) adminNameEl.textContent = adminData.name;
            if (adminEmailEl) adminEmailEl.textContent = adminData.email;

            const initialPage = window.location.hash.replace('#', '');
            if (initialPage) {
                showPage(initialPage);
            }

            // Add animations style
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(400px); opacity: 0; }
                    to   { transform: translateX(0);     opacity: 1; }
                }
                @keyframes slideOut {
                    to   { transform: translateX(400px); opacity: 0; }
                }
            `;
            document.head.appendChild(style);

            // Close modals when clicking outside
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function (e) {
                    if (e.target === this) this.classList.remove('show');
                });
            });

            // Close modals with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal.show').forEach(modal => {
                        modal.classList.remove('show');
                    });
                }
            });

            // Search box keyup handler
            document.querySelectorAll('.search-box').forEach(searchBox => {
                searchBox.addEventListener('keyup', function () {
                    console.log('Search:', this.value);
                });
            });
        });
    </script>
</body>

</html>