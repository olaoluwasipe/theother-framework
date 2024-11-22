<?php

namespace App\Controllers;

use App\Facades\Auth;
use App\Models\CampaignAgency;
use App\Models\Game;
use App\Models\Transaction;
use Core\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class HomeController2 extends Controller
{
    public function index()
    {
        $services = Game::all();
        $revenue = $this->calculateStats('transactions', 'amount', '>', 1);
        $subs = $this->calculateStats('transactions', 'amount', '>=', 0, [
            ['charges_status', 'Success'],
            ['bearer_id', 'SecureD']
        ], 'count');
        $unSubs = $this->calculateStats('transactions', 'charges_status', '=', 'You deactivate the service successfully.', [], 'count', 'charges_status');
        $subRev = $this->calculateStats('transactions', 'amount', '>', 1, [
            ['charges_status', 'Success'],
            ['bearer_id', '<>', 'system-renewal']
        ]);
        $renRev = $this->calculateStats('transactions', 'amount', '>', 1, [
            ['charges_status', 'Success'],
            ['bearer_id', '=', 'system-renewal']
        ]);
        $churnRate = $this->getChurnRate();
        $report = $this->getReport();

        view('home', compact('revenue', 'subs', 'unSubs', 'subRev', 'renRev', 'services', 'report', 'churnRate'));
    }

    public function getData()
    {
        $data = [
            'stats' => $this->generateStats(),
            'graphs' => $this->generateGraphs()
        ];
        return json_success($data);
    }

    public function getCampaignData($code)
    {
        $campaignAgency = CampaignAgency::where('code', $code)->firstOrFail();
        $query = Transaction::where('amount', '>', 1)->where('trans_id', 'LIKE', "{$campaignAgency->code}%");

        $data = [
            'stats' => $this->generateStats($query),
            'graphs' => $this->generateGraphs($query)
        ];

        return json_success($data);
    }

    public function getReport($service = null)
    {
        $serviceIDs = Game::pluck('service_id');
        $query = DB::connection('mysql2')
        ->table('transactions')
        ->select(
            DB::raw("DATE(t_date) AS date"),
            // DB::raw("'$service' AS camp_name"),
            DB::raw("'Active' AS status"),
            DB::raw("'MTN Nigeria' AS geography"),
            DB::raw("COUNT(DISTINCT CASE WHEN charges_status = 'Success' AND bearer_id <> 'system-renewal' THEN msisdn END) AS new_conversion"),
            DB::raw("'0.35' AS cost_per_aqui"),
            DB::raw("COUNT(DISTINCT CASE WHEN charges_status = 'Success' AND bearer_id <> 'system-renewal' THEN msisdn END) * 0.35 AS conversion_amount"),
            DB::raw("COUNT(DISTINCT CASE WHEN charges_status = 'Success' AND bearer_id <> 'system-renewal' AND amount > 0 THEN msisdn END) AS subscription"),
            DB::raw("COUNT(DISTINCT CASE WHEN charges_status = 'You deactivate the service successfully.' THEN msisdn END) AS unsub"),
            DB::raw("SUM(CASE WHEN charges_status = 'Success' AND bearer_id <> 'system-renewal' AND amount > 0 THEN amount ELSE 0 END) AS sub_revenue"),
            DB::raw("SUM(CASE WHEN charges_status = 'Success' AND bearer_id = 'system-renewal' AND amount > 0 THEN amount ELSE 0 END) AS renewal_revenue"),
            DB::raw("SUM(CASE WHEN charges_status = 'Success' AND bearer_id = 'system-renewal' THEN 1 ELSE 0 END) AS total_rev"),
            DB::raw('COUNT(DISTINCT CASE WHEN charges_status = "Success" AND bearer_id <> "SecureD" THEN msisdn END) AS active'),
            DB::raw('COUNT(DISTINCT CASE WHEN charges_status = "You deactivate the service successfully." THEN msisdn END) AS lost'),
            DB::raw('ROUND((COUNT(DISTINCT CASE WHEN charges_status = "You deactivate the service successfully." THEN msisdn END) / 
                            COUNT(DISTINCT CASE WHEN charges_status = "Success" AND bearer_id <> "SecureD" THEN msisdn END)) * 100, 2) AS churn_rate')
        )
                    ->where('t_date', '>', DB::raw('NOW() - INTERVAL 10 DAY'))
                    ->groupBy(DB::raw('DATE(t_date)'))
                    // ->groupBy(DB::raw('DATE(t_date), service_id')) 
                    ->orderBy(DB::raw('DATE(t_date)'), 'DESC');

        if ($service) {
            $query->where('service_id', $service);
        } else {
            $query->whereIn('service_id', $serviceIDs);
        }

        return $query->get();
    }

    public function getChurnRate()
    {
        $serviceIDs = Game::pluck('service_id');

        $activeSubscribers = Transaction::whereIn('service_id', $serviceIDs)
            ->where('charges_status', 'Success')
            ->distinct('msisdn')
            ->count('msisdn');

        $subscribersLost = Transaction::whereIn('service_id', $serviceIDs)
            ->where('charges_status', 'You deactivate the service successfully.')
            ->distinct('msisdn')
            ->count('msisdn');

        return $activeSubscribers ? ($subscribersLost / $activeSubscribers) * 100 : 0;
    }

    private function calculateStats($table, $column, $operator, $value, $conditions = [], $operation='sum', $targetColumn = 'amount', )
    {
        $query = Transaction::where($column, $operator, $value);

        foreach ($conditions as $condition) {
            $query->where($condition[0], $condition[1], $condition[2]);
        }

        switch ($operation) {
            case 'sum':
                $result = $query->sum($targetColumn);
                break;
            case 'count':
                $result = $query->count();
                break;
            default:
                $result = $query->sum($targetColumn);
                break;
        }

        return [
            'total' => $operation == 'sum' ? format_money($result) : number_format($result),
            'percentage' => get_percentage_difference($table, $column, $conditions, '7 day', 'mysql2', 't_date', true)
        ];
    }

    private function generateStats($query = null)
    {
        $query = $query ?? Transaction::query();
        return [
            'revenue' => $this->calculateStats($query, 'amount', '>', 1),
            'subs' => $this->calculateStats($query, 'amount', '>=', 0, [
                ['charges_status', 'Success'],
                ['bearer_id', 'SecureD']
            ]),
            'unSubs' => $this->calculateStats($query, '*', '=', 'You deactivate the service successfully.', [], 'charges_status'),
        ];
    }

    private function generateGraphs($query = null)
    {
        // Replace this method with reusable graph generation logic
        
        
    }

    // private function generateGraphs($query = null)
    //         {
    //             $query = $query ?? Transaction::query();
                
    //             return [
    //                 'daily' => [
    //                     'labels' => $this->getLast7Days(),
    //                     'datasets' => [
    //                         [
    //                             'label' => 'Revenue',
    //                             'data' => $this->getDailyRevenue($query),
    //                             'borderColor' => '#4CAF50',
    //                             'fill' => false
    //                         ],
    //                         [
    //                             'label' => 'Subscriptions',
    //                             'data' => $this->getDailySubscriptions($query),
    //                             'borderColor' => '#2196F3',
    //                             'fill' => false
    //                         ],
    //                         [
    //                             'label' => 'Unsubscriptions',
    //                             'data' => $this->getDailyUnsubscriptions($query),
    //                             'borderColor' => '#F44336',
    //                             'fill' => false
    //                         ]
    //                     ]
    //                 ],
    //                 'monthly' => [
    //                     'labels' => $this->getLast6Months(),
    //                     'datasets' => [
    //                         [
    //                             'label' => 'Revenue',
    //                             'data' => $this->getMonthlyRevenue($query),
    //                             'borderColor' => '#4CAF50',
    //                             'fill' => false
    //                         ],
    //                         [
    //                             'label' => 'Subscriptions',
    //                             'data' => $this->getMonthlySubscriptions($query),
    //                             'borderColor' => '#2196F3',
    //                             'fill' => false
    //                         ],
    //                         [
    //                             'label' => 'Unsubscriptions',
    //                             'data' => $this->getMonthlyUnsubscriptions($query),
    //                             'borderColor' => '#F44336',
    //                             'fill' => false
    //                         ]
    //                     ]
    //                 ]
    //             ];
    //         }
        

    public function login()
    {
        if (Auth::check()) {
            redirect('/');
        }
        view('login');
    }

    public function register()
    {
        if (Auth::check()) {
            redirect('/');
        }
        view('register');
    }
}
