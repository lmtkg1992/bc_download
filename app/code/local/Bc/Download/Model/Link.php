<?php
class Bc_Download_Model_Link extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('download/link');
    }

    public static function getBaseTmpPath()
    {
        return Mage::getBaseDir('media') . DS . 'download' . DS . 'tmp' . DS . 'links';
    }

    public static function getBasePath()
    {
        return Mage::getBaseDir('media') . DS . 'download' . DS . 'files' . DS . 'links';
    }

    protected function _afterSave()
    {
        $this->getResource()->saveItemTitle($this);
        return parent::_afterSave();
    }

    public function getSearchableData($productId, $storeId)
    {
        return $this->_getResource()->getSearchableData($productId, $storeId);
    }



}