<?php
namespace App\Controllers;

use App\Facades\Auth;
use App\Helpers\CacheManager;
use App\Models\CampaignAgency;
use App\Models\CampaignLog;
use App\Models\Game;
use App\Models\Transaction;
use Core\Controller;
use Core\Logger;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Cache;
use Core\CacheService;

class HomeController extends Controller {

    protected $cache;

    protected $logger;

    public function __construct() {
        $cacheService = new CacheService();
        $this->logger = new Logger();
        $this->cache = $cacheService->getCache();
    }
    
    private function getBasicStats($query = null, $campaignQuery = null) {

        // Initialize the base query
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');
        $baseQuery = $query ?? Transaction::query()->whereIn('service_id', $serviceIDs);
        $campaignQuery = $campaignQuery ?? CampaignLog::query();

        $filtersKey = null;
        $filters = null;

        // Check if filters are set in the session
        if (!empty($_SESSION['filters'])) {
            $filtersKey = 'data_stats:' . $_SESSION['filters'];
            $filters = $this->cache->get($filtersKey);
        }

        // If filters are not set, you might want to handle a fallback scenario
        if (is_null($filters)) {
            // Example: set default filters or log that no filters were found
            $filters = []; // Default value (optional)
        }

        // Cache time-to-live in seconds
        $cacheTTL = 3600;

        // Fetch the last update time from the cache, or default to 1 day ago
        $lastUpdateTimeKey = 'stats:last_update_time';
        $lastUpdateTime = $this->cache->get($lastUpdateTimeKey, now()->subDays(1));

        // Fetch new transactions since the last update
        $newTransactionsQuery = ($query || $filters ? $query : null) ?? (clone $baseQuery)->where('t_date', '>', $lastUpdateTime);
        $newCampaignQuery = ($campaignQuery || $filters ? $campaignQuery : null) ?? (clone $campaignQuery)->where('t_date', '>', $lastUpdateTime);

        // After fetching new transactions, update the last update time in the cache
        $this->cache->put($lastUpdateTimeKey, now(), $cacheTTL);

        // Helper function to update a specific cached stat
        $updateCachedStat = function ($key, $newValue) use ($cacheTTL, $filters, $query) {
            // Get the cached value or default to 0 if it doesn't exist
            $cachedValue = $this->cache->get($key, 0);
            $this->logger->info('Cached value for ' . $key . ': ' . $cachedValue);
            
            // Ensure both values are properly converted to integers before addition
            if($filters !== null || $filters !== '' || $query !== null) {
                $updatedValue = transformToFloat($newValue);
            } else {
                $updatedValue = transformToFloat($cachedValue) + transformToFloat($newValue);
            }
            // $updatedValue = transformToInteger($cachedValue) + transformToInteger($newValue);

            // Update the cache with the new value
            $this->cache->put($key, $updatedValue, $cacheTTL);

            // Handle specific formats (e.g., percentage, currency)
            if (strpos($newValue, '%') !== false || strpos($cachedValue, '%') !== false) {
                return number_format($updatedValue, 2) . '%';
            }
            if (strpos($cachedValue, '₦') !== false || strpos($newValue, '₦') !== false) {
                return '₦' . number_format($updatedValue, 2);
            }
            if (strpos($newValue, ',') !== false) {
                return number_format($updatedValue);
            }

            return $updatedValue;
        };

        // Generate and update stats
        return [
            'revenue' => [
                'total' => $updateCachedStat(
                    'stats:revenue:total',
                    format_money((clone $newTransactionsQuery)->where('amount', '>', 0)->where('charges_status', 'Success')->sum('amount'))
                ),
                'percentage' => $this->cache->remember('stats:revenue:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1]], '7', 'mysql2', 't_date', true);
                }),
            ],
            'subs' => [
                'total' => $updateCachedStat(
                    'stats:subs:total',
                    (isset($_POST['agency'])) ? number_format((clone $newCampaignQuery)->where('status', 1)->count()) : number_format((clone $newTransactionsQuery)->whereNotNull('amount')->where('bearer_id', 'SecureD')->count())
                ),
                'percentage' => $this->cache->remember('stats:subs:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1], ['column' => 'charges_status', 'value' => 'Success'], ['column' => 'bearer_id', 'value' => 'SecureD']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'unSubs' => [
                'total' => $updateCachedStat(
                    'stats:unSubs:total',
                    number_format((clone $newTransactionsQuery)->where('charges_status', 'You deactivate the service successfully.')->count())
                ),
                'percentage' => $this->cache->remember('stats:unSubs:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', '*', [['column' => 'charges_status', 'value' => 'You deactivate the service successfully.']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'subRev' => [
                'total' => $updateCachedStat(
                    'stats:subRev:total',
                    format_money((clone $newTransactionsQuery)->where('amount', '>', 1)->where('charges_status', 'Success')->where('bearer_id', '<>', 'system-renewal')->sum('amount'))
                ),
                'percentage' => $this->cache->remember('stats:subRev:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1], ['column' => 'charges_status', 'value' => 'Success'], ['column' => 'bearer_id', 'operator' => '<>', 'value' => 'system-renewal']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'renRev' => [
                'total' => $updateCachedStat(
                    'stats:renRev:total',
                    format_money((clone $newTransactionsQuery)->where('amount', '>', 1)->where('charges_status', 'Success')->where('bearer_id', '=', 'system-renewal')->sum('amount'))
                ),
                'percentage' => $this->cache->remember('stats:renRev:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1], ['column' => 'charges_status', 'value' => 'Success'], ['column' => 'bearer_id', 'value' => 'system-renewal']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'churnRate' => [
                'total' => $updateCachedStat(
                    'stats:churnRate:total',
                     $this->getChurnRate()
                ),
                'percentage' => $this->cache->remember('stats:churnRate:percentage', $cacheTTL, function () {
                    return get_percentage_difference('transactions', 'amount', [['column' => 'amount', 'operator' => '>', 'value' => 1], ['column' => 'charges_status', 'value' => 'Success'], ['column' => 'bearer_id', 'value' => 'system-renewal']], '7', 'mysql2', 't_date', true, true);
                }),
            ],
            'conversionRate' => [
                'total' => $updateCachedStat(
                    'stats:conversionRate:total',
                    $this->getConversionRate( $newCampaignQuery ?? null)
                ),
                'percentage' => $this->cache->remember('stats:conversionRate:percentage', $cacheTTL, function () {
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
        $this->cache->clear();
        $services = Game::all();

        $date['initial'] = now()->subDays(1)->format('Y-m-d\TH:i');
        $date['final'] = now()->format('Y-m-d\TH:i');

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

        // $report = paginate($this->getReport(), 10);
        $games = Transaction::query()
                ->whereIn('service_id', $services->pluck('service_id'))
                ->select( DB::raw('COUNT(id) as transaction_count'))
                ->groupBy('service_id')
                ->get();
        
        $gameNames = $services->pluck('name');
        $transactionCounts = $games->pluck('transaction_count');

        $pastFourMonths = [];

        foreach ($services as $game) {
            $pastFourMonths[] = Transaction::select(
                                            DB::raw("SUM(amount) as total_amount"),
                                            DB::raw("DATE_FORMAT(t_date, '%Y-%m') as month_year") // Format year and month for ordering
                                        )
                                        ->where('service_id', $game->service_id)
                                        ->where('t_date', '>=', now()->subMonths(4)) // Get transactions within last 4 months
                                        ->groupBy(DB::raw("DATE_FORMAT(t_date, '%Y-%m')")) // Group by formatted date
                                        ->orderBy(DB::raw("DATE_FORMAT(t_date, '%Y-%m')"), 'asc') // Order by formatted date
                                        ->pluck('total_amount') // Get as a simple array
                                        ->toArray();
        }

        $pastFourMonths = json_encode($pastFourMonths);

        $campaigns = CampaignAgency::all()->map(function ($agency) {
            $result = new \stdClass;
            
            // Aggregate transactions for each agency by `trans_id` prefix
            $query = Transaction::query()->where('trans_id', 'LIKE', $agency->code . '%')->where('charges_status', 'Success');
            
            // Get the sum of amounts
            $result->total_amount = $query->sum('amount');
            
            // Get the count of transactions where bearer_id is 'SecureD'
            $result->transaction_count = CampaignLog::where('uniq_id', 'LIKE', $agency->code . '%')->where('status', 1)->count();
            
            // Add the agency's name to the result
            $result->agency_name = $agency->name;
            
            return $result;
        })
        ->sortByDesc(function ($item) {
            // Sort first by total_amount, and if equal, then by transaction_count
            return $item->transaction_count;
        })
        ->values(); // Reset the array keys after sorting

        return view('home', compact(
                'stats', 
            'services', 
            'report', 
            'churnRate', 
            'date', 
            'gameNames', 
            'transactionCounts', 
            'pastFourMonths',
            'campaigns'
        ));
    }

    public function getData()
    {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');
        $serviceCodes = $services->pluck('service_code');

        // Build the initial query
        $query = Transaction::query()->whereIn('service_id', $serviceIDs);
        $campaignQuery = CampaignLog::query();

        // Filter by Service 
        if (!empty($_POST['service'])) {
            $service = $_POST['service'];
            $query = $query->where('service_id', $service);
            $campaignQuery = $campaignQuery->where('service_code', Game::where('service_id', $service)->first()->code);
        }

        // Filter by agency
        if (!empty($_POST['agency'])) {
            $code = $_POST['agency'];
            $campaignAgency = CampaignAgency::where('code', $code)->firstOrFail();
            $query = $query->where('amount', '>', 1)->where('trans_id', 'LIKE', "$campaignAgency->code%");
            $campaignQuery = $campaignQuery->where('uniq_id', 'LIKE', "$campaignAgency->code%");
        }

        // Filter by date range
        if (!empty($_POST['from'])) {
            $from = $_POST['from'] . ' 00:00:00';
            $to = (!empty($_POST['to']) ? $_POST['to'] . ' 23:59:59' : (now()->modify('23:59:59'))->format('Y-m-d H:i:s'));
            $query = $query->whereBetween('t_date', [$from, $to]);
            $campaignQuery = $campaignQuery->whereBetween('t_date', [$from, $to]);
        }

        // Logging the query and bindings
        $this->logger->info('Query: ' . $query->toSql());
        $this->logger->info('Bindings: ' . json_encode($query->getBindings()));

        // Generate a unique cache key based on filters
        $filtersKey = md5(json_encode($_POST));

        // Invalidate the cache for the key before querying
        // $this->cache->forget('data_stats:' . $filtersKey);
        // $this->cache->forget('data_graphs:' . $filtersKey);

        $_SESSION['filters'] = $filtersKey;

        // Paginate the query (10 items per page)
        // $paginatedResults = $query->paginate(10);

        return json_success([
            'stats' => $this->cache->remember('data_stats:' . $filtersKey, 3600, function () use ($query, $campaignQuery) {
        return $this->getBasicStats($query, $campaignQuery);
            }),
            'graphs' => $this->cache->remember('data_graphs:' . $filtersKey, 3600, function () {
                return $this->getGraphData();
            }),
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

    public function getConversionRate($query = null) {
        // Ensure $query is a valid QueryBuilder instance
        // $query = $query ?? CampaignLog::query();
    
        // Clone the query before modifying it
        $success = (clone $query)->where('status', 1)->count();
        $unsuccess = (clone $query)->where('status', 0)->count();
    
        // Avoid division by zero
        if ($success + $unsuccess === 0) {
            return 0; // No data available, so return 0% conversion rate
        }

        // print_r(($success / ($success + $unsuccess)) * 100);
        return number_format(($success / ($success + $unsuccess)) * 100) . '%';
    }
    

    public function getReport($service = null, $perPage = 10)
    {
        $services = Game::all();
        $serviceIDs = $services->pluck('service_id');

        return $this->cache->remember('report:' . ($service ?? 'all'), 3600, function () use ($service, $serviceIDs, $perPage) {
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

            // Apply service filter if provided
            if ($service === null) {
                $query->whereIn('service_id', $serviceIDs);
            } else {
                $query->where('service_id', $service);
            }

            // Paginate the results
            return $query->get();
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

            return number_format($activeSubscribers > 0 ? ($subscribersLost / $activeSubscribers) * 100 : 0) . '%';
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
