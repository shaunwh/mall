<?php
class Magentothem_Catlist_Block_Catlist extends Mage_Core_Block_Template
{

    protected $level = 1;
    public $cats = array();

    public function getCatStore()
    {
        $rootcatId= Mage::app()->getStore()->getRootCategoryId();
        $categories = Mage::getModel('catalog/category')->getCategories($rootcatId);
        return $categories;
    }

    // protected function setCatImage()
    // {
    //     return 2; // config level display image
    // }

    public function getListCfg($cfg)
    {
        return Mage::helper('catlist')->getListCfg($cfg);
    }

    public function getCatRootId()
    {
        return $rootcatId= Mage::app()->getStore()->getRootCategoryId();
    }
    public function getCatListTop()
    {
        $collection = Mage::getModel('catalog/category')
                            ->getCollection()
                            ->addAttributeToSelect('entity_id')
                            ->addAttributeToSelect('name')
                            ->addAttributeToSelect('thumbnail')
                            ->addAttributeToSelect('url_path')
                            ->addFieldToFilter('parent_id', array('eq'=>$this->getCatRootId()))
                            ->addFieldToFilter('catlist', array('eq'=>'1'))
                            ->addFieldToFilter('is_active', array('eq'=>'1'));
        return $collection;
    }

    public function getCatByPath($parentId, $path)
    {
        $collection = Mage::getModel('catalog/category')->getCollection()
                        ->addAttributeToSelect('name')
                        ->addAttributeToSelect('url_path')
                        ->addAttributeToFilter('entity_id', array('neq' => $parentId))
                        ->addFieldToFilter('path', array('like' => "$path%"))
                        //->addAttributeToSort('path', 'asc')
                        ->addAttributeToSort('level', 'asc')
                        ->addFieldToFilter('is_active', array('eq'=>'1'))
                        ->addFieldToFilter('catlist', array('eq'=>'1'))
                        //->getSelect()->limit(5)
                        //->load(5) // display SQL
                        ->load();
                        //->toArray();
        return $collection;
    }

    public function getImage($cat)
    {
        return $this->getCatResizedImage($cat, $this->getListCfg('width_thumbnail'), $this->getListCfg('height_thumbnail') );
    }

    public function getCatResizedImage($cat ,$width, $height = null, $quality = 100) {
		$cat = Mage::getModel('catalog/category')->load($cat->getId());

        if (! $cat->getCatlistThumbnail())
            return false;

        $imageUrl = Mage::getBaseDir ( 'media' ) . DS . "catalog" . DS . "category" . DS . $cat->getCatlistThumbnail();
        if (! is_file ( $imageUrl ))
            return false;

            $imageResized = Mage::getBaseDir ( 'media' ) . DS . "catalog" . DS . "category" . DS . "cache" . DS . "cat_resized" . DS . $cat->getCatlistThumbnail();// Because clean Image cache function works in this folder only
            if (! file_exists ( $imageResized ) && file_exists ( $imageUrl ) || file_exists($imageUrl) && filemtime($imageUrl) > filemtime($imageResized)) :
                $imageObj = new Varien_Image ( $imageUrl );
            $imageObj->constrainOnly ( true );
            $imageObj->keepAspectRatio ( true );
            $imageObj->keepFrame ( true ); // ep
            $imageObj->quality ( $quality );
            $imageObj->keepTransparency(true);  // png
            $imageObj->backgroundColor(array(255,255,255));
            $imageObj->resize ( $width, $height );
            $imageObj->save ( $imageResized );
            endif;
            
            if(file_exists($imageResized)){
                return Mage::getBaseUrl ( 'media' ) ."/catalog/category/cache/cat_resized/" . $cat->getCatlistThumbnail();
            }else{
                return $this->getImageUrl();
            }

        }

    }