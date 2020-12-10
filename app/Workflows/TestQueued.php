<?php

declare(strict_types=1);

namespace App\Workflows;

use App\Jobs\TestQueued1;
use App\Jobs\TestQueued2;
use App\Jobs\TestQueued3;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\Models\Workflow;
use Sassnowski\Venture\WorkflowDefinition;

class TestQueued extends AbstractWorkflow
{
    public $creation = 'foo';

    public function definition(): WorkflowDefinition
    {
        return \Sassnowski\Venture\Facades\Workflow::define('test queued workflow')
            ->addJob(new TestQueued1(), [], 'job 1')
            ->addJob(new TestQueued2(), [
                TestQueued1::class,
            ], 'job 2')
            ->addJobWithDelay(new TestQueued3(), 2, [
                TestQueued2::class,
            ], 'job 3')
            ->then(function (Workflow $workflow) {
                info('finished');
            });
    }
}
