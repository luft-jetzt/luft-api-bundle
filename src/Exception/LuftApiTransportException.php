<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Exception;

/**
 * Raised when the request to the Luft API could not be completed at the
 * transport level (connection refused, DNS failure, timeout, ...).
 */
class LuftApiTransportException extends LuftApiException
{
}
