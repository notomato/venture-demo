<?php

declare(strict_types=1);

namespace App\Workflows;

use App\Jobs\Creations\CreateCompositeLayerImages;
use App\Jobs\Creations\FetchPrintfulMockups;
use App\Jobs\Creations\SubmitPrintfulMockups;
use App\Jobs\Test1;
use App\Jobs\Test2;
use App\Jobs\Test3;
use App\Models\Creation;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\Models\Workflow;
use Sassnowski\Venture\WorkflowDefinition;

class Test extends AbstractWorkflow
{
    public $creation = 'foo';

    public function definition(): WorkflowDefinition
    {
        return \Sassnowski\Venture\Facades\Workflow::define('Generate Printful Mockups')
            ->addJob(new Test1($this->creation), [], 'Create layer images')
            ->addJob(new Test2($this->creation), [
                Test1::class,
            ], 'Submit for generation')
            ->addJobWithDelay(new Test3($this->creation), 2, [
                Test2::class,
            ], 'Fetch generated previews')
            ->then(function (Workflow $workflow) {
                info('Finished generating printful mockups');
            });
    }
}
