<?php

/**
 * Magegiant
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the magegiant.com license that is
 * available through the world-wide-web at this URL:
 * http://magegiant.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magegiant
 * @package     Magegiant_SocialLogin
 * @copyright   Copyright (c) 2014 Magegiant (http://magegiant.com/)
 * @license     http://magegiant.com/license-agreement/
 */

/**
 * SocialLogin Index Controller
 *
 * @category    Magegiant
 * @package     Magegiant_SocialLogin
 * @author      Magegiant Developer
 */
class Magegiant_SocialLogin_FbloginController extends Mage_Core_Controller_Front_Action
{

    private function _createCustomerAddress($customer) {

      Mage::Log ("Year :::  " . $customer->getYear());

      //Validate and fill default values.
      $year = ($customer->getYear() ? $customer->getYear() : "9999");
      $course = ($customer->getCourse() ? $customer->getCourse() : "NA");
      $college = ($customer->getCollege() ? $customer->getCollege() : "NA");
      $place = ($customer->getPlace() ? $customer->getPlace() : "NA");
      $phone = ($customer->getPhone() ? $customer->getPhone() : "9999999999");

        $_custom_address = array (
          'firstname' => $customer->getFirstname(),
          'lastname' => $customer->getLastname(),
          'street' => array (
              '0' =>  $year . ", " . $course,
              '1' => $college,
          ),
          'city' => $place,
          'region_id' => '',
          'region' => '',
          'postcode' => '0',
          'country_id' => 'IND', /* Croatia */
          'telephone' => $phone,
        );
        $customAddress = Mage::getModel('customer/address');

        $customAddress->setData($_custom_address)
                  ->setCustomerId($customer->getId())
                  ->setIsDefaultBilling('1')
                  ->setIsDefaultShipping('1')
                  ->setSaveInAddressBook('1');
        try {
          $customAddress->save();
          Mage::Log("Adding information success!!!");
        }
        catch (Exception $ex) {
          //Zend_Debug::dump($ex->getMessage());
        Mage::Log("Error in Address " . $ex->getMessage());
        }
        return;
    }

    /**
     * Facebook login callBack
     */
    public function callbackAction()
    {
        $isAuth   = $this->getRequest()->getParam('auth');
        $facebook = Mage::getModel('sociallogin/channel_fb')->newFb();
        $userId   = $facebook->getUser();
        if ($isAuth && !$userId && $this->getRequest()->getParam('error_reason') == 'user_denied') {
            $this->_appendJs("<script>window.close()</script>");

            return;
        } elseif ($isAuth && !$userId) {
            $loginUrl = $facebook->getLoginUrl(array('scope' => 'email'));
            $this->_appendJs("<script type='text/javascript'>top.location.href = '$loginUrl';</script>");

            return $this;
        }
        $user = Mage::getModel('sociallogin/channel_fb')->getFbUser();

        if ($isAuth && $user) {
            $store_id   = Mage::app()->getStore()->getStoreId(); //add
            $website_id = Mage::app()->getStore()->getWebsiteId(); //add
            $data       = array('firstname' => $user['first_name'], 'lastname' => $user['last_name'], 'email' => $user['email']);
            if ($data['email']) {
                $customer = Mage::helper('sociallogin')->getCustomerByEmail($data['email'], $website_id); //add edition
                if (!$customer || !$customer->getId()) {
                    //Login multisite
                    $customer = Mage::helper('sociallogin')->createCustomerMultiWebsite($data, $website_id, $store_id);
                    if (Mage::getStoreConfig('sociallogin/fblogin/is_send_password_to_customer')) {
                        $customer->sendPasswordReminderEmail();
                    }
                }
                // fix confirmation
                if ($customer->getConfirmation()) {
                    try {
                        $customer->setConfirmation(null);
                        $customer->save();

                        //create address here...
                        //$this->_createCustomerAddress($customer);

                    } catch (Exception $e) {

                    }
                }
                Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
                $this->_appendJs("<script type=\"text/javascript\">try{window.opener.location.href=\"" . $this->_loginPostRedirect() . "\";}catch(e){window.opener.location.reload(true);} window.close();</script>");
            } else {
                Mage::getSingleton('core/session')->addError('You provided a email invalid!');
                $this->_appendJs("<script type=\"text/javascript\">try{window.opener.location.reload(true);}catch(e){window.opener.location.href=\"" . Mage::getBaseUrl() . "\"} window.close();</script>");
            }
        }
    }

    protected function _appendJs($string)
    {
        $this->loadLayout();
        $layout = Mage::app()->getLayout();
        $block  = $layout->createBlock('core/text');
        $block->setText(
            $string
        );
        $this->getLayout()->getBlock('head')->append($block);
        $this->renderLayout();
    }

    /**
     * redirect login
     *
     * @return mixed
     */
    protected function _loginPostRedirect()
    {
        $session = Mage::getSingleton('customer/session');

        if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl()) {
            // Set default URL to redirect customer to
            $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        } else if ($session->getBeforeAuthUrl() == Mage::helper('customer')->getLogoutUrl()) {
            $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        } else {
            if (!$session->getAfterAuthUrl()) {
                $session->setAfterAuthUrl($session->getBeforeAuthUrl());
            }
            if ($session->isLoggedIn()) {
                $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
            }
        }

        return $session->getBeforeAuthUrl(true);
    }

}
