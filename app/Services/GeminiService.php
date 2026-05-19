<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://integrate.api.nvidia.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
    }

    /**
     * Get financial advice based on transaction history and budget status using google/gemma-3n-e2b-it.
     */
    public function getFinancialAdvice(array $summaryData): string
    {
        $key = $this->apiKey;

        $prompt = "You are a smart personal finance coach. Analyze the following monthly financial summary and provide 3 actionable, encouraging, and highly specific tips or insights. Keep the response concise, formatted in clean markdown, and tone professional but friendly. Use ₹ (Rupees) as the currency.

Summary data:
- Current Month Balance: ₹" . number_format($summaryData['balance'], 2) . "
- Income: ₹" . number_format($summaryData['income'], 2) . " (Change from last month: " . $summaryData['incomeChange'] . "%)
- Expenses: ₹" . number_format($summaryData['expense'], 2) . " (Change from last month: " . $summaryData['expenseChange'] . "%)
- Savings Rate: " . $summaryData['savingsRate'] . "%
- Category Breakdown: " . json_encode($summaryData['categorySpending']) . "
- Budgets Status: " . json_encode($summaryData['budgets']);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $key,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'model' => 'google/gemma-3n-e2b-it',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 1024,
                'temperature' => 0.20,
                'top_p' => 0.70,
                'frequency_penalty' => 0.00,
                'presence_penalty' => 0.00,
                'stream' => false
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? "Could not generate advice at this moment.";
            }

            Log::error('NVIDIA Gemma API Error: ' . $response->body());
            return "Financial Advisor is temporarily offline. (API Error: " . $response->status() . ")";
        } catch (\Exception $e) {
            Log::error('NVIDIA Gemma Service Exception: ' . $e->getMessage());
            return "Unable to connect to AI Advisor right now.";
        }
    }

    /**
     * Scan and parse a receipt image to extract transaction details using google/gemma-3n-e2b-it multimodal capability.
     */
    public function parseReceipt(string $imagePath): ?array
    {
        if (!file_exists($imagePath)) {
            Log::error("Receipt file not found: {$imagePath}");
            return null;
        }

        $key = $this->apiKey;
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath);

        $prompt = "Analyze this receipt image. Extract:
1. 'amount' (numeric, total paid/received)
2. 'description' (string, name of shop or purchase item)
3. 'date' (format YYYY-MM-DD, default to current date if not found)
4. 'type' (output 'income' if it looks like salary/deposit, otherwise output 'expense')
5. 'category' (string, a short general category like Groceries, Utilities, Travel, Food, etc.)

Return the response ONLY as a valid JSON object matching these keys. Do not include markdown code block formatting (like ```json).";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $key,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                'model' => 'google/gemma-3n-e2b-it',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $prompt
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:' . $mimeType . ';base64,' . $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => 512,
                'temperature' => 0.20,
                'top_p' => 0.70,
                'frequency_penalty' => 0.00,
                'presence_penalty' => 0.00,
                'stream' => false
            ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content');
                
                // Clean markdown code blocks if the model wraps the output in them
                $cleanedText = preg_replace('/^```json\s*/i', '', trim($text));
                $cleanedText = preg_replace('/```$/', '', $cleanedText);
                
                $data = json_decode($cleanedText, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $data;
                }
                
                Log::error("NVIDIA Gemma failed to return valid JSON. Response: " . $text);
            } else {
                Log::error("NVIDIA Gemma receipt parse error: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('NVIDIA Gemma Receipt parsing exception: ' . $e->getMessage());
        }

        return null;
    }
}
