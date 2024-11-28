<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FamilyMember;
use App\Models\Social;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $totalClients = Client::count();
        $totalFamilyMembers = FamilyMember::count();
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        $clientsLastYear = Client::whereYear('created_at', $previousYear)->get();
        $monthlyData = $clientsLastYear->groupBy(function ($date) {
            return $date->created_at->format('m'); // Group by month
        });

        $monthlyAverages = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyCount = $monthlyData->has(str_pad($month, 2, '0', STR_PAD_LEFT)) ? $monthlyData[str_pad($month, 2, '0', STR_PAD_LEFT)]->count() : 0;
            $monthlyAverages[] = $monthlyCount;
        }
        $totalMonthlyClients = array_sum($monthlyAverages);
        $monthly_average = count($monthlyAverages) > 0 ? $totalMonthlyClients / count($monthlyAverages) : 0;
        $predictedClients = intval($monthly_average * 12);

        $barangays = [
            'Bagumbayan',
            'Bambang',
            'Calzada',
            'Cembo',
            'Central Bicutan',
            'Central Signal Village',
            'Comembo',
            'East Rembo',
            'Fort Bonifacio',
            'Hagonoy',
            'Ibayo-Tipas',
            'Katuparan',
            'Ligid-Tipas',
            'Lower Bicutan',
            'Maharlika Village',
            'Napindan',
            'New Lower Bicutan',
            'North Daang Hari',
            'North Signal Village',
            'Rizal',
            'Palingon',
            'Pembo',
            'Pinagsama',
            'Pitogo',
            'Post Proper Northside',
            'Post Proper Southside',
            'San Miguel',
            'Santa Ana',
            'South Daang Hari',
            'South Signal Village',
            'South Cembo',
            'Tuktukan',
            'Tanyag',
            'Upper Bicutan',
            'Ususan',
            'Wawa',
            'Western Bicutan',
            'West Rembo',
        ];

        // Services and Predictions
        $services = [
            'Burial Assistance',
            'Crisis Intervention Unit',
            'Solo Parent Services',
            'Pre-marriage Counseling',
            'After-Care Services',
            'Poverty Alleviation Program',
        ];

        $servicePredictions = [];
        $barangayServiceCounts = [];

        foreach ($barangays as $barangay) {
            foreach ($services as $service) {
                $clientsLastYearWithService = Client::where('barangay', $barangay)
                    ->where('services', 'LIKE', '%' . $service . '%')
                    ->whereYear('created_at', $previousYear)
                    ->get();

                $monthlyData = $clientsLastYearWithService->groupBy(function ($date) {
                    return $date->created_at->format('m');
                });

                $monthlyAverages = [];
                for ($month = 1; $month <= 12; $month++) {
                    $monthlyCount = $monthlyData->has(str_pad($month, 2, '0', STR_PAD_LEFT)) ? $monthlyData[str_pad($month, 2, '0', STR_PAD_LEFT)]->count() : 0;
                    $monthlyAverages[] = $monthlyCount;
                }

                $totalMonthlyClients = array_sum($monthlyAverages);
                $monthlyAverage = count($monthlyAverages) > 0 ? $totalMonthlyClients / count($monthlyAverages) : 0;

                $barangayServiceCounts[$barangay][$service] = $totalMonthlyClients;

                $predictedNextYears = [];
                for ($year = 1; $year <= 3; $year++) {
                    $predictedNextYears[$year] = intval($monthlyAverage * 12);
                }

                $servicePredictions[$barangay][$service] = $predictedNextYears;
            }
        }

        // Income Ranges
        $incomeRanges = [
            'No Income' => 0,
            '100 PHP - 500 PHP' => 300,
            '500 PHP - 1000 PHP' => 750,
            '1000 PHP - 2000 PHP' => 1500,
            '2000 PHP - 5000 PHP' => 3500,
            '5000 PHP - 6000 PHP' => 5500,
            '6000 PHP - 7000 PHP' => 6500,
            '7000 PHP - 8000 PHP' => 7500,
            '8000 PHP - 9000 PHP' => 8500,
            '9000 PHP - 10,000 PHP' => 9500,
            'Above 20,000 PHP' => 20000,
        ];

        // Calculate barangay income totals and counts
        $barangayIncomeTotals = [];
        $barangayIncomeCounts = [];

        foreach ($barangays as $barangay) {
            $clientsInBarangay = Client::where('barangay', $barangay)->get();
            foreach ($clientsInBarangay as $client) {
                $incomeRange = $client->monthly_income;
                $income = isset($incomeRanges[$incomeRange]) ? $incomeRanges[$incomeRange] : 0;

                if (!isset($barangayIncomeTotals[$barangay])) {
                    $barangayIncomeTotals[$barangay] = 0;
                    $barangayIncomeCounts[$barangay] = 0;
                }
                $barangayIncomeTotals[$barangay] += $income;
                $barangayIncomeCounts[$barangay]++;
            }
        }

        // Calculate average income per barangay
        $barangayAverageIncome = [];
        foreach ($barangayIncomeTotals as $barangay => $total) {
            $average = $barangayIncomeCounts[$barangay] > 0 ? $total / $barangayIncomeCounts[$barangay] : 0;
            $barangayAverageIncome[$barangay] = $average;
        }

        // Calculate predicted average income for the next 3 years
        $predictedAverageIncomeData = [];
        $growthRate = 0.05; // 5% growth rate

        foreach ($barangayAverageIncome as $barangay => $averageIncome) {
            $predictedIncome = [];
            for ($year = 1; $year <= 3; $year++) {
                $predictedIncome[$year] = round($averageIncome * pow(1 + $growthRate, $year));
            }
            $predictedAverageIncomeData[$barangay] = $predictedIncome;
        }


        foreach ($barangays as $barangay) {
            if (!isset($predictedAverageIncomeData[$barangay])) {
                $predictedAverageIncomeData[$barangay] = [1 => 0, 2 => 0, 3 => 0];
            }
        }

        // Data for the view
        $averageIncomeData = json_encode($barangayAverageIncome);
        $predictedAverageIncomeData = json_encode($predictedAverageIncomeData);

        $completedClients = Client::where('problem_identification', 'Done')
            ->where('data_gather', 'Done')
            ->where('assessment', 'Done')
            ->where('eval', 'Done')
            ->count();
        $incompleteClients = Client::where(function ($query) {
            $query->where('problem_identification', '!=', 'Done')
                ->orWhere('data_gather', '!=', 'Done')
                ->orWhere('assessment', '!=', 'Done')
                ->orWhere('eval', '!=', 'Done');
        })->count();
        $closedClients = Client::where('tracking', '=', 'Approve')->count();
        $ongoingClients = Client::where('tracking', '=', 'Re-access')->count();
        $totalSocialWorkers = Social::where('role', 'social-worker')->count();

        return view('home', compact(
            'totalClients',
            'closedClients',
            'ongoingClients',
            'averageIncomeData',
            'predictedAverageIncomeData',
            'servicePredictions',
            'totalFamilyMembers',
            'totalSocialWorkers',
            'barangays',
            'predictedClients',
            'monthly_average',
            'barangayServiceCounts',
            'services'
        ));
    }


    public function getIncomeBrackets(Request $request)
    {
        if ($request->barangay === 'all') {

            $clients = Client::all();
        } else {
            // Query for specific barangay
            $clients = Client::where('barangay', $request->barangay)->get();
        }

        return response()->json(['clients' => $clients]);
    }

    public function getMostRequestedServices(Request $request)
    {
        $barangay = $request->input('barangay');

        if ($barangay === 'all') {

            $mostRequestedServices = DB::table('clients')
                ->select(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(IFNULL(services, "[]"), "$[*]")) AS service, COUNT(*) as count'))
                ->groupBy('service')
                ->orderBy('count', 'DESC')
                ->limit(5)
                ->get();
        } else {
            $mostRequestedServices = DB::table('clients')
                ->select(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(IFNULL(services, "[]"), "$[*]")) AS service, COUNT(*) as count'))
                ->where('barangay', $barangay)
                ->groupBy('service')
                ->orderBy('count', 'DESC')
                ->limit(5)
                ->get();
        }

        $requirements = [
            'Burial Assistance' => ['Burial', 'Financial', 'Crisis Intervention Unit = Valid ID', 'Barangay Clearance.', 'Medical Certificate.', 'Incident Report.', 'Funeral Contract.', 'Death Certificate.'],
            'Crisis Intervention Unit' => ['Valid ID', 'Residence Certificate Or Barangay Clearance', 'Clinical Abstract/Medical Certificate', 'Police Report Or Incident Report', 'Funeral Contract And Registered Death Certificate. (if Applicable)'],
            'Solo Parent Services' => ['Solo Parent = Agency Referral', 'Residency Cert.', 'Medical Cert.', 'Billing Proof', 'Birth Cert.', 'ID Copy', 'Senior Citizen ID (60+)'],
            'Pre-marriage Counseling' => ['Pre-marriage Counseling = Valid ID', 'Birth Certificate', 'CENOMAR', 'Barangay Clearance', 'Passport-sized Photos'],
            'After-Care Services' => ['After-Care Services = Valid ID', 'Birth Certificate.', 'Residence Certificate.', 'SCSR', 'Medical Records'],
            'Poverty Alleviation Program' => ['Poverty Alleviation Program = Valid ID', 'Residence Certificate', 'Income Certificate',  'Application Form'],
        ];

        $unrelatedServices = [
            'Refrigerator',
            'Washing Machine',
            'Television',
            'Microwave',
            'Air Conditioner',
            'Electric Fan'
        ];

        $requestedServices = $mostRequestedServices->map(function ($serviceCount) use ($requirements, $unrelatedServices) {
            $serviceArray = json_decode($serviceCount->service);

            if (empty($serviceArray)) {
                return null;
            }

            $filteredServiceArray = array_diff($serviceArray, $unrelatedServices);
            $matchedService = null;

            foreach ($requirements as $serviceName => $req) {
                if (array_intersect($req, $filteredServiceArray)) {
                    $matchedService = $serviceName;
                    break;
                }
            }

            $serviceCount->service = $matchedService ?? $serviceCount->service;
            return $serviceCount;
        })->filter();

        return response()->json($requestedServices->values());
    }



    public function getGenderDistribution(Request $request)
    {
        $barangay = $request->input('barangay');

        // Prepare the query
        $query = DB::table('clients')
            ->select(DB::raw('sex AS gender, COUNT(*) as count'))
            ->groupBy('sex');


        if ($barangay !== 'all') {
            $query->where('barangay', $barangay);
        }

        $genderData = $query->get()->pluck('count', 'gender');

        return response()->json([
            'male' => $genderData->get('Male', 0),
            'female' => $genderData->get('Female', 0),
            'others' => $genderData->get('Other', 0),
        ]);
    }

    public function getAgeGroupServices(Request $request)
    {
        $barangay = $request->input('barangay');


        $ageGroups = [
            '0-17' => 0,
            '18-64' => 0,
            '65+' => 0,
        ];

        $query = DB::table('clients');


        if ($barangay !== 'all') {
            $query->where('barangay', $barangay);
        }


        $clients = $query->get();


        foreach ($clients as $client) {
            $age = intval($client->age);
            if ($age <= 17) {
                $ageGroups['0-17']++;
            } elseif ($age <= 64) {
                $ageGroups['18-64']++;
            } else {
                $ageGroups['65+']++;
            }
        }

        return response()->json($ageGroups);
    }

    public function getReportData()
    {
        // Fetch general statistics
        $totalClients = Client::count();
        $totalFamilyMembers = FamilyMember::count();
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        $ongoingClients = Client::where('tracking', '=', 'Re-access')->count();
        $closedClients = Client::where('tracking', '=', 'Approve')->count();

        // Fetch client data for the previous year
        $clientsLastYear = Client::whereYear('created_at', $previousYear)->get();

        // Group client data by month
        $monthlyData = $clientsLastYear->groupBy(function ($date) {
            return $date->created_at->format('m'); // Group by month (e.g., 01, 02, etc.)
        });

        // Calculate monthly averages
        $monthlyAverages = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyKey = str_pad($month, 2, '0', STR_PAD_LEFT); // Convert to two-digit format
            $monthlyCount = $monthlyData->has($monthlyKey) ? $monthlyData[$monthlyKey]->count() : 0;
            $monthlyAverages[] = $monthlyCount;
        }

        // Calculate total monthly clients and the overall average
        $totalMonthlyClients = array_sum($monthlyAverages);
        $monthly_average = count($monthlyAverages) > 0 ? $totalMonthlyClients / count($monthlyAverages) : 0;

        // Predict total clients for the next year based on the monthly average
        $predictedClients = intval($monthly_average * 12);

        // Barangay list
        $barangays = [
            'Bagumbayan',
            'Bambang',
            'Calzada',
            'Cembo',
            'Central Bicutan',
            'Central Signal Village',
            'Comembo',
            'East Rembo',
            'Fort Bonifacio',
            'Hagonoy',
            'Ibayo-Tipas',
            'Katuparan',
            'Ligid-Tipas',
            'Lower Bicutan',
            'Maharlika Village',
            'Napindan',
            'New Lower Bicutan',
            'North Daang Hari',
            'North Signal Village',
            'Rizal',
            'Palingon',
            'Pembo',
            'Pinagsama',
            'Pitogo',
            'Post Proper Northside',
            'Post Proper Southside',
            'San Miguel',
            'Santa Ana',
            'South Daang Hari',
            'South Signal Village',
            'South Cembo',
            'Tuktukan',
            'Tanyag',
            'Upper Bicutan',
            'Ususan',
            'Wawa',
            'Western Bicutan',
            'West Rembo',
        ];

        // Income ranges and their corresponding values
        $incomeRanges = [
            'No Income' => 0,
            '100 PHP - 500 PHP' => 300,
            '500 PHP - 1000 PHP' => 750,
            '1000 PHP - 2000 PHP' => 1500,
            '2000 PHP - 5000 PHP' => 3500,
            '5000 PHP - 6000 PHP' => 5500,
            '6000 PHP - 7000 PHP' => 6500,
            '7000 PHP - 8000 PHP' => 7500,
            '8000 PHP - 9000 PHP' => 8500,
            '9000 PHP - 10,000 PHP' => 9500,
            'Above 20,000 PHP' => 20000,
        ];

        // Initialize variables for storing income totals and counts
        $barangayIncomeTotals = [];
        $barangayIncomeCounts = [];

        // Iterate through barangays to calculate income totals and averages
        foreach ($barangays as $barangay) {
            $clientsInBarangay = Client::where('barangay', $barangay)->get();
            foreach ($clientsInBarangay as $client) {
                $incomeRange = $client->monthly_income;
                $income = isset($incomeRanges[$incomeRange]) ? $incomeRanges[$incomeRange] : 0;

                if (!isset($barangayIncomeTotals[$barangay])) {
                    $barangayIncomeTotals[$barangay] = 0;
                    $barangayIncomeCounts[$barangay] = 0;
                }

                $barangayIncomeTotals[$barangay] += $income;
                $barangayIncomeCounts[$barangay]++;
            }
        }

        // Calculate average income for each barangay
        $barangayAverageIncome = [];
        foreach ($barangayIncomeTotals as $barangay => $total) {
            $average = $barangayIncomeCounts[$barangay] > 0 ? $total / $barangayIncomeCounts[$barangay] : 0;
            $barangayAverageIncome[$barangay] = $average;
        }

        $services = [
            'Burial Assistance',
            'Crisis Intervention Unit',
            'Solo Parent Services',
            'Pre-marriage Counseling',
            'After-Care Services',
            'Poverty Alleviation Program',
        ];

        $servicePredictions = [];
        foreach ($barangays as $barangay) {
            foreach ($services as $service) {
                // Collect data for predictions (e.g., from previous year's data)
                $clientsLastYearWithService = Client::where('barangay', $barangay)
                    ->where('services', 'LIKE', '%' . $service . '%')
                    ->whereYear('created_at', $previousYear)
                    ->get();

                $monthlyData = $clientsLastYearWithService->groupBy(function ($date) {
                    return $date->created_at->format('m');
                });

                $monthlyAverages = [];
                for ($month = 1; $month <= 12; $month++) {
                    $monthlyCount = $monthlyData->has(str_pad($month, 2, '0', STR_PAD_LEFT)) ? $monthlyData[str_pad($month, 2, '0', STR_PAD_LEFT)]->count() : 0;
                    $monthlyAverages[] = $monthlyCount;
                }

                $totalMonthlyClients = array_sum($monthlyAverages);
                $monthlyAverage = count($monthlyAverages) > 0 ? $totalMonthlyClients / count($monthlyAverages) : 0;

                $predictedNextYears = [];
                for ($year = 1; $year <= 3; $year++) {
                    $predictedNextYears[$year] = intval($monthlyAverage * 12);
                }

                $servicePredictions[$barangay][$service] = $predictedNextYears;
            }
        }

        // Encode average income data to JSON for easy usage in views
        $averageIncomeData = json_encode($barangayAverageIncome);

        // Return all data as an array for use in the PDF report generation
        return compact(
            'totalClients',
            'totalFamilyMembers',
            'barangays',
            'services',
            'servicePredictions',
            'closedClients',
            'ongoingClients',
            'averageIncomeData'
        );
    }


    public function generatePDF()
    {
        $data = $this->getReportData();

        // Set timezone to Manila (Asia/Manila)
        $manilaTime = now()->setTimezone('Asia/Manila');

        // Generate the PDF using the data
        $pdf = Pdf::loadView('reports.report', $data);

        // Save PDF to storage with Manila time
        $filePath = 'reports/' . $manilaTime->format('Y-m-d_H-i-s') . '_report.pdf';
        Storage::put($filePath, $pdf->output());

        // Save the report record to the database with Manila time
        Report::create([
            'title' => 'Generated Report - ' . $manilaTime->format('Y-m-d H:i:s'),
            'description' => 'This is a system-generated report.',
            'file_path' => $filePath,
            'status' => 'active',
        ]);

        return redirect()->route('reports.index')->with('success', 'Report generated successfully!');
    }

    public function blank()
    {
        return view('layouts.register');
    }
}
