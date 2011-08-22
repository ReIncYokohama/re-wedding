<?php
/**
 * Image
 * 
 * Class for manipulating images as PHP objects
 *
 * @package default
 * @author Dom Hastings
 */
class Image {
  /**
   * options
   *
   * @var array Contains the options for the functions
   * @access private
   */
  private $options = array(
    // array Load options
    'load' => array(
      // integer Force the input type of image
      'forceType' => false
    ),

    // array Resizing specific options
    'scale' => array(
      // boolean Whether or not to force the resize (true) or preserve the ratio
      'force' => false
    ),
    
    // array Cutout specific options
    'cutout' => array(
      // mixed If null will default to taking the cutout from the absolute center of the image, otherwise uses the co-ordinates specified
      'offsetX' => null,
      'offsetY' => null,

      // mixed If null defaults to the smallest possible size, otherwise resizes (forced) to the specified size
      'sourceX' => null,
      'sourceY' => null
    ),
    
    // array Whitespace specific options
    'whitespace' => array(
      // string HTML hex code for the 'white' space
      'color' => '#ffffff',
      // integer Transparency value (see http://php.net/imagecolorallocatealpha)
      'transparency' => 127,
      // string Filename for applying as a background image
      'image' => '',
      // dimensions for scaling the image
      'scaleX' => null,
      'scaleY' => null,
      // offsets for placing the image
      'offsetX' => 0,
      'offsetY' => 0
    ),
    
    // array Watermarking options
    'watermark' => array(
      // mixed If null will default to taking the placing the watermark in the absolute center of the image, otherwise uses the co-ordinates specified
      'offsetX' => null,
      'offsetY' => null,
      // boolean Repeats the image on the specified axis
      'repeatX' => true,
      'repeatY' => true
    ),
    
    // array Text options
    'text' => array(
      // string The font file to use (TTF)
      'font' => '',
      // integer The font size in px (GD) or pt (GD2)
      'size' => 10,
      // integer The angle
      'angle' => 0,
      // string HTML colour code
      'color' => '#000',
      // integer Transparency value (see http://php.net/imagecolorallocatealpha)
      'transparency' => 0
    ),
    
    // array Line options
    'line' => array(
      // array The style of the line (see http://php.net/imagesetstyle)
      'style' => array(),
      // integer The line size in px
      'size' => 1,
      // string HTML colour code
      'color' => '#000',
      // integer Transparency value (see http://php.net/imagecolorallocatealpha)
      'transparency' => 0
    ),
    
    // array Line options
    'box' => array(
      // array The style of the line (see http://php.net/imagesetstyle)
      'style' => array(),
      // integer The line size in px
      'size' => 1,
      // string HTML colour code
      'color' => '#000',
      // integer Transparency value (see http://php.net/imagecolorallocatealpha)
      'transparency' => 0,
      // boolean If the box is filled or not
      'filled' => true
    ),
    
    // array Outputting options
    'output' => array(
      // integer Force the output type of image
      'forceType' => false,
      // array File options
      'file' => array(
        // boolean Whether to append the default extension
        'extension' => false
      ),
      // array JPEG options
      'jpeg' => array(
        // integer The quality parameter of imagejpeg() (http://php.net/imagejpeg)
        'quality' => 85
      ),
      // array PNG options
      'png' => array(
        // integer The quality parameter of imagepng() (http://php.net/imagepng)
        'quality' => 1,
        // integer The filters parameter...
        'filters' => PNG_ALL_FILTERS
      )
    )
  );
  
  /**
   * filename
   *
   * @var string The filename of the source image
   * @access private
   */
  private $filename = '';
  
  /**
   * source
   *
   * @var resource The GD image resource
   * @access private
   */
  private $source = null;
  
  /**
   * current
   *
   * @var resource The GD image resource
   * @access private
   */
  private $current = null;
  
  /**
   * info
   *
   * @var array The data from getimagesize() (http://php.net/function.getimagesize)
   * @access private
   */
  private $info = null;
  
  /**
   * __construct
   * 
   * The constructor for the Image object
   *
   * @param string $f The filename of the source image
   * @param array $o The options for the object
   * @access public
   * @author Dom Hastings
   */
  public function __construct($f, $o = array()) {
    if (file_exists($f)) {
      $this->options = array_merge_recursive_distinct($this->options, is_array($o) ? $o : array());
      
      // store the filename
      $this->filename = $f;

      // load the image
      $this->load();

    } else {
      throw new Exception('Imgae::__construct: Unable to load image \''.$f.'\'.');
    }
  }
  
  /**
   * __get
   * 
   * Magic method wrapper for specific properties
   *
   * @param string $p The property being retrieved
   * @return mixed The return value of the function called
   * @access public
   * @author Dom Hastings
   */
  public function __get($p) {
    // only run this function if the image loaded successfully
    if (!$this->source) {
      throw new Exception('Image::__get: No image loaded.');
    }
    
    // switch the property
    switch ($p) {
      // return the image width
      case 'x':
      case 'width':
        return $this->x();
        
        break;
      
      // return the image height
      case 'y':
      case 'height':
        return $this->y();
        
        break;
      
      // return the image width
      case 'currentX':
      case 'currentWidth':
        return $this->currentX();
        
        break;
      
      // return the image height
      case 'currentY':
      case 'currentHeight':
        return $this->currentY();
        
        break;
      
      // return the image size ratio
      case 'ratio':
        return $this->x() / $this->y();
        
        break;
      
      // return the image size details
      case 'size':
        return array($this->x(), $this->y());
        
        break;
      
      // return the image information
      case 'mimetype':
        return $this->info[3];
        
        break;
      
      // return the image information
      case 'extension':
        return image_type_to_extension(
          (!empty($this->options['forceWriteType']) ? $this->options['forceWriteType'] : $this->info[2])
        );
        
        break;
      
      // return the image information
      case 'imagetype':
        return $this->info[2];
        
        break;
      
      // return the image information
      case 'info':
        return $this->info;
        
        break;
      
      // not caught
      default:
        throw new Exception('Image::__get: Undefined property');
        
        break;
    }
  }
  
  /**
   * __set
   * 
   * Magic method wrapper for setting values
   *
   * @param string $p The property being 'set'
   * @param mixed $v The value to 'set' property to
   * @return void
   * @access public
   * @author Dom Hastings
   */
  public function __set($p, $v) {
    switch ($p) {
      case 'width':
      case 'x':
        $this->scale($v, 0);
        break;
      
      case 'height':
      case 'y':
        $this->scale(0, $v);
        break;
      
      case 'watermark':
        $this->watermark($v);
        break;
      
      case 'type':
        $this->options['output']['forceType'] = $v;
        break;
      
      default:
        break;
    }
  }
  
  /**
   * load
   * 
   * Loads the image and saves the details
   *
   * @return void
   * @access private
   * @author Dom Hastings
   */
  private function load($options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['load'])) ? $this->options['load'] : array(),
      (is_array($options)) ? $options : array()
    );

    // get the image details stored
    $this->info();
    
    // if we're forcing a read type
    if (!empty($options['forceType'])) {
      // use it
      $imageType = $options['forceType'];
      
    } else {
      // otherwise use the discovered type
      $imageType = $this->info[2];
    }

    $this->source = $this->current = $this->loadFile($this->filename, $imageType);
    
    // if the image loading failed
    if (!$this->source) {
      throw new Exception('Imgae::load: Unable to load image \''.$this->filename.'\'.');
    }
  }
  
  /**
   * loadFile
   * 
   * Loads an image image from a file
   *
   * @param string f The filename
   * @param string imageType The type of image
   * @return resource The loaded image
   * @access private
   * @author Dom Hastings
   */
  private function loadFile($f = null, $imageType = null) {
    // switch the type and load using the correct function
    switch ($imageType) {
      case IMAGETYPE_GIF:
        $resource = imagecreatefromgif($this->filename);
        break;
        
      case IMAGETYPE_JPEG:
      case IMAGETYPE_JPEG2000:
      case IMAGETYPE_JPC:
      case IMAGETYPE_JP2:
      case IMAGETYPE_JPX:
        $resource = imagecreatefromjpeg($this->filename);
        break;
        
      case IMAGETYPE_PNG:
        $resource = imagecreatefrompng($this->filename);
        break;
        
      case IMAGETYPE_BMP:
      case IMAGETYPE_WBMP:
        $resource = imagecreatefromwbmp($this->filename);
        break;
        
      case IMAGETYPE_XBM:
        $resource = imagecreatefromxbm($this->filename);
        break;
        
      case IMAGETYPE_TIFF_II:
      case IMAGETYPE_TIFF_MM:
      case IMAGETYPE_IFF:
      case IMAGETYPE_JB2:
      case IMAGETYPE_SWF:
      case IMAGETYPE_PSD:
      case IMAGETYPE_SWC:
      // case IMAGETYPE_ICO:
      default:
        $resource = null;
        break;
    }
    
    return $resource;
  }

  /**
   * output
   * 
   * Output the image
   *
   * @param string $f (Optional) The filename to output to, if this is omitted the image is output to the browser
   * @return void
   * @access private
   * @author Dom Hastings
   */
  public function output($f = null, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['output'])) ? $this->options['output'] : array(),
      (is_array($options)) ? $options : array()
    );

    // if we're forcing an output type
    if (!empty($options['forceType'])) {
      $imageType = $options['forceType'];
      
    } else {
      $imageType = $this->info[2];
    }
    
    // use the correct output function
    switch ($imageType) {
      case IMAGETYPE_GIF:
        header('Content-type: '.image_type_to_mime_type($imageType));
        imagegif($this->current, $f);
        break;
        
      case IMAGETYPE_JPEG:
      case IMAGETYPE_JPEG2000:
      case IMAGETYPE_JPC:
      case IMAGETYPE_JP2:
      case IMAGETYPE_JPX:
        header('Content-type: '.image_type_to_mime_type($imageType));
        imagejpeg($this->current, $f, $options['jpeg']['quality']);
        break;
        
      case IMAGETYPE_PNG:
        header('Content-type: '.image_type_to_mime_type($imageType));
        imagepng($this->current, $f, $options['png']['quality'], $options['png']['filters']);
        break;
        
      case IMAGETYPE_BMP:
      case IMAGETYPE_WBMP:
        header('Content-type: '.image_type_to_mime_type($imageType));
        imagewbmp($this->current, $f);
        break;
        
      case IMAGETYPE_XBM:
        header('Content-type: '.image_type_to_mime_type($imageType));
        imagexbm($this->current, $f);
        break;
        
      case IMAGETYPE_TIFF_II:
      case IMAGETYPE_TIFF_MM:
      case IMAGETYPE_IFF:
      case IMAGETYPE_JB2:
      case IMAGETYPE_SWF:
      case IMAGETYPE_PSD:
      case IMAGETYPE_SWC:
      // case IMAGETYPE_ICO:
      default:
        break;
    }
  }
  
  /**
   * write
   * 
   * Writes the output data to the specified filename
   *
   * @param string $f The filename
   * @return string The filename written to
   * @access public
   * @author Dom Hastings
   */
  public function write($f, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['output']['file'])) ? $this->options['output']['file'] : array(),
      (is_array($options)) ? $options : array()
    );
    
    if ($this->options['output']['forceType']) {
      $imageType = $this->options['output']['forceType'];
      
    } else {
      $imageType = $this->info[2];
    }
    
    if ($options['extension'] || strpos($f, '.') === false) {
      $f .= $this->extension;
    }
    
    $this->output($f);
    
    return $f;
  }
  
  /**
   * resource
   * 
   * Returns the current image as a resource
   *
   * @return void
   * @access public
   * @author Dom Hastings
   */
  public function resource() {
    return $this->current;
  }
  
  /**
   * info
   * 
   * Gets information about the current image
   *
   * @return void
   * @access private
   * @author Dom Hastings
   */
  private function info($f = null) {
    // if the filename is empty
    if (empty($f)) {
      // stores the image information inside the object
      $this->info = getimagesize($this->filename);

    } else {
      // it's not the main image so return it directly
      return getimagesize($f);
    }
  }
  
  /**
   * x
   * 
   * Returns the width of the image
   *
   * @param string $a Reads the image directly, otherwise uses the cached information form load
   * @return integer The width of the image
   * @access public
   * @author Dom Hastings
   */
  public function x($a = false) {
    if ($a) {
      return imagesx($this->source);
      
    } else {
      if (empty($this->info)) {
        $this->info();
      }
      
      return $this->info[0];
    }
  }
  
  /**
   * currentX
   * 
   * Returns the width of the thumb image
   *
   * @param string $a Reads the image directly, otherwise uses the cached information form load
   * @return integer The width of the image
   * @access public
   * @author Dom Hastings
   */
  public function currentX() {
    if ($this->current) {
      return imagesx($this->current);
    }
  }
  
  /**
   * y
   * 
   * Returns the height of the image
   *
   * @param boolean $a Reads the image directly, otherwise uses the cached information form load
   * @return integer The height of the image
   * @access public
   * @author Dom Hastings
   */
  public function y($a = false) {
    if ($a) {
      return imagesy($this->source);
      
    } else {
      if (empty($this->info)) {
        $this->info();
      }
      
      return $this->info[1];
    }
  }
  
  /**
   * currentY
   * 
   * Returns the height of the current image
   *
   * @param boolean $a Reads the image directly, otherwise uses the cached information form load
   * @return integer The height of the image
   * @access public
   * @author Dom Hastings
   */
  public function currentY($a = false) {
    if ($this->current) {
      return imagesy($this->current);
    }
  }
  
  /**
   * scale
   * 
   * Scales the current image to the dimensions specified, using the options specified
   *
   * @param integer $x The desired width
   * @param integer $y The desired height
   * @param array $options See main options block at top of file
   * @return resource The new image
   * @access public
   * @author Dom Hastings
   */
  public function scale($x, $y, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['scale'])) ? $this->options['scale'] : array(),
      (is_array($options)) ? $options : array()
    );
    
    // if we're not forcing the size
    if (empty($options['force'])) {
      // check we're not trying to enlarge the image
      if ($x > $this->x) {
        $x = $this->x;
      }

      if ($y > $this->y) {
        $y = $this->y;
      }
      
      // if neither dimension is specified
      if ($x == 0 && $y == 0) {
        throw new Exception('Image::scale: At least one dimension must be spcified to scale an image.');
        
      } elseif ($x > 0 && $y > 0) {
        // maths!
        $destX = $x;
        $destY = intval($x / $this->ratio);
        
        if ($destY > $y) {
          $destX = intval($y * $this->ratio);
          $destY = $y;
        }
        
      } elseif ($x == 0) {
        $destX = intval($y * $this->ratio);
        $destY = $y;
        
      } elseif ($y == 0) {
        $destX = $x;
        $destY = intval($x / $this->ratio);
      }
      
    } else {
      $destX = $x;
      $destY = $y;
    }
    
    // create the destination
    $dest = imagecreatetruecolor($destX, $destY);

    $col_g = imagecolorallocate($dest, 255, 255, 255);//imagecolorallocate($image,0xff,0xff,0xff);
    
    $col_t = imagecolorallocate($dest,000,000,000);
    $col_b = imagecolorallocate($dest,255, 255, 255);
    
    imagecolortransparent($dest, $col_g);
    //imagerectangle($image,2,2,$len * $size_trgt - 2,$size_trgt - 2,$col_g);
    imagefill($dest,0,0,$col_g);

    // resample the image as specified
    if (!imagecopyresampled($dest, $this->source, 0, 0, 0, 0, $destX, $destY, $this->x, $this->y)) {
      throw new Exception('Image::scale: Error scaling image');
    }
    
    $this->current = $dest;
    
    return $dest;
  }
  
  /**
   * cutout
   * 
   * Returns a selected portion of the image after optionally resizing it
   *
   * @param integer $x The desired width
   * @param integer $y The desired height
   * @param array $options 
   * @return resource The new image
   * @access public
   * @author Dom Hastings
   */
  public function cutout($x, $y, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['cutout'])) ? $this->options['cutout'] : array(),
      (is_array($options)) ? $options : array()
    );
    
    // if the source image dimensions haven't been specified, work them out as best you can
    if (empty($options['scaleX']) && empty($options['scaleY'])) {
      // more maths!
      if ($this->x >= $this->y) {
        // landscape
        $scaleX = intval($y * $this->ratio);
        $scaleY = $y;
        
        if ($scaleX < $x) {
          $scaleX = $x;
          $scaleY = intval($x / $this->ratio);
        }
        
      } else {
        // portrait
        $scaleX = $x;
        $scaleY = intval($x / $this->ratio);
        
        if ($scaleY < $y) {
          $scaleX = intval($y * $this->ratio);
          $scaleY = $y;
        }
      }
      
    } else {
      $scaleX = $options['scaleX'];
      $scaleY = $options['scaleY'];
    }
    
    // scale the image
    $source = $this->scale($scaleX, $scaleY, array('force' => true));
    
    // if the offset hasn't been specified
    if (!isset($options['offsetX']) || !isset($options['offsetY'])) {
      // calculate the center
      $offsetX = intval(($scaleX / 2) - ($x / 2));
      $offsetY = intval(($scaleY / 2) - ($y / 2));
      
    } else {
      $offsetX = $options['offsetX'];
      $offsetY = $options['offsetY'];
    }
    
    // create the destination
    $dest = imagecreatetruecolor($x, $y);
    
    // cut it out
    if (!imagecopy($dest, $source, 0, 0, $offsetX, $offsetY, $scaleX, $scaleY)) {
      throw new Exception('Image::scale: Error cutting out image');
    }
    
    $this->current = $dest;
    
    return $dest;
  }
  
  /**
   * whitespace
   * 
   * Returns a scaled version of the image with any white space on the base filled with an image or a colour, depending on options specified
   *
   * @param string $x 
   * @param string $y 
   * @param string $options 
   * @return void
   * @access public
   * @author Dom Hastings
   */
  public function whitespace($x, $y, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['whitespace'])) ? $this->options['whitespace'] : array(),
      (is_array($options)) ? $options : array()
    );
    
    // if we're using an image background
    if (!empty($options['image'])) {
      // load it
      $orig = new Image($options['image']);

      $orig->scale($x, $y, array('force' => true));

      $dest = $orig->resource();
      
    // else if it's just a colour
    } elseif (!empty($options['color'])) {
      // create the base image
      $dest = imagecreatetruecolor($x, $y);
      
      // extract the int values of the colour
      list($r, $g, $b) = $this->hexToRGB($options['color']);
      
      // allocate the colour
      $color = imagecolorallocatealpha($dest, $r, $g, $b, $options['transparency']);
      
      // fill it
      imagefill($dest, 0, 0, $color);
      
    // else, we aren't keeping any whitespace, so just scale it
    } else {
      return $this->scale($x, $y);
    }
    
    // if scaling options have been set
    if (!empty($options['scaleX']) || !empty($options['scaleY'])) {
      // use them
      $scaleX = $options['scaleX'];
      $scaleY = $options['scaleY'];
      
      $options = array(
        'force' => true
      );

    } else {
      // otherwise assume the passed options
      $scaleX = $x;
      $scaleY = $y;
      
      $options = array();
    }
    
    // scale the image
    $source = $this->scale($scaleX, $scaleY, $options);
    
    // extract the new height and width
    $scaleX = $this->currentX;
    $scaleY = $this->currentY;
    
    // determine the offset
    if (!isset($options['offsetX']) || !isset($options['offsetY'])) {
      $offsetX = intval(($x / 2) - ($scaleX / 2));
      $offsetY = intval(($y / 2) - ($scaleY / 2));

    } else {
      $offsetX = $options['offsetX'];
      $offsetY = $options['offsetY'];
    }
    
    // overlay it
    if (!imagecopy($dest, $source, $offsetX, $offsetY, 0, 0, $scaleX, $scaleY)) {
      throw new Exception('Image::scale: Error whitespacing image');
    }
    
    $this->current = $dest;
    
    return $dest;
  }
  
  /**
   * watermark
   * 
   * Watermarks the current image with the specified image
   *
   * @param string $i The image to use as a watermark
   * @param array $options The options
   * @return resource The watermarked image
   * @access public
   * @author Dom Hastings
   */
  public function watermark($i, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['watermark'])) ? $this->options['watermark'] : array(),
      (is_array($options)) ? $options : array()
    );
    
    if (!file_exists($i)) {
      throw new Exception('Image::watermark: Missing watermark image \''.$i.'\'.');
    }
    
    $dest = $this->current;
    
    // load the watermark
    $watermark = new Image($i);
    
    // determine the offset
    if (!isset($options['offsetX']) || !isset($options['offsetY'])) {
      $offsetX = intval(($this->currentX / 2) - ($watermark->currentX / 2));
      $offsetY = intval(($this->currentY / 2) - ($watermark->currentY / 2));

    } else {
      $offsetX = $options['offsetX'];
      $offsetY = $options['offsetY'];
    }
    
    // overlay it
    if (!empty($options['repeatX']) && !empty($options['repeatY'])) {
      $offsetX = $offsetY = 0;
      
      // rows
      for ($i = $offsetY; $i < $this->currentY; $i += $watermark->y) {
        // cols
        for ($j = $offsetX; $j < $this->currentX; $j += $watermark->x) {
          if (!imagecopy($dest, $watermark->resource(), $j, $i, 0, 0, $watermark->x, $watermark->y)) {
            throw new Exception('Image::scale: Error watermarking image.');
          }
        }
      }
      
    } elseif (!empty($options['repeatX'])) {
      $offsetX = 0;
      
      for ($i = $offsetX; $i <= $this->currentX; $i += $watermark->x) {
        if (!imagecopy($dest, $watermark->resource(), $i, $offsetY, 0, 0, $watermark->x, $watermark->y)) {
          throw new Exception('Image::scale: Error watermarking image.');
        }
      }
      
    } elseif (!empty($options['repeatY'])) {
      $offsetY = 0;
      
      for ($i = $offsetY; $i <= $this->currentY; $i += $watermark->y) {
        if (!imagecopy($dest, $watermark->resource(), $offsetX, $i, 0, 0, $watermark->x, $watermark->y)) {
          throw new Exception('Image::scale: Error watermarking image.');
        }
      }
      
    } else {
      if (!imagecopy($dest, $watermark->resource(), $offsetX, $offsetY, 0, 0, $watermark->x, $watermark->y)) {
        throw new Exception('Image::scale: Error watermarking image.');
      }
    }

    $this->current = $dest;
    
    return $dest;
  }
  
  /**
   * hexToRGB
   * 
   * Returns the integer colour values from an HTML hex code
   *
   * @param string $h The HTML hex code
   * @return array The integer colour values
   * @access public
   * @author Dom Hastings
   */
  private function hexToRGB($h) {
    // strip off the # if it's there
    $h = trim($h, '#');
    
    if (strlen($h) == 6) {
      return array(
        hexdec(substr($h, 0, 2)),
        hexdec(substr($h, 2, 2)),
        hexdec(substr($h, 4, 2))
      );
      
    } elseif (strlen($h) == 3) {
      return array(
        hexdec(substr($h, 0, 1).substr($h, 0, 1)),
        hexdec(substr($h, 1, 1).substr($h, 1, 1)),
        hexdec(substr($h, 2, 1).substr($h, 2, 1))
      );
      
    } else {
      // default to white
      return array(255, 255, 255);
    }
  }
  
  /**
   * addText
   * 
   * Adds the specified text to the image at the specified location
   *
   * @param string $t The text to add to the image
   * @param integer $x The x co-ordinate of the text
   * @param integer $y The y co-ordinate of the text
   * @param array $options The options
   * @return array Results from imagettftext()
   * @author Dom Hastings
   */
  function addText($text, $x, $y, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['text'])) ? $this->options['text'] : array(),
      (is_array($options)) ? $options : array()
    );
    
    // check the font file exists
    if (substr($options['font'], 0, 1) == '/') {
      if (!file_exists($options['font'])) {
        throw new Exception('Imge::addText: Unable to find font file \''.$options['font'].'\'');
      }

    } else {
      if (!file_exists($options['font'].'.ttf')) {
        throw new Exception('Imge::addText: Unable to find font file \''.$options['font'].'\'');
      }
    }
    
    list($r, $g, $b) = $this->hexToRGB($options['color']);
    
    $colour = imagecolorallocatealpha($this->current, $r, $g, $b, $options['transparency']);

    return imagettftext($this->current, $options['size'], $options['angle'], $x, $y, $colour, $options['font'], $text);
  }
  
  /**
   * drawLine
   * 
   * Draws a line from the co-ordinates in array start to the co-ordinates in array finish using the GD library function
   *
   * @param array $start The start point index 0 should be the x co-ordinate, 1 the y
   * @param array $finish The end point index 0 should be the x co-ordinate, 1 the y
   * @param array $options The options
   * @return mixed The result from imageline()
   * @author Dom Hastings
   */
  function drawLine($start, $finish, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['line'])) ? $this->options['line'] : array(),
      (is_array($options)) ? $options : array()
    );
    
    imagesetthickness($this->current, $options['size']);
    
    if (!is_array($start) || !is_array($finish)) {
      throw new Exception('Image::drawLine: Arguments 0 and 1 must be arrays.');
    }
    
    list($sX, $sY, $fX, $fY) = array_merge(array_values($start), array_values($finish));
    
    list($r, $g, $b) = $this->hexToRGB($options['color']);
    
    $colour = imagecolorallocatealpha($this->current, $r, $g, $b, $options['transparency']);
    
    if (!empty($options['style'])) {
      imagesetstyle($this->current, $options['style']);
    }
    
    return imageline($this->current, $sX, $sY, $fX, $fY, $colour);
  }
  
  /**
   * drawBox
   * 
   * Draws a box from the co-ordinates in array start to the co-ordinates in array finish using the GD library function
   *
   * @param array $start The start point index 0 should be the x co-ordinate, 1 the y
   * @param array $finish The end point index 0 should be the x co-ordinate, 1 the y
   * @param array $options The options
   * @return mixed The result from imagerectangle()
   * @author Dom Hastings
   */
  function drawBox($start, $finish, $options = array()) {
    // merge in the options
    $options = array_merge_recursive_distinct(
      (is_array($this->options['box'])) ? $this->options['box'] : array(),
      (is_array($options)) ? $options : array()
    );
    
    imagesetthickness($this->current, $options['size']);
    
    if (!is_array($start) || !is_array($finish)) {
      throw new Exception('Image::drawLine: Arguments 0 and 1 must be arrays.');
    }
    
    list($sX, $sY, $fX, $fY) = array_merge(array_values($start), array_values($finish));
    
    list($r, $g, $b) = $this->hexToRGB($options['color']);
    
    $colour = imagecolorallocatealpha($this->current, $r, $g, $b, $options['transparency']);
    
    if (empty($options['filled'])) {
      if (!empty($options['style'])) {
        imagesetstyle($this->current, $options['style']);
      }

      return imagerectangle($this->current, $sX, $sY, $fX, $fY, $colour);

    } else {
      return imagefilledrectangle($this->current, $sX, $sY, $fX, $fY, $colour);
    }
  }
}

/**
 * array_merge_recursive_distinct
 * 
 * Recursively process an array merge all child nodes together
 *
 * @return array
 * @author Dom Hastings
 */
if (!function_exists('array_merge_recursive_distinct')) {
  function array_merge_recursive_distinct() {
    switch (func_num_args()) {
      case 0:
        return array();
        break;

      case 1:
        return (array) func_get_arg(0);
        break;

      default:
        $a = func_get_args();
        $s = (array) array_shift($a);

        foreach ($a as $i => $b) {
          if (!is_array($b)) {
            $b = (array) $b;
          }

          foreach ($b as $k => $v) {
            if (is_numeric($k)) {
              $s[] = $v;

            } else {
              if (isset($s[$k])) {
                if (is_array($s[$k]) && is_array($v)) {
                  $s[$k] = array_merge_recursive_distinct($s[$k], $v);

                } else {
                  $s[$k] = $v;
                }

              } else {
                $s[$k] = $v;
              }
            }
          }
        }
        break;
    }

    return $s;
  }
}
?>
