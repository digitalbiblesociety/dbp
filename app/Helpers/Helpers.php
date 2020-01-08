<?php

/**
 * Check query parameters for a given parameter name, and check the headers for the same parameter name;
 * also allow for two or more parameter names to match to the same $paramName using pipes to separate them.
 * Also check specially for the "key" param to come from the Authorization header.
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
        return str_replace('Bearer ', '', request()->header('Authorization'));
        ;
    }

    foreach (explode('|', $paramName) as $current_param) {
        // Header params
        if ($url_header = request()->header($current_param)) {
            return $url_header;
            break;
        }

        // GET/JSON/POST body params
        if ($queryParam = request()->input($current_param)) {
            return $queryParam;
            break;
        }

        if ($session_param = session()->get($current_param)) {
            return $session_param;
            break;
        }
    }

    if ($required) {
        Log::channel('errorlog')->error(["Missing Param '$paramName", 422]);
        abort(422, "You need to provide the missing parameter '$paramName'. Please append it to the url or the request Header.");
    }
}

function checkBoolean(string $paramName, $required = false, $inPathValue = null)
{
    $param = checkParam($paramName, $required, $inPathValue);
    $param = $param && $param != 'false';
    return $param;
}

function apiLogs($request, $status_code, $s3_string = false, $ip_address = null)
{
    $log_string = time() . '∞' . config('app.server_name') . '∞' . $status_code . '∞' . $request->path() . '∞';
    $log_string .= '"' . $request->header('User-Agent') . '"' . '∞';
    foreach ($_GET as $header => $value) {
        $log_string .= ($value !== '') ? $header . '=' . $value . '|' : $header . '|';
    }
    $log_string = rtrim($log_string, '|');
    $log_string .= '∞' . $ip_address . '∞';
    if ($s3_string) {
        $log_string .= $s3_string;
    }

    if (config('app.env') !== 'local') {
        App\Jobs\SendApiLogs::dispatch($log_string);
    }
}

if (!function_exists('csvToArray')) {
    function csvToArray($csvfile)
    {
        $csv      = [];
        $rowcount = 0;
        if (($handle = fopen($csvfile, 'r')) !== false) {
            $max_line_length = defined('MAX_LINE_LENGTH') ? MAX_LINE_LENGTH : 10000;
            $header          = fgetcsv($handle, $max_line_length);
            $header_colcount = count($header);
            while (($row = fgetcsv($handle, $max_line_length)) !== false) {
                $row_colcount = count($row);
                if ($row_colcount == $header_colcount) {
                    $entry = array_combine($header, $row);
                    $csv[] = $entry;
                } else {
                    error_log('csvreader: Invalid number of columns at line ' . ($rowcount + 2) . ' (row ' . ($rowcount + 1) . "). Expected=$header_colcount Got=$row_colcount");
                    return null;
                }
                $rowcount++;
            }
            //echo "Totally $rowcount rows found\n";
            fclose($handle);
        } else {
            error_log("csvreader: Could not read CSV \"$csvfile\"");
            return null;
        }
        return $csv;
    }
}

if (!function_exists('unique_random')) {
    /**
     *
     * Generate a unique random string of characters
     *
     * @param      $table - name of the table
     * @param      $col   - name of the column that needs to be tested
     * @param int  $chars - length of the random string
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
            $random = Illuminate\Support\Str::random($chars);

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

if (!function_exists('getFilesetFromDamId')) {
    function getFilesetFromDamId($dam_id, $filesets)
    {
        $fileset = $filesets->where('id', $dam_id)->first();

        if (!$fileset) {
            $fileset = $filesets->where('id', substr($dam_id, 0, -4))->first();
        }
        if (!$fileset) {
            $fileset = $filesets->where('id', substr($dam_id, 0, -2))->first();
        }
        if (!$fileset) {
            // echo "\n Error!! Could not find FILESET_ID: " . substr($dam_id, 0, 6);
            return false;
        }

        return $fileset;
    }
}

if (!function_exists('validateV2Annotation')) {
    function validateV2Annotation($annotation, $filesets, $books, $v4_users, $v4_annotations)
    {
        if (isset($v4_annotations[$annotation->id])) {
            // echo "\n Error!! Note already inserted: " . $note->id;
            return false;
        }

        if (!isset($v4_users[$annotation->user_id])) {
            // echo "\n Error!! Could not find USER_ID: " . $note->user_id;
            return false;
        }

        if (!isset($books[$annotation->book_id])) {
            // echo "\n Error!! Could not find BOOK_ID: " . $note->book_id;
            return false;
        }

        if (!isset($filesets[$annotation->dam_id])) {
            // echo "\n Error!! Could not find FILESET_ID: " . $note->dam_id;
            return false;
        }

        $fileset = $filesets[$annotation->dam_id];

        if ($fileset->bible->first()) {
            if (!isset($fileset->bible->first()->id)) {
                // echo "\n Error!! Could not find BIBLE_ID";
                return false;
            }
        } else {
            // echo "\n Error!! Could not find BIBLE_ID";
            return false;
        }

        return true;
    }
}
