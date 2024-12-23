<?php
namespace App\Controllers;

use App\Facades\Auth;
use App\Helpers\CacheManager;
use App\Models\CampaignAgency;
use App\Models\Game;
use App\Models\Transaction;
use Core\Controller;
use Core\Logger;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Cache;
use Core\CacheService;

class HomeController extends Controller {

    protected $cache;

    public function __construct() {
        $cacheService = new CacheService();
        $this->cache = $cacheService->getCache();
    }
    
    private function getBasicStats($query = null)
    {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');
        $baseQuery = $query ?? Transaction::query()->whereIn('service_id', $serviceIDs);

        // Cache time-to-live in seconds
        $cacheTTL = 3600;

        // Fetch the last update time from the cache
        $lastUpdateTimeKey = 'stats:last_update_time';
        $lastUpdateTime = $this->cache->get($lastUpdateTimeKey, now()->subDays(1));

        // Fetch new transactions since the last update
        $newTransactionsQuery = (clone $baseQuery)->where('t_date', '>', $lastUpdateTime);

        // Update the last update time in the cache
        $this->cache->put($lastUpdateTimeKey, now(), $cacheTTL);

        // Helper function to update a specific cached stat
        $updateCachedStat = function ($key, $newValue) use ($cacheTTL) {
            $cachedValue = $this->cache->get($key, 0);
            $updatedValue = transformToInteger($cachedValue) + $newValue;
            $this->cache->put($key, $updatedValue, $cacheTTL);
            if(strpos($cachedValue, '%') === true) {
                return number_format($updatedValue, 2) . '%';
            }
            if(strpos($cachedValue, '₦') === true) {
                return '₦' . number_format($updatedValue, 2);
            }
            return $updatedValue;
        };

        // Generate and update stats
        return [
            'revenue' => [
                'total' => $updateCachedStat(
                    'stats:revenue:total',
                    (clone $newTransactionsQuery)->where('amount', '>', 1)->sum('amount')
                ),
                'percentage' => $this->cache->remember('stats:revenue:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1]], '7', 'mysql2', 't_date', true);
                }),
            ],
            'subs' => [
                'total' => $updateCachedStat(
                    'stats:subs:total',
                    (clone $newTransactionsQuery)->where('amount', '>=', 0)->where('charges_status', 'Success')->where('bearer_id', 'SecureD')->count()
                ),
                'percentage' => $this->cache->remember('stats:subs:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1], ['column' => 'charges_status', 'value' => 'Success'], ['column' => 'bearer_id', 'value' => 'SecureD']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'unSubs' => [
                'total' => $updateCachedStat(
                    'stats:unSubs:total',
                    (clone $newTransactionsQuery)->where('charges_status', 'You deactivate the service successfully.')->count()
                ),
                'percentage' => $this->cache->remember('stats:unSubs:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', '*', [['column' => 'charges_status', 'value' => 'You deactivate the service successfully.']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'subRev' => [
                'total' => $updateCachedStat(
                    'stats:subRev:total',
                    (clone $newTransactionsQuery)->where('amount', '>', 1)->where('charges_status', 'Success')->where('bearer_id', '<>', 'system-renewal')->sum('amount')
                ),
                'percentage' => $this->cache->remember('stats:subRev:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1], ['column' => 'charges_status', 'value' => 'Success'], ['column' => 'bearer_id', 'operator' => '<>', 'value' => 'system-renewal']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'renRev' => [
                'total' => $updateCachedStat(
                    'stats:renRev:total',
                    (clone $newTransactionsQuery)->where('amount', '>', 1)->where('charges_status', 'Success')->where('bearer_id', '=', 'system-renewal')->sum('amount')
                ),
                'percentage' => $this->cache->remember('stats:renRev:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1], ['column' => 'charges_status', 'value' => 'Success'], ['column' => 'bearer_id', 'value' => 'system-renewal']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'churnRate' => [
                'total' => $this->cache->remember('stats:churnRate:total', $cacheTTL, function () {
                    return number_format($this->getChurnRate(), 2) . '%';
                }),
                'percentage' => $this->cache->remember('stats:churnRate:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1], ['column' => 'charges_status', 'value' => 'Success'], ['column' => 'bearer_id', 'value' => 'system-renewal']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
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

    public function index()
    {
        $services = Game::all();

        // Cache the basic stats and churn rate for 1 hour
        $stats = $this->cache->remember('basic_stats', 3600, function () {
            return $this->getBasicStats();
        });

        $churnRate = $this->cache->remember('churn_rate', 3600, function () {
            return $this->getChurnRate();
        });

        // Cache the report for 1 hour
        $report = $this->cache->remember('report', 3600, function () {
            return $this->getReport();
        });

        return view('home', compact('stats', 'services', 'report', 'churnRate'));
    }

    public function getData()
    {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');

        // Build the initial query
        $query = Transaction::query()->whereIn('service_id', $serviceIDs);

        // Filter by agency
        if (!empty($_POST['agency'])) {
            $code = $_POST['agency'];
            $campaignAgency = CampaignAgency::where('code', $code)->firstOrFail();
            $query = $query->where('amount', '>', 1)->where('trans_id', 'LIKE', "$campaignAgency->code%");
        }

        // Filter by date range
        if (!empty($_POST['from'])) {
            $from = $_POST['from'];
            $to = $_POST['to'] ?? now();
            $query = $query->whereBetween('t_date', [$from, $to]);
        }

        // Logging the query and bindings
        $logger = new Logger();
        $logger->info('Query: ' . $query->toSql());
        $logger->info('Bindings: ' . json_encode($query->getBindings()));

        // Generate a unique cache key based on filters
        $filtersKey = md5(json_encode($_POST));

        // Paginate the query (10 items per page)
        $paginatedResults = $query->paginate(10);

        return json_success([
            'stats' => $this->cache->remember('data_stats:' . $filtersKey, 3600, function () use ($query) {
                return $this->getBasicStats($query);
            }),
            'graphs' => $this->cache->remember('data_graphs:' . $filtersKey, 3600, function () {
                return $this->getGraphData();
            }),
            'pagination' => $paginatedResults, // Add the paginated results to the response
        ]);
    }



    public function getCampaignData($code)
    {
        $campaignAgency = CampaignAgency::where('code', $code)->firstOrFail();

        $query = Transaction::where('amount', '>', 1)->where('trans_id', 'LIKE', "$campaignAgency->code%");

        return json_success([
            'stats' => $this->cache->remember("campaign_data_stats:{$code}", 3600, function () use ($query) {
                return $this->getBasicStats($query);
            }),
            'graphs' => $this->cache->remember("campaign_data_graphs:{$code}", 3600, function () {
                return $this->getGraphData();
            }),
        ]);
    }

    public function getReport($service = null)
    {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');

        return $this->cache->remember('report:' . ($service ?? 'all'), 3600, function () use ($service, $serviceIDs) {
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
                $query->where('service_id', $service)->when($serviceIDs->has($service), function ($q) {
                    return $q;
                })->get();
        });
    }

    public function getChurnRate()
    {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');

        return $this->cache->remember('churn_rate', 3600, function () use ($serviceIDs) {
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
        });
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
