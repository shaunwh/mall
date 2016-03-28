<?php

class Magentothem_Ajaxlogin_RegisterController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $json_response = array('title' => '', 'body' => '');
        $block = $this->getLayout()
            ->createBlock('magentothem_ajaxlogin/register')
            ->setTemplate('magentothem/ajaxlogin/register.phtml')
            ->toHtml();
        $json_response['title'] = "Login or create account";
        $json_response['body'] = $block;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($json_response));
    }

}