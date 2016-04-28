<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Conference\Views;

use Carbon\Carbon;
use SellerLabs\Illuminated\Conference\Views\FormSpecPresenter;
use SellerLabs\Nucleus\Meditation\Boa;
use SellerLabs\Nucleus\Meditation\FormSpec;
use SellerLabs\Nucleus\Testing\TestCase;
use SellerLabs\Nucleus\View\Common\Input;
use SellerLabs\Nucleus\View\Common\Option;
use SellerLabs\Nucleus\View\Common\Select;

/**
 * Class FormSpecPresenterTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Conference\Views
 */
class FormSpecPresenterTest extends TestCase
{
    public function testRenderFieldWithInArray()
    {
        $spec = (new FormSpec())
            ->withFieldConstraints('omg', Boa::in(['such', 'wow']));

        $presenter = new FormSpecPresenter($spec);

        $this->assertEquals(
            (new Select(['id' => 'omg'], [
                new Option(['value' => 'such'], 'such'),
                new Option(['value' => 'wow'], 'wow'),
            ]))->render(),
            $presenter->renderField('omg')->render()
        );
    }

    public function testRenderFieldWithPrimitives()
    {
        $spec = (new FormSpec())
            ->withFieldType('first_name', Boa::string())
            ->withFieldType('last_name', Boa::string())
            ->withFieldDefault('first_name', 'Bob')
            ->withFieldType('age', Boa::integer())
            ->withFieldType('focus', Boa::boolean())
            ->withFieldType('awesome', Boa::boolean())
            ->withFieldDefault('awesome', true)
            ->withFieldType('price', Boa::float());

        $presenter = new FormSpecPresenter($spec);

        $this->assertEquals(
            (new Input([
                'id' => 'first_name',
                'type' => 'text',
                'value' => 'Bob',
            ]))->render(),
            $presenter->renderField('first_name')->render()
        );
        $this->assertEquals(
            (new Input([
                'id' => 'last_name',
                'type' => 'text',
            ]))->render(),
            $presenter->renderField('last_name')->render()
        );
        $this->assertEquals(
            (new Input([
                'id' => 'age',
                'type' => 'number',
            ]))->render(),
            $presenter->renderField('age')->render()
        );
        $this->assertEquals(
            (new Input([
                'id' => 'focus',
                'type' => 'checkbox',
            ]))->render(),
            $presenter->renderField('focus')->render()
        );
        $this->assertEquals(
            (new Input([
                'id' => 'awesome',
                'type' => 'checkbox',
                'checked' => null,
            ]))->render(),
            $presenter->renderField('awesome')->render()
        );
        $this->assertEquals(
            (new Input([
                'id' => 'price',
                'type' => 'number',
            ]))->render(),
            $presenter->renderField('price')->render()
        );
    }

    public function testRenderFieldWithOther()
    {
        $spec = (new FormSpec())
            ->withFieldType('expire_at', Boa::instance(Carbon::class));

        $presenter = new FormSpecPresenter($spec);

        $this->assertEquals(
            (new Input([
                'id' => 'expire_at',
                'type' => 'text',
            ]))->render(),
            $presenter->renderField('expire_at')->render()
        );
    }
}
