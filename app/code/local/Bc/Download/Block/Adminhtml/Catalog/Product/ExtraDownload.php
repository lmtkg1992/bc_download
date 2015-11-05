<?php

class Bc_Download_Block_Adminhtml_Catalog_Product_ExtraDownload extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface{

    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('download/catalog/product/extradownload.phtml');
    }

    public function isReadonly()
    {
        return $this->getProduct()->getDownloadableReadonly();
    }

    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Extra Downloads');
    }

    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view extra downloads');
    }
    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    public function getProduct()
    {
        return Mage::registry('current_product');
    }


    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    protected function _toHtml()
    {
        $accordion = $this->getLayout()->createBlock('adminhtml/widget_accordion')
            ->setId('extraDownloadInfo');

        $accordion->addItem('general', array(
            'title'   => Mage::helper('adminhtml')->__('General'),
            'content' => $this->getLayout()
                ->createBlock('download/adminhtml_catalog_product_extradownload_general')->toHtml(),
            'open'    => true,
        ));

   /*     $accordion->addItem('statistics', array(
            'title'   => Mage::helper('adminhtml')->__('Statistics'),
            'content' => $this->getLayout()->createBlock(
                'download/adminhtml_catalog_product_extradownload_statistics')->toHtml(),
            'open'    => true,
        ));*/

        $this->setChild('accordion', $accordion);

        return parent::_toHtml();
    }

}