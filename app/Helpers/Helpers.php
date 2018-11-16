<?php

/**
 * @param string $param
 * @param null|string $v4Style
 * @param bool $required
 *
 * @return array|bool|null|string
 */
function checkParam(string $param, $required = false, $v4Style = null)
{
    $url_param = null;
    if (strpos($param, '|') !== false) {
        $url_params = explode('|', $param);
        foreach ($url_params as $current_param) {
            if ($url_param) {
                continue;
            }
            $url_param = $_GET[$current_param] ?? null;
        }
    } else {
        $url_param = $_GET[$param] ?? false;
    }

    $url_header = request()->header($param);
    if ($param === 'key' && !$url_header) {
        $url_header = request()->header('Authorization');
    }

    if ($v4Style) {
        return $v4Style;
    }
    if (!$url_param && !$url_header) {
        $body_param = request()->input($param);
        if (!$body_param) {
            if ($required) {
                \Log::channel('errorlog')->error(["Missing Param '$param", 422]);
                abort(422, "You need to provide the missing parameter '$param'. Please append it to the url or the request Header.");
            }
        } else {
            return $body_param;
        }
        return null;
    }
    if ($url_param) {
        return $url_param;
    }
    if ($url_header) {
        return $url_header;
    }
    return null;
}

function fetchBible($bible_id)
{
    $bibleEquivalent = \App\Models\Bible\BibleEquivalent::where('equivalent_id', $bible_id)->orWhere('equivalent_id', substr($bible_id, 0, 7))->first();
    if ($bibleEquivalent === null) {
        return \App\Models\Bible\Bible::find($bible_id);
    }
    if ($bibleEquivalent !== null) {
        return $bibleEquivalent->bible;
    }
    return [];
}

function apiLogs($request, $status_code, $s3_string = false, $ip_address = null)
{
    $log_string = time().'∞'.config('app.server_name').'∞'.$status_code.'∞'.$request->path().'∞';
    $log_string .= '"'.$request->header('User-Agent').'"'.'∞';
    foreach ($_GET as $header => $value) {
        $log_string .= ($value !== '') ? $header.'='.$value.'|' : $header.'|';
    }
    $log_string = rtrim($log_string, '|');
    $log_string .= '∞'.$ip_address.'∞';
    if ($s3_string) {
        $log_string .= $s3_string;
    }

    if (config('app.env') !== 'local') {
        App\Jobs\SendApiLogs::dispatch($log_string);
    }
}

if (! function_exists('unique_random')) {
    /**
     *
     * Generate a unique random string of characters
     * uses str_random() helper for generating the random string
     *
     * @param     $table - name of the table
     * @param     $col - name of the column that needs to be tested
     * @param int $chars - length of the random string
     *
     * @return string
     */
    function unique_random($table, $col, $chars = 16)
    {

        $unique = false;

        // Store tested results in array to not test them again
        $tested = [];

        do {
            // Generate random string of characters
            $random = str_random($chars);

            // Check if it's already testing
            // If so, don't query the database again
            if (in_array($random, $tested)) {
                continue;
            }

            // Check if it is unique in the database
            $count = DB::table($table)->where($col, '=', $random)->count();

            // Store the random character in the tested array
            // To keep track which ones are already tested
            $tested[] = $random;

            // String appears to be unique
            if ($count === 0) {
                // Set unique to true to break the loop
                $unique = true;
            }

            // If unique is still false at this point
            // it will just repeat all the steps until
            // it has generated a random string of characters
        } while (!$unique);


        return $random;
    }

}
