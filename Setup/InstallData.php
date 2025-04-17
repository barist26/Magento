<?php
/**
 * Pdf_Lookbook InstallData
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;

class InstallData implements InstallDataInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(
        Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
    }

    /**
     * Install data
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        try {
            // Create media directories
            $this->createMediaDirectories();
        } catch (\Exception $e) {
            // Log the error but don't stop installation
            // This allows the module to be installed even if directory creation fails
            // The directories can be created manually later
        }

        $setup->endSetup();
    }

    /**
     * Create media directories
     *
     * @return void
     * @throws FileSystemException
     */
    private function createMediaDirectories()
    {
        try {
            $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);

            // Create lookbook directory
            $lookbookDir = 'lookbook';
            if (!$mediaDirectory->isExist($lookbookDir)) {
                $mediaDirectory->create($lookbookDir);
            }

            // Create covers directory
            $coversDir = 'lookbook/covers';
            if (!$mediaDirectory->isExist($coversDir)) {
                $mediaDirectory->create($coversDir);
            }

            // Create tmp directory
            $tmpDir = 'lookbook/tmp';
            if (!$mediaDirectory->isExist($tmpDir)) {
                $mediaDirectory->create($tmpDir);
            }

            // Set permissions
            $mediaDirectory->changePermissions($lookbookDir, 0755);
            $mediaDirectory->changePermissions($coversDir, 0755);
            $mediaDirectory->changePermissions($tmpDir, 0755);
        } catch (FileSystemException $e) {
            throw new FileSystemException(
                __('Could not create media directories: %1', $e->getMessage())
            );
        }
    }
}
