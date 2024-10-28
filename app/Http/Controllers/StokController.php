<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Stok;
use App\Models\Barang;
use App\Models\transaksikeluardetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class StokController extends Controller
{
    public function index()
    {
        $stoks = Stok::with('barang')->get();
        $stoks->map(function ($stok) {
            $stok->current_stock = $stok->barang->stok;
            return $stok;
        });
        return response()->json($stoks);
    }

    public function show($id)
    {
        $stok = Stok::find($id);
        if (!$stok) {
            return response()->json([
                'message' => 'stok not found'
            ], 404);
        }
        return response()->json([
            'stok' => $stok
        ]);
    }
    public function getStokKeluar()
    {
        try {
            $stokKeluarData = DB::table('transaksikeluardetails')
                ->select('barang_id', DB::raw('SUM(jumlah) as total_keluar'))
                ->groupBy('barang_id')
                ->get();

            return response()->json($stokKeluarData);
        } catch (Exception $e) {
            Log::error('Error fetching stok keluar data: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Failed to fetch stok keluar data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getStokKeluarDetails($barang_id)
    {
        try {
            $details = DB::table('transaksi_keluars')
                ->join('transaksikeluardetails', 'transaksikeluardetails.transaksi_keluar_id', '=', 'transaksi_keluars.id')
                ->join('barangs', 'barangs.id', '=', 'transaksikeluardetails.barang_id')
                ->where('transaksikeluardetails.barang_id', $barang_id)
                ->select('transaksi_keluars.*', 'transaksikeluardetails.jumlah', 'transaksikeluardetails.awalpinjam', 'barangs.name', 'barangs.kode')
                ->get();

            return response()->json($details);
        } catch (\Exception $e) {
            Log::error('Error fetching details: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Failed to fetch details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}