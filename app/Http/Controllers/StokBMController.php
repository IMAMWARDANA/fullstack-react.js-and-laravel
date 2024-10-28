<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Stok;
use App\Models\Barang;
use App\Models\StokBM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class StokBMController extends Controller
{
    public function index()
    {
        $stokbm = StokBM::with('barang')->get();
        return response()->json($stokbm);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keterangan' => 'nullable|string',
            'tanggalmasuk' => 'required|date',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.stok' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            foreach ($request->details as $detail) {
                $stokbm = StokBM::create([
                    'barang_id' => $detail['barang_id'],
                    'stok' => $detail['stok'],
                    'keterangan' => $request->keterangan,
                    'tanggalmasuk' => $request->tanggalmasuk,
                ]);

                $barang = Barang::find($detail['barang_id']);
                if ($barang) {
                    $barang->stok = ($barang->stok ?? 0) + $detail['stok'];
                    $barang->save();
                }

                $stok = Stok::where('barang_id', $detail['barang_id'])->first();
                $stok = Stok::where('barang_id', $detail['barang_id'])->first();
                if ($stok) {
                    $stok->stokmasuk = ($stok->stokmasuk ?? 0) + $detail['stok'];
                    $stok->stokawal = $stok->stokawal ?? 0;
                    $stok->save();
                } else {
                    Stok::create([
                        'barang_id' => $detail['barang_id'],
                        'stokawal' => 0,
                        'stokmasuk' => $detail['stok'],
                    ]);
                }
            }

            return response()->json([
                'message' => 'Stok BM berhasil ditambahkan',
            ], 201);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Database error occurred while creating stokbm',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while creating the stokbm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'keterangan' => 'nullable|string',
            'tanggalmasuk' => 'required|date',
            'stok' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $stokbm = StokBM::find($id);
            if (!$stokbm) {
                return response()->json(['message' => 'StokBM not found'], 404);
            }

            // Update stock in Barang
            $barang = Barang::find($stokbm->barang_id);
            if ($barang) {
                $barang->stok = ($barang->stok ?? 0) - $stokbm->stok + $request->stok;
                $barang->save();
            }

            // Update stok in Stok table
            $stok = Stok::where('barang_id', $stokbm->barang_id)->first();
            if ($stok) {
                $stok->stokmasuk = ($stok->stokmasuk ?? 0) - $stokbm->stok + $request->stok;
                $stok->save();
            }

            // Update StokBM entry
            $stokbm->update([
                'stok' => $request->stok,
                'keterangan' => $request->keterangan,
                'tanggalmasuk' => $request->tanggalmasuk,
            ]);

            return response()->json(['message' => 'Stok BM successfully updated'], 200);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Database error occurred while updating stokbm', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'An error occurred while updating the stokbm', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $stokbm = StokBM::find($id);
        if (!$stokbm) {
            return response()->json([
                'message' => 'StokBM not found'
            ], 404);
        }

        try {
            $barang = Barang::find($stokbm->barang_id);
            if ($barang) {
                $barang->stok = ($barang->stok ?? 0) - $stokbm->stok;
                $barang->save();
            }

            $stok = Stok::where('barang_id', $stokbm->barang_id)->first();
            if ($stok) {
                $stok->stok = ($stok->stok ?? 0) - $stokbm->stok;
                $stok->save();
            }

            $stokbm->delete();

            return response()->json([
                'message' => 'StokBM berhasil dihapus'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat menghapus data stokbm'
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $stokbm = StokBM::where('barang_id', $id)->get();
            if ($stokbm->isEmpty()) {
                return response()->json([
                    'message' => 'No details found for this ID'
                ], 404);
            }

            return response()->json($stokbm);
        } catch (Exception $e) {
            Log::error('Error fetching stok masuk details: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while fetching stok masuk details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
