<!DOCTYPE html>
<html>
<head>
    <title>Mandated Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
        }

        .section-title {
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mandated Report</h1>
        <p><strong>Generated On:</strong> {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div>
        <h2 class="section-title">Summary Section</h2>
        <p><strong>Total Number of Applicants:</strong> {{ $totalClients }}</p>
        <p><strong>Ongoing Applicants:</strong> {{ $ongoingClients }}</p>
        <p><strong>Completed Applicants:</strong> {{ $closedClients }}</p>
    </div>

    <div>
        <h2 class="section-title">Demographic Breakdown</h2>
        <table>
            <thead>
                <tr>
                    <th>Barangay</th>
                    <th>Average Income</th>
                </tr>
            </thead>
            <tbody>
                @foreach (json_decode($averageIncomeData, true) as $barangay => $income)
                <tr>
                    <td>{{ $barangay }}</td>
                    <td>₱{{ number_format($income, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        <h2 class="section-title">Predictive Analytics Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Barangay</th>
                    <th>Service</th>
                    <th>Predicted Next 3 Years</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servicePredictions as $barangay => $services)
                    @foreach ($services as $service => $predictions)
                    <tr>
                        <td>{{ $barangay }}</td>
                        <td>{{ $service }}</td>
                        <td>
                            Year 1: {{ $predictions[1] }},
                            Year 2: {{ $predictions[2] }},
                            Year 3: {{ $predictions[3] }}
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        <h2 class="section-title">Recommendations and Insights</h2>
        <p>Based on trends and predictive analytics, it is recommended to allocate additional resources to the following services:</p>
        <ul>
            <li>Burial Assistance</li>
            <li>Crisis Intervention Unit</li>
            <li>Solo Parent Services</li>
        </ul>
    </div>
</body>
</html>
