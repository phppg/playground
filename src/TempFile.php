<?php

namespace Playground;

use Safe\Exceptions\FilesystemException;
use function Safe\unlink;
use function file_exists;

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
