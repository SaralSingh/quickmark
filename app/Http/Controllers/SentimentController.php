<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SentimentController extends Controller
{
    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        try {
            $response = Http::timeout(5)->post(
                'http://127.0.0.1:8001/predict',
                ['text' => $validated['comment']]
            );

            if (!$response->successful()) {  // 200-299 range
                return response()->json([
                    'status'  => false,
                    'message' => 'Sentiment service unavailable (HTTP ' . $response->status() . ')',
                ], $response->status() ?: 503);
            }

            $data = $response->json();  // Automatically decodes JSON to array

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid response from ML service',
                ], 500);
            }

            return response()->json([
                'status'    => true,
                'sentiment' => $data['label']   ?? 'unknown',
                'emoji'     => $data['emoji']   ?? '❓',
            ]);

        } catch (\Exception $e) {
            // Log kar lo future mein debug ke liye
            Log::error('Sentiment API error: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong with the analysis service',
            ], 500);
        }
    }
}