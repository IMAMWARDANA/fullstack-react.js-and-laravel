<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Stok;
use App\Models\Barang;
use App\Models\StokBM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('stoks', 'kategori', 'satuan')->get();
        return response()->json($barangs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'merek' => 'required|string|min:1',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id' => 'required|exists:satuans,id',
        ]);

        try {
            // Create the new Barang
            $barang = Barang::create([
                'name' => $request->name,
                'kode' => $request->kode,
                'merek' => $request->merek,
                'stok' => $request->stok,
                'kategori_id' => $request->kategori_id,
                'satuan_id' => $request->satuan_id,
            ]);

            // Add an entry to the Stok table
            Stok::create([
                'stokawal' => $request->stok,
                'stokmasuk' => 0,
                'barang_id' => $barang->id,
            ]);

            StokBM::create([
                'barang_id' => $barang->id,
                'stok' => $request->stok,
                'keterangan' => 'Stok Awal',
                'tanggalmasuk' => now(),
            ]);

            return response()->json([
                'message' => 'Berhasil Menambahkan Barang dan Stok',
                'barang' => $barang
            ], 201);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Database error occurred while creating barang',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while creating the barang',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json([
                'message' => 'barang not found'
            ], 404);
        }
        return response()->json([
            'barang' => $barang
        ]);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json([
                'message' => 'barang not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'merek' => 'required|string|min:1',
            'kategori_id' => 'required|exists:kategoris,id',
            'satuan_id' => 'required|exists:satuans,id',
            'stok' => 'required|numeric|min:0',
        ]);

        try {
            // Update the Barang
            $barang->update([
                'name' => $request->name,
                'kode' => $request->kode, // Ensure this is set correctly
                'merek' => $request->merek,
                'kategori_id' => $request->kategori_id,
                'satuan_id' => $request->satuan_id,
                'stok' => $request->stok, // Update the stock in Barang
            ]);

            // Update the stok table
            $stok = Stok::where('barang_id', $id)->first();
            if ($stok) {
                $stok->update([
                    'stok' => $request->stok // Update the stock in the stok table
                ]);
            } else {
                // Handle the case where the stock entry does not exist
                Stok::create([
                    'barang_id' => $id,
                    'stok' => $request->stok
                ]);
            }

            return response()->json([
                'message' => 'Berhasil memperbarui data barang'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat memperbarui data barang'
            ], 500);
        }
    }


    public function destroy($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json([
                'message' => 'barang not found'
            ], 404);
        }

        try {
            $barang->delete();
            return response()->json([
                'message' => 'Berhasil menghapus data barang'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat menghapus data barang'
            ], 500);
        }
    }
}
