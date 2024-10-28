<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Keperluan; // Pastikan model ini sudah ada di App\Models
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class KeperluanController extends Controller
{
    public function index()
    {
        $keperluans = Keperluan::all();
        return response()->json($keperluans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string', // Tambahkan aturan validasi jika diperlukan
        ]);

        try {
            $keperluan = Keperluan::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Berhasil Menambahkan Keperluan',
                'keperluan' => $keperluan
            ], 201);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Database error occurred while creating keperluan',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while creating the keperluan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $keperluan = Keperluan::find($id);
        if (!$keperluan) {
            return response()->json([
                'message' => 'Keperluan not found'
            ], 404);
        }
        return response()->json([
            'keperluan' => $keperluan
        ]);
    }

    public function update(Request $request, $id)
    {
        $keperluan = Keperluan::find($id);
        if (!$keperluan) {
            return response()->json([
                'message' => 'Keperluan not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $keperluan->fill([
                'name' => $request->name,
                'description' => $request->description,
            ])->save();

            return response()->json([
                'message' => 'Berhasil memperbarui data keperluan'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat memperbarui data keperluan'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $keperluan = Keperluan::find($id);
        if (!$keperluan) {
            return response()->json([
                'message' => 'Keperluan not found'
            ], 404);
        }

        try {
            $keperluan->delete();
            return response()->json([
                'message' => 'Berhasil menghapus data keperluan'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat menghapus data keperluan'
            ], 500);
        }
    }
}
