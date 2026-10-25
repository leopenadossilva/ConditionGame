<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Agent provider
    |--------------------------------------------------------------------------
    |
    | Any OpenAI-compatible chat completions endpoint works. Leave the API key
    | empty to run the deterministic, keyword-driven helper used in v1.
    |
    */
    'api_key' => env('AGENT_API_KEY'),
    'base_url' => env('AGENT_BASE_URL', 'https://api.openai.com/v1'),
    'model' => env('AGENT_MODEL', 'gpt-4o-mini'),
    'timeout' => (int) env('AGENT_TIMEOUT', 30),
    'max_steps' => 5,
];
