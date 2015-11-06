<?php


class Bc_Download_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $_linkCollection = Mage::getModel('download/link')->getCollection()->addFieldToFilter('product_id',$this->getProduct()->getId()) ;


        $data = array(
            'link' => array(
                array(
                    'is_delete' => 0,
                    'link_id' => 0,
                    'title' => 'aaaa',
                    'is_visible' => 1,
                    'type' => 'file',
                    'file' => '[{"file":"/p/d/pdf-sample_2.pdf","name":"pdf-sample.pdf","size":7945,"status":"new"}]',
                    'link_url' => '',
                    'sort_order' => '',
                )
            )

        );



    }
}