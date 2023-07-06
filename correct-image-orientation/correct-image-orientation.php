<?php
/*
Plugin Name: Correct Image Orientation
Plugin URI: https://www.wpospeed.com
Description: This plugin corrects the orientation of images uploaded from an iPhone based on their EXIF metadata
Version: 1.0
Author: Cristian A Sánchez G
Author URI: https://www.linkedin.com/in/cristian246/
*/

function correct_image_orientation($file) {
    if (function_exists('exif_read_data')) {
        $exif = exif_read_data($file['tmp_name']);
        if($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            if($orientation != 1){
                $img = imagecreatefromjpeg($file['tmp_name']);
                $deg = 0;
                switch ($orientation) {
                    case 3:
                        $deg = 180;
                        break;
                    case 6:
                        $deg = 270;
                        break;
                    case 8:
                        $deg = 90;
                        break;
                }
                if ($deg) {
                    $img = imagerotate($img, $deg, 0);        
                }
                imagejpeg($img, $file['tmp_name'], 95);
            }
        }
    }
    return $file;
}

add_filter('wp_handle_upload_prefilter', 'correct_image_orientation'); 

?>
