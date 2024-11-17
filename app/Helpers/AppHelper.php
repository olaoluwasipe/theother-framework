<?php

use App\Facades\Auth;
use Illuminate\Database\Capsule\Manager as DB;
use App\Facades\CustomDateTime;
use App\Facades\Log;
use Core\Logger;

if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        // if (is_null($key)) {
        //     return app('config');
        // }

        // if (is_array($key)) {
        //     return app('config')->set($key);
        // }

        $parts = explode('.', $key);
        $filename = array_shift($parts);
        $configPath = __DIR__ . '/../../config/' . $filename . '.php';

        if (!file_exists($configPath)) {
            return $default;
        }

        $config = require $configPath;

        foreach ($parts as $part) {
            if (!isset($config[$part])) {
                return $default;
            }
            $config = $config[$part];
        }

        return $config;
    }
}


if (!function_exists('redirect')) {
    function redirect($url = null)
    {
        if (is_null($url)) {
            $url = '/';
        }

        // Ensure the URL is safe by filtering and sanitizing
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // Validate URL format
        if (!filter_var($url, FILTER_VALIDATE_URL) && !str_starts_with($url, '/')) {
            $url = '/' . ltrim($url, '/');
        }

        // Prevent header injection
        $url = str_replace(["\r", "\n", '%0d', '%0a'], '', $url);

        // Perform the redirect
        if (!headers_sent()) {
            header('Location: ' . $url, true, 302);
            exit();
        }

        // Fallback for when headers are already sent
        echo '<script>window.location.href = "' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"></noscript>';
        exit();
    }
}

if (!function_exists('view')) {
    function view($view, $data = [])
    {
        $viewPath = __DIR__ . '/../../resources/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            extract($data);
            require $viewPath;
        } else {
            throw new Exception("View file not found: $view");
        }
        exit();
    }
}

if (!function_exists('asset')) {
    function asset($path)
    {
        $basePath = config('app.url');
        return $basePath . '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url($path = '')
    {
        $basePath = config('app.url');
        return $basePath . '/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        $csrfToken = $_SESSION['csrf_token'] ?? null;
        if (!$csrfToken) {
            $csrfToken = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrfToken;
        }
        return $csrfToken;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field()
    {
        $csrfToken = csrf_token();
        return '<input type="hidden" name="csrf_token" value="' . $csrfToken . '">';
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? null;
            $storedToken = $_SESSION['csrf_token'] ?? null;
            
            if (!$token || !$storedToken || !hash_equals($storedToken, $token)) {
                throw new Exception('CSRF token validation failed');
            }
        }
    }
}


if (!function_exists('json_response')) {
    function json_response($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}

if (!function_exists('json_error')) {
    function json_error($message, $status = 400)
    {
        http_response_code($status);
        json_response(['status'=>'error', 'message'=> $message]);
    }
}

if (!function_exists('json_success')) {
    function json_success($data, $message= "Request Successful")
    {
        json_response(['status' => 'success', 'data' => $data, 'message' => $message]);
    }
}

if (!function_exists('json_not_found')) {
    function json_not_found($message = 'Not Found')
    {
        json_error($message, 404);
    }
}

if (!function_exists('json_unauthorized')) {
    function json_unauthorized($message = 'Unauthorized')
    {
        json_error($message, 401);
    }
}

if (!function_exists('json_forbidden')) {
    function json_forbidden($message = 'Forbidden')
    {
        json_error($message, 403);
    }
}

if (!function_exists('json_bad_request')) {
    function json_bad_request($message = 'Bad Request')
    {
        json_error($message, 400);
    }
}

if (!function_exists('json_server_error')) {
    function json_server_error($message = 'Internal Server Error')
    {
        json_error($message, 500);
    }
}

if (!function_exists('json_unprocessable_entity')) {
    function json_unprocessable_entity($message = 'Unprocessable Entity')
    {
        json_error($message, 422);
    }
}


if (!function_exists('json_too_many_requests')) {
    function json_too_many_requests($message = 'Too Many Requests')
    {
        json_error($message, 429);
    }
}

if (!function_exists('json_service_unavailable')) {
    function json_service_unavailable($message = 'Service Unavailable')
    {
        json_error($message, 503);
    }
}

if (!function_exists('json_gateway_timeout')) {
    function json_gateway_timeout($message = 'Gateway Timeout')
    {
        json_error($message, 504);
    }
}

if (!function_exists('json_http_version_not_supported')) {
    function json_http_version_not_supported($message = 'HTTP Version Not Supported')
    {
        json_error($message, 505);
    }
}

if (!function_exists('auth')) {
    function auth()
    {
        $user = Auth::user();
        return $user;
    }
}

if (!function_exists('auth_user_id')) {
    function auth_user_id()
    {
        return auth()->id;
    }
}

if (!function_exists('auth_check')) {
    function auth_check()
    {
        return auth()->check();
    }
}

if (!function_exists('auth_user_name')) {
    function auth_user_name()
    {
        return auth() ? auth()->name : null;
    }
}

if (!function_exists('auth_username')) {
    function auth_user_email()
    {
        return auth() ? auth()->username : null;
    }
}

if (!function_exists('format_money')) {
    function format_money($amount, $symbol = '₦', $decimals = 2, $decimalSeparator = '.', $thousandsSeparator = ',', $symbolPosition = 'before')
    {
        // Convert to float and round to specified decimals
        $amount = round((float)$amount, $decimals);
        
        // Format the number with specified separators
        $formattedNumber = number_format($amount, $decimals, $decimalSeparator, $thousandsSeparator);
        
        // Return formatted string based on symbol position
        if ($symbolPosition === 'before') {
            return $symbol . $formattedNumber;
        }
        
        return $formattedNumber . $symbol;
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd/m/Y')
    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('format_time')) {
    function format_time($time, $format = 'h:i A')
    {
        return date($format, strtotime($time));
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime($datetime, $format = 'd/m/Y h:i A')
    {
        return date($format, strtotime($datetime));
    }
}

if (!function_exists('format_phone_number')) {
    function format_phone_number($phoneNumber)
    {
        // Remove any non-digit characters from the phone number
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Check if the phone number is in the correct format
        if (strlen($phoneNumber) === 11) {
            // Format the phone number as 08012345678
            return '0' . substr($phoneNumber, 1);
        } elseif (strlen($phoneNumber) === 13) {
            // Format the phone number as +2348012345678
            return '+' . $phoneNumber;
        } else {
            // Invalid phone number format
            return null;
        }
    }
}

if (!function_exists('get_percentage_difference')) {
    function get_percentage_difference($table, $column, $whereClauses = [], $interval = '1 day', $connection = 'default', $timeColumn = 'created_at', $formatted = false, $count = false)
    {
        try {
            // Validate where clauses
            $applyWhereClauses = function ($query) use ($whereClauses) {
                foreach ($whereClauses as $clause) {
                    if (!isset($clause['column'], $clause['value'])) {
                        throw new \InvalidArgumentException("Invalid where clause: 'column' and 'value' keys are required.");
                    }

                    $query->where($clause['column'], $clause['operator'] ?? '=', $clause['value']);
                }
                return $query;
            };

            // Get current value
            if ($count) {
                $currentValue = $applyWhereClauses(DB::connection($connection)->table($table))->count();
            } else {
                $currentValue = $applyWhereClauses(DB::connection($connection)->table($table))->sum($column);
            }

            // Get previous value based on interval
            if ($count) {
                $previousValue = $applyWhereClauses(DB::connection($connection)
                    ->table($table)
                    ->where($timeColumn, '<=', DB::raw("DATE_SUB(NOW(), INTERVAL {$interval})")))
                    ->count();
            } else {
                $previousValue = $applyWhereClauses(DB::connection($connection)
                    ->table($table)
                    ->where($timeColumn, '<=', DB::raw("DATE_SUB(NOW(), INTERVAL {$interval})")))
                    ->sum($column);
            }

            // If previous value is 0, return 100% increase
            if ($previousValue == 0) {
                return $currentValue > 0 ? 100 : 0;
            }

            // Calculate percentage difference
            $difference = $currentValue - $previousValue;
            $percentageDifference = ($difference / $previousValue) * 100;

            if ($formatted) {
                return format_percentage($difference, $percentageDifference);
            }

            // Round to 2 decimal places
            return round($percentageDifference, 2);
        } catch (\Exception $e) {
            $logger  = new Logger();
            // Log error for debugging
            $logger->error('Error in get_percentage_difference: ' . $e->getMessage());
            return 0;
        }
    }

}


if (!function_exists('format_percentage')) {
    function format_percentage($difference, $percentageDifference) {
        if($difference > 0){
            return '
            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                <span><i class="fa fa-fw fa-arrow-up"></i></span><span>'.round($percentageDifference, 2).'%</span>
            </div>';
        } else {
            return '
            <div class="metric-label d-inline-block float-right text-danger font-weight-bold">
                <span><i class="fa fa-fw fa-arrow-down"></i></span><span>'.round($percentageDifference, 2).'%</span>
            </div>';
        }
        if($difference == 0){
            return '
            <div class="metric-label d-inline-block float-right text-primary font-weight-bold">
                <span>N/A</span>
            </div>';
        }
    }

}

if (!function_exists('now')) {
    function now() {
        return new CustomDateTime();
    }
}

if (!function_exists('get_interval_data')) {
    function get_interval_data($table, $column, $whereClauses = [], $interval = 'day', $amount = 7, $connection = 'default', $timeColumn = 'created_at', $count = false, $formatted = false)
    {
        try {
            $data = [];

            // Helper function to apply where clauses
            $applyWhereClauses = function ($query) use ($whereClauses) {
                foreach ($whereClauses as $clause) {
                    $query->where($clause['column'], $clause['operator'] ?? '=', $clause['value']);
                }
                return $query;
            };

            for ($i = $amount - 1; $i >= 0; $i--) {
                // Calculate the interval's start and end time
                $startTime = DB::raw("DATE_SUB(NOW(), INTERVAL {$i} {$interval})");
                $endTime = DB::raw("DATE_SUB(NOW(), INTERVAL " . ($i + 1) . " {$interval})");

                // Query for each interval with where clauses
                $query = DB::connection($connection)
                    ->table($table)
                    ->where($timeColumn, '<=', $startTime)
                    ->where($timeColumn, '>', $endTime);

                // Apply additional where clauses
                $query = $applyWhereClauses($query);

                $intervalValue = $count ? $query->count() : $query->sum($column);

                // Determine the key label based on the interval type
                if ($formatted) {
                    $label = match ($interval) {
                        'day' => (new DateTime())->modify("-{$i} days")->format('l'),     // e.g., 'Monday'
                        'month' => (new DateTime())->modify("-{$i} months")->format('F'), // e.g., 'January'
                        'week' => 'Week ' . (new DateTime())->modify("-{$i} weeks")->format('W'), // e.g., 'Week 42'
                        default => (new DateTime())->modify("-{$i} days")->format('Y-m-d'), // default to full date for custom intervals
                    };

                    // Add to data array with the label as the key
                    $data[$label] = $intervalValue;
                } else {
                    $data[] = $intervalValue;
                }
            }

            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }
}






if (!function_exists('get_percentage_difference_by_date')) {
    function get_percentage_difference_by_date($table, $column, $startDate, $endDate, $interval = 'week', $connection = 'default', $timeColumn = 'created_at')
    {
        try {
            // Get current value
            $currentValue = DB::connection($connection)
                ->table($table)
                ->whereBetween($timeColumn, [$startDate, $endDate])
                ->sum($column);
                // Get previous value based on interval
                $previousValue = DB::connection($connection)
                ->table($table)
                ->whereBetween($timeColumn, [$startDate, $endDate])
                ->where($timeColumn, '<=', DB::raw("DATE_SUB(NOW(), INTERVAL 1 {$interval})"))
                ->sum($column);
                // If previous value is 0, return 100% increase
                if ($previousValue == 0) {
                    return $currentValue > 0 ? 100 : 0;
                }
                // Calculate percentage difference
                $difference = $currentValue - $previousValue;
                $percentageDifference = ($difference / $previousValue) * 100;
                // Round to 2 decimal places
                return round($percentageDifference, 2);
            }
        catch (\Exception $e) {
                return 0;
        }
    }
}


if (!function_exists ('get_percentage_difference_by_week')) {
    function get_percentage_difference_by_week($table, $column, $connection = 'default', $timeColumn = 'created_at') {
        $currentWeek = date('W');
        $previousWeek = date('W', strtotime('-1 week'));

        $currentYear = date('Y');
        $previousYear = date('Y', strtotime('-1 year'));
        // Get current value
        $percentageDifference = get_percentage_difference_by_date($table, $column, $currentWeek, $previousWeek, 'week', $connection, $timeColumn);

        return $percentageDifference;
    }
}




