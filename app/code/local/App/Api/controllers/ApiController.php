<?php
/**
 * Created by PhpStorm.
 * User: shaun
 * Date: 15-10-8
 * Time: 上午9:52
 */
class App_Api_ApiController extends Mage_Core_Controller_Front_Action{

    public function IndexAction(){
        $str = Mage::getSingleton('api/api');
        echo get_class($str);exit;
    }

    /**
     * get categories tree
     */
    /*public function get_categories($categories){
        //这里的变量 $array 将存储的是所有目录信息
        //{}

        $array = array();
        foreach ($categories as $category) {
            $cat    = Mage::getModel('catalog/category')->load($category->getId());
            $count  = $cat->getProductCount();

            $array[] = array($category->getName(),$count);
            if ($category->hasChildren()) {
                $children = Mage::getModel('catalog/category')
                    ->getCategories($category->getId());
                $array .=  array($this->get_categories($children)); //递归生成子目录
            }
        }
        return  $array;
    }*/

    /**
     * new customer session
     */
    public  function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * new shop cart session
     */
    public function _getCartSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * new product action
     */
    public function _newProduct(){
        return Mage::getModel('catalog/product');
    }

    /**
     * new category action
     */
    public function _newCategory(){
        return Mage::getModel('catalog/category');
    }

    /**
     * new user action
     */
    public function _newCustomer(){
        return Mage::getModel('customer/customer');
    }

    /**
     * new order action
     */
    public function _newOrder(){
        return Mage::getModel('sales/order');
    }

    /**
     * new cart action
     */
    public function _newCart(){
        return Mage::getModel('checkout/cart');
    }

    /**
     * new customer address action
     */
    public function _newAddress(){
        return Mage::getModel('customer/address');
    }

    public function testAction(){
        $customer = $this->_newCustomer();
        $cus = $customer->getCollection()
            ->addAttributeToSelect("*")
            ->load();
        $arr = array();
        foreach($cus as $cu){
            $arr[] = $cu->getData();
        }
        var_dump($arr);exit;
    }

    /**
     * api for user sign in
     */
    public function signInAction(){
        Mage::log("receive sign action.");
        $this->setJsonHeader();
        $paras = $this->getRequest()->getParams();
        //$paras = array("name" => "qwe","email" => "qwe@126.com","password" => "123456","head" => "this is head test","phone" => "1554434332333");
        if(empty($paras['email']) || empty($paras['name']) || empty($paras['password'])){
            echo Zend_Json::encode(array("success" => false, "data" => "邮箱、用户名、密码不能为空"));return;
        }
        //$session = $this->_getSession();
        //$code = $session->getData('mcode');
        //if(!$code || $code['mobile'] != $paras['phone'] || $code['secret'] != $paras['secret']){
        //    echo Zend_Json::encode(array("success" => false, "data" => "verify phone failed."));return;
        //}
        $cus_model = $this->_newCustomer();
        $customer = $cus_model->getCollection()
            ->addAttributeToFilter('email',$paras['email'])
            ->getFirstItem();
        if($customer->getData('entity_id')){
            echo Zend_Json::encode(array("success" => false, "data" => "该邮箱已经注册"));return;
        }else{
            try{
                $customer = $this->_newCustomer();
                $customer->setGroupId(1);
                $customer->setEmail($paras['email']);
                $customer->setFirstname($paras['name']);
                $customer->setLastname($paras['name']);
                $customer->setPassword($paras['password']);
                //$customer->setHead($paras['head']);
                //$customer->setPhone($paras['phone']);
                $customer->setConfirmation(null);
                $customer->save();
                $data = $customer->getData();
                if(!empty($data)){
                    $session = $this->_getSession();
                    $session->login($paras['email'],$paras['password']);
                    //$session->setCustomerAsLoggedIn($customer);
                    //$session->renewSession();
                    echo Zend_Json::encode(array("success" => true, "data" => $data));return;
                }else{
                    echo Zend_Json::encode(array('success' => false, 'data' => '注册失败，请联系管理员'));return;
                }
            }catch (Exception $e){
                echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
            }

        }

    }

    /**
     * verify message function
     *
     */
    public function _verifyMessage($mobile,$secret){
        $this->getResponse()->setHeader('Content-Type',"application/json; charset=utf-8");

        $apiKey = "7cf6493146d440d5a20d7d04821981c1";
        $appId = "inY1447Vi91e";
        $content = "【佳杰科技】您的验证码为：".$secret."，五分钟内有效，请尽快验证。";
        $url = "https://sms.zhiyan.net/sms/match_send.json";
        $json_arr = array(
            "mobile" => $mobile,
            "content" => $content,
            "appId"=>$appId,
            "apiKey"=>$apiKey,
            "extend" => "",
            "uid" => ""
        );
        $array =json_encode($json_arr);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec ($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * api for verify message
     */
    public function sendSmsAction(){
        Mage::log("receive verify message action.");
        $this->setJsonHeader();
        $mobile = $this->getRequest()->getParam('mobile',false);
        if(!$mobile){
            echo Zend_Json::encode(array("success" => false, "data" => "电话号码不能为空"));return;
        }
        $secret = rand(100000, 999999);
        $result = $this->_verifyMessage($mobile,$secret);
        $res = json_decode($result,true);
        if($res['result'] == "SUCCESS"){
            Mage::log("发送验证码到 $mobile 成功，验证码是 $secret");
            $session = $this->_getSession();
            $session->setData('mcode', array('mobile'=>$mobile, 'secret'=>$secret));
            echo Zend_Json::encode(array("success" => true, "data" => "发送验证码成功"));return;
        }else{
            echo Zend_Json::encode(array("success" => false, "data" => $res['reason']));
        }
    }

    /**
     * api for user login
     */
    public function loginAction(){
        Mage::log("receive login action");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if($session->isLoggedIn()){
            $customerData = $session->getCustomer()->getData();
            echo Zend_Json::encode(array("success" => true, "data" => $customerData));return;
        }
        $paras = $this->getRequest()->getParams();
        if(empty($paras['username']) || empty($paras['password'])){
            echo Zend_Json::encode(array("success" => false, "data" => "用户名和密码不能为空"));return;
        }
        // if username is phone , then select email by phone
        if(!strstr($paras['username'],'@') && strlen($paras['username']) == 11){
            $mobileNu = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToFilter('phone', $paras['username'])
                ->getFirstItem();
            if($mobileNu->getData('email')){
                $paras['username'] = $mobileNu->getData('email');
            }else{
                echo Zend_Json::encode(array("success" => false, "data" => "该用户不存在"));return;
            }
        }
        try{
            $session->login($paras['username'],$paras['password']);
            $customerData = $session->getCustomer()->getData();
            echo Zend_Json::encode(array("success" => true, "data" => $customerData));return;
        } catch(Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }
    }

    /**
     * api for user info
     */
    public function myInfoAction(){
        Mage::log("receive my info action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        try{
            $customer_id = $session->getCustomerId();
            $customer = $this->_newCustomer()->load($customer_id);
            //$customer_data = $customer->getData();
            /*if(!$customer->getData("head")){
                $head = Mage::getBaseUrl('media').$customer->getData("head");
            }else{
                $head = "";
            }*/
            $head = $customer->getData("head");
            if(empty($head) || !$head){
                $url = "";
            }else{
                $url = Mage::getBaseUrl('media').$head;
            }
            $customer_data = array(
                "id"      => $customer->getData("entity_id"),
                "name"    => $customer->getData("firstname"),
                "company" => $customer->getData("prefix"),
                "phone"   => $customer->getData("phone"),
                "head"    => $url,
                "email"   => $customer->getData("email")
            );
            echo Zend_Json::encode(array("success" => true, "data" => $customer_data));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for user logout
     */
    public function logoutAction(){
        Mage::log("receive logout action.");
        $this->setJsonHeader();
        try{
            $this->_getSession()->logout()
                ->renewSession()
                ->setBeforeAuthUrl($this->_getRefererUrl());

            echo Zend_Json::encode(array("success" => true, "data" => "注销成功"));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "dat" => $e->getMessage()));
        }

    }

    /**
     * api for user to change password
     */
    public function changePasswordAction(){

        Mage::log("receive change password action.");
        $this->setJsonHeader();
        $paras = $this->getRequest()->getParam('password',false);
        $old_password = $this->getRequest()->getParam('old_password',false);
        $session = $this->_getSession();
        $customerId = $session->getCustomerId();
        Mage::log("get customerId is :".$customerId);
        Mage::log("get password is :".$paras);
        if($customerId){
            $customer = $this->_newCustomer();
            $customer->load($customerId);
            $oldPass = $session->getCustomer()->getPasswordHash();
            if (Mage::helper('core/string')->strpos($oldPass, ':')) {
                list($_salt, $salt) = explode(':', $oldPass);
            } else {
                $salt = false;
            }
            if($oldPass == $customer->hashPassword($old_password,$salt)){
                $customer->setPassword($paras);
                $customer->save();
                $session->login($customer->getData('email'),$paras);
                $customer_data = $session->getCustomer()->getData();
                echo Zend_Json::encode(array("success" => true, "data" => $customer_data));return;
            }
            echo Zend_Json::encode(array("success" => false, "data" => "旧密码验证错误"));return;
        }else{
            echo Zend_Json::encode(array("success" => false, "data" => "修改密码失败"));
        }
    }

    /**
     * api for post send sms code
     */
    public function postSmsCodeAction(){
        Mage::log("receive post sms code action.");
        $this->setJsonHeader();
        $paras = $this->getRequest()->getParams();
        Mage::log("paras is :".var_export($paras,true));
        //$paras = array("mobile" => 15210010339, "secret" => 847399);
        //session_start();
        //Mage::log("var dump session is:".var_export($_SESSION,true));
        $session = $this->_getSession();
        $mCode = $session->getData('mcode');
        //echo Zend_Json::encode(array("data" => $mCode));exit;
        Mage::log("session code is :".var_export($mCode,true));
        if(empty($paras['mobile']) || !isset($paras) || empty($paras['secret'])){
            echo Zend_Json::encode(array("success" => false, "data" => "请填写电话号码和验证码"));return;
        }
        if(!$mCode || $mCode['mobile'] != $paras['mobile'] || $mCode['secret'] != $paras['secret']){
            echo Zend_Json::encode(array("success" => false, "data" => "手机号码或者验证码不匹配"));return;
        }
        $customerId = $session->getCustomerId();
        Mage::log("post sms customer id is :".$customerId);
        $verify = $this->_verifyMobile($paras['mobile']);
        Mage::log("verify mobile result is :".$verify);
        if($customerId){
            $customer = $this->_newCustomer()->load($customerId);
            if($customer->getData("entity_id") && $verify){
                $customer->setPhone($paras['mobile']);
                $customer->save();
                echo Zend_Json::encode(array("success" => true, "data" => array("entity_id" => $customerId)));return;
            }else{
                echo Zend_Json::encode(array("success" => false, "data" => "该手机号已经注册过，请使用其他手机号码"));return;
            }
        }
        echo Zend_Json::encode(array("success" => true, "data" => "验证成功"));return;
    }

    /**
     * function for verify mobile
     * @param $mobile
     * @return bool
     */
    public function _verifyMobile($mobile){
        $customer = $this->_newCustomer()->getCollection()
            ->addAttributeToFilter("phone",$mobile)
            ->load();
        if($customer->getData("entity_id")){
            return false;
        }else{
            return true;
        }
    }

    /**
     * api for user to forget password
     */
    public function resetPasswordAction(){
       Mage::log("receive forgot password action.");
        $this->setJsonHeader();
       $session = $this->_getSession();
       $password = $this->getRequest()->getParam("password",false);
       if(!$password){
           echo Zend_Json::encode(array("success" => false, "data" => "密码不能为空"));return;
       }
       $mCode = $session->getData('mcode');
       Mage::log("reset password get session data is :".var_export($mCode,true));
       $customer = $this->_newCustomer()->getCollection()
           ->addAttributeToFilter('phone', $mCode['mobile'])
           ->getFirstItem();
       $email = $customer->getData('email');
       Mage::log("get email is :".$email);
       if(!empty($email)){
           $customer->setPassword($password);
           $customer->save();
           $session->login($email,$password);
           $customer_data = $session->getCustomer()->getData();
           echo Zend_Json::encode(array("success" => true, "data" => $customer_data));return;
       }else{
           echo Zend_Json::encode(array("success" => false, "data" => "重置密码失败"));return;
       }
    }

    /**
     * api for edit user info
     */
    public function editUserInfoAction(){
        Mage::log("receive edit user info action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $paras = $this->getRequest()->getParams();
        $customerId = $session->getCustomerId();
        $customer = $this->_newCustomer()->load($customerId);
        if(!empty($paras['name']) && isset($paras['name'])){
            $customer->setData('firstname',$paras['name']);
            $customer->setData('lastname',$paras['name']);
        }
        if(!empty($paras['company']) && isset($paras['company'])){
            $customer->setPrefix($paras['company']);
        }
        if(!empty($paras['phone']) && isset($paras['phone'])){
            $customer->setPhone($paras['phone']);
        }
        if(!empty($paras['email']) && isset($paras['email'])){
            $customer->setEmail($paras['email']);
        }
        /*if(!empty($paras['gender']) && isset($paras['gender'])){
            $customer->setGender($paras['gender']);
        }*/
        if(!empty($paras['path']) && isset($paras['path'])){
            $head = $this->_upload();
            Mage::log("upload file name is:".$head);
            if($head){
                $customer->setHead("/head/".$head);
            }
        }

        try{
            $customer->save();
            //Mage::log("edit user info email is :".$customer->getEmail()." password is :".$customer->getData("password"));
            //$session->login($customer->getEmail(),$customer->getData("password"));
            /*$head = $customer->getData("head");
            if(empty($head) || !$head){
                $url = "";
            }else{
                $url = Mage::getBaseUrl('media').$head;
            }
            $customer_data = array(
                "id"      => $customer->getData("entity_id"),
                "name"    => $customer->getData("firstname"),
                "company" => $customer->getData("prefix"),
                "phone"   => $customer->getData("phone"),
                "head"    => $url,
                "email"   => $customer->getData("email")
            );
            Mage::log("user data is :".var_export($customer_data,true));*/
            echo Zend_Json::encode(array("success" => true, "data" => "修改成功"));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for list categories
     */
    /*public function listCategoriesAction(){
        Mage::log("receive list category action.");
        $cate = $this->_newCategory()->getCategories(Mage::app()->getStore()->getRootCategoryId());
        $categories = $this->get_categories($cate);

        //echo Zend_Json::encode(array("success" => true, "data" => $categories));
        var_dump($categories) ;

    }*/

    /**
     * function for get product qty
     * @param $product_id
     * @return mixed
     */
    public function _getQty($product_id){
        return Mage::getModel('cataloginventory/stock_item')->loadByProduct($product_id)->getQty();
    }

    /**
     * api for get products by category
     */
    /*public function getProductByCategoryAction(){
        Mage::log("receive get product by category action.");
        $session = $this->_getSession();
        $customerId = $session->getCustomerId();
        Mage::log("get customer id is :".$customerId);
        $categoryId = $this->getRequest()->getParam('categoryId');
        $sortColumn = $this->getRequest()->getParam('sortColumn','price');
        $sort = $this->getRequest()->getParam('sort','asc');
        $page = $this->getRequest()->getParam('page',1);
        $pageSize = $this->getRequest()->getParam('pageSize',10);
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $groupId = $customer->getData('groupId');
        $product = $this->_newProduct()->getCollection()
            ->addAttributeToSelect("*")
            ->addAttributeToFilter("category_id",$categoryId)
            ->addAttributeToSort($sortColumn,$sort)
            ->setCurPage($page)
            ->setPageSize($pageSize)
            ->load();
        $arr = array();
        foreach($product as $pro){
            if($groupId == 2){
                $price = $pro->getData('price');
            }else{
                $price = '暂无报价';
            }
            $arr[] = array("id" => $pro->getData('entity_id'),
                "sku" => $pro->getData('sku'),
                "name" => $pro->getData('name'),
                "image" => Mage::getBaseUrl('media').'catalog/product'.$pro->getData('small_image'),
                "price" => $price,
                "qty"   => $this->_getQty($pro->getData('entity_id'))
            );
        }
        echo Zend_Json::encode(array("success" => true, "data" => $arr));
    }*/

    /**
     * api for product info
     */
    public function productInfoAction(){
        Mage::log("receive product info action.");
        $this->setJsonHeader();
        $productId = $this->getRequest()->getParam("product_id",false);
        Mage::log("product id is :".$productId);
        $cId = $this->getRequest()->getParam("c_id",false);
        if(!$productId){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择产品"));return;
        }
        try{
            $session = $this->_getSession();
            $customerId = $session->getCustomerId();
            Mage::log("get customer id is :".$customerId);
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $groupId = $customer->getData('groupId');
            $product = $this->_newProduct()->getCollection()
                ->addAttributeToSelect("*")
                ->addAttributeToFilter("entity_id",$productId)
                ->load();
            foreach($product as $pro){
                if($groupId == 2 || $cId == 6){
                    $price = $pro->getData('price');
                }else{
                    $price = '暂无报价';
                }
                $arr = array("id" => $pro->getData('entity_id'),
                    "sku" => $pro->getData('sku'),
                    "name" => $pro->getData('name'),
                    "pn" => $pro->getData('pn'),
                    "description" => $pro->getData('description'),
                    "short_description" => $pro->getData('short_description'),
                    //"image" => Mage::getBaseUrl('media').'catalog/product'.$pro->getData('small_image'),
                    "image" => $pro->getImageUrl(),
                    "price" => $price,
                    "qty"   => $this->_getQty($pro->getData('entity_id'))
                );
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * init product function
     */
    protected function _initProduct($productId)
    {
        //$productId = (int) $this->getRequest()->getParam($product_id);
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }

    /**
     * go back function
     */
    protected function _goBack()
    {
        $returnUrl = $this->getRequest()->getParam('return_url');
        if ($returnUrl) {

            if (!$this->_isUrlInternal($returnUrl)) {
                throw new Mage_Exception('External urls redirect to "' . $returnUrl . '" denied!');
            }

            $this->_getSession()->getMessages(true);
            $this->getResponse()->setRedirect($returnUrl);
        } elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart')
            && !$this->getRequest()->getParam('in_cart')
            && $backUrl = $this->_getRefererUrl()
        ) {
            $this->getResponse()->setRedirect($backUrl);
        } else {
            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
            }
            $this->_redirect('checkout/cart');
        }
        return $this;
    }

    /**
     * api for add shop cart
     */
    public function addCartAction(){
        Mage::log("receive add cart action.");
        $this->setJsonHeader();
        $cart = $this->_newCart();
        $params = $this->getRequest()->getParams();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        //$params = array("qty" => 100, "product_id" => 2);
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct($params['product_id']);
            $related = $this->getRequest()->getParam('related_product');
            /**
             * Check product availability
             */
            if (!$product) {
                echo Zend_Json::encode(array("success" => false, "data" => "没有这个产品"));
                return;
            }
            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getCartSession()->setCartWasUpdated(true);

            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

            if (!$this->_getCartSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    //$message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                    //$this->_getCartSession()->addSuccess("add ".$product->getName()." success.");
                    echo Zend_Json::encode(array("success" => true, "data" => "成功添加购物车"));return;
                }
            }
        } catch (Mage_Core_Exception $e) {
            //echo $e->getMessage();
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));return;
        }
    }

    /**
     * api for shop cart
     */
    public function cartAction(){
        Mage::log("receive cart action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn())
        {
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $cart = $this->_newCart();

        $items = $cart->getQuote()->getAllItems();
        $arr = array();
        $arr['count'] = 0;
        $arr['total_price'] = $cart->getQuote()->getGrandTotal();
        foreach($items as $item){
            $arr['data'][] = array(
                    "id"               => $item->getId(),
                    "productId"        => $item->getProductId(),
                    "productName"      => $item->getName(),
                    "sku"              => $item->getSku(),
                    "qty"              => $item->getQty(),
                    "price"            => $item->getPrice(),
                    "total_price"      => $item->getRow_total(),
                    "image"            => $this->_getProductImage($item->getProductId())
            );
            $arr['count'] += $item->getQty();
        }
        echo Zend_Json::encode(array("success" => true, "data" => $arr));return;

    }

    public function _getProductImage($productId){
        $product = $this->_newProduct()->load($productId);
        return $product->getSmallImageUrl();
    }

    /**
     * api for edit cart
     */
    public function editCartAction(){
        Mage::log("receive edit cart action.");
        //todo logic is update cart item one by one
        $cart_id = $this->getRequest()->getParam("cart_id",false);
        $products = $this->getRequest()->getParam("products",false);
    }

    /**
     * api for update cart
     */
    public function updateCartAction(){
        Mage::log("receive update cart action.");
        $this->setJsonHeader();
        $updateAction = (string)$this->getRequest()->getParam('update_cart_action');
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "登录过期，请重新登录"));return;
        }
        Mage::log("update action is :".$updateAction);
        switch ($updateAction) {
            case 'empty_cart':
                $this->_emptyShoppingCart();
                break;
            case 'update_qty':
                $this->_updateShoppingCart();
                break;
            default:
                $this->_updateShoppingCart();
        }
    }

    /**
     * Update customer's shopping cart
     */
    protected function _updateShoppingCart()
    {
        $this->setJsonHeader();
        try {
            $cartData = $this->getRequest()->getParam("cart",false);
            //批量更新$cartData = array("7" => array("qty" => 12),"8" => array("qty" => 12));
            //单个更新$cartData = array("id" => 8, "qty" => 1);
            $cartData = json_decode($cartData,true);
            Mage::log("update cart products list is :".var_export($cartData,true));
            if (is_array($cartData)) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                //foreach ($cartData as $data) {
                    //if (isset($cartData['qty'])) {
                        //$id = $cartData['id'];
                        //$cartData[$id]['qty'] = $filter->filter(trim($cartData['qty']));
                    //}
                //}
                foreach ($cartData as $index => $data) {
                    if (isset($data['qty'])) {
                        $cartData[$index]['qty'] = $filter->filter(trim($data['qty']));
                    }
                }
                $cart = $this->_newCart();
                if (! $cart->getCustomerSession()->getCustomer()->getId() && $cart->getQuote()->getCustomerId()) {
                    $cart->getQuote()->setCustomerId(null);
                }

                $cartData = $cart->suggestItemsQty($cartData);
                $cart->updateItems($cartData)
                    ->save();
                $this->_getSession()->setCartWasUpdated(true);
                echo Zend_Json::encode(array("success" => true, "data" => "成功更新购物车"));return;
            }
            echo Zend_Json::encode(array("success" => false, "data" => "修改购物车失败"));return;
        } catch (Mage_Core_Exception $e) {
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }
    }

    /**
     * Empty customer's shopping cart
     */
    protected function _emptyShoppingCart()
    {
        $this->setJsonHeader();
        try {
            $this->_newCart()->truncate()->save();
            $this->_getSession()->setCartWasUpdated(true);
            echo Zend_Json::encode(array("success" => true, "data" => "成功清空购物车"));return;
        } catch (Mage_Core_Exception $exception) {
            //$this->_getSession()->addError($exception->getMessage());
            echo Zend_Json::encode(array("success" => false, "data" => $exception->getMessage()));return;
        }
    }

    /**
     * api for delete cart
     */
    public function deleteCartAction(){
        Mage::log("receive delete cart action.");
        $this->setJsonHeader();
        $id = (int) $this->getRequest()->getParam('id');
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        if ($id) {
            try {
                $this->_newCart()->removeItem($id)
                    ->save();
                echo Zend_Json::encode(array("success" => true, "data" => "成功移除购物车"));return;
            } catch (Exception $e) {
                echo Zend_Json::encode(array("success" => false, "data" => "移除购物车失败"));return;
            }
        }
        echo Zend_Json::encode(array("success" => false, "data" => "请选择你要移除的购物车"));
    }

    /**
     * api for order list by status
     */
    public function orderListByStatusAction(){
        Mage::log("receive order list by status action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        $customerId = $session->getCustomerId();
        Mage::log("login customer id is :".$customerId);
        if(!$customerId){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $status = $this->getRequest()->getParam("status",false);
        Mage::log("get order list status is :".$status);
        if(!$status){
            echo Zend_Json::encode(array("success" => false, "data" => "请指定要获取订单的状态属性"));return;
        }
        try{
            $order = $this->_newOrder()->getCollection()
                ->addAttributeToSelect("*")
                ->addAttributeToFilter("customer_id",$customerId)
                ->addAttributeToFilter("status",$status)
                ->addAttributeToSort("entity_id","desc")
                ->load();
            $arr = array();
            foreach($order as $list){
                $arr[] = array("id" => $list->getData("entity_id"),
                    "status" => $list->getData("status"),
                    "order_code" => $list->getData("increment_id"),
                    "order_price" => $list->getData("subtotal"),
                    "total_price" => $list->getData("grand_total"),
                    "express" => $list->getData("shipping_amount"),
                    "order_qty" => $list->getData("total_qty_ordered"),
                    "order_date" => $list->getData("created_at"),
                    "customer_name" => $list->getData("customer_lastname"),
                    "customer_email" => $list->getData("customer_email")
                );
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }
    }

    /**
     * api for get product info on order
     */
    public function getProductByOrderId($id){
        $sales_order = Mage::getModel('sales/order')->load($id);
        foreach ($sales_order->getAllItems() as $item) {
            //$data = array("qty" =>  $item->getQtyOrdered(),"name" => $item->getName());
            $data = $item->getName();
        }

        return $data;
    }

    /**
     * api for order list
     */
    public function orderListAction(){
        Mage::log("receive order list action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        $customerId = $session->getCustomerId();
        Mage::log("get customer id is :".$customerId);
        if(!$customerId){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        try{
            $order = $this->_newOrder()->getCollection()
                ->addAttributeToSelect("*")
                ->addAttributeToFilter("customer_id",$customerId)
                ->addAttributeToSort("entity_id","desc")
                ->load();
            $arr = array();
            foreach($order as $list){
                //$arr[] = $list->getData();
                $arr[] = array("id" => $list->getData("entity_id"),
                    "status" => $list->getData("status"),
                    "order_code" => $list->getData("increment_id"),
                    "order_price" => $list->getData("subtotal"),
                    "total_price" => $list->getData("grand_total"),
                    "express" => $list->getData("shipping_amount"),
                    "product_name" => $this->getProductByOrderId($list->getData("entity_id")),
                    "order_qty" => $list->getData("total_qty_ordered"),
                    "order_date" => $list->getData("created_at"),
                    "customer_name" => $list->getData("customer_lastname"),
                    "customer_email" => $list->getData("customer_email")
                );
            }
            //var_dump($arr);exit;
            echo Zend_Json::encode(array("success" => true, "data" => $arr));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for order info
     */
    public function orderInfoAction(){
        Mage::log("receive order info action.");
        $this->setJsonHeader();
        $order_id = $this->getRequest()->getParam('id',false);
        if(!$order_id){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择你要查看的订单"));return;
        }
        //product items
        //http://blog.sina.com.cn/s/blog_8a69598a0101kbsr.html
        $data = array();
        try{
            $order = $this->_newOrder()->load($order_id);
            foreach($order->getAllItems() as $item){
                $image = $this->_getProductImage($item->getData("product_id"));
                $data['product_info'][] = array(
                    "id" => $item->getData("order_id"),
                    "name" => $item->getData("name"),
                    "price" => $item->getData("price"),
                    "product_id" => $item->getData("product_id"),
                    "qty_ordered" => $item->getData("qty_ordered"),
                    "image" => $image
                );
                //$data[] = $item->getData();
            }
            $address = Mage::getSingleton("sales/order_address")->load($order_id);
            $arr['address_info'] = array(
                "customer_name" => $address->getData("firstname"),
                "telephone" => $address->getData("telephone"),
                "street" => $address->getData("street"),
                "city" => $address->getData("city")
            );
            $newarray = array_merge($data,$arr);
            //var_dump($newarray);exit;
            //var_dump($data);exit;
            echo Zend_Json::encode(array("success" => true, "data" => $newarray));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for cancel order
     */
    public function cancelOrderAction(){
        Mage::log("receive cancel order action.");
        $this->setJsonHeader();
        $order_id = $this->getRequest()->getParam("id",false);
        if(!$order_id){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择你要取消的订单"));return;
        }
        try{
            $order = $this->_newOrder()->load($order_id);
            $res = $order->cancel();
            echo Zend_Json::encode(array("success" => true, "data" => $res));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for save order
     */
    public function saveOrderAction(){
        Mage::log("receive save order action.");
        $this->setJsonHeader();
        //填写客户的 Id 号
        $session = $this->_getSession();
        $customerId = $session->getCustomerId();
        $customer   = Mage::getModel('customer/customer')->load($customerId);
        //use transaction
        $transaction     = Mage::getModel('core/resource_transaction');
        $storeId         = $customer->getStoreId();
        $reservedOrderId = Mage::getSingleton('eav/config')->getEntityType('order')
            ->fetchNewIncrementId($storeId);
        //echo $reservedOrderId;exit;
        $order = Mage::getModel('sales/order');
        $order->setIncrementId($reservedOrderId)
            ->setStoreId($storeId)
            ->setQuoteId(0)
            ->setGlobal_currency_code('USD')
            ->setBase_currency_code('USD')
            ->setStore_currency_code('USD')
            ->setOrder_currency_code('USD');
        //这里我设置成'CN', 你可以根据自己的需求修改或添加

        //保存用户信息
        $order->setCustomer_email($customer->getEmail())
            ->setCustomerFirstname($customer->getFirstname())
            ->setCustomerLastname($customer->getLastname())
            ->setCustomerGroupId($customer->getGroupId())
            ->setCustomer_is_guest(0)
            ->setCustomer($customer);
        // 保存 Billing Address
        try{
            $addressId = $this->getRequest()->getParam('address_id',false);
            $billing        = $customer->getAddressItemById($addressId);
            //var_dump($billing);exit;
            //$billing        = $customer->getDefaultBillingAddress();
            //var_dump($billing);exit;
            $billingAddress = Mage::getModel('sales/order_address');
            $billingAddress->setStoreId($storeId)
                ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_BILLING)
                ->setCustomerId($customer->getId())
                ->setCustomerAddressId($customer->getDefaultBilling())
                ->setCustomer_address_id($billing->getEntityId())
                ->setPrefix($billing->getPrefix())
                ->setFirstname($billing->getFirstname())
                ->setMiddlename($billing->getMiddlename())
                ->setLastname($billing->getLastname())
                ->setSuffix($billing->getSuffix())
                ->setCompany($billing->getCompany())
                ->setStreet($billing->getStreet())
                ->setCity($billing->getCity())
                ->setCountry_id($billing->getCountryId())
                ->setRegion($billing->getRegion())
                ->setRegion_id($billing->getRegionId())
                ->setPostcode($billing->getPostcode())
                ->setTelephone($billing->getTelephone())
                ->setFax($billing->getFax());

            $order->setBillingAddress($billingAddress);

            // 保存 Shipping Address
            //$shipping        = $customer->getDefaultShippingAddress();
            $shipping        = $customer->getAddressItemById($addressId);
            $shippingAddress = Mage::getModel('sales/order_address');
            $shippingAddress->setStoreId($storeId)
                ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
                ->setCustomerId($customer->getId())
                ->setCustomerAddressId($customer->getDefaultShipping())
                ->setCustomer_address_id($shipping->getEntityId())
                ->setPrefix($shipping->getPrefix())
                ->setFirstname($shipping->getFirstname())
                ->setMiddlename($shipping->getMiddlename())
                ->setLastname($shipping->getLastname())
                ->setSuffix($shipping->getSuffix())
                ->setCompany($shipping->getCompany())
                ->setStreet($shipping->getStreet())
                ->setCity($shipping->getCity())
                ->setCountry_id($shipping->getCountryId())
                ->setRegion($shipping->getRegion())
                ->setRegion_id($shipping->getRegionId())
                ->setPostcode($shipping->getPostcode())
                ->setTelephone($shipping->getTelephone())
                ->setFax($shipping->getFax());

            $order->setShippingAddress($shippingAddress)
                ->setShipping_method('flatrate_flatrate')
                ->setShippingDescription('快递运输');

            //这里可以根据你的需求来设置付款方式名称
            $orderPayment = Mage::getModel('sales/order_payment');
            $orderPayment->setStoreId($storeId)
                ->setCustomerPaymentId(0)
                ->setMethod('purchaseorder');

            $order->setPayment($orderPayment);

            //这里假设有一个产品
            //请先确认你所输的产品是否存在， 这里我输的产品号 Id 是 43
            $subTotal = 0;
            $products = $this->getRequest()->getParam('products',false);
            if(!$products){
                echo Zend_Json::encode(array("success" => false, "data" => "请选择你要购买的产品"));return;
            }
            $products = json_decode($products,true);

            Mage::log("save order products list is :".var_export($products,true));

            foreach ($products as $productId => $product) {
                $_product  = Mage::getModel('catalog/product')->load($productId);
                $rowTotal  = $_product->getPrice() * $product['qty'];
                $orderItem = Mage::getModel('sales/order_item');
                $orderItem->setStoreId($storeId)
                    ->setQuoteItemId(0)
                    ->setQuoteParentItemId(NULL)
                    ->setProductId($productId)
                    ->setProductType($_product->getTypeId())
                    ->setQtyBackordered(NULL)
                    ->setTotalQtyOrdered($product['qty'])
                    ->setQtyOrdered($product['qty'])
                    ->setName($_product->getName())
                    ->setSku($_product->getSku())
                    ->setPrice($_product->getPrice())
                    ->setBasePrice($_product->getPrice())
                    ->setOriginalPrice($_product->getPrice())
                    ->setRowTotal($rowTotal)
                    ->setBaseRowTotal($rowTotal);

                $subTotal += $rowTotal;
                $order->addItem($orderItem);
            }

            $order->setSubtotal($subTotal)
                ->setBaseSubtotal($subTotal)
                ->setGrandTotal($subTotal)
                ->setBaseGrandTotal($subTotal);

            $transaction->addObject($order);
            $transaction->addCommitCallback(array($order, 'place'));
            $transaction->addCommitCallback(array($order, 'save'));
            $transaction->save();
            //empty shopping cart
            $this->_newCart()->truncate()->save();
            $this->_getSession()->setCartWasUpdated(true);
            echo Zend_Json::encode(array("success" => true, "data" => "成功生成订单"));
        } catch (Exception $e) {
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for customer save address
     */
    public function saveAddressAction(){
        Mage::log("receive save customer address action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        //$customerId = $session->getCustomerId();
        $address = $this->_newAddress();
        $errors = array();
        $addressForm = Mage::getModel('customer/form');
        $addressForm->setFormCode('customer_address_edit')
            ->setEntity($address);
        //$addressData    = $addressForm->extractData($this->getRequest());
        $paras = $this->getRequest()->getParams();
        $addressData = array(
            "firstname" => $paras['name'],
            "lastname" => $paras['name'],
            "company" => $paras['company'],
            "street" => $paras['street'],
            "city" => $paras['city'],
            "country_id" => "CN",
            "postcode" => $paras['postcode'],
            "telephone" => $paras['telephone']
        );
        /*$addressData = array(
            "firstname" => "li",
            "lastname" => "ha",
            "company" => "北京科技",
            "street" => "北京大街",
            "city" => "北京",
            "country_id" => "CN",
            "postcode" => "100999",
            "telephone" => "13333333332"
        );*/
        $addressErrors  = $addressForm->validateData($addressData);
        if ($addressErrors !== true) {
            $errors = $addressErrors;
        }

        try {
            $addressForm->compactData($addressData);
            $address->setCustomerId($session->getId())
                ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));

            $addressErrors = $address->validate();
            if ($addressErrors !== true) {
                $errors = array_merge($errors, $addressErrors);
            }

            if (count($errors) === 0) {
                $address->save();
                //$data = $address->getData();
                echo Zend_Json::encode(array("success" => true, "data" => "保存成功"));
                return;
            } else {
                echo Zend_Json::encode(array("success" => false, "data" => "保存收货地址失败"));return;
            }
        } catch (Mage_Core_Exception $e) {
            echo Zend_Json::encode(array("success" => false, "error" => $e->getMessage()));return;
        }
    }

    /**
     * api for get default address
     */
    public function getDefaultAddressAction(){
        Mage::log("receive get default address action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $customerId = $session->getId();
        Mage::log("get customer id is: ".$customerId);
        try{
            $_pAddsses = $session->getCustomer()->getDefaultBilling();
            $item = $session->getCustomer()->getAddressById($_pAddsses);
            if($item){
                $arr = array(
                    "id"        => $item->getData("entity_id"),
                    "city"      => $item->getData("city"),
                    "name"      => $item->getData("firstname"),
                    "company"   => $item->getData("company"),
                    "telephone" => $item->getData("telephone"),
                    "street"    => $item->getData("street")
                );
            }else{
                $arr = array();
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr));return;
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }
    }

    /**
     * api for set default address action.
     */
    public function setDefaultAddressAction(){
        Mage::log("receive set default address action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $address_id = $this->getRequest()->getParam("address_id",false);
        if(!$address_id){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择一个地址"));return;
        }
        try{
            $address = $this->_newAddress()->load($address_id);
            $address->setCustomerId($session->getId())
                ->setIsDefaultBilling(true)
                ->setIsDefaultShipping(true);
            $address->save();
            echo Zend_Json::encode(array("success" => true, "data" => "设置成功"));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for customer get address list
     */
    public function listAddressAction(){
        Mage::log("receive customer address action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        try{
            $address = $this->_newAddress();
            $customerId = $session->getCustomerId();
            $defaultAddressId = $this->_newCustomer()->load($customerId)->getData("default_shipping");
            $address_collection = $address->getCollection()
                ->addAttributeToSelect("*")
                ->addAttributeToFilter('parent_id',$customerId)
                ->load();
            $arr = array();
            foreach($address_collection as $item){
                if(!empty($defaultAddressId) && $defaultAddressId == $item->getData("entity_id")){
                    $default = true;
                }else{
                    $default = false;
                }
                $arr[] = array(
                    "entity_id" => $item->getData("entity_id"),
                    "firstname" => $item->getData("firstname"),
                    "lastname" => $item->getData("lastname"),
                    "company" => $item->getData("company"),
                    "street" => $item->getData("street"),
                    "city" => $item->getData("city"),
                    "telephone" => $item->getData("telephone"),
                    "country_id" => $item->getData("country_id"),
                    "postcode" => $item->getData("postcode"),
                    "default_shipping" => $default
                );
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for customer edit address
     */
    public function editAddressAction(){
        Mage::log("receive edit address action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $address  = $this->_newAddress();

        $paras = $this->getRequest()->getParams();
        $addressData = array(
            "firstname" => $paras['name'],
            "lastname" => $paras['name'],
            "company" => $paras['company'],
            "street" => $paras['street'],
            "city" => $paras['city'],
            "country_id" => "CN",
            "postcode" => $paras['postcode'],
            "telephone" => $paras['telephone']
        );
        $addressId = $paras['id'];
        /*$addressData = array(
            "firstname" => "lii",
            "lastname" => "haa",
            "company" => "北京科技1",
            "street" => "北京大街1",
            "city" => "北京",
            "country_id" => "CN",
            "postcode" => "100999",
            "telephone" => "13333333332"
        );*/
        if ($addressId) {
            $existsAddress = $session->getCustomer()->getAddressById($addressId);
            if ($existsAddress->getId() && $existsAddress->getCustomerId() == $session->getId()) {
                $address->setId($existsAddress->getId());
            }
        }

        $errors = array();
        $addressForm = Mage::getModel('customer/form');
        $addressForm->setFormCode('customer_address_edit')
            ->setEntity($address);
        //$addressData    = $addressForm->extractData($this->getRequest());
        $addressErrors  = $addressForm->validateData($addressData);
        if ($addressErrors !== true) {
            $errors = $addressErrors;
        }

        try {
            $addressForm->compactData($addressData);
            $address->setCustomerId($session->getId())
                ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));

            $addressErrors = $address->validate();
            if ($addressErrors !== true) {
                $errors = array_merge($errors, $addressErrors);
            }

            if (count($errors) === 0) {
                $address->save();
                //$data = $address->getData();
                echo Zend_Json::encode(array("success" => true, "data" => "修改成功"));
                return;
            } else {
                echo Zend_Json::encode(array("success" => false, "data" => "修改失败"));return;
            }
        } catch (Mage_Core_Exception $e) {
            echo Zend_Json::encode(array("success" => false, "error" => $e->getMessage()));return;
        }
    }

    /**
     * api for customer delete address
     */
    public function deleteAddressAction(){
        Mage::log("receive delete address action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $addressId = $this->getRequest()->getParam("id",false);
        if(!$addressId){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择你要移除的地址"));return;
        }
        $address = $this->_newAddress()->load($addressId);
        if($address->getCustomerId() != $session->getCustomerId()){
            echo Zend_Json::encode(array("success" => false, "data" => "系统错误，这个地址不属于你管理"));return;
        }
        try {
            $address->delete();
            echo Zend_Json::encode(array("success" => true, "data" => "删除成功"));return;
        } catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));return;
        }
    }

    /**
     * api for customer address detail
     */
    public function addressInfoAction(){
        Mage::log("get address info action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $addressId = $this->getRequest()->getParam("id",false);
        Mage::log("get address id is :".$addressId);
        if(!$addressId){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择你要管理的地址"));return;
        }
        $address = $this->_newAddress()->getCollection()
            ->addAttributeToSelect("*")
            ->addAttributeToFilter("entity_id",$addressId)
            ->load();
        foreach($address as $add){
            $arr = $add->getData();
        }
        //$addressInfo = $address->getData();

        echo Zend_Json::encode(array("success" => true,"data" => $arr));

    }

    /**
     * api for add wish list
     */
    public function addWishListAction(){
        Mage::log("receive add wishlist action.");
        $this->setJsonHeader();
        try {
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            if(!$customerId){
                echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
            }
            $wishlist = Mage::getModel('wishlist/wishlist');
            $wishlist->loadByCustomer($customerId, true);
            if (!$wishlist->getId() || $wishlist->getCustomerId() != $customerId) {
                $wishlist = null;
                echo Zend_Json::encode(array("success" => false, "data" => "添加我的关注失败"));return;
            }
            $session = Mage::getSingleton('customer/session');

            $productId = (int)$this->getRequest()->getParam('id',false);
            if (!$productId) {
                echo Zend_Json::encode(array("success" => false, "data" => "请选择你要关注的产品"));
                return;
            }

            $product = Mage::getModel('catalog/product')->load($productId);
            if (!$product->getId() || !$product->isVisibleInCatalog()) {
                echo Zend_Json::encode(array("success" => false, "data" => "该产品不存在"));return;
            }

            try {
                //$requestParams = $this->getRequest()->getParams();
                $requestParams = array("product" => $productId);
                if ($session->getBeforeWishlistRequest()) {
                    $requestParams = $session->getBeforeWishlistRequest();
                    $session->unsBeforeWishlistRequest();
                }
                $buyRequest = new Varien_Object($requestParams);
                $result = $wishlist->addNewItem($product, $buyRequest);
                if (is_string($result)) {
                    Mage::throwException($result);
                }
                $wishlist->save();
                $id = $wishlist->getData("wishlist_id");
                echo Zend_Json::encode(array("success" => true, "data" => "关注成功", "wishlist_id" => $id));return;
            } catch (Exception $e) {
                echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
            }
        } catch (Exception $e) {
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }
    }

    /**
     * api for user add wishlist or not
     */
    public function userWishListAction(){
        Mage::log("receive user wishlist action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        $customerId = $session->getCustomerId();
        $productId = $this->getRequest()->getParam("product_id",false);
        if(empty($customerId) || $customerId == '' || !$customerId){
            echo Zend_Json::encode(array("success" => false));return;
        }
        if(!$productId){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择一个商品"));return;
        }
        try{
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $sql = "select a.wishlist_id from wishlist as a left join wishlist_item as b on a.wishlist_id = b.wishlist_id where a.customer_id = "."'$customerId'"." and b.product_id = "."'$productId'";
            $res = $read->fetchAll($sql);
            if(empty($res)){
                echo Zend_Json::encode(array("success" => false));return;
            }else{
                foreach($res as $item){
                    $wishlist_id = $item['wishlist_id'];
                }
                echo Zend_Json::encode(array("success" => true,"data" => $wishlist_id));return;
            }
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e));return;
        }

    }

    /**
     * api for list of wish list
     */
    public function wishListAction(){
        Mage::log("receive wishlist action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        if(!$session->isLoggedIn()){
            echo Zend_Json::encode(array("success" => false, "data" => "请先登录"));return;
        }
        $customerId = $session->getCustomerId();
        $category_id = $this->getRequest()->getParam("c_id",false);
        Mage::log("category id is :".$category_id);
        if(!$category_id){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择一个分类"));return;
        }
        try{
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $sql = "select a.wishlist_id, b.product_id, b.qty, b.wishlist_item_id from wishlist as a left join wishlist_item as b on a.wishlist_id = b.wishlist_id left join catalog_category_product as c on c.product_id = b.product_id where a.customer_id = "."'$customerId'"." and c.category_id = "."'$category_id'";
            $res = $read->fetchAll($sql);
            $arr = array();
            foreach($res as $item){
                $product_id  = $item['product_id'];
                $product     = $this->_newProduct()->load($product_id);
                $name        = $product->getData('name');
                $price       = $product->getPrice();
                $image       = $product->getSmallImageUrl();
                $qty         = $item['qty'];
                $item_id     = $item['wishlist_item_id'];
                $wishlist_id = $item['wishlist_id'];

                $arr[] = array(
                    "wishlist_id" => $wishlist_id,
                    "product_id"  => $product_id,
                    "item_id"     => $item_id,
                    "name"        => $name,
                    "price"       => $price,
                    //"image"     => Mage::getBaseUrl('media').'catalog/category'.$image,
                    "image"       => $image,
                    "qty"         => $qty
                );
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * get wish list function
     */
    public function _getWishlist($wishlistId = null)
    {
        $wishlist = Mage::registry('wishlist');
        if ($wishlist) {
            return $wishlist;
        }

        try {
            if (!$wishlistId) {
                $wishlistId = $this->getRequest()->getParam('wishlist_id');
            }
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            /* @var Mage_Wishlist_Model_Wishlist $wishlist */
            $wishlist = Mage::getModel('wishlist/wishlist');
            if ($wishlistId) {
                $wishlist->load($wishlistId);
            } else {
                $wishlist->loadByCustomer($customerId, true);
            }

            if (!$wishlist->getId() || $wishlist->getCustomerId() != $customerId) {
                $wishlist = null;
                Mage::throwException(
                    Mage::helper('wishlist')->__("Requested wishlist doesn't exist")
                );
            }

            Mage::register('wishlist', $wishlist);
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('wishlist/session')->addError($e->getMessage());
            return false;
        } catch (Exception $e) {
            Mage::getSingleton('wishlist/session')->addException($e,
                Mage::helper('wishlist')->__('Wishlist could not be created.')
            );
            return false;
        }

        return $wishlist;
    }

    /**
     * api for remove attention product
     */
    public function removeWishListAction(){
        Mage::log("receive remove wish list action.");
        $this->setJsonHeader();
        $id = (int) $this->getRequest()->getParam('item_id');
        $item = Mage::getModel('wishlist/item')->load($id);
        if (!$item->getId()) {
            echo Zend_Json::encode(array("success" => false, "data" => "请选择你要移除的关注产品"));return;
        }
        $wishlist = $this->_getWishlist($item->getWishlistId());
        if (!$wishlist) {
            echo Zend_Json::encode(array("success" => false, "data" => "系统错误，请联系管理员"));return;
        }
        try {
            $item->delete();
            $wishlist->save();

            echo Zend_Json::encode(array("success" => true, "data" => "成功移除"));return;
        } catch (Exception $e) {
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));return;
        }
        //Mage::helper('wishlist')->calculate();

        //$this->_redirectReferer(Mage::getUrl('*/*'));
    }

    /**
     * api for delete wishlist by product
     */
    public function deleteWishListByProductAction(){
        $this->setJsonHeader();
        Mage::log("receive remove wish list by product action.");
        $id = (int) $this->getRequest()->getParam('product_id',false);
        $wId = (int) $this->getRequest()->getParam("wishlist_id",false);
        if (!$id || !$wId) {
            echo Zend_Json::encode(array("success" => false, "data" => "请选择你要移除的关注产品"));return;
        }

        try {
            $read = Mage::getSingleton('core/resource')->getConnection('core_write');
            $where = "product_id = "."'$id'"." and wishlist_id = "."'$wId'";
            $read->delete('wishlist_item',$where);

            echo Zend_Json::encode(array("success" => true, "data" => "取消关注"));return;
        } catch (Exception $e) {
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));return;
        }
    }

    /**
     * api for cms
     */
    public function getCmsAction(){
        Mage::log("receive get cms action.");
        $this->setJsonHeader();
        try{
            $sql_where = "";
            $date = $this->getRequest()->getParam("date",false);
            if($date){
                $sql_where .= "and date(a.creation_time) = "."'$date'";
            }
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $select = "select a.title, a.identifier from cms_page as a left join cms_page_store as b on a.page_id = b.page_id where b.store_id = 1 and a.root_template = 'empty' ".$sql_where." order by a.creation_time desc";
            $res = $read->fetchAll($select);
            $arr = array();
            foreach($res as $item){
                $arr[] = array(
                    "title" => $item['title'],
                    "url"   => $home_url = Mage::helper('core/url')->getHomeUrl().$item['identifier']
                );
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * function for upload image
     */
    public function _upload(){
        Mage::log("receive upload image function.");
        $fileName = '';
        Mage::log("upload files is :".var_export($_FILES,true));
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            try {
                $fileName       = $_FILES['image']['name'];
                $fileExt        = strtolower(substr(strrchr($fileName, ".") ,1));
                $fileNamewoe    = rtrim($fileName, $fileExt);
                $fileName       = preg_replace('/\s+', '', $fileNamewoe) . time() . '.' . $fileExt;

                $uploader       = new Varien_File_Uploader('image');
                $uploader->setAllowedExtensions(array('png', 'jpeg','gif','jpg'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = Mage::getBaseDir('media') . DS . 'head';
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                $uploader->save($path . DS, $fileName );

                return $fileName;

            } catch (Exception $e) {
                echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
            }
        }
    }

    /**
     * api for get product by PN code
     */
    public function getProductByScanAction(){
        Mage::log("receive get product by scan action.");
        $this->setJsonHeader();
        $pn_code = $this->getRequest()->getParam('pn_code',false);
        Mage::log("pn code is:".$pn_code);
        if(!$pn_code){
            echo Zend_Json::encode(array("success" => false, "data" => "PN码没有获取到"));return;
        }
        $product = $this->_newProduct()->getCollection()
            ->addAttributeToSelect("*")
            ->addAttributeToFilter("pn",$pn_code)
            ->load();
        foreach($product as $pro){
            $arr = array("id" => $pro->getData('entity_id'),
                "sku" => $pro->getData('sku'),
                "name" => $pro->getData('name'),
                //"image" => Mage::getBaseUrl('media').'catalog/product'.$pro->getData('small_image'),
                "image" => $pro->getSmallImageUrl(),
                "price" => $pro->getData('price'),
                "qty"   => $this->_getQty($pro->getData('entity_id'))
            );
        }
        echo Zend_Json::encode(array("success" => true, "data" => $arr));
    }

    /**
     * api for get categories
     */
    public function getCategoriesByCidAction(){
        Mage::log("receive get categories by category id action.");
        $this->setJsonHeader();
        $c_id = $this->getRequest()->getParam("c_id",3);
        Mage::log("c_id is :".$c_id);
        if(!$c_id){
            echo Zend_Json::encode(array("success" => false, "data" => "分类id必须"));return;
        }
        try{
            $cate = $this->_newCategory()->getCategories($c_id);
            $arr = array();
            foreach ($cate as $category) {
                $name   = $category->getName();
                $id     = $category->getId();
                $img    = Mage::getModel('catalog/category')->load($id)->getImageUrl();
                //$img    = Mage::getBaseUrl('media').'catalog/category/'.Mage::getModel('catalog/category')->load($id)->getThumbnail();
                $arr[] = array(
                    "name"  => $name,
                    "id"    => $id,
                    "image" => $img
                );
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for list products
     */
    public function searchProductsAction(){
        Mage::log("receive search products action");
        $this->setJsonHeader();
        $session = $this->_getSession();
        $customerId = $session->getCustomerId();
        Mage::log("get customer id is :".$customerId);
        $page = $this->getRequest()->getParam("page",1);
        $pageSize = $this->getRequest()->getParam("pageSize",10);
        $search = $this->getRequest()->getParam("search",false);
        $c_id = $this->getRequest()->getParam("c_id",false);
        $sortColumn = $this->getRequest()->getParam("sortColumn",false);
        $sort = $this->getRequest()->getParam("sort","asc");
        if(!$search){
            echo Zend_Json::encode(array("success" => false, "data" => "请输入你要搜索的产品名"));return;
        }
        if(!$c_id){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择你搜索的分类"));return;
        }
        try{
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $groupId = $customer->getData('groupId');
            $products = Mage::getModel('catalog/category')->load($c_id)
                ->getProductCollection();
            $products->addAttributeToSelect('*');
            $products->addAttributeToFilter('status', 1);
            $products->addAttributeToFilter('visibility', 4);
            $products->addAttributeToFilter('name', array("like" => "%$search%"));
            if($sortColumn){
                $products->addAttributeToSort($sortColumn,$sort);
            }
            $products->setCurPage($page);
            $products->setPageSize($pageSize);
            $p = Mage::getModel('catalog/category')->load($c_id)
                ->getProductCollection();
            $p->addAttributeToFilter('name', array("like" => "%$search%"));
            if($sortColumn){
                $products->addAttributeToSort($sortColumn,$sort);
            }
            $count = $p->count();
            $arr = array();
            Mage::log("get search products the product counts is :".$count);
            $pages = ceil($count/$pageSize);
            if($page > $pages){
                echo Zend_Json::encode(array("success" => true, "data" => $arr, "count" => $count));return;
            }

            foreach($products as $pro){
                if($groupId == 2){
                    $price = $pro->getData('price');
                }else{
                    $price = '暂无报价';
                }
                $arr[] = array("id" => $pro->getData('entity_id'),
                    "sku" => $pro->getData('sku'),
                    "name" => $pro->getData('name'),
                    //"image" => Mage::getBaseUrl('media').'catalog/product'.$pro->getData('small_image'),
                    "image" => $pro->getSmallImageUrl(),
                    "price" => $price,
                    "qty"   => $this->_getQty($pro->getData('entity_id'))
                );
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr, "count" => $count));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    public function setJsonHeader(){
        return $this->getResponse()->setHeader("Content-type", "application/json;charset=utf-8");
    }

    /**
     * api for get product by category
     */
    public function getProductsByCidAction(){
        Mage::log("receive get products by category id action.");
        $this->setJsonHeader();
        $session = $this->_getSession();
        $customerId = $session->getCustomerId();
        Mage::log("get customer id is :".$customerId);
        $category_id = $this->getRequest()->getParam("c_id",false);
        $sortColumn = $this->getRequest()->getParam('sortColumn');
        //$price = $this->getRequest()->getParam('price',false);
        //$search = $this->getRequest()->getParam('search');
        $sort = $this->getRequest()->getParam('sort','asc');
        $page = $this->getRequest()->getParam('page',1);
        $pageSize = $this->getRequest()->getParam('pageSize',false);
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $groupId = $customer->getData('groupId');
        Mage::log("customer group is :".$groupId);
        if(!$category_id){
            echo Zend_Json::encode(array("success" => false, "data" => "请传入分类的id"));return;
        }
        try{
            $products = Mage::getModel('catalog/category')->load($category_id)
                ->getProductCollection();
            $products->addAttributeToSelect('*');
            $products->addAttributeToFilter('status', 1);
            $products->addAttributeToFilter('visibility', 4);
            if(!empty($sortColumn) && isset($sortColumn)){
                $products->addAttributeToSort($sortColumn,$sort);
            }
            //if(!empty($search) && isset($search)){
                //Mage::log("receive serch value is :".$search);
                //$products->addAttributeToFilter('name', array("like" => "%$search%"));
                //$products->addAttributeToFilter(array("name","description"),array(array("like" => "%$search%"),array("like" => "%$search%")));
            //}

            $products->setCurPage($page);
            $products->setPageSize($pageSize);
            //$count = $products->count();
            $arr = array();
            $count = Mage::getModel('catalog/category')->load($category_id)
                ->getProductCollection()->count();
            $pages = ceil($count/$pageSize);
            if($page > $pages){
                echo Zend_Json::encode(array("success" => true, "data" => $arr, "count" => $count, "v" => array("version" => 1.0, "url" => Mage::getBaseUrl()."app_api/api/download")));return;
            }
            foreach($products as $pro){
                if($groupId == 2 || $category_id == 6){
                    $price = $pro->getData('price');
                }else{
                    $price = '暂无报价';
                }
                $arr[] = array("id" => $pro->getData('entity_id'),
                    "sku" => $pro->getData('sku'),
                    "name" => $pro->getData('name'),
                    //"image" => Mage::getBaseUrl('media').'catalog/product'.$pro->getData('small_image'),
                    "image" => $pro->getSmallImageUrl(),
                    "price" => $price,
                    "qty"   => $this->_getQty($pro->getData('entity_id'))
                );

            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr, "count" => $count, "v" => array("version" => 2.0, "url" => Mage::getBaseUrl()."app_api/api/download")));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * function for get delivery code
     */
    public function _getDeliveryCode($order_id){
        Mage::log("receive get delivery code function.");
        $tracks = Mage::getModel('sales/order_shipment_track')
            ->getCollection()
            ->addFieldToFilter('order_id', $order_id);
        foreach($tracks as $item){
            $arr = array("track_number" => $item->getData("track_number"),"company" => $item["carrier_code"]);
        }

        return $arr;
    }

    /**
     * api for delivery info
     */
    public function getDeliveryInfoAction(){
        Mage::log("receive get delivery info action.");
        $this->setJsonHeader();
        $order_id = $this->getRequest()->getParam("order_id",false);
        if(!$order_id){
            echo Zend_Json::encode(array("success" => false, "data" => "请选择一个订单"));return;
        }
        $res = $this->_getDeliveryCode($order_id);
        $typeCom = $res["company"];//快递公司
        $typeNu = $res["track_number"];  //快递单号
        //if(empty($typeCom) || empty($typeNu)){
            //echo Zend_Json::encode(array("success" => false, "data" => "暂时还没有发货，请耐心等待"));return;
        //}
        $AppKey='XXXXXX';
        $url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$typeCom.'&nu='.$typeNu.'&show=0&muti=1&order=asc';
        //请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
        $powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';
        try{
            /*$curl = curl_init();
            curl_setopt ($curl, CURLOPT_URL, $url);
            curl_setopt ($curl, CURLOPT_HEADER,0);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
            curl_setopt ($curl, CURLOPT_TIMEOUT,3);
            $get_content = curl_exec($curl);
            curl_close ($curl);*/
            $get_content = '{"message":"ok","status":"1","state":"3","data":
            [{"time":"2012-07-07 13:35:14","context":"客户已签收"},
             {"time":"2012-07-07 09:10:10","context":"离开 [北京石景山营业厅] 派送中，递送员[温]，电话[]"},
             {"time":"2012-07-06 19:46:38","context":"到达 [北京石景山营业厅]"},
             {"time":"2012-07-06 15:22:32","context":"离开 [北京石景山营业厅] 派送中，递送员[温]，电话[]"},
             {"time":"2012-07-06 15:05:00","context":"到达 [北京石景山营业厅]"},
             {"time":"2012-07-06 13:37:52","context":"离开 [北京_同城中转站] 发往 [北京石景山营业厅]"},
             {"time":"2012-07-06 12:54:41","context":"到达 [北京_同城中转站]"},
             {"time":"2012-07-06 11:11:03","context":"离开 [北京运转中心驻站班组] 发往 [北京_同城中转站]"},
             {"time":"2012-07-06 10:43:21","context":"到达 [北京运转中心驻站班组]"},
             {"time":"2012-07-05 21:18:53","context":"离开 [福建_厦门支公司] 发往 [北京运转中心_航空]"},
             {"time":"2012-07-05 20:07:27","context":"已取件，到达 [福建_厦门支公司]"}
            ]}';
            $res = json_decode($get_content,true);
            if($res['status'] == 1){
                echo Zend_Json::encode(array("success" => true, "data" => $res['data']));
            }else if($res['status'] == 0){
                echo Zend_Json::encode(array("success" => false, "data" => "暂时没有查询到物流信息，请稍后在查"));
            }else if($res['status'] == 2){
                echo Zend_Json::encode(array("success" => false, "data" => "系统错误，请联系管理员"));
            }
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }

    }

    /**
     * api for banner
     */
    public function getBannerAction(){
        Mage::log("receive get banner action.");
        $this->setJsonHeader();
        try{
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $select = "select * from banner7 where status = 1";
            $res = $read->fetchAll($select);
            $arr = array();
            foreach($res as $item){
                $arr[] = array(
                    "title" => $item['title'],
                    "url" => $item['link'],
                    "image" => Mage::getBaseUrl("media").$item['image']
                );
            }
            echo Zend_Json::encode(array("success" => true, "data" => $arr));
        }catch (Exception $e){
            echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
        }
    }

    /**
     * api for upload android apk
     */
    public function uploadAction(){
        Mage::log("receive upload android apk action.");
        $fileName = '';
        Mage::log("upload files is :".var_export($_FILES,true));
        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
            try {
                $fileName       = $_FILES['file']['name'];
                $uploader       = new Varien_File_Uploader('file');
                $uploader->setAllowedExtensions(array('apk'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = Mage::getBaseDir('media') . DS . 'apk';
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                $uploader->save($path . DS, $fileName );
                echo Zend_Json::encode(array("success" => true, "data" => "上传成功"));

            } catch (Exception $e) {
                echo Zend_Json::encode(array("success" => false, "data" => $e->getMessage()));
            }
        }
    }

    /**
     * api for download android apk
     */
    public function downloadAction(){
        Mage::log("receive download action.");
        $name = "android.apk";
        $file_dir = "/opt/lampp/htdocs/magento/media/apk/";
        //$file_dir = "C:/xampp/htdocs/magento/media/apk/";
        if (!file_exists($file_dir.$name)){
            echo Zend_Json::encode(array("success" => false, "data" => "文件未找到"));return;
        } else {
            $file = fopen($file_dir.$name,"r");
            Mage::log("file size is :".filesize($file_dir.$name));
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: ".filesize($file_dir . $name));
            Header("Content-Disposition: attachment; filename=".$name);
            Header("Content-Length: " .filesize($file_dir . $name));
            echo fread($file, filesize($file_dir.$name));
            fclose($file);
        }
    }

}
