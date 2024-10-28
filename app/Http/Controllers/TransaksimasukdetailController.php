<?php

namespace App\Http\Controllers;

use App\Models\transaksimasukdetail;
use Illuminate\Http\Request;

class TransaksimasukdetailController extends Controller
{
    public function index()
    {
        try {
            $transaksiMasukDetails = TransaksiMasukDetail::with('transaksiMasuk', 'barang')->get();
            return response()->json($transaksiMasukDetails);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch data.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $transaksiMasukDetail = TransaksiMasukDetail::with('transaksiMasuk', 'barang')->find($id);

            if (!$transaksiMasukDetail) {
                return response()->json(['message' => 'Transaksi not found'], 404);
            }

            return response()->json($transaksiMasukDetail);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch data.'], 500);
        }
    }
}