<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 05/12/2014
 * Time: 11:19
 * To change this template use File | Settings | File Templates.
 */

class Magentothem_Ajaxlogin_LoginController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $json_response = array('title' => '', 'body' => '');
        $block = $this->getLayout()
                      ->createBlock('magentothem_ajaxlogin/login')
                      ->setTemplate('magentothem/ajaxlogin/ajax.phtml')
                      ->toHtml();
        $json_response['title'] = "Login or create account";
        $json_response['body'] = $block;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($json_response));
    }

    public function loginPostAction()
    {

        $error = false;
        $message = '';

        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $email = $this->getRequest()->getPost('email');
            $pass = $this->getRequest()->getPost('pass');

            try {
                $session->login($email, $pass);
                $message = "Login Successfully!";
                $session->addSuccess($message);
            } catch (Mage_Core_Exception $e) {
                $error = true;
                switch ($e->getCode()) {
                    case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                        $value = $this->_getHelper('customer')->getEmailConfirmationUrl($email);
                        $message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                        break;
                    case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                        $message = $e->getMessage();
                        break;
                    default:
                        $message = $e->getMessage();
                }
                $session->setUsername($email);
            } catch (Exception $e) {
                $error = true;
                $message = "An error occurred while login to system";
                Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
            }

        }

        $result = array(
            'error' => $error,
            'message' => $message,
        );

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function logoutAction()
    {
        $error = false;
        $message = '';

        try {
            $this->_getSession()->logout()
                ->renewSession()
                ->setBeforeAuthUrl($this->_getRefererUrl());
            $message = "Logout Successfully!";
            $this->_getSession()->addSuccess($message);
        } catch(Exception $e) {
            Mage::logException($e);
            $error = true;
            $message = "An error occurred while log out account";
        }

        $result = array(
            'error' => $error,
            'message' => $message
        );

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

}