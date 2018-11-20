<?php

/**
 * Check query parameters for a given parameter name, and check the headers for the same parameter name;
 * also allow for two or more parameter names to match to the same $paramName using pipes to separate them.
 * Also check specially for the "key" param to come from the Authorizaiton header.
 * Finally, allows for values set in paths to override all other values.
 *
 * @param string $paramName
 * @param bool $required
 * @param null|string $inPathValue
 *
 * @return array|bool|null|string
 */
function checkParam(string $paramName, $required = false, $inPathValue = null)
{
    // Path params
    if ($inPathValue) {
        return $inPathValue;
    }

    // Authorization params (with key => Authorization translation)
    if ($paramName === 'key' && request()->header('Authorization')) {
        return request()->header('Authorization');
    }

    foreach (explode('|', $paramName) as $current_param) {
        // Header params
        if ($url_header = request()->header($current_param)) {
            return $url_header;
        }

        // GET/JSON/POST body params
        if ($body_param = request()->input($paramName)) {
            return $body_param;
        }
    }

    if ($required) {
        Log::channel('errorlog')->error(["Missing Param '$paramName", 422]);
        abort(422, "You need to provide the missing parameter '$paramName'. Please append it to the url or the request Header.");
    }
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
