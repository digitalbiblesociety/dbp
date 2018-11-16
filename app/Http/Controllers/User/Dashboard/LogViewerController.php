<?php
namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use Storage;

class LogViewerController extends APIController
{

    public function index($log = 'laravel')
    {
        // Fetch files from log folder
        $files    = Storage::disk('logs')->files();

        // Ensure only logs are returned
        foreach ($files as $key => $file) {
            if (!ends_with($file, '.log')) {
                unset($files[$key]);
            }
        }

        // Get the current log and parse it
        $log_file = Storage::disk('logs')->get($log.'.log');
        $logs = $this->all($log_file);

        return view('dashboard.log', compact('logs', 'files'));
    }

    public function all($file)
    {
        $log = array();

        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/';
        $log_levels = [
            'emergency',
            'alert',
            'critical',
            'error',
            'warning',
            'notice',
            'info',
            'debug',
            'processed'
        ];
        $levels_classes = [
            'debug'     => 'has-text-grey',
            'info'      => 'has-text-grey',
            'notice'    => 'has-text-grey',
            'warning'   => 'has-text-warning',
            'error'     => 'has-text-danger',
            'critical'  => 'has-text-danger',
            'alert'     => 'has-text-danger',
            'emergency' => 'has-text-danger',
            'processed' => 'has-text-grey'
        ];

        preg_match_all($pattern, $file, $headings);

        if (!\is_array($headings)) {
            return $log;
        }

        $log_data = preg_split($pattern, $file);

        if ($log_data[0] < 1) {
            array_shift($log_data);
        }

        foreach ($headings as $h) {
            for ($i=0, $j = \count($h); $i < $j; $i++) {
                foreach ($log_levels as $level) {
                    if (stripos($h[$i], '.' . $level) || stripos($h[$i], $level . ':')) {
                        $expression = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\](?:.*?(\w+)\.|.*?)' . $level . ': (.*?)( in .*?:[0-9]+)?$/i';
                        preg_match($expression, $h[$i], $current);
                        if (!isset($current[3])) {
                            continue;
                        }
                        $log[] = array(
                            'context' => $current[2],
                            'level' => $level,
                            'level_class' => $levels_classes[$level],
                            'date' => $current[1],
                            'text' => $current[3],
                            'in_file' => $current[4] ?? null,
                            'stack' => preg_replace("/^\n*/", '', $log_data[$i])
                        );
                    }
                }
            }
        }

        return array_reverse($log);
    }
}
