<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Stok;
use App\Models\Barang;
use App\Models\StokBR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StokBRController extends Controller
{
    public function index()
    {
        $stokbrs = StokBR::with('barang')->get();
        return response()->json($stokbrs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.stok' => 'required|integer',
            'details.*.keterangan' => 'nullable|string',
            'details.*.tanggalrusak' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->details as $detail) {
            $barang = Barang::find($detail['barang_id']);
            if ($barang->stok < $detail['stok']) {
                return response()->json(['message' => 'Stok tidak cukup untuk barang ID ' . $detail['barang_id']], 400);
            }

            // Decrease stock in Barang table
            $barang->stok -= $detail['stok'];
            $barang->save();

            // Update the Stok table
            $stokRecord = Stok::where('barang_id', $detail['barang_id'])->first();
            if ($stokRecord) {
                $stokRecord->stokmasuk -= $detail['stok'];
                $stokRecord->save();
            }

            // Create a new StokBR record
            StokBR::create([
                'barang_id' => $detail['barang_id'],
                'stok' => $detail['stok'],
                'keterangan' => $detail['keterangan'], // Adjusted to take from the detail
                'tanggalrusak' => $detail['tanggalrusak'], // Adjusted to take from the detail
            ]);
        }

        return response()->json(['message' => 'Stok rusak berhasil ditambahkan'], 200);
    }

    public function show($id)
    {
        $stokbr = StokBR::find($id);
        if (!$stokbr) {
            return response()->json(['message' => 'stok not found'], 404);
        }
        return response()->json(['stokbr' => $stokbr]);
    }

    public function update(Request $request, $id)
    {
        $stokbr = StokBR::find($id);
        if (!$stokbr) {
            return response()->json(['message' => 'stok not found'], 404);
        }

        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'stok' => 'required|integer',
            'keterangan' => 'nullable|string',
            'tanggalrusak' => 'required|date',
        ]);

        try {
            $oldStok = $stokbr->stok;
            $stokbr->fill([
                'stok' => $request->stok,
                'barang_id' => $request->barang_id,
                'keterangan' => $request->keterangan,
                'tanggalrusak' => $request->tanggalrusak,
            ])->save();
            $barang = Barang::find($stokbr->barang_id);
            if ($barang) {
                $barang->stok = ($barang->stok ?? 0) - $oldStok + $request->stok;
                $barang->save();
            }

            return response()->json(['message' => 'Berhasil memperbarui data stok']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Ada yang tidak beres saat memperbarui data stok'], 500);
        }
    }

    public function destroy($id)
    {
        $stokbr = StokBR::find($id);
        if (!$stokbr) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $barang = Barang::find($stokbr->barang_id);
        if ($barang) {
            // Add stock back to Barang table
            $barang->stok += $stokbr->stok;
            $barang->save();

            // Update the Stok table
            $stokRecord = Stok::where('barang_id', $stokbr->barang_id)->first();
            if ($stokRecord) {
                $stokRecord->stok += $stokbr->stok;
                $stokRecord->save();
            }
        }
        $stokbr->delete();

        return response()->json(['message' => 'Stok rusak berhasil dihapus'], 200);
    }
}
