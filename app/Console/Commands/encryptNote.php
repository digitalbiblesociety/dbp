<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class encryptNote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encrypt {note} {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'encrypt a note';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $note = base64_decode($this->argument('note'));
        $id = $this->argument('id');
        echo json_encode(['value' => encrypt($note), 'id' => $id]);
    }
}
