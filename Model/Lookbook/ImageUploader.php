<?php
/**
 * Pdf_Lookbook ImageUploader
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Model\Lookbook;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ImageUploader
{
    /**
     * Core file storage database
     *
     * @var Database
     */
    protected $coreFileStorageDatabase;

    /**
     * Media directory object (writable)
     *
     * @var WriteInterface
     */
    protected $mediaDirectory;

    /**
     * Uploader factory
     *
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Base tmp path
     *
     * @var string
     */
    protected $baseTmpPath;

    /**
     * Base path for PDFs
     *
     * @var string
     */
    protected $basePdfPath;

    /**
     * Base path for cover images
     *
     * @var string
     */
    protected $baseCoverPath;

    /**
     * Allowed extensions
     *
     * @var array
     */
    protected $allowedPdfExtensions;

    /**
     * Allowed image extensions
     *
     * @var array
     */
    protected $allowedImageExtensions;

    /**
     * @param Database $coreFileStorageDatabase
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = 'lookbook/tmp';
        $this->basePdfPath = 'lookbook';
        $this->baseCoverPath = 'lookbook/covers';
        $this->allowedPdfExtensions = ['pdf'];
        $this->allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    }

    /**
     * Set base tmp path
     *
     * @param string $baseTmpPath
     * @return void
     */
    public function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * Set base path
     *
     * @param string $basePath
     * @param string $type
     * @return void
     */
    public function setBasePath($basePath, $type = 'pdf')
    {
        if ($type === 'pdf') {
            $this->basePdfPath = $basePath;
        } else {
            $this->baseCoverPath = $basePath;
        }
    }

    /**
     * Set allowed extensions
     *
     * @param array $allowedExtensions
     * @param string $type
     * @return void
     */
    public function setAllowedExtensions($allowedExtensions, $type = 'pdf')
    {
        if ($type === 'pdf') {
            $this->allowedPdfExtensions = $allowedExtensions;
        } else {
            $this->allowedImageExtensions = $allowedExtensions;
        }
    }

    /**
     * Retrieve base tmp path
     *
     * @return string
     */
    public function getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    /**
     * Retrieve base path
     *
     * @param string $type
     * @return string
     */
    public function getBasePath($type = 'pdf')
    {
        return $type === 'pdf' ? $this->basePdfPath : $this->baseCoverPath;
    }

    /**
     * Retrieve allowed extensions
     *
     * @param string $type
     * @return array
     */
    public function getAllowedExtensions($type = 'pdf')
    {
        return $type === 'pdf' ? $this->allowedPdfExtensions : $this->allowedImageExtensions;
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * Checking file for moving and move it
     *
     * @param string $imageName
     * @param string $type
     * @return string
     * @throws LocalizedException
     */
    public function moveFileFromTmp($imageName, $type = 'pdf')
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath($type);

        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }

        return $imageName;
    }

    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     * @param string $type
     * @return array
     * @throws LocalizedException
     */
    public function saveFileToTmpDir($fileId, $type = 'pdf')
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $allowedExtensions = $this->getAllowedExtensions($type);

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($allowedExtensions);
        $uploader->setAllowRenameFiles(true);

        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));

        if (!$result) {
            throw new LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        /**
         * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
         */
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['url'] = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }

        return $result;
    }
}
