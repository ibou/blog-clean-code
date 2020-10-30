<?php


namespace App\Uploader;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader implements UploaderInterface
{

    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;
    /**
     * @var string
     */
    private string $uploadsRelativeDir;
    /**
     * @var string
     */
    private string $uploadsAbsoluteDir;

    /**
     * Uploader constructor.
     * @param SluggerInterface $slugger
     * @param string $uploadsRelativeDir
     * @param string $uploadsAbsoluteDir
     */
    public function __construct(SluggerInterface $slugger, string $uploadsRelativeDir, string $uploadsAbsoluteDir)
    {
        $this->slugger = $slugger;
        $this->uploadsRelativeDir = $uploadsRelativeDir;
        $this->uploadsAbsoluteDir = $uploadsAbsoluteDir;
    }


    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $filename = sprintf(
            '%s_%s.%s',
            $this->slugger->slug($originalFilename),
            uniqid(),
            $file->getClientOriginalExtension()
        );
        $file->move($this->uploadsAbsoluteDir, $filename);
        return $this->uploadsRelativeDir.DIRECTORY_SEPARATOR.$filename;
    }
}