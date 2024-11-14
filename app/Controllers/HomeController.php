<?php
namespace App\Controllers;

use App\Facades\Auth;
use App\Facades\CustomDateTime;
use App\Models\CampaignAgency;
use App\Models\SubDetail;
use App\Models\Transaction;
use Core\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    public function index() {
        $revenue = [
            'total' => format_money(Transaction::where('amount','>', 1)->sum('amount')),
            'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1]], '7 day', 'mysql2', 't_date', true)
        ];
        $subs = [
            'total' => number_format(Transaction::where('amount','>', 1)->where('charges_status','Success')->where('bearer_id','SecureD')->count()),
            'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'SecureD']], '7 day', 'mysql2', 't_date', true, true),
        ];
        $unSubs = [
            // 'total' => number_format(SubDetail::where('sub_status', 0)->count()),
            'total' => number_format(Transaction::where('amount','>', 0)->where('charges_status', 'You deactivate the service successfully.')->count()),
            'percentage' => get_percentage_difference('transactions', '*', [['column' => 'charges_status', 'value'=>'You deactivate the service successfully.']], '7 day', 'mysql2', 't_date', true, true),
            // 'percentage' => get_percentage_difference('sub_details', '*', [['column' => 'sub_status', 'value'=>0]], '7 day', 'mysql2', 't_date', true, true),
        ];
        $subRev = [
            'total' => number_format(Transaction::where('amount','>', 1)->where('charges_status','Success')->where('bearer_id','<>','system-renewal')->sum('amount')),
            'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator' => '<>', 'value'=> 'system-renewal']], '7 day', 'mysql2', 't_date', true, true),
        ];
        $renRev = [
            'total' => number_format(Transaction::where('amount','>', 1)->where('charges_status','Success')->where('bearer_id','=','system-renewal')->sum('amount')),
            'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'],['column' => 'bearer_id', 'value' => 'system-renewal']], '7 day', 'mysql2', 't_date', true, true),
        ];

        $churnRate = [
            'total' => number_format(Transaction::where('amount','>', 1)->where('charges_status','Success')->where('bearer_id','=','system-renewal')->sum('amount')),
            'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'system-renewal']], '7 day', 'mysql2', 't_date', true, true),
        ];
        view('home', compact('revenue', 'subs', 'unSubs', 'subRev', 'renRev', 'churnRate'));
    }

    public function getData () {
        $data = [
            'stats' => [
                'revenue' => [
                    'total' => format_money(Transaction::where('amount','>', 1)->sum('amount')),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1]], '7 day', 'mysql2', 't_date', true)
                ],
                'subs' => [
                    'total' => number_format(Transaction::where('amount','>', 1)->where('charges_status','Success')->where('bearer_id','SecureD')->count()),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'SecureD']], '7 day', 'mysql2', 't_date', true, true),
                ],
                'unSubs' => [
                    'total' => number_format(Transaction::where('amount','>', 0)->where('charges_status', 'You deactivate the service successfully.')->count()),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column' => 'charges_status', 'value'=>'You deactivate the service successfully.']], '7 day', 'mysql2', 't_date', true, true),
                ],
                'subRev' => [
                    'total' => number_format(Transaction::where('amount','>', 1)->where('charges_status','Success')->where('bearer_id','<>','system-renewal')->sum('amount')),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator' => '<>', 'value'=> 'system-renewal']], '7 day', 'mysql2', 't_date', true, true),
                ],
                'renRev' => [
                    'total' => number_format(Transaction::where('amount','>', 1)->where('charges_status','Success')->where('bearer_id','=','system-renewal')->sum('amount')),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'],['column' =>   'bearer_id', 'value' => 'system-renewal']], '7 day', 'mysql2', 't_date', true, true),
                ],
                'churnRate' => [
                    'total' => number_format(Transaction::where('amount','>', 1)->where('charges_status','Success')->where('bearer_id','=','system-renewal')->sum('amount')),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'system-renewal']], '7 day', 'mysql2', 't_date', true, true),
                ],
            ],
            'graphs' => [
                'revenue' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1]], 'day', 7, 'mysql2', 't_date', false ),
                'subscriptions' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'SecureD']], 'day', 7, 'mysql2', 't_date', true, ),
                'unSubs' => get_interval_data('transactions', 'amount', [['column' => 'charges_status', 'value'=>'You deactivate the service successfully.']], 'day', 7, 'mysql2', 't_date', true, ),
                'subRev' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator' => '<>', 'value'=> 'system-renewal']], 'day', 7, 'mysql2', 't_date', true, ),
                'renRev' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'],['column' =>   'bearer_id', 'value' => 'system-renewal']], 'day', 7, 'mysql2', 't_date', true, ),
            ]
        ];
        return json_success($data);
    }

    public function getCampaignData ($code) {
        $campaignAgency = CampaignAgency::where('code', $code)->first();
        $query = Transaction::where('amount','>', 1)->where('trans_id', 'LIKE', "$campaignAgency->code%");
        $data = [
            'stats' =>[
                'revenue' => [
                    'total' => format_money($query->sum('amount')),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1], ['column'=> 'trans_id', 'operator'=> 'LIKE', 'value' => "$campaignAgency->code%"]], '7 day', 'mysql2', 't_date', true)
                ],
                'subs' => [
                    'total' => number_format($query->where('charges_status','Success')->where('bearer_id','SecureD')->count()),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'SecureD'], ['column'=> 'trans_id', 'operator'=> 'LIKE', 'value' => "$campaignAgency->code%"]], '7 day', 'mysql2', 't_date', true, true),
                ],
                'unSubs' => [
                    'total' => number_format($query->where('charges_status', 'You deactivate the service successfully.')->count()),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column' => 'charges_status', 'value'=>'You deactivate the service successfully.'], ['column'=> 'trans_id', 'operator'=> 'LIKE', 'value' => "$campaignAgency->code%"]], '7 day', 'mysql2', 't_date', true, true),
                ],
                'subRev' => [
                    'total' => number_format($query->where('charges_status','Success')->where('bearer_id','<>','system-renewal')->sum('amount')),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator' => '<>', 'value'=> 'system-renewal'], ['column'=> 'trans_id', 'operator'=> 'LIKE', 'value' => "$campaignAgency->code%"]], '7 day', 'mysql2', 't_date', true, true),
                ],
                'renRev' => [
                    'total' => number_format($query->where('charges_status','Success')->where('bearer_id','=','system-renewal')->sum('amount')),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'],['column' =>   'bearer_id', 'value' => 'system-renewal'], ['column'=> 'trans_id', 'operator'=> 'LIKE', 'value' => "$campaignAgency->code%"]], '7 day', 'mysql2', 't_date', true, true),
                ],
                'churnRate' => [
                    'total' => number_format($query->where('charges_status','Success')->where('bearer_id','=','system-renewal')->sum('amount')),
                    'percentage' => get_percentage_difference('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'system-renewal'], ['column'=> 'trans_id', 'operator'=> 'LIKE', 'value' => "$campaignAgency->code%"]], '7 day', 'mysql2', 't_date', true, true),
                ],
            ],
            'graphs' => [
                'revenue' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1]], 'day', 7, 'mysql2', 't_date', false ),
                'subscriptions' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'SecureD']], 'day', 7, 'mysql2', 't_date', true, ),
                'unSubs' => get_interval_data('transactions', 'amount', [['column' => 'charges_status', 'value'=>'You deactivate the service successfully.']], 'day', 7, 'mysql2', 't_date', true, ),
                'subRev' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator' => '<>', 'value'=> 'system-renewal']], 'day', 7, 'mysql2', 't_date', true, ),
                'renRev' => get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'],['column' =>   'bearer_id', 'value' => 'system-renewal']], 'day', 7, 'mysql2', 't_date', true, ),
            ]
        ];
        return json_success($data);
    }

    public function login () {
        if(Auth::check()) redirect('/');
        view('login');
    }

    public function register () {
        if(Auth::check()) redirect('/');
        view('register');
    }
}


