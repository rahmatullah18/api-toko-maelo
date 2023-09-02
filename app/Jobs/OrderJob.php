<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
    public function handle()
    {

        $user = User::find((int)$this->request['id']);
        $user->update([
            'user_address' => $this->request['user_address'],
            'user_no_wa' => $this->request['user_no_wa']
        ]);

        $user = User::create([
            'user_name' => $this->request['user_name'],
            'user_address' => $this->request['user_address'],
            'user_no_wa' => $this->request['user_no_wa']
        ]);

        $user->order()->create([
            'order_quantity' => $this->request['order_quantity'],
            'order_total_price' => $this->request['order_total_price'],
            'order_status' => "pending",
            'product_id' => 1
        ]);

        $paragraph = $this->request['paragraph'];
        $wa = $this->request['wa'];

        $curl = curl_init();
        $token = "79Sr8smAgKAxHEPhmhDNop1Xd2HMo9Q9HerCyuor2PeTOPnKVSqwT48y3bTWeyYt";
        $data = [
            'phone' => "$wa",
            'message' => "$paragraph",
        ];
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL,  "https://kudus.wablas.com/api/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        echo "<pre>";
        print_r($result);
    }
}
