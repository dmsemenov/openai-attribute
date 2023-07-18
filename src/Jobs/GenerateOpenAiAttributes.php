<?php

namespace Dmsemenov\OpenaiAttribute\Jobs;

use Dmsemenov\OpenaiAttribute\Facades\OpenaiAttribute;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class GenerateOpenAiAttributes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Model $model
    ) {
        $this->onQueue(config('openai-attribute.queue_name'));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        OpenaiAttribute::generate($this->model);
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping($this->model->id)];
    }
}
