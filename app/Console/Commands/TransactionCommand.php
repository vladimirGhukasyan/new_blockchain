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

                $input_value = 0;
                $input_wallets = [];

                foreach ($resp->inputs as $item){

                    $input_value +=  $item->prev_out->value;
                    array_push($input_wallets,$item->prev_out->addr);
                }

                $output_value = 0;
                $output_wallets = [];

                foreach ($resp->out as $item){

                    $output_value +=  $item->value;
                    array_push($output_wallets,$item->addr);
                }


                Transaction::create([
                    'size' => $resp->size,
                    'time' => $resp->time,
                    'tx_index' => $resp->tx_index,
                    'hash' => $resp->hash,
                    'inputs' => count($resp->inputs),
                    'outputs' => count($resp->out),
                    'input_value' => $input_value,
                    'output_value' => $output_value,
                    'wallets' => json_encode([
                        'input'=> $input_wallets,
                        'output'=> $output_wallets,
                    ])
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
