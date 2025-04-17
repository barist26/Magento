<?php
/**
 * Pdf_Lookbook LookbookInterface
 *
 * @category  Pdf
 * @package   Pdf_Lookbook
 */

namespace Pdf\Lookbook\Api\Data;

interface LookbookInterface
{
    /**
     * Constants for keys of data array
     */
    const ENTITY_ID = 'entity_id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const PDF_FILE = 'pdf_file';
    const COVER_IMAGE = 'cover_image';
    const IS_ACTIVE = 'is_active';
    const SORT_ORDER = 'sort_order';
    const STORE_ID = 'store_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get PDF file
     *
     * @return string|null
     */
    public function getPdfFile();

    /**
     * Set PDF file
     *
     * @param string $pdfFile
     * @return $this
     */
    public function setPdfFile($pdfFile);

    /**
     * Get cover image
     *
     * @return string|null
     */
    public function getCoverImage();

    /**
     * Set cover image
     *
     * @param string $coverImage
     * @return $this
     */
    public function setCoverImage($coverImage);

    /**
     * Get is active
     *
     * @return bool|null
     */
    public function getIsActive();

    /**
     * Set is active
     *
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive($isActive);

    /**
     * Get sort order
     *
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * Get store id
     *
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store id
     *
     * @param string $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}
