<?php
namespace AppBundle\Helper;
class ImageResizer {
    public $options = NULL;
    public $image = NULL;
    public function copy($old, $way, $file = NULL) {
        if (is_file($old)) {
            if ($file === NULL) {
                if ($this->options === NULL) {
                    $this->getImageDetails($old);
                }
                $file = $this->options->name.'.'.$this->options->extension;
            }
            $new = $way.'/'.$file;
            if (is_file($new)) {
                @unlink($new);
            }
            copy($old, $new);
            return $file;
        }
        return false;
    }
    /**
     * Resize an image
     *
     * @param string $image (The full image path with filename and extension)
     * @param string $newPath (The new path to where the image needs to be stored)
     * @param int $height (The new height to resize the image to)
     * @param int $width (The new width to resize the image to)
     * @return string (The new path to the reized image)
     */
    public function resizeImage($image, $newPath, $height=0, $width=0){
        $this->getImageDetails($image);
        $jpegQuality = 75;
        if ($width > $this->options->width && $height > $this->options->height){
            return false;
        }
        if($height > 0){
            $width = $height * $this->options->ratio;
        } else if($width > 0){
            $height = $width / $this->options->ratio;
        }
        $width = round($width);
        $height = round($height);
        $gd_image_dest = imagecreatetruecolor($width, $height);
        $gd_image_src = null;
        $newFileName = $newPath . $this->options->name .'.'. $this->options->extension;
        switch( $this->options->extension ){
            case 'png' :
                $gd_image_src = imagecreatefrompng($image);
                imagealphablending( $gd_image_dest, false );
                imagesavealpha( $gd_image_dest, true );
                imagecopyresampled($gd_image_dest, $gd_image_src, 0, 0, 0, 0, $width, $height, $this->options->width, $this->options->height);
                imagepng($gd_image_dest, $newFileName);
                break;
            case 'jpeg': case 'jpg': $gd_image_src = imagecreatefromjpeg($image); 
                imagecopyresampled($gd_image_dest, $gd_image_src, 0, 0, 0, 0, $width, $height, $this->options->width, $this->options->height);
                imagejpeg($gd_image_dest, $newFileName, $jpegQuality);
                break;
            case 'gif' : $gd_image_src = imagecreatefromgif($image); 
                imagecopyresampled($gd_image_dest, $gd_image_src, 0, 0, 0, 0, $width, $height, $this->options->width, $this->options->height);
                imagegif($gd_image_dest, $newFileName);
                break;
            default: break;
        }
        $this->getImageDetails($image, TRUE);
        return $newPath;
    }
    /**
     *
     * Gets image details such as the extension, sizes and filename and returns them as a standard object.
     *
     * @param $imageWithPath
     * @return \stdClass
     */
    public function getImageDetails($image, $clear = false){
        if (($this->options === Null) || ($this->image != $image) || ($clear === true)) { 
            $this->image = $image;
            $this->options = new \stdClass();
            $size = getimagesize($image);
            $this->options->height = $size[1];
            $this->options->width = $size[0];
            $this->options->ratio = $size[0] / $size[1];
            $this->options->size = filesize($image);
            $imgParts = explode("/",$image);
            $lastPart = $imgParts[count($imgParts)-1];
            if(stristr("?",$lastPart)){
                $lastPart = substr($lastPart,0,stripos("?",$lastPart));
            }
            if(stristr("#",$lastPart)){
                $lastPart = substr($lastPart,0,stripos("#",$lastPart));
            }
            $dotPos = stripos($lastPart,".");
            $name = substr($lastPart,0,$dotPos);
            $extension = substr($lastPart,$dotPos+1);
            $this->options->extension = $extension;
            $this->options->name = $name;
        }
        return $this->options;
    }
    public function rotate($image, $angle) {
        $this->getImageDetails($image);
        $source = $this->get($image);
        $source = imagerotate($source, $angle, 0);
        $this->write($source, $image);
        $this->getImageDetails($image, TRUE);
    }
    public function crop($image, array $option) {
        $this->getImageDetails($image);
        $source = $this->get($image);
        $source = imagecrop($source, $option);
        $this->write($source, $image);
        $this->getImageDetails($image, TRUE);
    }
    public function getIndent($value, $str) {
        return (int) floor(($value-$str)/2);
    }
    public function square($image) {
        $this->getImageDetails($image);
        if ($this->options->width != $this->options->height) {
            $str = $this->options->width;
            $x = 0;
            $y = 0;
            if($str > $this->options->height) {
                $str = $this->options->height;
                $x = $this->getIndent($this->options->width, $str);
            } else {
                $y = $this->getIndent($this->options->height, $str);
            }
            $source = imagecreatetruecolor($str, $str);
            switch( $this->options->extension ){
                case 'png' :
                    $gd_image_src = imagecreatefrompng($image);
                    imagealphablending( $source, false );
                    imagesavealpha( $source, true );
                    imagecopyresampled($source, $gd_image_src, 0, 0, $x, $y, $str, $str, $str, $str);
                    imagepng($source, $image);
                    break;
                case 'jpeg': case 'jpg': $gd_image_src = imagecreatefromjpeg($image); 
                    imagecopyresampled($source, $gd_image_src, 0, 0, $x, $y, $str, $str, $str, $str);
                    imagejpeg($source, $image);
                    break;
                case 'gif' : $gd_image_src = imagecreatefromgif($image); 
                    imagecopyresampled($source, $gd_image_src, 0, 0, $x, $y, $str, $str, $str, $str);
                    imagegif($source, $image);
                    break;
                default: break;
            }
            $this->getImageDetails($image, TRUE);
        }
    }
    function get($image) {
        switch( $this->options->extension ){
            case 'png' : 
                $source = imagecreatefrompng($image);
                break;
            case 'jpeg' : case 'jpg' : 
                $source = imagecreatefromjpeg($image);
                break;
            case 'gif' : 
                $source = imagecreatefromgif($image);
                break;
            default: break;
        }
        return $source;
    }
    function write($source, $image) {
        switch( $this->options->extension ){
            case 'png' :
                imagepng($source, $image);
                break;
            case 'jpeg' :
            case 'jpg' :
                imagejpeg($source, $image);
                break;
            case 'gif' :
                imagegif($source, $image);
                break;
            default: break;
        }
    }
}
?>