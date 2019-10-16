<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Amp\Delayed;
use Amp\Websocket\Message;
use Amp\Websocket;
use App\Block;

class BlockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:server_new_block';

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


        \Amp\Loop::run(function () {

            $connection = yield Websocket\connect('wss://ws.blockchain.info/inv');
            yield $connection->send('{"op" : "blocks_sub"}');
            $i = 0;
            while ($message = yield $connection->receive()) {

                $payload = yield $message->buffer();

                $data = json_decode($payload)->x;

                Block::create([
                    'size' => $data->size,
                    'time' => $data->time,
                    'blockIndex' =>$data->blockIndex,
                    'prevBlockIndex' =>$data->prevBlockIndex,
                    'hash' => $data->hash,
                    'mrklRoot' => $data->mrklRoot,
                    "totalBTCSent" => $data->totalBTCSent,
                    "estimatedBTCSent" => $data->estimatedBTCSent,
                ]);


                dump(json_decode($payload));

                if ($payload === "Goodbye!") {
                    $connection->close();
                    break;
                }
                yield new Delayed(1000);
                if ($i < 3) {
                    yield $connection->send("Ping: " . ++$i);
                } else {
                    yield $connection->send("Goodbye!");
                }
            }
        });
    }
}
