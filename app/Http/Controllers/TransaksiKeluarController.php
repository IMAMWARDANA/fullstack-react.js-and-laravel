<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Barang;
use App\Models\StokBK;
use Illuminate\Http\Request;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\transaksikeluardetail;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class TransaksiKeluarController extends Controller
{
    public function index()
    {
        $transaksikeluars = TransaksiKeluar::with('keperluan')->get();
        return response()->json($transaksikeluars);
    }

    public function show($id)
    {
        $transaksiKeluar = TransaksiKeluar::with('transaksikeluardetails.barang')->find($id);

        if (!$transaksiKeluar) {
            return response()->json(['message' => 'Transaksi Keluar not found'], 404);
        }

        return response()->json([
            'transaksikeluar' => $transaksiKeluar,
            'details' => $transaksiKeluar->transaksikeluardetails
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksikeluar.notransaksi' => 'required|string',
            'transaksikeluar.namainstansi' => 'required|string',
            'transaksikeluar.alasan' => 'required|string|max:1000',
            'transaksikeluar.tanggalinput' => 'required|date',
            'transaksikeluar.keperluan_id' => 'required|integer|exists:keperluans,id',
            'details.*.barang_id' => 'required|integer|exists:barangs,id',
            'details.*.awalpinjam' => 'integer|min:1',
            'details.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $transaksiKeluar = TransaksiKeluar::create($validated['transaksikeluar']);

            foreach ($validated['details'] as $detail) {
                $barang = Barang::findOrFail($detail['barang_id']);

                if ($barang->stok < $detail['jumlah']) {
                    $kode = $barang->kode;
                    $name = $barang->name;
                    $remainingStock = $barang->stok;
                    throw new \Exception("Stok tidak mencukupi untuk barang dengan kode: {$kode}, nama: {$name}. Sisa stok saat ini: {$remainingStock}");
                }

                $barang->stok -= $detail['jumlah'];
                $barang->save();

                TransaksiKeluarDetail::create([
                    'transaksi_keluar_id' => $transaksiKeluar->id,
                    'barang_id' => $detail['barang_id'],
                    'awalpinjam' => $detail['awalpinjam'],
                    'jumlah' => $detail['jumlah'],
                ]);

                StokBK::create([
                    'barang_id' => $detail['barang_id'],
                    'stok' => $detail['jumlah'],
                    'keperluan_id' => $validated['transaksikeluar']['keperluan_id'],
                    'keterangan' => $validated['transaksikeluar']['alasan'],
                    'tanggalkeluar' => $validated['transaksikeluar']['tanggalinput'],
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