<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokBM;
use App\Models\transaksikeluardetail;
use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\transaksimasukdetail;

class TransaksiMasukController extends Controller
{
    public function index()
    {
        try {
            Log::info('Fetching Transaksi Masuk data');
            $transaksiMasuk = TransaksiMasuk::with('transaksikeluar.transaksikeluardetails.barang', 'transaksimasukdetails.barang')->get();
            return response()->json($transaksiMasuk);
        } catch (\Exception $e) {
            Log::error('Failed to fetch data in index method', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to fetch data.'], 500);
        }
    }

    public function show($id)
    {
        try {
            Log::info('Fetching Transaksi Masuk data for ID', ['id' => $id]);
            $transaksiMasuk = TransaksiMasuk::with('transaksikeluar.transaksikeluardetails.barang', 'transaksimasukdetails.barang')->find($id);

            if (!$transaksiMasuk) {
                return response()->json(['message' => 'Transaksi Masuk not found'], 404);
            }

            return response()->json(['transaksimasuk' => $transaksiMasuk]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch data in show method', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to fetch data.'], 500);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'transaksi_keluar_id' => 'required|integer|exists:transaksi_keluars,id',
            'alasan' => 'required|string',
            'tanggalinput' => 'required|date',
            'details' => 'required|array',
            'details.*.barang_id' => 'required|integer|exists:barangs,id',
            'details.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $transaksiMasuk = TransaksiMasuk::create([
                'transaksi_keluar_id' => $request->input('transaksi_keluar_id'),
                'alasan' => $request->input('alasan'),
                'tanggalinput' => $request->input('tanggalinput'),
            ]);

            foreach ($request->input('details') as $detail) {
                TransaksiMasukDetail::create([
                    'transaksi_masuk_id' => $transaksiMasuk->id,
                    'barang_id' => $detail['barang_id'],
                    'jumlah' => $detail['jumlah'],
                ]);

                $barang = Barang::find($detail['barang_id']);
                if ($barang) {
                    $barang->stok = ($barang->stok ?? 0) + $detail['jumlah'];
                    $barang->save();
                }

                $notransaksi = $transaksiMasuk['transaksikeluar']['notransaksi'];
                StokBM::create([
                    'barang_id' => $detail['barang_id'],
                    'stok' => $detail['jumlah'],
                    'keterangan' => 'Pengembalian barang No.Transaksi : ' . $notransaksi,
                    'tanggalmasuk' => $request->input('tanggalinput'),
                ]);
                $transaksiKeluarDetail = TransaksiKeluarDetail::where('transaksi_keluar_id', $request->input('transaksi_keluar_id'))
                    ->where('barang_id', $detail['barang_id'])
                    ->first();

                if ($transaksiKeluarDetail) {
                    $newJumlah = $transaksiKeluarDetail->jumlah - $detail['jumlah'];
                    if ($newJumlah < 0) {
                        throw new \Exception('Jumlah pengembalian melebihi jumlah yang ada.');
                    }

                    if ($newJumlah > 0) {
                        $transaksiKeluarDetail->jumlah = $newJumlah;
                        $transaksiKeluarDetail->save();
                    } else {
                        $transaksiKeluarDetail->delete();
                    }
                } else {
                    throw new \Exception('Detail transaksi keluar tidak ditemukan.');
                }
            }
            DB::commit();
            return response()->json(['message' => 'Transaction successfully added!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Transaksi Masuk', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to add transaction.'], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'transaksi_keluar_id' => 'required|integer|exists:transaksi_keluars,id',
            'alasan' => 'required|string',
            'tanggalinput' => 'required|date',
            'details' => 'nullable|array',
            'details.*.barang_id' => 'required|integer|exists:barangs,id',
            'details.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $transaksiMasuk = TransaksiMasuk::find($id);

            if (!$transaksiMasuk) {
                return response()->json(['message' => 'Transaksi Masuk not found'], 404);
            }
            $transaksiMasuk->transaksi_keluar_id = $request->input('transaksi_keluar_id');
            $transaksiMasuk->alasan = $request->input('alasan');
            $transaksiMasuk->tanggalinput = $request->input('tanggalinput');
            $transaksiMasuk->save();

            if ($request->has('details')) {
                TransaksiMasukDetail::where('transaksi_masuk_id', $id)->delete();

                foreach ($request->input('details') as $detail) {
                    TransaksiMasukDetail::create([
                        'transaksi_masuk_id' => $id,
                        'barang_id' => $detail['barang_id'],
                        'jumlah' => $detail['jumlah'],
                    ]);

                    $barang = Barang::find($detail['barang_id']);
                    if ($barang) {
                        $barang->stok = ($barang->stok ?? 0) + $detail['jumlah'];
                        $barang->save();
                    }

                    $notransaksi = $transaksiMasuk->transaksikeluar->notransaksi;
                    StokBM::create([
                        'barang_id' => $detail['barang_id'],
                        'stok' => $detail['jumlah'],
                        'keterangan' => 'Pengembalian barang No.Transaksi : ' . $notransaksi,
                        'tanggalmasuk' => $request->input('tanggalinput'),
                    ]);

                    $transaksiKeluarDetail = TransaksiKeluarDetail::where('transaksi_keluar_id', $request->input('transaksi_keluar_id'))
                        ->where('barang_id', $detail['barang_id'])
                        ->first();

                    if ($transaksiKeluarDetail) {
                        $newJumlah = $transaksiKeluarDetail->jumlah - $detail['jumlah'];
                        if ($newJumlah < 0) {
                            throw new \Exception('Jumlah pengembalian melebihi jumlah yang ada.');
                        }

                        if ($newJumlah > 0) {
                            $transaksiKeluarDetail->jumlah = $newJumlah;
                            $transaksiKeluarDetail->save();
                        } else {
                            $transaksiKeluarDetail->delete();
                        }
                    } else {
                        throw new \Exception('Detail transaksi keluar tidak ditemukan.');
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Transaction successfully updated!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Transaksi Masuk', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update transaction.'], 500);
        }
    }
}
