<?php

class Bc_Download_Model_Observer
{
    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
        Mage::log('saveProductTabData',null,'bcdownload.log');

        $request = $observer->getEvent()->getRequest();
        $product = $observer->getEvent()->getProduct();

        if ($data = $request->getPost('extradownload')) {
            Mage::log($data,null,'bcdownload.log');

            if(isset($data['link'])){
                $_deleteItems = array();
                foreach ($data['link'] as $linkItem) {
                    if ($linkItem['is_delete'] == 1) {
                        if ($linkItem['link_id']) {
                            $_deleteItems[] = $linkItem['link_id'];
                        }
                    }
                    else{

                        unset($linkItem['is_delete']);
                        if (!$linkItem['link_id']) {
                            unset($linkItem['link_id']);
                        }
                        $files = array();
                        if (isset($linkItem['file'])) {
                            $files = Mage::helper('core')->jsonDecode($linkItem['file']);
                            unset($linkItem['file']);
                        }

                        $linkModel = Mage::getModel('download/link')->setData($linkItem)->setLinkType($linkItem['type'])->setProductId($product->getId())->setStoreId($product->getStoreId());

                        $linkFileName = Mage::helper('download/file')->moveFileFromTmp(
                            Bc_Download_Model_Link::getBaseTmpPath(),
                            Bc_Download_Model_Link::getBasePath(),
                            $files
                        );
                        $linkModel->setLinkFile($linkFileName);

                        $linkModel->save();

                    }
                }
                if ($_deleteItems) {
                    Mage::log('delete Items',null,'bcdownload.log');
                    Mage::log($_deleteItems,null,'bcdownload.log');
                    Mage::getResourceModel('download/link')->deleteItems($_deleteItems);
                }
            }

        }

        return $this;
    }

    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}