<?php
class Bc_Download_Block_Adminhtml_Catalog_Product_ExtraDownload_General extends Mage_Adminhtml_Block_Template
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('download/catalog/product/extradownload/general.phtml');
    }

    public function getLinkData()
    {
        $linkArr = array();

        $links = null;

        $_linkCollection = Mage::getModel('download/link')->getCollection()->addFieldToFilter('product_id',$this->getProduct()->getId())->addTitleToResult($this->getProduct()->getStoreId()); ;
        $linksCollectionById = array();
        foreach ($_linkCollection as $link) {
            $linksCollectionById[$link->getId()] = $link;
        }

        foreach ($linksCollectionById as $item) {
            $tmpLinkItem = array(
                'link_id' => $item->getId(),
                'title' => $this->escapeHtml($item->getTitle()),
                'is_visible' => $item->getIsVisible(),
                'link_url' => $item->getLinkUrl(),
                'link_type' => $item->getLinkType(),
                'sort_order' => $item->getSortOrder(),
            );

            $file = Mage::helper('download/file')->getFilePath(
                Bc_Download_Model_Link::getBasePath(), $item->getLinkFile()
            );

            if ($item->getLinkFile() && !is_file($file)) {
                Mage::helper('core/file_storage_database')->saveFileToFilesystem($file);
            }

            if ($item->getLinkFile() && is_file($file)) {
                $name = '<a href="'
                    . $this->getUrl('*/download_product_edit/link', array(
                        'id' => $item->getId(),
                        '_secure' => true
                    )) . '">' . Mage::helper('download/file')->getFileFromPathFile($item->getLinkFile()) . '</a>';
                $tmpLinkItem['file_save'] = array(
                    array(
                        'file' => $item->getLinkFile(),
                        'name' => $name,
                        'size' => filesize($file),
                        'status' => 'old'
                    ));
            }

            $linkArr[] = new Varien_Object($tmpLinkItem);
        }

        return $linkArr;
    }


    protected function _prepareLayout()
    {
        $this->setChild(
            'upload_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->addData(array(
                'id'      => '',
                'label'   => Mage::helper('adminhtml')->__('Upload Files'),
                'type'    => 'button',
                'onclick' => 'Downloadable.massUploadByType(\'links\');'
            ))
        );
    }


    public function getAddButtonHtml()
    {
        $addButton = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label' => Mage::helper('download')->__('Add New Row'),
                'id'    => 'extradownload_add_link_item',
                'class' => 'add'
            ));
        return $addButton->toHtml();
    }

    public function getConfigJson($type='links')
    {
        $this->getConfig()->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/download_file/upload', array('type' => $type, '_secure' => true)));
        $this->getConfig()->setParams(array('form_key' => $this->getFormKey()));
        $this->getConfig()->setFileField($type);
        $this->getConfig()->setFilters(array(
            'all'    => array(
                'label' => Mage::helper('adminhtml')->__('All Files'),
                'files' => array('*.*')
            )
        ));
        $this->getConfig()->setReplaceBrowseWithRemove(true);
        $this->getConfig()->setWidth('32');
        $this->getConfig()->setHideUploadButton(true);
        return Mage::helper('core')->jsonEncode($this->getConfig()->getData());
    }

    public function getConfig()
    {
        if(is_null($this->_config)) {
            $this->_config = new Varien_Object();
        }

        return $this->_config;
    }

    /**
     * Retrieve Upload button HTML
     *
     * @return string
     */
    public function getUploadButtonHtml()
    {
        return $this->getChild('upload_button')->toHtml();
    }

    public function getProduct()
    {
        return Mage::registry('product');
    }


}