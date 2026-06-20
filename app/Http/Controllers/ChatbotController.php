<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function send(Request $request)
    {
        $roomData = Room::select('number', 'type', 'price', 'floor', 'status')->get()->toArray();
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        try {

            $response = Http::timeout(60)
                ->withHeaders([
                    'x-api-key' => env('ZAPI_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.zpi.web.id/v1/ai:chatex/chat', [
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => '
                        Kamu adalah chatbot resmi aplikasi KosKita.

                        Tugasmu membantu calon penghuni dan penghuni kos.

                        Topik:
                        - Harga kamar
                        - Fasilitas kos
                        - Cara booking
                        - Lokasi kos
                        - Pembayaran
                        - Maintenance

                        Jika pertanyaan di luar kos,
                        jawab:
                        "Maaf, saya hanya dapat membantu informasi terkait kos."
                        ',
                        ],
                        [
                            'role' => 'system',
                            'content' => 'Data kamar saat ini: '.json_encode($roomData),
                        ],
                        [
                            'role' => 'user',
                            'content' => $request->message,
                        ],
                    ],
                ]);

            $data = $response->json();

            return response()->json([
                'reply' => $data['data']['choices'][0]['message']['content']
                    ?? 'Maaf, tidak ada respon.',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'reply' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }
}
