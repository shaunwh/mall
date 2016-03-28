<?php

class Mage_Adminhtml_Model_System_Config_Source_Imagelist {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        $url = Mage::getBaseDir('media') . '/banner_images/default'; 

        function dirImages($dir) {
            $d = dir($dir); //Open Directory
            while (false !== ($file = $d->read())) { 
               // $extension = substr($file, strrpos($file, '.')); // Gets the File Extension
              // echo $file;
               $extension = explode(".",$file);
               //print_r($extension);
                $extensionsdir = strtolower($extension[1]);
               // echo $extensionsdir;
                if ($extensionsdir == "jpg" || $extensionsdir == "jpeg" || $extensionsdir == "gif" | $extensionsdir == "png") // Extensions Allowed
                    $images[$file] = $file; // Store in Array

            }
            $d->close(); 


            return $images;
        }

        $array = dirImages($url);

        function DateCmp($a, $b) {
            return ($a[1] < $b[1]) ? -1 : 0;
        }

        function SortByDate(&$Files) {
            usort($Files, 'DateCmp');
        }

        SortByDate($array);

//To list in the select box.
        foreach ($array as $key => $image) { 
            $selectvalue[] = array('value' => $image, 'label' => Mage::helper('adminhtml')->__($image),);
        }
       
        return $selectvalue;
    }

}
