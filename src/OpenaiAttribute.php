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
            $prompt = $model->generatedAttributes()[$attributeName]['prompt'] ?? '';

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
        $client = OpenAI::client(config('openai-attribute.openai_api_key'));

        foreach ($model->generatedAttributes() as $attributeName => $attributeConfig) {
            $prompt = $this->getPrompt($model, $attributeName);
            if (empty($prompt)) {
                continue;
            }

            $attributeConfig['prompt'] = $prompt;
            $extraParams = array_merge(config('openai-attribute.text-davinci-003'), $attributeConfig);

            $result = $client->completions()->create([
                'model' => 'text-davinci-003',
                ...$extraParams
            ]);

            if ($text = Arr::get($result, 'choices.0.text')) {
                $model->$attributeName = $text;
                $model->save();
            }
        }
    }
}
