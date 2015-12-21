<?php

namespace Chromabits\Illuminated\Raml\Security;

use Chromabits\Illuminated\Raml\Enums\RamlTypes;
use Chromabits\Illuminated\Raml\RamlParameter;

/**
 * Class HmacScheme.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml\Security
 */
class HmacScheme extends SecurityScheme
{
    /**
     * Construct an instance of a HmacScheme.
     */
    public function __construct()
    {
        parent::__construct();

        $this->description = 'This API supports HMAC for authenticating ' .
            'requests.';
        $this->type = 'x-HMAC';

        $this->headers = [
            'Authorization' => (new RamlParameter())
                ->setDescription(
                    'Used to send a valid signature hash of the request ' .
                    'body. Expected format: `Hash <hash>`'
                )
                ->setType(RamlTypes::TYPE_STRING),
        ];
    }
}