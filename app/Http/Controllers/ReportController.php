<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $reports = DB::table('orders')
            ->select('users.user_name', 'users.user_address', 'users.user_no_wa', 'orders.order_quantity', 'orders.order_total_price', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('order_status', '=', 'selesai')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->get();
        $total = DB::table('orders')
            ->whereBetween('created_at', [$start, $end])
            ->where('order_status', '=', 'selesai')
            ->sum('order_total_price');
        return response()->json([
            'total' => $total,
            'reports' => $reports
        ]);
    }
}
