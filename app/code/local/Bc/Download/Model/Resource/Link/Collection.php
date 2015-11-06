<?php
class Bc_Download_Model_Resource_Link_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('download/link','link_id');
    }

    public function addTitleToResult($storeId = 0)
    {
        $ifNullDefaultTitle = $this->getConnection()
            ->getIfNullSql('st.title', 'd.title');
        $this->getSelect()
            ->joinLeft(array('d' => $this->getTable('download/link_title')),
                'd.link_id=main_table.link_id AND d.store_id = 0',
                array('default_title' => 'title'))
            ->joinLeft(array('st' => $this->getTable('download/link_title')),
                'st.link_id=main_table.link_id AND st.store_id = ' . (int)$storeId,
                array('store_title' => 'title','title' => $ifNullDefaultTitle))
            ->order('main_table.sort_order ASC')
            ->order('title ASC');

        return $this;
    }

}