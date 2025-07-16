<?php

namespace KaspiQrSdk\Exception;

/**
 * Represents an exception that is thrown when authorization-related errors occur
 * within the Kaspi SDK.
 *
 * This exception typically indicates issues such as invalid credentials,
 * insufficient permissions, or authentication failures when interacting with the SDK.
 *
 * Extends the base KaspiSdkException to provide more specific context for
 * authorization errors.
 */
class AuthorisationException extends KaspiSdkException
{
}
