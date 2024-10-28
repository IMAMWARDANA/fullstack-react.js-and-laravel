<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Barang;
use App\Models\StokBK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StokBKController extends Controller
{
    public function index()
    {
        $stokbks = StokBK::with('barang','keperluan')->get();
        return response()->json($stokbks);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'exists:barangs,id',
            'stok' => 'required|integer',
            'keperluan_id' => 'exists:keperluans,id',
            'keterangan' => 'nullable|string',
            'tanggalkeluar' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $barang = Barang::find($request->barang_id);
        $stokbk = $request->stok;

        if ($barang->stok < $stokbk) {
            return response()->json(['message' => 'Stok tidak cukup'], 400);
        }

        // Kurangi stok barang
        $barang->stok -= $stokbk;
        $barang->save();

        // Update stok di table stok
        $stok = Stok::where('barang_id', $request->barang_id)->first();
        if ($stok) {
            $stok->stok -= $stokbk;
            $stok->save();
        } else {
            // If no entry exists in the Stok table, create a new one
            Stok::create([
                'barang_id' => $request->barang_id,
                'stok' => -$stokbk, // Negative value for stock out
            ]);
        }

        // Simpan data stok keluar
        StokBK::create([
            'barang_id' => $request->barang_id,
            'stok' => $request->stok,
            'keperluan' => $request->keperluan,
            'keterangan' => $request->keterangan,
            'tanggalkeluar' => $request->tanggalkeluar,
        ]);

        return response()->json(['message' => 'Stok keluar berhasil ditambahkan'], 200);
    }

    public function destroy($id)
    {
        $stokbk = StokBK::find($id);
        if (!$stokbk) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $barang = Barang::find($stokbk->barang_id);

        // Tambah kembali stok barang
        if ($barang) {
            $barang->stok += $stokbk->stok;
            $barang->save();
        }

        // Update stok di table stok
        $stok = Stok::where('barang_id', $stokbk->barang_id)->first();
        if ($stok) {
            $stok->stok += $stokbk->stok;
            $stok->save();
        } else {
            // If no entry exists in the Stok table, create a new one
            Stok::create([
                'barang_id' => $stokbk->barang_id,
                'stok' => $stokbk->stok, // Positive value for stock in
            ]);
        }

        // Hapus data stok keluar
        $stokbk->delete();

        return response()->json(['message' => 'Stok keluar berhasil dihapus'], 200);
    }
}
