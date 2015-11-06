<?php

require_once 'Mage/Adminhtml/controllers/Catalog/ProductController.php';

class Bc_Download_Adminhtml_Download_Product_EditController extends Mage_Adminhtml_Catalog_ProductController
{

    /**
     * Varien class constructor
     *
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Bc_Download');
    }

    public function formAction()
    {
        $this->_initProduct();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('download/adminhtml_catalog_product_extradownload', 'admin.product.download.information')
                ->toHtml()
        );
    }

    /**
     * Download process
     *
     * @param string $resource
     * @param string $resourceType
     */
    protected function _processDownload($resource, $resourceType)
    {
        $helper = Mage::helper('download/download');

        $helper->setResource($resource, $resourceType);

        $fileName       = $helper->getFilename();
        $contentType    = $helper->getContentType();

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true);

        if ($fileSize = $helper->getFilesize()) {
            $this->getResponse()
                ->setHeader('Content-Length', $fileSize);
        }

        if ($contentDisposition = $helper->getContentDisposition()) {
            $this->getResponse()
                ->setHeader('Content-Disposition', $contentDisposition . '; filename='.$fileName);
        }

        $this->getResponse()
            ->clearBody();
        $this->getResponse()
            ->sendHeaders();

        $helper->output();
    }

    /**
     * Download link action
     *
     */
    public function linkAction()
    {                Mage::log('linkAction',null,'bcdownload.log');

        $linkId = $this->getRequest()->getParam('id', 0);
        $link = Mage::getModel('download/link')->load($linkId);
        if ($link->getId()) {
            $resource = '';
            $resourceType = '';
            if ($link->getLinkType() == Bc_Download_Helper_Download::LINK_TYPE_URL) {
                $resource = $link->getLinkUrl();
                $resourceType = Bc_Download_Helper_Download::LINK_TYPE_URL;
            } elseif ($link->getLinkType() == Bc_Download_Helper_Download::LINK_TYPE_FILE) {
                $resource = Mage::helper('download/file')->getFilePath(
                    Bc_Download_Model_Link::getBasePath(), $link->getLinkFile()
                );
                $resourceType = Bc_Download_Helper_Download::LINK_TYPE_FILE;
            }
            try {
                $this->_processDownload($resource, $resourceType);
            } catch (Mage_Core_Exception $e) {
                Mage::log($e->getMessages(),null,'bcdownload.log');
                $this->_getCustomerSession()->addError(Mage::helper('download')->__('An error occurred while getting the requested content.'));
            }
        }
        exit(0);
    }

}
