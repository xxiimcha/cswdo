<!DOCTYPE html>
<html>
<head>
    <title>Mandated Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 20px;
        }

        .header {
            text-align: center;

            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            vertical-align: middle;
            width: 80px;
            height: auto;
        }

        .header .title {
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            margin: 0 20px;
        }

        .header .title h1 {
            margin: 5px 0;
            font-size: 20px;
        }

        .mandated-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
            text-align: center;
        }

        .generated-on {
            font-size: 12px;
            text-align: center;
            margin-bottom: 15px;
        }

        .mandate-vision {
            margin-top: 20px;
        }

        .mandate-vision h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            display: inline-block;
            width: 70px;
        }

        .mandate-vision p {
            display: inline-block;
            margin-left: 10px;
            text-align: justify;
        }

        .section-title {
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
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
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <img src="img/logo.png" width="120" height="120" alt="City of Taguig Logo">
        <div class="title">
            <h1>City Social Welfare and Development Office</h1>
        </div>
        <img src="img/logo2.png" width="100" height="90" alt="Right Logo">
    </div>

    <!-- Mandated Report Title and Generated Date -->
    <div>
        <p class="mandated-title">Mandated Report</p>
        <p class="generated-on">Generated On: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <!-- Mandate and Vision Section -->
    <div class="mandate-vision">
        <div>
            <h3><b>MANDATE</b></h3>
            <p>
                The CSWDO is established and mandated to care, protect and rehabilitate most disadvantaged individuals
                and have the least in life in terms of social welfare assistance and development interventions
                so that they could become more productive members of society.
            </p>
        </div>

        <div>
            <h3><b>VISION</b></h3>
            <p>
                A society where the poor vulnerable and disadvantaged individuals, families and communities
                are empowered for an improved quality of life.
            </p>
        </div>
    </div>

    <!-- Summary Section -->
    <div>
        <h2 class="section-title">Summary Section</h2>
        <p><strong>Total Number of Applicants:</strong> {{ $totalClients }}</p>
        <p><strong>Ongoing Applicants:</strong> {{ $ongoingClients }}</p>
        <p><strong>Completed Applicants:</strong> {{ $closedClients }}</p>
    </div>

    <!-- Demographic Breakdown Section -->
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
                @if (!empty($averageIncomeData))
                    @foreach (json_decode($averageIncomeData, true) as $barangay => $income)
                        <tr>
                            <td>{{ $barangay }}</td>
                            <td>PHP {{ number_format($income, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Predictive Analytics Summary Section -->
    <div>
        <h2 class="section-title">Predictive Analytics Summary</h2>
        @foreach ($servicePredictions as $barangay => $services)
        <h3>{{ $barangay }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Predicted Next 3 Years</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service => $predictions)
                <tr>
                    <td>{{ $service }}</td>
                    <td>
                        Year 1: {{ $predictions[1] }}<br>
                        Year 2: {{ $predictions[2] }}<br>
                        Year 3: {{ $predictions[3] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>

    <!-- Recommendations and Insights Section -->
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
