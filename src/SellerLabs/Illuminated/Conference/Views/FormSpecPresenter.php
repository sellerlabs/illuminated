<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference\Views;

use SellerLabs\Nucleus\Control\Maybe;
use SellerLabs\Nucleus\Data\ArrayList;
use SellerLabs\Nucleus\Data\ArrayMap;
use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Meditation\Constraints\AbstractConstraint;
use SellerLabs\Nucleus\Meditation\Constraints\InArrayConstraint;
use SellerLabs\Nucleus\Meditation\Constraints\PrimitiveTypeConstraint;
use SellerLabs\Nucleus\Meditation\FormSpec;
use SellerLabs\Nucleus\Meditation\Primitives\ScalarTypes;
use SellerLabs\Nucleus\Support\Html;
use SellerLabs\Nucleus\View\Bootstrap\Row;
use SellerLabs\Nucleus\View\Common\Button;
use SellerLabs\Nucleus\View\Common\Div;
use SellerLabs\Nucleus\View\Common\Form;
use SellerLabs\Nucleus\View\Common\Input;
use SellerLabs\Nucleus\View\Common\Option;
use SellerLabs\Nucleus\View\Common\Paragraph;
use SellerLabs\Nucleus\View\Common\Select;
use SellerLabs\Nucleus\View\Common\Small;
use SellerLabs\Nucleus\View\Interfaces\RenderableInterface;
use SellerLabs\Nucleus\View\Interfaces\SafeHtmlProducerInterface;
use SellerLabs\Nucleus\View\Node;
use SellerLabs\Nucleus\View\SafeHtmlWrapper;

/**
 * Class FormSpecPresenter.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Views
 */
class FormSpecPresenter extends BaseObject implements
    RenderableInterface,
    SafeHtmlProducerInterface
{
    /**
     * @var FormSpec
     */
    protected $spec;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * Construct an instance of a FormSpecPresenter.
     *
     * @param FormSpec $spec
     * @param array $attributes
     *
     * @throws LackOfCoffeeException
     */
    public function __construct(FormSpec $spec, array $attributes = [])
    {
        parent::__construct();

        $this->spec = $spec;
        $this->attributes = $attributes;
    }

    public function renderField($fieldName)
    {
        // First, attempt to render a select field if the spec mentions an
        // InArrayConstraint.
        $constraints = $this->spec
            ->getFieldConstraints($fieldName)
            ->find(function (AbstractConstraint $constraint) {
                return ($constraint instanceof InArrayConstraint);
            })
            ->bind(function (InArrayConstraint $constraint) use ($fieldName) {
                return Maybe::just(new Select(
                    ['id' => $fieldName, 'class' => 'c-select'],
                    ArrayList::of($constraint->getAllowed())
                        ->map(function ($item) {
                            return new Option(
                                ['value' => (string) $item],
                                (string) $item
                            );
                        })
                ));
            });

        if ($constraints->isJust()) {
            return Maybe::fromJust($constraints);
        }

        // Otherwise, we default to render the thing closes to the type of the
        // field.
        $type = $this->spec->getFieldType($fieldName);

        if ($type instanceof PrimitiveTypeConstraint) {
            $attributes = ArrayMap::of([
                'id' => $fieldName,
                'class' => 'form-control',
            ]);

            switch ($type->toString()) {
                case ScalarTypes::SCALAR_STRING:
                    $attributes = $attributes->insert('type', 'text');
                    $default = $this->spec->getFieldDefault($fieldName);

                    if ($default->isJust()) {
                        $attributes = $attributes->insert(
                            'value',
                            Maybe::fromJust($default)
                        );
                    }

                    return new Input($attributes->toArray());
                case ScalarTypes::SCALAR_BOOLEAN:
                    $attributes = $attributes->insert('type', 'checkbox');
                    $default = $this->spec->getFieldDefault($fieldName);

                    if ($default->isJust()) {
                        $attributes = $attributes->insert('checked', null);
                    }

                    return new Input($attributes->toArray());
                case ScalarTypes::SCALAR_INTEGER:
                case ScalarTypes::SCALAR_FLOAT:
                    $attributes = $attributes->insert('type', 'number');
                    $default = $this->spec->getFieldDefault($fieldName);

                    if ($default->isJust()) {
                        $attributes = $attributes->insert(
                            'value',
                            Maybe::fromJust($default)
                        );
                    }

                    return new Input($attributes->toArray());
            }
        }

        // If it is not a primitive type, we bail and render a simple text
        // field.
        return new Input([
            'id' => $fieldName,
            'class' => 'form-control',
            'type' => 'text',
        ]);
    }

    protected function renderFullField($fieldName)
    {
        $fieldNodes = $this->renderField($fieldName);

        if (!is_array($fieldNodes)) {
            $nodes = ArrayList::of([$fieldNodes]);
        } else {
            $nodes = ArrayList::of($fieldNodes);
        }

        if ($this->spec->getFieldDescription($fieldName)->isJust()) {
            $nodes = $nodes->append(ArrayList::of([
                new Paragraph([], new Small(['class' => 'text-muted'], [
                    Maybe::fromJust(
                        $this->spec->getFieldDescription($fieldName)
                    ),
                ])),
            ]));
        }

        return $nodes->toArray();
    }

    /**
     * Render the object into a string.
     *
     * @return mixed
     */
    public function render()
    {
        $addColon = function ($label) {
            return Maybe::just(vsprintf('%s:', [$label]));
        };

        return (new Form(
            $this->attributes,
            $this->spec
                ->getAnnotations()
                ->map(function ($_, $key) use ($addColon) {
                    return new Row(['class' => 'form-group'], [
                        new Node(
                            'label',
                            ['class' => 'col-sm-2 form-control-label'],
                            Maybe::fromMaybe(
                                '',
                                $this->spec
                                    ->getFieldLabel($key)
                                    ->bind($addColon)
                            )
                        ),
                        new Div(
                            ['class' => 'col-sm-8'],
                            $this->renderFullField($key)
                        ),
                    ]);
                })
                ->append(ArrayMap::of([
                    new Row(['class' => 'form-group'], [
                        new Div(['class' => 'col-sm-offset-2 col-sm-10'], [
                            new Div(['class' => 'btn-group'], [
                                new Button(
                                    [
                                        'type' => 'reset',
                                        'class' => 'btn btn-secondary',
                                    ],
                                    'Reset'
                                ),
                                new Button(
                                    [
                                        'type' => 'submit',
                                        'class' => 'btn btn-primary',
                                    ],
                                    'Submit'
                                ),
                            ]),
                        ]),
                    ]),
                ]))
        ))->render();
    }

    /**
     * Get a safe HTML version of the contents of this object.
     *
     * @return SafeHtmlWrapper
     */
    public function getSafeHtml()
    {
        return Html::safe($this->render());
    }
}
