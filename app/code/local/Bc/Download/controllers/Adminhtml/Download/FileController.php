<?php
class Bc_Download_Adminhtml_Download_FileController extends Mage_Adminhtml_Controller_Action{
    /**
     * Upload file controller action
     */
    public function uploadAction()
    {
        $type = $this->getRequest()->getParam('type');

        $tmpPath = Bc_Download_Model_Link::getBaseTmpPath();

        $result = array();

        try {
            $uploader = new Mage_Core_Model_File_Uploader($type);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save($tmpPath);

            /**
             * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
             */
            $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
            $result['path'] = str_replace(DS, "/", $result['path']);

            if (isset($result['file'])) {
                $fullPath = rtrim($tmpPath, DS) . DS . ltrim($result['file'], DS);
                Mage::helper('core/file_storage_database')->saveFile($fullPath);
            }

            $result['cookie'] = array(
                'name'     => session_name(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage(), 'errorcode'=>$e->getCode());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/products');
    }
}