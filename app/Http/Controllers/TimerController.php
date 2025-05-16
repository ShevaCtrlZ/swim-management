<?php

namespace App\Http\Controllers;

use Carbon\Carbon as CarbonCarbon;
use illumninate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\DetailLomba;
use Illuminate\Support\Carbon as SupportCarbon;

class TimerController extends Controller
{
    public function startSeries(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $peserta = DetailLomba::find($id);
            if ($peserta && !$peserta->start_time) {
                $peserta->start_time = now();
                $peserta->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function stopSeries(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $detail = DetailLomba::find($id);
            if ($detail) {
                $stopTime = CarbonCarbon::now(); // waktu saat ini
                $startTime = CarbonCarbon::parse($detail->waktu_mulai ?? now()); // fallback sekarang kalau nggak ada
                $elapsed = $startTime->diff($stopTime);
                $formatted = $elapsed->format('%H:%I:%S');

                $detail->catatan_waktu = $formatted;
                $detail->save();
            }
        }

        return response()->json(['success' => true]);
    }


    public function getTimes(Request $request)
    {
        $ids = $request->input('ids');
        $now = now();
        $data = [];

        foreach ($ids as $id) {
            $peserta = DetailLomba::find($id);
            if ($peserta && $peserta->start_time) {
                $elapsed = $now->diff($peserta->start_time);
                $data[$id] = sprintf(
                    '%02d:%02d:%02d',
                    $elapsed->h + $elapsed->d * 24,
                    $elapsed->i,
                    $elapsed->s
                );
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}