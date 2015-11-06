<?php
class Bc_Download_Model_Resource_Link extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('download/link', 'link_id');
    }

    public function deleteItems($items)
    {
        $writeAdapter   = $this->_getWriteAdapter();
        $where = array();
        if ($items instanceof Mage_Download_Model_Link) {
            $where = array('link_id = ?' => $items->getId());
        }elseif (is_array($items)) {
            $where = array('link_id in (?)' => $items);
        }

        if ($where) {
            $writeAdapter->delete($this->getMainTable(), $where);
        }

        return $this;
    }


    public function saveItemTitle($linkObject)
    {
        Mage::log('saveItemTitle',null,'bcdownload.log');
        Mage::log($linkObject,null,'bcdownload.log');

        $writeAdapter   = $this->_getWriteAdapter();
        $linkTitleTable = $this->getTable('download/link_title');

        $select = $writeAdapter->select()->from($this->getTable('download/link_title'))->where('link_id=:link_id AND store_id=:store_id');
        $bind = array(
            ':link_id'   => $linkObject->getId(),
            ':store_id'  => (int)$linkObject->getStoreId()
        );

        if ($writeAdapter->fetchOne($select, $bind)) {
            $where = array(
                'link_id = ?'  => $linkObject->getId(),
                'store_id = ?' => (int)$linkObject->getStoreId()
            );
                $insertData = array('title' => $linkObject->getTitle());
                $writeAdapter->update($linkTitleTable,$insertData,$where);
        } else {
                $writeAdapter->insert($linkTitleTable,
                    array(
                        'link_id'   => $linkObject->getId(),
                        'store_id'  => (int)$linkObject->getStoreId(),
                        'title'     => $linkObject->getTitle(),
                    ));
        }

        return $this;
    }

    public function getSearchableData($productId, $storeId)
    {
        $adapter    = $this->_getReadAdapter();
        $ifNullDefaultTitle = $adapter->getIfNullSql('st.title', 's.title');
        $select = $adapter->select()
            ->from(array('m' => $this->getMainTable()), null)
            ->join(
                array('s' => $this->getTable('download/link_title')),
                's.link_id=m.link_id AND s.store_id=0',
                array())
            ->joinLeft(
                array('st' => $this->getTable('download/link_title')),
                'st.link_id=m.link_id AND st.store_id=:store_id',
                array('title' => $ifNullDefaultTitle))
            ->where('m.product_id=:product_id');
        $bind = array(
            ':store_id'   => (int)$storeId,
            ':product_id' => $productId
        );

        return $adapter->fetchCol($select, $bind);
    }


}