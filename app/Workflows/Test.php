<?php

declare(strict_types=1);

namespace App\Workflows;

use App\Jobs\Test1;
use App\Jobs\Test2;
use App\Jobs\Test3;
use App\Jobs\Test4;
use App\Jobs\TestQueued1;
use App\Jobs\TestQueued2;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\Models\Workflow;
use Sassnowski\Venture\WorkflowDefinition;

class Test extends AbstractWorkflow
{
    public function definition(): WorkflowDefinition
    {
        return \Sassnowski\Venture\Facades\Workflow::define('test workflow')
            ->addJob(new Test1(), [], 'job 1')
            ->addJob(new Test4(), [], 'job 4')
            ->addJob(new Test2(), [
                Test1::class,
            ], 'job 2')
            ->addJobWithDelay(new Test3(), 2, [
                Test2::class,
                Test4::class,
            ], 'job 3')
            ->then(function (Workflow $workflow) {
                info('finished');
            });
    }
}
