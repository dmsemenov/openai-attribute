<?php

namespace Dmsemenov\OpenaiAttribute;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI;

class OpenaiAttribute
{
    /**
     * Generate prompt from model
     *
     * @param Model $model
     * @return ?string
     */
    public function getPrompt(Model $model, string $attributeName): ?string
    {
        try {
            $prompt = Arr::get($model->generatedAttributes(), "{$attributeName}.prompt");

            $replaced = preg_replace_callback(
                "/\[model:\w*\]/",
                function ($matches) use ($model) {
                    return $model->{Str::between(current($matches), 'model:', ']')};
                },
                $prompt
            );
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return null;
        }

        return $replaced;
    }

    /**
     * Fill generated attributes for model.
     *
     * @param Model $model
     * @return void
     */
    public function generate(Model $model): void
    {
        $modelUpdated = false;

        foreach ($model->generatedAttributes() as $attributeName => $attributeConfig) {
            $prompt = $this->getPrompt($model, $attributeName);
            if (empty($prompt)) {
                continue;
            }

            $requestBody = array_merge(config('openai-attribute.default_options'), Arr::except($attributeConfig, ['prompt']));

            $client = OpenAI::client(config('openai-attribute.api_key'));
            try {
                $result = $client->chat()->create([
                    ...$requestBody,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ]);
            } catch (\Throwable $exception) {
                Log::error($exception->getMessage());
                return;
            }

            if ($text = Arr::get($result, 'choices.0.message.content')) {
                $modelUpdated = true;
                $model->generatedTextAlter($attributeName, $text);
            }
        }

        if ($modelUpdated) {
            $model->save();
        }
    }
}
