<?php

namespace App\Http\Controllers;

use App\Jobs\OrderJob;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function getAllOrder()
    {
        $orders = DB::table('orders')
            ->join("users", 'orders.user_id', '=', 'users.id')
            ->orderBy('id', 'desc')
            ->select("orders.id", "orders.user_id", "orders.order_quantity", "orders.order_total_price", "orders.order_status", "users.user_name", "users.user_address", "users.user_no_wa", "orders.created_at")
            ->get();

        return response()->json($orders);
    }

    public function createOrder(Request $request)
    {
        try {
            // queue proses order
            // $orderJob = new OrderJob($request->all());
            // dispatch($orderJob);

            $user = User::find((int)$request->id);
            $user->update([
                'user_address' => $request->user_address,
                'user_no_wa' => $request->user_no_wa
            ]);

            $order = $user->order()->create([
                'order_quantity' => $request->order_quantity,
                'order_total_price' => $request->order_total_price,
                'order_status' => "pending",
                'product_id' => 1
            ]);

            $paragraph = $request->paragraph;
            $wa = $request->wa;

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
            // echo "<pre>";
            // print_r($result);
            return response()->json([
                'message' => 'Data berhasil di input',
                'order' => $order
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }
    }
    // $token = "79Sr8smAgKAxHEPhmhDNop1Xd2HMo9Q9HerCyuor2PeTOPnKVSqwT48y3bTWeyYt";

    public function sendMessage(Request $request)
    {
        $paragraph = $request->paragraph;
        $wa = $request->wa;

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

    public function deleteOrder($id)
    {
        try {
            $order = Order::find($id);
            $order->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }
        return response()->json("order berhasil di hapus");
    }

    public function updateStatusOrder(Request $request)
    {
        $status = $request->status;
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if ($status === "diantar") {
            $curl = curl_init();
            $token = "79Sr8smAgKAxHEPhmhDNop1Xd2HMo9Q9HerCyuor2PeTOPnKVSqwT48y3bTWeyYt";
            $data = [
                'phone' => $user['user_no_wa'],
                'message' => "Pesanan anda sedang diantar ke tujuan",
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
        $id = $request->id;
        try {
            $order = Order::find($id);
            $order->update([
                'order_status' => $status
            ]);

            return response()->json("data berhasil di update");
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }

    public function pesananDiterima(Request $request)
    {
        try {
            $user_id = (int)$request->user_id;
            $order_id = (int)$request->order_id;

            $user = User::find($user_id);
            $wa = $user['user_no_wa'];

            $order = Order::find($order_id);
            $order->update([
                'order_status' => "selesai"
            ]);

            $curl = curl_init();
            $token = "79Sr8smAgKAxHEPhmhDNop1Xd2HMo9Q9HerCyuor2PeTOPnKVSqwT48y3bTWeyYt";
            $data = [
                'phone' => $wa,
                'message' => "Pesanan anda telah selesai. Terimakasih telah belanja di toko kami😸",
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
            // echo "<pre>";
            // print_r($result);
            return response()->json([
                'message' => "data update"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }
}
