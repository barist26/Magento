<?php
/**
 * Pdf_Lookbook DataProvider
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Model\Lookbook;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Pdf\Lookbook\Model\ResourceModel\Lookbook\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Pdf\Lookbook\Model\Lookbook $lookbook */
        foreach ($items as $lookbook) {
            $this->loadedData[$lookbook->getId()] = $lookbook->getData();
            
            if ($lookbook->getPdfFile()) {
                $pdfFile = $lookbook->getPdfFile();
                $this->loadedData[$lookbook->getId()]['pdf_file'] = [
                    [
                        'name' => $pdfFile,
                        'url' => $this->getMediaUrl('lookbook/' . $pdfFile),
                        'size' => $this->getFileSize('lookbook/' . $pdfFile),
                        'type' => 'application/pdf'
                    ]
                ];
            }
            
            if ($lookbook->getCoverImage()) {
                $coverImage = $lookbook->getCoverImage();
                $this->loadedData[$lookbook->getId()]['cover_image'] = [
                    [
                        'name' => $coverImage,
                        'url' => $this->getMediaUrl('lookbook/covers/' . $coverImage),
                        'size' => $this->getFileSize('lookbook/covers/' . $coverImage),
                        'type' => $this->getFileType($coverImage)
                    ]
                ];
            }
            
            if ($lookbook->getStoreId()) {
                $this->loadedData[$lookbook->getId()]['store_id'] = explode(',', $lookbook->getStoreId());
            }
        }

        $data = $this->dataPersistor->get('pdf_lookbook');
        if (!empty($data)) {
            $lookbook = $this->collection->getNewEmptyItem();
            $lookbook->setData($data);
            $this->loadedData[$lookbook->getId()] = $lookbook->getData();
            $this->dataPersistor->clear('pdf_lookbook');
        }

        return $this->loadedData;
    }

    /**
     * Get media URL
     *
     * @param string $path
     * @return string
     */
    protected function getMediaUrl($path)
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $path;
    }

    /**
     * Get file size
     *
     * @param string $path
     * @return int
     */
    protected function getFileSize($path)
    {
        $mediaPath = $this->storeManager->getStore()->getBaseMediaDir() . '/' . $path;
        if (file_exists($mediaPath)) {
            return filesize($mediaPath);
        }
        return 0;
    }

    /**
     * Get file type
     *
     * @param string $filename
     * @return string
     */
    protected function getFileType($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif'
        ];
        
        return isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';
    }
}
