<?php
class Magentothem_Banner7_Block_Render_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        $url = Mage::getBaseUrl('media').$value;
        $img = "<img src=$url alt=$value width='180' height='80'/>";
        //return $value;
        return $img;
    }
}