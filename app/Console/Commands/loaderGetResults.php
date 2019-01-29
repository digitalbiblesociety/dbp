<?php

namespace App\Console\Commands;

use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;

class loaderGetResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loader:pull';

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
        $this->loaderIoKey = config('services.loaderIo.key');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.loader.io/v2/','headers' => ['Loaderio-Auth' => $this->loaderIoKey]]);
        $tests = $client->request('GET', 'tests');

        dd($tests);
        $tests = json_decode($this->fetchExistingTests());
        foreach ($tests as $test) {
            dd($test);
        }

    }

    private function fetchExistingTests()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.loader.io/v2/tests');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Loaderio-Auth: '.$this->loaderIoKey;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        return $result;
    }

}
