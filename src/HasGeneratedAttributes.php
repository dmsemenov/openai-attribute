<?php

namespace Dmsemenov\OpenaiAttribute;

trait HasGeneratedAttributes
{
    /**
     * List of model generated attributes with prompt
     * Example:
     * 'description' => [
     *      'prompt' => 'Say something about [model:title]',
     *      'temperature' => 0.3,
     *      'max_tokens' => 1000,
     *      'top_p' => 1.0,
     *      'frequency_penalty' => 0.0,
     *      'presence_penalty' => 0.0
     * ]
     *
     * @return  array
     */
    public function generatedAttributes(): array
    {
        return [];
    }

    /**
     * Define condition if needed to generate model attributes presented in $generatedAttributes
     *
     * @return bool
     */
    public function NeedsGenerateAttributes(): bool
    {
        return true;
    }
}
