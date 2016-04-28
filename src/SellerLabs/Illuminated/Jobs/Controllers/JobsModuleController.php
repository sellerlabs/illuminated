<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs\Controllers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use SellerLabs\Illuminated\Conference\Entities\ConferenceContext;
use SellerLabs\Illuminated\Conference\Views\ConferencePaginator;
use SellerLabs\Illuminated\Http\BaseController;
use SellerLabs\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobFactoryInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Nucleus\Support\Arr;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\View\Bootstrap\Card;
use SellerLabs\Nucleus\View\Bootstrap\CardBlock;
use SellerLabs\Nucleus\View\Bootstrap\CardHeader;
use SellerLabs\Nucleus\View\Bootstrap\Column;
use SellerLabs\Nucleus\View\Bootstrap\Row;
use SellerLabs\Nucleus\View\Bootstrap\SimpleTable;
use SellerLabs\Nucleus\View\Common\Anchor;
use SellerLabs\Nucleus\View\Common\Button;
use SellerLabs\Nucleus\View\Common\HeaderOne;
use SellerLabs\Nucleus\View\Common\HeaderSix;
use SellerLabs\Nucleus\View\Common\Italic;
use SellerLabs\Nucleus\View\Common\Paragraph;
use SellerLabs\Nucleus\View\Common\PreformattedText;

/**
 * Class JobsModuleController.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs\Controllers
 */
class JobsModuleController extends BaseController
{
    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * @var JobSchedulerInterface
     */
    protected $scheduler;

    /**
     * @var HandlerResolverInterface
     */
    protected $resolver;

    /**
     * @var JobFactoryInterface
     */
    protected $factory;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Construct an instance of a JobsModuleController.
     *
     * @param JobRepositoryInterface $jobs
     * @param JobSchedulerInterface $scheduler
     * @param HandlerResolverInterface $resolver
     * @param JobFactoryInterface $factory
     * @param Request $request
     */
    public function __construct(
        JobRepositoryInterface $jobs,
        JobSchedulerInterface $scheduler,
        HandlerResolverInterface $resolver,
        JobFactoryInterface $factory,
        Request $request
    ) {
        $this->jobs = $jobs;
        $this->scheduler = $scheduler;
        $this->resolver = $resolver;
        $this->factory = $factory;
        $this->request = $request;
    }

    /**
     * Get all jobs.
     *
     * @return Card
     */
    public function getIndex()
    {
        $jobs = $this->jobs->getPaginated();

        return new Card([], [
            new CardHeader([], [
                new Row([], [
                    new Column(['medium' => 6, 'class' => 'btn-y-align'], [
                        'All Jobs',
                    ]),
                    new Column(['medium' => 6, 'class' => 'text-right'], [
                        new Button(
                            ['class' => 'btn btn-sm btn-primary-outline'],
                            'Create new job'
                        ),
                    ]),
                ]),
            ]),
            $this->renderJobsTable($jobs),
            new ConferencePaginator($jobs),
        ]);
    }

    public function getScheduled()
    {
        $jobs = $this->jobs->getScheduledPaginated();

        return new Card([], [
            new CardHeader([], [
                new Row([], [
                    new Column(['medium' => 6, 'class' => 'btn-y-align'], [
                        'Scheduled Jobs',
                    ]),
                    new Column(['medium' => 6, 'class' => 'text-right'], [
                        new Button(
                            ['class' => 'btn btn-sm btn-primary-outline'],
                            'Create new job'
                        ),
                    ]),
                ]),
            ]),
            $this->renderJobsTable($jobs),
            new ConferencePaginator($jobs),
        ]);
    }

    public function getQueued()
    {
        $jobs = $this->jobs->getQueuedPaginated();

        return new Card([], [
            new CardHeader([], [
                new Row([], [
                    new Column(['medium' => 6, 'class' => 'btn-y-align'], [
                        'Queued Jobs',
                    ]),
                    new Column(['medium' => 6, 'class' => 'text-right'], [
                        new Button(
                            ['class' => 'btn btn-sm btn-primary-outline'],
                            'Create new job'
                        ),
                    ]),
                ]),
            ]),
            $this->renderJobsTable($jobs),
            new ConferencePaginator($jobs),
        ]);
    }

    public function getFailed()
    {
        $jobs = $this->jobs->getFailedPaginated();

        return new Card([], [
            new CardHeader([], [
                new Row([], [
                    new Column(['medium' => 6, 'class' => 'btn-y-align'], [
                        'Failed Jobs',
                    ]),
                    new Column(['medium' => 6, 'class' => 'text-right'], [
                        new Button(
                            ['class' => 'btn btn-sm btn-primary-outline'],
                            'Create new job'
                        ),
                    ]),
                ]),
            ]),
            $this->renderJobsTable($jobs),
            new ConferencePaginator($jobs),
        ]);
    }

    public function getReference(
        HandlerResolverInterface $resolver,
        ConferenceContext $context
    ) {
        return new Card([], [
            new CardHeader([], 'Task Reference'),
            new SimpleTable(
                ['Task', 'Description'],
                Std::map(function ($taskName) use ($resolver, $context) {
                    $instance = $resolver->instantiate($taskName);

                    return [
                        new Anchor([
                            'href' => $context->method(
                                'illuminated.jobs',
                                'reference.single',
                                ['id' => $taskName]
                            ),
                        ], $taskName),
                        $instance->getDescription(),
                    ];
                }, $resolver->getAvailableTasks())
            ),
        ]);
    }

    public function getReferenceSingle(
        HandlerResolverInterface $resolver,
        ConferenceContext $context,
        Request $request
    ) {
        $taskName = $request->query->get('id');
        $handler = $resolver->instantiate($taskName);
        $defaults = $handler->getDefaults();
        $types = $handler->getTypes();

        return new Card([], [
            new CardHeader([], 'Reference for task:'),
            new CardBlock([], [
                new HeaderOne(['class' => 'display-one'], $taskName),
                new Paragraph(['class' => 'lead'], $handler->getDescription()),
            ]),
            new SimpleTable(
                ['Field Name', 'Type', 'Default', 'Description'],
                Std::map(function ($description, $field) use ($types, $defaults) {
                    return [
                        $field,
                        Arr::dotGet($types, $field, '-'),
                        (string) Arr::dotGet($defaults, $field, '-'),
                        $description,
                    ];
                }, $handler->getReference())
            ),
            new CardBlock([], [
                new HeaderSix([], 'Example usage:'),
                new PreformattedText(
                    [],
                    json_encode($defaults, JSON_PRETTY_PRINT)
                ),
            ]),
        ]);
    }

    /**
     * Render a table showing jobs.
     *
     * @param Paginator $jobs
     *
     * @return mixed
     */
    protected function renderJobsTable(Paginator $jobs)
    {
        return Std::firstBias(
            count($jobs->items()) > 0,
            function () use ($jobs) {
                return new SimpleTable(
                    [
                        'ID', 'Task', 'State', 'Runs', 'Created At',
                        'Duration',
                    ],
                    Std::map(
                        function (Job $job) {
                            return [
                                $job->id,
                                $job->state,
                                $job->task,
                                $job->attempts,
                                $job->created_at->toDayDateTimeString(),
                                $job->getExecutionTime(),
                            ];
                        },
                        $jobs->items()
                    )
                );
            },
            function () {
                return new CardBlock(
                    ['class' => 'card-block text-center'],
                    [
                        new Paragraph([], [
                            new Italic(
                                ['class' => 'fa fa-4x fa-search text-light']
                            ),
                        ]),
                        'No jobs found matching the specified criteria.',
                    ]
                );
            }
        );
    }
}
