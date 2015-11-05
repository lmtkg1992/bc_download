<?php
class Bc_Download_Block_Adminhtml_Catalog_Product_ExtraDownload_General extends Mage_Adminhtml_Block_Template
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('download/catalog/product/extradownload/general.phtml');
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
                'label' => Mage::helper('downloadable')->__('Add New Row'),
                'id'    => 'extradownload_add_link_item',
                'class' => 'add'
            ));
        return $addButton->toHtml();
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