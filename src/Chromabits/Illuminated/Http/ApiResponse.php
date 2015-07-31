<?php

namespace Chromabits\Illuminated\Http;

use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Meditation\Arguments;
use Chromabits\Nucleus\Meditation\Constraints\EitherConstraint;
use Chromabits\Nucleus\Meditation\Constraints\InArrayConstraint;
use Chromabits\Nucleus\Meditation\Constraints\PrimitiveTypeConstraint;
use Chromabits\Nucleus\Meditation\Exceptions\InvalidArgumentException;
use Chromabits\Nucleus\Meditation\Primitives\CompoundTypes;
use Chromabits\Nucleus\Meditation\Primitives\ScalarTypes;
use Chromabits\Nucleus\Meditation\SpecResult;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiResponse
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http
 */
class ApiResponse extends BaseObject
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    const STATUS_INVALID = 'invalid';
    const STATUS_UNAUTHORIZED = 'unauthorized';
    const STATUS_FORBIDDEN = 'forbidden';
    const STATUS_NOT_FOUND = 'not_found';

    protected $content;

    protected $status;

    protected $messages;

    /**
     * Construct an instance of an API response.
     *
     * @param mixed $content
     * @param string $status
     * @param array $messages
     */
    public function __construct($content, $status = 'success', $messages = [])
    {
        Arguments::contain(
            PrimitiveTypeConstraint::forType(CompoundTypes::COMPOUND_ARRAY),
            new InArrayConstraint($this->getValidStatuses()),
            PrimitiveTypeConstraint::forType(CompoundTypes::COMPOUND_ARRAY)
        )->check($content, $status, $messages);

        $this->content = $content;
        $this->status = $status;
        $this->messages = $messages;
    }

    /**
     * Construct an instance of an API response.
     *
     * @param mixed $content
     * @param string $status
     * @param array $messages
     *
     * @return static
     */
    public static function create (
        $content,
        $status = 'success',
        $messages = []
    ) {
        return new static($content, $status, $messages);
    }

    /**
     * Construct an instance of an API response.
     *
     * @param mixed $content
     * @param string $status
     * @param array $messages
     *
     * @return Response
     */
    public static function send (
        $content,
        $status = 'success',
        $messages = []
    ) {
        return (new static($content, $status, $messages))->toResponse();
    }

    /**
     * Create a resource not found response.
     *
     * @param string $name
     * @param integer|string $identifier
     *
     * @return static
     */
    public static function makeNotFound($name, $identifier)
    {
        return new static([
            'provided_id' => $identifier,
            'resource_name' => $name,
        ], static::STATUS_NOT_FOUND, [
            vsprintf(
                'The resource \'%s\' with the identifier \'%s\' could not be'
                . ' found.',
                [$name, $identifier]
            )
        ]);
    }

    /**
     * Create a validation response out of a SpecResult.
     *
     * @param SpecResult $result
     *
     * @return static
     * @throws LackOfCoffeeException
     */
    public static function makeFromSpec(SpecResult $result)
    {
        if ($result->passed()) {
            throw new LackOfCoffeeException(
                'You are trying to send a validation error response,'
                . ' but your validation actually passed!'
            );
        }

        return new static([
            'missing' => $result->getMissing(),
            'validation' => $result->getFailed(),
        ], static::STATUS_INVALID, [
            'One or more fields are invalid. Please check your input.'
        ]);
    }

    /**
     * Get list of possible statuses.
     *
     * @return string[]
     */
    public static function getValidStatuses()
    {
        return [
            static::STATUS_SUCCESS,
            static::STATUS_ERROR,
            static::STATUS_INVALID,
            static::STATUS_UNAUTHORIZED,
            static::STATUS_FORBIDDEN,
            static::STATUS_NOT_FOUND,
        ];
    }

    /**
     * Get list of reserved keys.
     *
     * @return array
     */
    public static function getReservedKeys()
    {
        return ['status', 'code', 'messages'];
    }

    /**
     * Add a message to the response.
     *
     * @param string $message
     *
     * @throws InvalidArgumentException
     */
    public function addMessage($message)
    {
        Arguments::contain(
            PrimitiveTypeConstraint::forType(ScalarTypes::SCALAR_STRING)
        )->check($message);

        $this->messages[] = $message;
    }

    /**
     * Set a key in the response.
     *
     * @param string $key
     * @param string|array $value
     *
     * @throws InvalidArgumentException
     */
    public function set($key, $value)
    {
        Arguments::contain(
            PrimitiveTypeConstraint::forType(ScalarTypes::SCALAR_STRING),
            EitherConstraint::create(
                PrimitiveTypeConstraint::forType(ScalarTypes::SCALAR_STRING),
                PrimitiveTypeConstraint::forType(CompoundTypes::COMPOUND_ARRAY)
            )
        )->check($key, $value);

        if (in_array($key, static::getReservedKeys())) {
            throw new InvalidArgumentException(
                'This response key is reserved.'
            );
        }

        $this->content[$key] = $value;
    }

    /**
     * Set the new status for this response.
     *
     * @param string $newStatus
     *
     * @throws InvalidArgumentException
     */
    public function setStatus($newStatus)
    {
        Arguments::contain(new InArrayConstraint($this->getValidStatuses()))
            ->check($newStatus);

        $this->status = $newStatus;
    }

    /**
     * Get the HTTP status code for this response.
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        switch ($this->status) {
            case static::STATUS_ERROR:
                return Response::HTTP_INTERNAL_SERVER_ERROR;
            case static::STATUS_INVALID:
                return Response::HTTP_BAD_REQUEST;
            case static::STATUS_UNAUTHORIZED:
                return Response::HTTP_UNAUTHORIZED;
            case static::STATUS_FORBIDDEN:
                return Response::HTTP_FORBIDDEN;
            case static::STATUS_NOT_FOUND:
                return Response::HTTP_NOT_FOUND;
            case static::STATUS_SUCCESS:
            default:
                return Response::HTTP_OK;
        }
    }

    /**
     * Generate a Symfony response.
     *
     * @param array $headers
     *
     * @return Response
     */
    public function toResponse(array $headers = [])
    {
        $code = $this->getHttpStatusCode();

        return Response::create(array_merge([
            'code' => $code,
            'status' => $this->status,
            'messages' => $this->messages,
        ], $this->content), $code, $headers);
    }
}
