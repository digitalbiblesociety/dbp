<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class loaderPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loader:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        $this->loaderIoKey = config('services.loaderIo.key');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = file_get_contents(base_path('/routes/api.php'));

        preg_match_all("/Route::name\('(.*?)'\)->\w+\(\[.*?\],\s?'(.*?)',\s+'.*?'\);/", $routes, $matches);
        $routeList = array_combine($matches[1], $matches[2]);

        $existingTests = json_decode($this->fetchExistingTests());

        foreach ($routeList as $route_name => $route_path) {
            if (!collect($existingTests)->pluck('name')->contains($route_name)) {
                $this->makeRequests($route_name, $route_path);
            }
        }

        return null;
    }

    private function fetchExistingTests()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.loader.io/v2/tests');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = [];
        $headers[] = 'Loaderio-Auth: '.$this->loaderIoKey;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    private function makeRequests($route_name, $route_path)
    {
        $ch = curl_init();

        $request_fields = [
            'name'      => $route_name,
            'test_type' => 'non-cycling',
            'total'     => 500,
            'duration'  => 60,
            'urls'      => [
                [
                    'url' => 'https://api.dbp4.org/'.$route_path,
                    'request_type' => 'get',
                    'headers'      => [
                        'Content-Type'  => 'application/json',
                        'v'             => '4',
                        'Authorization' => 'tighten_37518dau8gb891ub',
                    ]
                ]
            ]
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://api.loader.io/v2/tests');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_fields));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = [
            'Loaderio-Auth: '.$this->loaderIoKey,
            'Content-Type: application/json',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }
}
