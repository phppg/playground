<?php

namespace Playground;

use function file_exists;
use Safe\Exceptions\FilesystemException;
use function Safe\unlink;

final class TempFile extends File
{
    public function __destruct()
    {
        try {
            if (file_exists($this->path)) {
                unlink($this->path);
            }
        } catch (FilesystemException $e) {
            // noop
        }
    }
}
