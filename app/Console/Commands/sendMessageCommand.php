<?php

namespace App\Console\Commands;

use App\Events\MessageEvent;
use Illuminate\Console\Command;
use function Laravel\Prompts\text;

class sendMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = 1;
        $text = text(
            label : 'c quoi le message ?',
            required: true
        );

        MessageEvent::dispatch($id, $text);
    }
}
