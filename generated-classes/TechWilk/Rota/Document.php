<?php  namespace TechWilk\Rota;

use TechWilk\Rota\Base\Document as BaseDocument;

/**
 * Skeleton subclass for representing a row from the 'cr_documents' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Document extends BaseDocument
{
    public function saveFile(\Psr\Http\Message\UploadedFileInterface $file)
    {
        if (!isset($file) || $file->getError() !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('File upload failed.');
        }
    //$acceptedFileExtensions = ['jpg', 'png', 'jpeg', 'gif', 'docx', 'pdf', 'xlsx', 'pptx'];
    $acceptedFileTypes = ['image/gif', 'image/png', 'image/jpeg', 'application/pdf', 'application/msword', 'application/excel'];
        $target_dir = __DIR__ . "/../documents/";
    $this->setUrl($file->getClientFilename());
    //$fileExtension = pathinfo(basename($file->getName()),PATHINFO_EXTENSION);

    // Allow certain file formats only
    if (!in_array($file->getClientMediaType(), $acceptedFileTypes)) {
        throw new InvalidArgumentException('Sorry, only ' . implode(', ', $acceptedFileTypes) . ' files are allowed.');
    }
        $this->save();
        $target_file = $target_dir . $this->getId();

    // Check if file already exists
    if (file_exists($target_file)) {
        throw new Exception("File already exists.");
    }

        $file->moveTo($target_file);
    }
}
