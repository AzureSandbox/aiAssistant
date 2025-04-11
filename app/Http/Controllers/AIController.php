<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\AILog;
use Carbon\Carbon;

class AIController extends Controller {
    public function processRequest(Request $request) {
        $request->validate([ 'prompt' => 'required|string|max:5000', 'tempChat' => 'required|boolean' ]);

        $prompt = $request->prompt;
        $apiPrompt = "";

        if ($prompt === "\"\"") {
            return response()-> json([
                'prompt' => $apiPrompt,
                'reply' => 'You cannot provide an empty string as input.',
                'timestamp' => Carbon::now()
            ]);
        }

        $decoded = json_decode($prompt, true);

        if (is_array($decoded)) {
            $json = $this->handleJson($prompt);
            $apiPrompt = "Given this JSON data {$json}, summarize any concerning trends for management.";
        } else {
            $apiPrompt = $prompt;
        }

        $apiKey = env('OPENAI_API_KEY'); // as mentioned in the readme file, it is better to save the api key in aws secrets manager
        // $response = Http::withHeaders([
        //     'Authorization' => "Bearer $apiKey",
        //     'Content-Type' => 'application/json',
        // ])->post('https://api.openai.com/v1/chat/completions', [
        //     'model' => 'gpt-4',
        //     'messages' => [['role' => 'user', 'content' => $apiPrompt]],
        //     'temperature' => 0.7,
        // ]);

        $aiReply = "Testing some code";
        // logging this api call will help during debugging

        // $aiReply = $response->json('choices.0.message.content', 'Error fetching response');

        if (!$request->tempChat) {
            try {
                AILog::create(['prompt' => $apiPrompt, 'response' => $aiReply]);
            } catch(err) {
                // we can log the error here instead of just handling it for splunk or sumologic or other sevices to read them, helping in debugging if something goes wrong
                return response()-> json([
                    'prompt' => $apiPrompt,
                    'reply' => 'There was an error while processing your request. Please try again later',
                    'timestamp' => Carbon::now()
                ]);
            }
        }

        return response()->json([
            'prompt' => $apiPrompt,
            'reply' => $aiReply,
            'timestamp' => Carbon::now()
        ]);
    }

    private function handleJson(String $json) {
        $data = json_decode($json, true);
        if (is_null($data) || json_last_error() !== JSON_ERROR_NONE) {
            return json([
                'error' => 'Malformed JSON provided. Please fix it and try again.'
            ]);
        }

        $validate = Validator::make($data, [
            '*.employee_id'         => 'required|string',
            '*.name'                => 'required|string',
            '*.team'                => 'required|string',
            '*.engagement_score'    => 'required|numeric',
            '*.training_completion' => 'required|numeric',
            '*.attendance_rate'     => 'required|numeric'
        ]);

        if ($validate -> fails()) {
            return json([
                'error' => 'Data provided could not be validated. Please check your data'
            ]);
        }

        return json_encode($data);
    }
}
