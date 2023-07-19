<?php

return [
    // OpenAI api key
    'api_key' => env('OPENAI_API_KEY'),

    // Queue name for generate attributes jobs
    'queue_name' => env('OPENAI_QUEUE_NMAE', 'openai_generate'),

    // Default options for api chat completions requests.
    'default_options' => [
        'model' => env('OPENAI_API_MODEL', 'gpt-3.5-turbo'),
        'temperature' => 0.3,
        'max_tokens' => 100,
        'top_p' => 1.0,
        'frequency_penalty' => 0.0,
        'presence_penalty' => 0.0
    ],
];
