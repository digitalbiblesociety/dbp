<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanAndImportKD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:kd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = glob('/Sites/dbp/storage/data/study/kd/*.htm');
        foreach ($files as $file) {
            $input_lines = file_get_contents($file);

            // Reorder whitespace
            //$input_lines = preg_replace("/\s+/s", ' ', $input_lines);
            //$input_lines = preg_replace("/<p/s", "\n<p", $input_lines);
            //$input_lines = preg_replace("/<p/s", "\n<p", $input_lines);

            // Remove Header | SO SLOW
            //$input_lines = preg_replace('/.+<body>/s', '', $input_lines);

            // Clean Links
            $input_lines = preg_replace('/onMouse\w+="javascript:\w+Pop\(.*?\)"/s', '', $input_lines);
            $input_lines = preg_replace('/href="javascript:BwRef\(\'(\w+) (\d+)\'\)"/s', 'data-chapter="$1_$2"', $input_lines);
            $input_lines = preg_replace('/href="javascript:BwRef\(\'(\w+) (\d+):(\d+)\'\)"/s', 'data-verse="$1_$2_$3"', $input_lines);
            $input_lines = preg_replace('/href="javascript:BwRef\(\'(\w+) (\d+):(\d+)-(\d+)\'\)"/s', 'data-verse="$1_$2_$3" data-verseEnd="$1_$2_$4"', $input_lines);
            // <p style='margin-left:18.0pt;;'>

            // Replace Fonts with language references
            $input_lines = str_replace('lang="el"','lang="he', $input_lines); // TEMP
            $input_lines = preg_replace("/\s+style='font-family:\s?\"TITUS Cyberbit Basic\";?'/", ', lang="he"', $input_lines);

            // Misc
            //$input_lines = preg_replace('/<i>(.*?)<\/i>/s', '_$1_', $input_lines);

            // Incidental Margin Removal
            $input_lines = preg_replace('/\s?margin-bottom:\d+.\d+pt[pt|cm];?/s', '', $input_lines);
            $input_lines = preg_replace('/\s?margin-left:\d+.\d+[pt|cm];?/s', '', $input_lines);
            $input_lines = preg_replace('/\s?margin-top:\d+.\d+pt[pt|cm];?/s', '', $input_lines);

            //$input_lines = str_replace(' class=MsoNormal', '', $input_lines);
            //$input_lines = str_replace('text-indent:10.8pt', '', $input_lines);
            //$input_lines = str_replace('text-autospace:none', '', $input_lines);
            //$input_lines = preg_replace("/\s+style='font-family:\s+Georgia'/", '', $input_lines);
            //$input_lines = str_replace(" style=';'", '', $input_lines);
            //$input_lines = str_replace('<p><span>&nbsp;</span></p>', '', $input_lines);
            //$input_lines = preg_replace('/text-autospace:\s+none/', '', $input_lines);

            $input_lines = preg_replace('/<p style=\'margin-left:18.0pt;;\'>(.*?)<\/p>/s', '> $1', $input_lines);

            file_put_contents($file, $input_lines);
        }

    }
}
