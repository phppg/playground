<?php

declare(strict_types=1);

namespace Playground;

use Safe\Exceptions\FilesystemException;
use function file_exists;
use function Safe\unlink;

final readonly class TempFile extends File
{
    public function __destruct()
    {
        try {
            if (file_exists($this->path)) {
                unlink($this->path);
            }
        } catch (FilesystemException) {
            // noop
        }
    }
}
