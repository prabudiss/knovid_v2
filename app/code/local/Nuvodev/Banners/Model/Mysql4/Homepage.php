<?php
class Nuvodev_Banners_Model_Mysql4_Homepage extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("banners/homepage", "id");
    }
}