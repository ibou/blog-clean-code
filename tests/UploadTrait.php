<?php


namespace App\Tests;

use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait UploadTrait
 * @package App\Tests
 */
trait UploadTrait
{

    /**
     * @return UploadedFile
     * @throws Exception
     */
    public static function createImage(): UploadedFile
    {
        $filename = uniqid().'.png';
        $format_dest = __DIR__.DIRECTORY_SEPARATOR."../public/uploads/%s";
        $file = sprintf($format_dest, $filename);
        copy(__DIR__.DIRECTORY_SEPARATOR.'images/image-test.jpg', $file);
        return new UploadedFile(
            $file,
            $filename,
            null,
            null,
            true
        );
    }

    /**
     * @return UploadedFile
     * @throws \Exception
     */
    public static function createTextFile(): UploadedFile
    {
        $filename = uniqid() . ".txt";

        copy(__DIR__ . '/files/file-test.txt', __DIR__ . '/../public/uploads/' . $filename);

        return new UploadedFile(
            __DIR__ . '/../public/uploads/' . $filename,
            $filename,
            null,
            null,
            true
        );
    }
}
