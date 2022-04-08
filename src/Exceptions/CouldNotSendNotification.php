<?php

namespace Meshgroup\Megafon\Exceptions;

use Exception;
use DomainException;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when content length is greater than 800 characters.
     *
     * @return static
     */
    public static function contentLengthLimitExceeded()
    {
        return new static(
            'Notification was not sent. Content length may not be greater than 800 characters.'
        );
    }

    /**
     * Thrown when we're unable to communicate with megafon.
     *
     * @param  DomainException  $exception
     *
     * @return static
     */
    public static function megafonRespondedWithAnError(DomainException $exception)
    {
        return new static(
            "megafon responded with an error '{$exception->getCode()}: {$exception->getMessage()}'",
            $exception->getCode(),
            $exception
        );
    }

    /**
     * Thrown when we're unable to communicate with Megafon.
     *
     * @param  Exception  $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithMegafon(Exception $exception)
    {
        return new static(
            "The communication with megafon failed. Reason: {$exception->getMessage()}",
            $exception->getCode(),
            $exception
        );
    }
}
