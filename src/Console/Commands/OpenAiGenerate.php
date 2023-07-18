<?php

namespace Dmsemenov\OpenaiAttribute\Console\Commands;

use Dmsemenov\OpenaiAttribute\Jobs\GenerateOpenAiAttributes;
use Dmsemenov\OpenaiAttribute\Facades\OpenaiAttribute;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OpenAiGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openai:generate
                            {model : Eloquent model like App/User}
                            {--ids=* : Eloquent model id}
                            {--queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill existing model attributes with OpenAi answer';

    /**
     * Model instance
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Flag process as queue jobs
     *
     * @var bool
     */
    protected bool $queued;

    /**
     * Model ids to process
     *
     * @var array
     */
    protected array $ids;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');

        $this->queued = $this->option('queued');
        $this->ids = $this->option('ids');

        try {
            $this->model = new $model;
        } catch (\Throwable $e) {
            $this->error('Cant\'t resolve model: ' . $model);
            exit(1);
        }

        $query = $this->model->newModelQuery();

        if (!empty($this->ids)) {
            $query = $query->whereIn($this->model->getKeyName(), $this->ids);
        }

        if ($this->queued) {
            $this->handleQueued($query);
            return;
        }

        $this->handleInstantly($query);
    }

    /**
     * Handle request with queue jobs
     *
     * @param Builder $query
     * @return void
     */
    protected function handleQueued(Builder $query)
    {
        $this->info('OpenAi attribute generation added to queue.');
        $models = $query->get()->filter(fn($model) => $model->NeedsGenerateAttributes());

        foreach ($models as $model) {
            GenerateOpenAiAttributes::dispatch($model);
        }
    }


    /**
     * Handle request instantly
     *
     * @param Builder $query
     * @return void
     */
    protected function handleInstantly(Builder $query)
    {
        $this->info('OpenAi attribute generation started instantly.');

        $models = $query->get()->filter(fn($model) => $model->NeedsGenerateAttributes());

        foreach ($models as $model) {
            OpenaiAttribute::generate($model);
        }
    }
}
