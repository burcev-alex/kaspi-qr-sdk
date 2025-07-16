<?php

namespace KaspiQrSdk\Exception;

/**
 * Represents an exception thrown when an unknown or invalid token is encountered within the Kaspi SDK context.
 *
 * This exception is a specialization of the `KaspiSdkException` class and is used to indicate specific errors
 * related to token processing that could not be identified or validated.
 *
 * Typically, this exception should be used in situations where the provided token fails to meet required validation rules
 * or cannot be recognized by the system.
 */
class UnknownTokenException extends KaspiSdkException
{
}
