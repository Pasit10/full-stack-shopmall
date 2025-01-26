<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
        }

        .navbar {
            display: flex;
            align-items: center;
            padding: 20px 5%;
            background-color: #ffffff;
            border-bottom: 3px solid #ddd;
        }

        .homepage a {
            font-size: 28px;
            color: #333;
            text-decoration: none;
            font-weight: 600;
        }

        .cart-profile-button {
            background: #1e2a3a;
            font-size: 16px;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
            margin-left: 15px;
            transition: all 0.3s ease;
        }

        .cart-profile-button:hover {
            background: #0d1926;
            transform: scale(1.05);
        }

        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
        }

        .card {
            text-align: center;
            width: 50%;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .card-header {
            font-size: 30px;
            color: #333;
            font-weight: 600;
            background: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 15px;
        }

        .dropdown-item {
            font-size: 16px;
            padding: 10px;
            transition: background 0.3s;
        }

        .dropdown-item:hover {
            background: #007bff;
            color: white;
        }

        .action-buttons {
            margin-top: 20px;
            display: none;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('.dropdown-item').click(function() {
                var selectedText = $(this).text();
                $('#dropdownMenuButton').text(selectedText);
                $('#confirmButton').data('report-type', selectedText);
                $('.action-buttons').show();
            });

            $('#confirmButton').click(function() {
                var reportType = $(this).data('report-type');
                if (reportType) {
                    window.location.href = reportType.replace(/ /g, '').toLowerCase() + ".php";
                }
            });

            $('#cancelButton').click(function() {
                $('#dropdownMenuButton').text('Choose Report Type');
                $('.action-buttons').hide();
            });
        });
    </script>
</head>

<body>
    <div>
        <div class="navbar">
            <div class="homepage">
                <a href="homeadmin.php">Homepage</a>
            </div>
            <div class="ml-auto">
                <a href="selectReport.php">
                    <button type="button" class="btn btn-info cart-profile-button">
                        <i class="fas fa-file-alt"></i> Report
                    </button>
                </a>
            </div>
        </div>

        <div class="card-container">
            <div class="card">
                <div class="card-header">Report</div>
                <div class="card-body">
                    <h5 class="card-title">Select Report</h5>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Choose Report Type
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item" type="button">AdminActivityReport</button>
                            <button class="dropdown-item" type="button">MonthlySalesReport</button>
                            <button class="dropdown-item" type="button">OrderStatusReport</button>
                            <button class="dropdown-item" type="button">Profitandcostreport</button>
                            <button class="dropdown-item" type="button">RevenueByProductReport</button>
                            <button class="dropdown-item" type="button">TopSellingReport</button>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button id="confirmButton" class="btn btn-success">Confirm</button>
                        <button id="cancelButton" class="btn btn-danger">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
