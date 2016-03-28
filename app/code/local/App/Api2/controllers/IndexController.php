<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-16
 * Time: 下午3:01
 */

class App_Api2_IndexController extends Mage_Core_Controller_Front_Action{

    /**
     * function for new customer class
     * @return Mage_Core_Model_Abstract
     */
    protected function _newCustomer(){
        return Mage::getSingleton('customer/customer');
    }

    protected function _newModel(){
        return Mage::getSingleton('products/blogpost');
    }

    //mobile message action
    public function mobileAction() {
        $mobile = $this->getRequest()->getParam('mobile');
        $secret = rand(100000, 999999);

        $this->_getSession()->setData('mcode', array('mobile'=>$mobile, 'secret'=>$secret));

        $message = iconv('UTF-8', 'GBK//IGNORE', "你的验证码是： $secret");
        $url = sprintf("http://xxxxxx.com/sys_port/gateway/?id=xxxx&pwd=xxxxx&to=%s&content=%s&time=%s", $mobile, urlencode($message), time());

        $client = new Zend_Http_Client($url, array('adapter'=>'Zend_Http_Client_Adapter_Curl'));
        $response = $client->request();
        if ($response->getStatus() == '200') {
            Mage::log("发送验证码到 $mobile 成功，验证码是 $secret，".$response->getBody());
            $tokens = explode("/", $response->getBody());
            if (count($tokens>1) && $tokens[1]=='Send:1') {
                $this->getResponse()->setBody("OK");
            } else {
                $this->getResponse()->setBody("ERROR: ".$response->getBody());
            }
        } else {
            Mage::log("发送验证码到 $mobile 失败，验证码是 $secret，".$response->getBody());
            $this->getResponse()->setBody("FAIL: {$response->getStatus()}, ".$response->getBody());
        }
    }

    //get categories
    public function get_categories($categories){
        //这里的变量 $array 将存储的是所有目录信息
        $array = '<ul>';

        foreach ($categories as $category) {
            $cat    = Mage::getModel('catalog/category')->load($category->getId());
            $count  = $cat->getProductCount(); //$count 是该目录含有产品的数量

            $array .= '<li>'
                . '<a href="' . Mage::getUrl($cat->getUrlPath()). '">' //获得到目录的 URL
                . $category->getName() //显示目录名称
                . "(".$count.")</a>"; //显示目录中产品数量

            if ($category->hasChildren()) { //检查该目录是否含有子目录，如果有，则递归生成子目录
                $children = Mage::getModel('catalog/category')
                    ->getCategories($category->getId());
                $array  .=  $this->get_categories($children); //递归生成子目录
            }
            $array .= '</li>';
        }
        return  $array . '</ul>';
    }

    /**
     * function for new products class
     * @return Mage_Core_Model_Abstract
     */
    protected function _newProducts(){
        return Mage::getSingleton('catalog/product');
    }

    protected function _getCustomerSession(){
        return Mage::getSingleton('customer/session');
    }

    public function deAction(){
        $customer = Mage::getModel('customer/customer')->setId(1)->delete();

        var_dump($customer->getData());exit;
    }

    public function getAction(){
        echo 'api2 module.';exit;
        Mage::log("receive customer module.");
        try {
            try{
                $customer = Mage::getModel('customer/customer');
                $customer->setGroupId(1);
                $customer->setEmail('nimeimei@126.com');
                $customer->setFirstname('ni');
                $customer->setLastname('mei');
                $customer->setPassword('1qaz2wsx');
                $customer->setPhone('13556789090');
                $customer->setHeadpic('/heda/a.png');
                $customer->setConfirmation(null);
                $customer->save();
                var_dump($customer->getData());exit;
            }catch (Exception $e){
                echo $e->getMessage();
            }

            $customer->getGroupId();
            $customer->setId(null);
            $customer->setData('firstname','wanghe1');
            $customer->setData('lastname','li1');
            $customer->setData('password','1qaz2wsx');
            $customer->setData('password_hash','9ea1693434ce4b84a23e9956738fe3ed:QiHTn2mrxqw1iki49GBH8DMZHLJ3FYvm');
            $customer->setData('phone','15532221111');
            $customer->setData('email','nihao22@163.com');
            $customer->setData('head_pic','headpic');
            $customer->save();
            var_dump($customer->getData());exit;
            $cus = $customer->getCollection()
                ->addAttributeToSelect("*")
                ->load();
            $arr = array();
            foreach($cus as $cu){
                $arr[] = $cu->getData();
            }
            var_dump($arr);exit;
            $customer = $this->_getCustomerSession();
            if($customer->isLoggedIn()){
                echo 'true';return;
            }else{
                echo 'false';return;
            }
            //get customer info
            $customer = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToSelect('*')
            ->load();
            foreach($customer as $cus){
                $arr[] = $cus->getData();
            }

            var_dump($arr);exit;
            //delete order
            $order = Mage::getModel('sales/order')->load(1);
            $res = $order->getData();
            var_dump($res);exit;
            $order = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToSelect("*")
                ->load();
            $arr = array();
            foreach($order as $order){
                $arr[] = $order->getData();
            }
            var_dump($arr);exit;
            try {
                $category->delete();
            } catch (Mage_Core_Exception $e) {
                Mage::log('error message is :'.$e->getMessage());
            }
            exit;
            // get categories tree
            $categories = Mage::getModel('catalog/category')->getCategories(Mage::app()->getStore()->getRootCategoryId());
            echo $this->get_categories($categories);exit;

            $category = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', 7)
                ->load();
            //$category->delete('entity_id',8);
            $arr = array();
            foreach($category as $res){
                //$res->delete(7);
                //$res->deleteData('entity_id',7);
                $arr[] = $res->getData();
            }

            var_dump($arr);exit;
            //get user info by user id
            $customer = Mage::getModel('customer/customer')->load(1)->getData();
            var_dump($customer);exit;
            //get category data paging/searching
            $entity_id = 3;
            $page = 1;
            $pageSize = 20;
            $sort = 'ASC';
            $category = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter(array(array('attribute'=>'entity_id', '=' => $entity_id)))
                ->addAttributeToSort($entity_id,$sort)
                ->setCurPage($page)
                ->setPageSize($pageSize)
                ->load();
            $arr = array();
            foreach($category as $cate){

                //$arr[] = $cate->getData();
                $cate->setData('name','豪车');
                $arr[] = $cate->getData();
            }
            var_dump($arr);exit;
            //get customer info by user id
            $customer = Mage::getModel('customer/customer')->load(1)->getData();
            //get product by product id
            $product = Mage::getModel('catalog/product')->load(2);
            // rest api for get products by product id
            $product = Mage::getModel('catalog/Product_Api_V2')->info(2);
            var_dump(json_encode($product));exit;
            $res['id'] = $product->getData('entity_id');
            $res['sku'] = $product->getData('sku');
            $res['product_name'] = $product->getData('name');
            $res['price'] = $product->getData('price');
            $res['image'] = $product->getData('small_image');
            $res['qty'] = $product->getData('qty');
            var_dump(json_encode($res));exit;
            var_dump($customer);exit;
            $products = $this->_newProducts();
            $res = $products->getCategory();
            //$res = $products->getUrlModel();
            // get store id
            //$products = $this->getStoreId();
            var_dump($res);exit;
            // customer model
            $abc = $this->_getCustomer();
            $res = $abc->getSharingConfig();
            var_dump($res);exit;

            var_dump(get_class($abc));exit;
            $arr = Mage::getModel('customer/customer');
            $pro = $arr->getCollection()
                ->addAttributeToSelect('*')
                ->load();
            $array = array();
            foreach($pro as $p){
                $p->getData();
                $array[] = array(
                    "name" => $p->getName(),
                    "phone" => $p->getPhone(),
                    "group" => $p->getGroupId()
                );
            }
            var_dump($array);exit;
            // product model
            $model = Mage::getModel('catalog/product');
            $_productCollection = $model->getCollection()
                ->addAttributeToSelect('*')
                ->load();
            $json_products = array();
            foreach($_productCollection as $_product){
                $_product->getData();
                $json_products[] = array(
                    'url' => ''.$_product->getProductUrl().'',
                    'description' => ''.nl2br($_product->getShortDescription()).'',
                    'price' => ''.$_product->getFormatedPrice().''
                );
            }
            var_dump($json_products);
        } catch (Exception $e) {
            Mage::log($e);
        }
    }

    public function indexAction() {
        $id = $this->getRequest()->getParam('id');

        if($id) {
            $_category = Mage::getModel('catalog/category')->load($id);
            $product = Mage::getModel('catalog/product');

            //load the category's products as a collection
            $_productCollection = $product->getCollection()
                ->addAttributeToSelect('*')
                ->addCategoryFilter($_category)
                ->load();

            // build an array for conversion
            $json_products = array();
            foreach ($_productCollection as $_product) {
                $_product->getData();
                $json_products[] = array(
                    'name' => ''.$helper->htmlEscape($_product->getName()).'',
                    'url' => ''.$_product->getProductUrl().'',
                    'description' => ''.nl2br($_product->getShortDescription()).'',
                    'price' => ''.$_product->getFormatedPrice().'');
            }

            $data = json_encode($json_products);

            echo $data;
        }
    }
    /**
     * function for verify user login status and vip level.
     */
    public function _verifyUser($userId){
        //todo verify in databases; exit return true else return false;
    }

    /**
     * function for login app
     */
    public function loginAction(){
        $userId = $this->getRequest()->getParam('userId');
        $userName = $this->getRequest()->getParam('userName');
        $password = $this->getRequest()->getParam('password');
        Mage::log("sign in userName is :".$userName);
        Mage::log("sign in password is :".$password);
        Mage::log("login userId is: ".$userId);
        if(!$userId){
            return 'not sign in';
        }
        $verify = $this->_verifyUser($userId);
        if($verify){
            return 'login success';
        }else{
            return 'not sign in';
        }

    }

    /**
     * function for sign in app
     */
    public function signInAction(){
        $userName = $this->getRequest()->getParam('userName');
        $password = $this->getRequest()->getParam('password');
        Mage::log("sign in userName is :".$userName);
        Mage::log("sign in password is :".$password);
        //todo 验证用户名是否可以用 保存数据库 成功后跳转首页
        $customer = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToSelect('*')
            ->load();
        $arr = array();
        foreach($customer as $cus){
            $arr[] = $cus->getData();
        }
        var_dump($arr) ;
    }

    /**
     * function for product category app
     */
    public function getCategoryAction(){

    }

    /**
     * function for product list, paging/order/category
     */
    public function getProductsAction(){
        //todo 每次获取数据的时候都需要验证用户是否登录，登录以后是普通会员还是高级会员。1、无登录情况：可以展示产品，但是价格需要处理不能显示，促销产品可以显示价格。2、普通会员登录情况：可以展示产品，但是价格不能显示，促销产品可以显示价格，并且下单。3、高级会员登录情况：展示产品，价格也展示，都可以下单。
        //logic 1、需要判断是否登录 根据userId 。 2、需要判断是普通会员还是高级会员 标识会在注册的时候返给前端
    }

    /**
     * function for get product detail
     */
    public function getProductDetailAction(){
        $productId = $this->getRequest()->getParam('entity_id');
        Mage::log("get product id is:".$productId);
        $product = Mage::getModel('catalog/product')->load($productId);
        if(!$product){
            return false;
        }
        $res['id'] = $product->getData('entity_id');
        $res['sku'] = $product->getData('sku');
        $res['product_name'] = $product->getData('name');
        $res['price'] = $product->getData('price');
        $res['image'] = $product->getData('small_image');

        return $product;
    }

    /**
     * function for get user information
     */
    public function getUserInfoAction(){
        $userId = $this->getRequest()->getParam('entity_id');
        Mage::log("get user id is :".$userId);
        $userInfo = Mage::getModel('customer/customer')->load($userId);
        if(!$userInfo){
            return false;
        }

        return $userInfo;
    }

    /**
     * function for get user shop cart
     */
    public function getUserCartAction(){
        $userId = $this->getRequest()->getParam('user_id');
        Mage::log("get user id is :".$userId);
        $cartInfo = Mage::getModel('sales/cart')->load($userId);
        if(!$cartInfo){
            return false;
        }

        return $cartInfo;
    }

    /**
     * function for delete user shop cart
     */
    public function removeProductFormCartAction(){

    }
}