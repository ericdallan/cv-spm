<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .sidebar {
            background-color: #343a40;
            color: white;
            min-height: 100vh;
            padding-top: 20px;
            width: 200px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #495057;
        }

        .content {
            padding: 20px;
        }

        .content h2 {
            margin-bottom: 20px;
            color: #343a40;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-text {
            color: #343a40;
        }

        .navbar-nav .nav-link {
            color: #343a40;
        }

        .navbar-nav .nav-link:hover {
            color: #007bff;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            color: #343a40;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        /* Responsive Styles */
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -200px;
                /* Hide sidebar initially */
                width: 200px;
                height: 100%;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.active {
                left: 0;
                /* Show sidebar when active */
            }

            .content {
                margin-left: 0;
                transition: margin-left 0.3s ease;
            }

            .content.active {
                margin-left: 200px;
                /* Adjust content margin */
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard_page') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('account_page') }}">
                            <i class="fas fa-solid fa-book"></i> Chart of Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('voucher_page') }}">
                            <i class="fas fa-file-invoice-dollar"></i> Voucher
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('generalledger_page') }}">
                            <i class="fas fa-file-alt"></i> General Ledger
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('trialBalance_page') }}">
                            <i class="fas fa-file"></i> Trial Balance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('incomeStatement_page') }}">
                            <i class="fa-solid fa-scale-balaned"></i> Income Statement
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('balanceSheet_page') }}">
                            <i class="fa-solid fa-building"></i> Balance Sheet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employee_page') }}">
                            <i class="fas fa-users"></i> Employee
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('company_page') }}">
                            <i class="fas fa-building"></i> Company
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="flex-grow-1 content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.navbar-toggler');
            const content = document.querySelector('.content');

            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            });
        });
    </script>
</body>

</html>