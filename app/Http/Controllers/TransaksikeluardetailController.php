<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\transaksikeluardetail;

class TransaksikeluardetailController extends Controller
{
    public function index()
    {
        $transaksikeluardetails = transaksikeluardetail::with('transaksikeluar', 'barang')->get();
        return response()->json($transaksikeluardetails);
    }
    public function show($id)
    {
        $transaksikeluardetail = transaksikeluardetail::with('barang')->find($id);

        if (!$transaksikeluardetail) {
            return response()->json(['message' => 'Transaksi not found'], 404);
        }
        return response()->json($transaksikeluardetail);
    }
    public function store(Request $request)
    {
        $entries = $request->input('entries');

        $validatedData = $request->validate([
            'entries' => 'required|array',
            'entries.*.barang_id' => 'required|exists:barangs,id',
            'entries.*.awalpinjam' => 'numeric|min:1',
            'entries.*.jumlah' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            foreach ($entries as $entry) {
                $barang = Barang::findOrFail($entry['barang_id']);

                if ($barang->stok < $entry['jumlah']) {
                    $kode = $barang->kode;
                    $name = $barang->name;
                    $remainingStock = $barang->stok;
                    throw new \Exception("Stok tidak mencukupi untuk barang dengan kode: {$kode}, nama: {$name}. Sisa stok saat ini: {$remainingStock}");
                }

                $barang->stok -= $entry['jumlah'];
                $barang->save();

                TransaksiKeluarDetail::create([
                    'transaksi_keluar_id' => $request->input('transaksi_keluar_id'),
                    'barang_id' => $entry['barang_id'],
                    'awalpinjam' => $entry['awalpinjam'],
                    'jumlah' => $entry['jumlah'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Transaction successfully processed.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}