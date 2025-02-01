<?php

namespace App\Controllers;

use App\Models\Game;
use App\Models\Transaction;
use Core\Controller;
use Exception;

class ActivityController extends Controller
{
    public function index()
    {
        return view('activities');
    }

    public function activities() {
        // Create the initial query
        $transactions = Transaction::query()->orderByDesc('id');
    
        // Count before pagination to avoid mismatch
        $totalRecords = (clone $transactions)->count();

        // echo "<pre>";
        // print_r($_REQUEST);
        // echo "</pre>";
        // exit;
    
        // Handle pagination manually
        $page = isset($_REQUEST['start']) ? ($_REQUEST['start'] / ($_REQUEST['length'] ?? 10)) + 1 : 1;
        $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 10;

        // echo $page;
        // exit;
        
        // Apply filters using $_REQUEST
        if (isset($_REQUEST['service']) && $_REQUEST['service'] !== 'all') {
            $transactions->where('service_id', $_REQUEST['service']);
        }
    
        if (isset($_REQUEST['agency']) && $_REQUEST['agency'] !== 'all') {
            $transactions->where('trans_id', 'LIKE', $_REQUEST['agency'] . '%');
        }
    
        if (isset($_REQUEST['status'])) {
            switch ($_REQUEST['status']) {
                case 'success':
                    $transactions->where('charges_status', 'Success');
                    break;
                case 'failed':
                    $transactions->whereIn('charges_status', [
                        'Subscriber has insufficient balance',
                        'Security Measures'
                    ]);
                    break;
                case 'deactivated':
                    $transactions->where('charges_status', 'You deactivate the service successfully.');
                    break;
                default:
                    echo json_encode(["error" => "Invalid status."], 400);
                    return;
            }
        }
    
        // Filter by date range if provided
        if ((isset($_REQUEST['start_date']) && $_REQUEST['start_date'] !== '') && (isset($_REQUEST['end_date']) && $_REQUEST['start_date'] !== '')) {
            $transactions->whereBetween('t_date', [$_REQUEST['start_date'], $_REQUEST['end_date']]);
        }

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !== '') {
            $transactions->where('msisdn', 'LIKE', '%'.$_REQUEST['search']['value'] . '%');
        }
    
        // Filter by type if provided
        if (isset($_REQUEST['type']) && $_REQUEST['type'] !== 'all') {
            $type = $_REQUEST['type'] == 'subscribed' ? 'SecureD' : $_REQUEST['type'];
            $transactions->where('type', $type);
        }
    
        // Apply sorting if requested (columns[0][data] and order)
        if (isset($_REQUEST['order']) && isset($_REQUEST['order'][0]['column']) && isset($_REQUEST['columns'][$_REQUEST['order'][0]['column']]['data'])) {
            $column = $_REQUEST['columns'][$_REQUEST['order'][0]['column']]['data'];
            $dir = $_REQUEST['order'][0]['dir'];
            $transactions->orderBy($column, $dir);
        }

        $countTrans = $transactions->count();
        // $transactions = $transactions->orderByDesc('t_date');
        $skip = ($page - 1) * $length;
    
        // Paginate the results based on the current page and length
        if($length < 0) {
            $data = $transactions->get();
        } else {
            $data = $transactions->skip($skip < 0 ? 0 : $skip )->take($length)->get();
        }

        $getType = function($bearerId) {
            switch($bearerId) {
                case 'SecureD':
                    return 'subscription';
                case 'USSD':
                    return 'unsubscribed';
                case 'SMS':
                    return 'subscription';
                case 'system-renewal':
                    return 'renewal';
                default:
                return 'unknown';
            }
        };

        // Format data
        $formattedData = array_map(function($transaction) use($getType) {
            // Change key 'trans_id' to 'reference' and other key/value changes
            return [
                'id' => $transaction['id'],
                'phone' => $transaction['msisdn'],
                'reference' => $transaction['trans_id'], // Change key from 'trans_id' to 'reference'
                'service' => Game::where('service_id', $transaction['service_id'])?->first()?->name ?? 'Unknown',
                'status' => $transaction['charges_status'],
                'amount' => $transaction['amount'],
                'type' => $getType($transaction['bearer_id']), // Example of changing values
                'date' => date('Y-m-d H:i:s', strtotime($transaction['t_date'])), // Format date
            ];
        }, $data->toArray());
    
        // Return the response in the DataTables format
        echo json_encode([
            "draw" => isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 1,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $countTrans,
            "data" => $formattedData
        ]);
    }
    
    

    public function show($id)
    {
        // Code for show method
    }
}