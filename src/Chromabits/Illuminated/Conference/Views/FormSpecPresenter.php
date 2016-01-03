<?php

namespace Chromabits\Illuminated\Conference\Views;

use Chromabits\Nucleus\Control\Maybe;
use Chromabits\Nucleus\Data\ArrayList;
use Chromabits\Nucleus\Data\ArrayMap;
use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Meditation\Constraints\AbstractConstraint;
use Chromabits\Nucleus\Meditation\Constraints\InArrayConstraint;
use Chromabits\Nucleus\Meditation\Constraints\PrimitiveTypeConstraint;
use Chromabits\Nucleus\Meditation\FormSpec;
use Chromabits\Nucleus\Meditation\Primitives\ScalarTypes;
use Chromabits\Nucleus\Support\Html;
use Chromabits\Nucleus\View\Bootstrap\Row;
use Chromabits\Nucleus\View\Common\Form;
use Chromabits\Nucleus\View\Common\Input;
use Chromabits\Nucleus\View\Common\Option;
use Chromabits\Nucleus\View\Common\Paragraph;
use Chromabits\Nucleus\View\Common\Select;
use Chromabits\Nucleus\View\Common\Small;
use Chromabits\Nucleus\View\Interfaces\RenderableInterface;
use Chromabits\Nucleus\View\Interfaces\SafeHtmlProducerInterface;
use Chromabits\Nucleus\View\Node;
use Chromabits\Nucleus\View\SafeHtmlWrapper;

/**
 * Class FormSpecPresenter.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Views
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
                    ['id' => $fieldName],
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
                'id' => $fieldName
            ]);

            switch($type->toString()) {
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
            $nodes = $nodes->append(ArrayList::of(
                new Paragraph([], new Small(['class' => 'text-muted'], [
                    Maybe::fromJust(
                        $this->spec->getFieldDescription($fieldName)
                    )
                ]))
            ));
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
            return vsprintf('%s:', [$label]);
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
                        $this->renderFullField($key)
                    ]);
                })
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