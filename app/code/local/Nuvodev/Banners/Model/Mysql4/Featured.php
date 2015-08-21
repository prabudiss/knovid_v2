<?php
class Nuvodev_Banners_Model_Mysql4_Featured extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("banners/featured", "id");
    }
}