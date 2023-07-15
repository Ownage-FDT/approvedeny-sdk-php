<?php

declare(strict_types=1);

namespace Ownage\Approvedeny\Exceptions;

use Exception;
use Throwable;

/**
 * @codeCoverageIgnore
 */
final class ClientException extends Exception implements BaseException
{
    public const MSG_INVALID_API_KEY = 'The approvedeny sdk requires a api key key to be provided at initialization';
    public const MSG_INAVLID_ENCRYPTION_KEY = 'The approvedeny sdk requires a encryption key to be provided at initialization';

    public static function invalidApiKey(Throwable $previous = null): self
    {
        return new self(self::MSG_INVALID_API_KEY, 0, $previous);
    }

    public static function invalidEncryptionKey(Throwable $previous = null): self
    {
        return new self(self::MSG_INAVLID_ENCRYPTION_KEY, 0, $previous);
    }
}
