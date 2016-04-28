<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Foundation\Controllers;

use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;
use SellerLabs\Illuminated\Conference\Entities\ConferenceContext;
use SellerLabs\Illuminated\Foundation\ApplicationManifest;
use SellerLabs\Illuminated\Foundation\Interfaces\ApplicationManifestInterface;
use SellerLabs\Illuminated\Http\ApiCheckableRequest;
use SellerLabs\Illuminated\Http\BaseController;
use SellerLabs\Illuminated\Http\Entities\ResourceMethod;
use SellerLabs\Illuminated\Http\Factories\ResourceFactory;
use SellerLabs\Illuminated\Http\ResourceReflector;
use SellerLabs\Nucleus\Meditation\Constraints\AbstractConstraint;
use SellerLabs\Nucleus\Meditation\Spec;
use SellerLabs\Nucleus\Support\Html;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\Validation\Validator;
use SellerLabs\Nucleus\View\Bootstrap\Card;
use SellerLabs\Nucleus\View\Bootstrap\CardBlock;
use SellerLabs\Nucleus\View\Bootstrap\CardHeader;
use SellerLabs\Nucleus\View\Common\Anchor;
use SellerLabs\Nucleus\View\Common\Bold;
use SellerLabs\Nucleus\View\Common\Div;
use SellerLabs\Nucleus\View\Common\HeaderOne;
use SellerLabs\Nucleus\View\Common\HeaderThree;
use SellerLabs\Nucleus\View\Common\Italic;
use SellerLabs\Nucleus\View\Common\Paragraph;
use SellerLabs\Nucleus\View\Common\Table;
use SellerLabs\Nucleus\View\Common\TableBody;
use SellerLabs\Nucleus\View\Common\TableCell;
use SellerLabs\Nucleus\View\Common\TableHeader;
use SellerLabs\Nucleus\View\Common\TableHeaderCell;
use SellerLabs\Nucleus\View\Common\TableRow;
use SellerLabs\Nucleus\View\Node;

/**
 * Class ApplicationController.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Foundation\Controllers
 */
class ApplicationController extends BaseController
{
    /**
     * @var ApplicationManifest
     */
    protected $manifest;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Construct an instance of a ApplicationController.
     *
     * @param ApplicationManifestInterface $manifest
     * @param Container $container
     */
    public function __construct(
        ApplicationManifestInterface $manifest,
        Container $container
    ) {
        $this->manifest = $manifest;
        $this->container = $container;
    }

    /**
     * Show basic application information.
     *
     * @param ConferenceContext $context
     *
     * @return Div
     */
    public function getIndex(ConferenceContext $context)
    {
        return new Div([], [
            new Card([], [
                new CardHeader([], 'Application Information'),
                new CardBlock([], [
                    new Paragraph([], [
                        new HeaderOne([], $this->manifest->getName()),
                    ]),
                    new Paragraph([], [
                        $this->manifest->getDescription(),
                    ]),
                ]),
            ]),
            $this->renderResources($context),
        ]);
    }

    /**
     * @return Div
     */
    public function getProse()
    {
        return new Div([],
            Std::map(function ($contents, $title) {
                return new Card([], [
                    new CardHeader([], $title),
                    new CardBlock([], [
                        Html::safe((new CommonMarkConverter())->convertToHtml(
                            $contents
                        )),
                    ]),
                ]);
            }, $this->manifest->getProse())
        );
    }

    /**
     * @param ConferenceContext $context
     *
     * @return Div
     */
    protected function renderResources(ConferenceContext $context)
    {
        return new Div([], Std::map(
            function (ResourceFactory $factory, $key) use ($context) {
                return $this->renderResource($context, $factory, false, $key);
            },
            $this->manifest->getResources())
        );
    }

    /**
     * @param ConferenceContext $context
     * @param ResourceFactory $factory
     * @param bool $reflect
     * @param int $id
     *
     * @return Div
     */
    protected function renderResource(
        ConferenceContext $context,
        ResourceFactory $factory,
        $reflect = false,
        $id = 0
    ) {
        return new Div([], [
            new Card([], [
                new CardHeader([], [
                    Std::coalesce($factory->getPrefix(), '/'),
                    ' ',
                    new Italic(
                        [
                            'class' => [
                                'fa',
                                'fa-arrow-circle-right ',
                            ],
                        ]
                    ),
                    ' ',
                    new Anchor(
                        ['href' => $context->method(
                            'illuminated.conference.application',
                            'single',
                            ['resource' => $id]
                        )],
                        new Bold([], $factory->getController())
                    ),
                ]),
                new CardBlock([], [
                    new Paragraph([], [
                        new Bold([], 'Middleware: '),
                        implode(', ', $factory->getMiddleware()),
                    ]),
                    new Div([], Std::map(
                        function (ResourceMethod $method) use (
                            $factory,
                            $reflect
                        ) {
                            return $this->renderRoute(
                                $factory,
                                $method,
                                $reflect
                            );
                        },
                        $factory->getMethods())
                    ),
                ]),
            ]),
        ]);
    }

    /**
     * @param ResourceFactory $factory
     * @param ResourceMethod $method
     *
     * @return Div|string
     */
    protected function reflectOnRequest(
        ResourceFactory $factory,
        ResourceMethod $method
    ) {
        try {
            $reflector = new ResourceReflector($this->container);

            $request = $reflector->getRequest(
                $reflector->getMethodArgumentTypes($factory, $method)
            );

            if ($request instanceof ApiCheckableRequest) {
                $spec = $request->getCheckable();

                if ($spec instanceof Validator) {
                    $spec = $spec->getSpec();
                }

                if (!$spec instanceof Spec) {
                    return 'Only Specs are supported at the moment.';
                }

                return new Div([], [
                    new Div([], [
                        new Bold([], 'Required: '),
                        implode(', ', $spec->getRequired()),
                    ]),
                    new Div([], [
                        new Table(
                            ['class' => 'table'], [
                                new TableHeader([], [
                                    new TableRow([], [
                                        new TableHeaderCell([], 'Field'),
                                        new TableHeaderCell([], 'Constraints'),
                                        new TableHeaderCell([], 'Default'),
                                    ]),
                                ]),
                                new TableBody([], Std::map(
                                    function ($value, $key) use ($spec) {
                                        return $this->renderConstraint(
                                            $spec,
                                            $key
                                        );
                                    },
                                    $spec->getConstraints())
                                ),
                            ]
                        ),
                    ]),
                ]);
            } else {
                return 'Handler cannot be reflected upon.';
            }
        } catch (Exception $e) {
            return 'Unable to resolve handler';
        }
    }

    /**
     * @param Spec $spec
     * @param string $field
     *
     * @return TableRow
     */
    protected function renderConstraint(Spec $spec, $field)
    {
        $constraints = $spec->getConstraints();
        $defaults = $spec->getDefaults();
        $default = '';

        if (array_key_exists($field, $defaults)) {
            $default = $defaults[$field];
        }

        $constraint = $constraints[$field];

        if ($constraint instanceof AbstractConstraint) {
            return new TableRow([], [
                new TableCell([], $field),
                new TableCell([], $constraint->toString()),
                new TableCell([], (string) $default),
            ]);
        }

        return new TableRow([], [
            new TableCell([], $field),
            new TableCell([], Std::map(
                function (AbstractConstraint $constraint) {
                    return $constraint->toString() . ', ';
                },
                $constraint
            )),
            new TableCell([], (string) $default),
        ]);
    }

    /**
     * @param ResourceFactory $factory
     * @param ResourceMethod $method
     * @param bool $reflect
     *
     * @return Div
     */
    protected function renderRoute(
        ResourceFactory $factory,
        ResourceMethod $method,
        $reflect = false
    ) {
        if ($reflect) {
            $reflection = $this->reflectOnRequest($factory, $method);

            return new Div(['style' => 'padding-top: 20px;'], [
                new HeaderThree([], [
                    new Node('code', [], strtoupper($method->getVerb())),
                    ' ',
                    new Bold([], $method->getPath()),
                    ' ',
                    $method->getMethod(),
                ]),
                new Div([], $reflection),
            ]);
        }

        return new Div(['style' => 'padding-top: 10px;'], [
            new Node('code', [], strtoupper($method->getVerb())),
            ' ',
            new Bold([], $method->getPath()),
            ' ',
            $method->getMethod(),
        ]);
    }

    /**
     * @param Request $request
     * @param ConferenceContext $context
     *
     * @return Div
     */
    public function getSingle(Request $request, ConferenceContext $context)
    {
        if (!$request->query->has('resource')) {
            return new Div([], [
                new Card([], [
                    new CardHeader([], 'Error'),
                    new CardBlock([], [
                        'Please provide valid resource.',
                    ]),
                ]),
            ]);
        }

        $index = $request->query->get('resource');

        return new Div([], [
            $this->renderResource(
                $context,
                $this->manifest->getResources()[$index],
                true,
                $index
            ),
        ]);
    }
}
