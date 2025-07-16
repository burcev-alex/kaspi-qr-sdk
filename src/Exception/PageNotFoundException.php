<?php

namespace KaspiQrSdk\Exception;

/**
 * Exception thrown when a requested page is not found within the Kaspi SDK context.
 * This exception is used to handle scenarios where a specific page or resource cannot be located,
 * allowing for consistent error handling and debugging within the SDK workflow.
 */
class PageNotFoundException extends KaspiSdkException
{
}
