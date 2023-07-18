<?php

return [
    'openai_api_key' => env('OPENAI_API_KEY'),

    'text-davinci-003' => [
        'temperature' => 0.3,
        'max_tokens' => 100,
        'top_p' => 1.0,
        'frequency_penalty' => 0.0,
        'presence_penalty' => 0.0
    ],

    'queue_name' => 'openai_generate'

];
