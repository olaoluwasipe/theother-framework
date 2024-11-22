<?php
namespace App\Controllers;

use App\Facades\Auth;
use App\Models\CampaignAgency;
use App\Models\Game;
use App\Models\Transaction;
use Core\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class HomeController extends Controller {
    private function getBasicStats($query = null) {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');
        $baseQuery = $query ?? Transaction::query()->whereIn('service_id', $serviceIDs);

        // echo number_format(Transaction::where('amount','>=', 0)->where('charges_status','Success')->where('bearer_id','SecureD')->count());
        // exit;
        
        return [
            'revenue' => [
                'total' => format_money((clone $baseQuery)->where('amount', '>', 1)->sum('amount')),
                'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1]], '7 day', 'mysql2', 't_date', true)
            ],
            'subs' => [
                'total' => number_format((clone $baseQuery)->where('amount','>=', 0)->where('charges_status','Success')->where('bearer_id','SecureD')->count()),
                'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1], ['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'SecureD']], '7 day', 'mysql2', 't_date', true, true)
            ],
            'unSubs' => [
                'total' => number_format((clone $baseQuery)->where('charges_status', 'You deactivate the service successfully.')->count()),
                'percentage' => get_percentage_difference('transactions', '*', [['column' => 'charges_status', 'value'=>'You deactivate the service successfully.']], '7 day', 'mysql2', 't_date', true, true)
            ],
            'subRev' => [
                'total' => format_money((clone $baseQuery)->where('amount', '>', 1)->where('charges_status', 'Success')->where('bearer_id', '<>', 'system-renewal')->sum('amount')),
                'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1], ['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator' => '<>', 'value'=> 'system-renewal']], '7 day', 'mysql2', 't_date', true, true)
            ],
            'renRev' => [
                'total' => format_money((clone $baseQuery)->where('amount', '>', 1)->where('charges_status', 'Success')->where('bearer_id', '=', 'system-renewal')->sum('amount')),
                'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1], ['column'=> 'charges_status', 'value'=> 'Success'], ['column' => 'bearer_id', 'value' => 'system-renewal']], '7 day', 'mysql2', 't_date', true, true)
            ],
            'churnRate' => [
                'total' => number_format($this->getChurnRate(),2) .'%',
                'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1], ['column'=> 'charges_status', 'value'=> 'Success'], ['column' => 'bearer_id', 'value' => 'system-renewal']], '7 day', 'mysql2', 't_date', true, true)
            ]
        ];
        
    }

    private function getGraphData() {
        return [
            'revenue' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1]], 'day', 7, 'mysql2', 't_date', false),
            'subscriptions' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1], ['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'SecureD']], 'day', 7, 'mysql2', 't_date', true),
            'unSubs' => get_interval_data('transactions', 'amount', [['column' => 'charges_status', 'value'=>'You deactivate the service successfully.']], 'day', 7, 'mysql2', 't_date', true),
            'subRev' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1], ['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator' => '<>', 'value'=> 'system-renewal']], 'day', 7, 'mysql2', 't_date', true),
            'renRev' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1], ['column'=> 'charges_status', 'value'=> 'Success'], ['column' => 'bearer_id', 'value' => 'system-renewal']], 'day', 7, 'mysql2', 't_date', true)
        ];
    }

    public function index() {
        $services = Game::all();
        $stats = $this->getBasicStats();
        $churnRate = $this->getChurnRate();
        $report = $this->getReport();

        view('home', compact('stats', 'services', 'report', 'churnRate'));
    }

    public function getData() {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');
        $query = Transaction::query()->whereIn('service_id', $serviceIDs);
        if(!empty($_POST['agency'])) {
            $code = $_POST['agency'];
            $campaignAgency = CampaignAgency::where('code', $code)->firstOrFail();
            $query = $query->where('amount', '>', 1)->where('trans_id', 'LIKE', "$campaignAgency->code%");
        }

        if (!empty($_POST['from'])) {
            $from = $_POST['from'];
            $to = $_POST['to'] ?? now();
            $query = $query->whereBetween('t_date', [$from, $to]);
        }
        return json_success([
            'stats' => $this->getBasicStats($query),
            'graphs' => $this->getGraphData()
        ]);
    }

    public function getCampaignData($code) {
        $campaignAgency = CampaignAgency::where('code', $code)->firstOrFail();
        $query = Transaction::where('amount', '>', 1)->where('trans_id', 'LIKE', "$campaignAgency->code%");
        
        return json_success([
            'stats' => $this->getBasicStats($query),
            'graphs' => $this->getGraphData()
        ]);
    }

    public function getReport($service = null) {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');

        $query = DB::connection('mysql2')
            ->table('transactions')
            ->select([
                DB::raw("DATE(t_date) AS date"),
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
                DB::raw('ROUND((COUNT(DISTINCT CASE WHEN charges_status = "You deactivate the service successfully." THEN msisdn END) / NULLIF(COUNT(DISTINCT CASE WHEN charges_status = "Success" AND bearer_id <> "SecureD" THEN msisdn END), 0)) * 100, 2) AS churn_rate')
            ])
            ->where('t_date', '>', DB::raw('NOW() - INTERVAL 10 DAY'))
            ->groupBy(DB::raw('DATE(t_date)'))
            ->orderBy(DB::raw('DATE(t_date)'), 'DESC');

        return $service === null ? 
            $query->whereIn('service_id', $serviceIDs)->get() : 
            $query->where('service_id', $service)->when($serviceIDs->has($service), function($q) { return $q; })->get();
    }

    public function getChurnRate() {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');

        $activeSubscribers = Transaction::where('charges_status', 'Success')
            ->whereIn('service_id', $serviceIDs)
            ->where('bearer_id', '<>', 'SecureD')
            ->distinct('msisdn')
            ->count('msisdn');

        $subscribersLost = Transaction::where('charges_status', 'You deactivate the service successfully.')
            ->whereIn('service_id', $serviceIDs)
            ->distinct('msisdn')
            ->count('msisdn');

        return $activeSubscribers > 0 ? ($subscribersLost / $activeSubscribers) * 100 : 0;
    }

    public function login() {
        if(Auth::check()) redirect('/');
        view('login');
    }

    public function register() {
        if(Auth::check()) redirect('/');
        view('register');
    }
}
