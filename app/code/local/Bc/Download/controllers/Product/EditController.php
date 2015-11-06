<?php

require_once 'Bc/Download/controllers/Adminhtml/Download/Product/EditController.php';

/**
 * Adminhtml downloadable product edit
 *
 * @category    Mage
 * @package     Mage_Downloadable
 * @author      Magento Core Team <core@magentocommerce.com>
 * @deprecated  after 1.4.2.0 Mage_Downloadable_Adminhtml_Downloadable_Product_EditController is used
 */
class Bc_Download_Product_EditController extends Bc_Download_Adminhtml_Download_Product_EditController
{
    /**
     * Controller predispatch method
     * Show 404 front page
     */
    public function preDispatch()
    {
        $this->_forward('defaultIndex', 'cms_index');
    }
}
