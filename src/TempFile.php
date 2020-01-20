<?php

namespace Playground;

use Safe\Exceptions\FilesystemException;
use function Safe\unlink;
use function file_exists;

final class TempFile extends File
{
    /**
     * Create file object from Code
     *
     * @return static
     */
    public static function fromCode(string $path, Code $code): self
    {
        return new self($path, $code);
    }

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
