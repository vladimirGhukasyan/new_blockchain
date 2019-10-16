<?php

namespace App\Console\Commands;

use App\Transaction;
use Illuminate\Console\Command;
use Amp\Delayed;
use Amp\Websocket;

class TransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:server_new_transaction';

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

            yield $connection->send('{"op" : "unconfirmed_sub"}');

            $i = 0;

            while ($message = yield $connection->receive()) {
                $payload = yield $message->buffer();

                $resp = json_decode($payload)->x;

                Transaction::create([
                    'size' => $resp->size,
                    'time' => $resp->time,
                    'tx_index' => $resp->tx_index,
                    'hash' => $resp->hash,
                    'inputs' => count($resp->inputs),
                    'outputs' => count($resp->out)
                ]);

                dump(json_decode($payload)->x->hash);

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
