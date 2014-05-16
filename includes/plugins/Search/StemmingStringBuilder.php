<?php
/**
 * 
 */
class StringBuilder extends Stemmer
{
  private $capacity = 0;
  private $length = 0;
  private $replacement = '';
  private $string = NULL;
  private $temp_self = NULL;
  private $temp_string = NULL;
  
  /**
   * Class constructor
   *
   * @param string $string
   * @param integer $capacity
   */
  public function __construct($string = '', $capacity = 0) 
  {
    $this->string = $string;
  }
  
  public function __toString()
  {
    return $this->string;
  }
  
  public function append($string)
  {
    $this->string .= $string;
  }
   
  public function AppendFormat()
  {
    //@TODO: yet to be implemented;
  }
  
  public function count()
  {
    return strlen($this->string);
  }
  
  
  public function EnsureCapacity()
  {
    //@TODO: check if there is any point to implement this method in PHP;
  }
  
  public function Equals(Util_StringBuilder $instance)
  {
    if ((string)$instance === (string)$this)
    return true;
    else return false;
  }
  
  public function GetHashCode()
  {
    //@TODO: Consider if there is meaningfull PHP implementation;
  }

  //@TODO: Reconsider if implementation is meaningful
  public function getType()
  {
    return get_class();
  }
  
  public function Insert($index, $value, $start = 0, $length = NULL)
  {
    if ($index  >= $this->count())
      throw new Exception('Out of range!', 101);
    $this->string = (($index  === 0) ? '' : substr($this->string, 0, $index)) . 
                                            $value . substr($this->string, $index);
  }
  
  public function offsetGet($offset)
  {
    return substr($this->string, $offset, 1);
  }
  
  public function offsetSet($offset, $value)
  {
    if ($offset >= $this->count())
      throw new Exception('Out of range!', 101);
    
    $this->string = substr($this->string, 0, $offset) . 
                    $value . 
                    substr($this->string, $offset + 1);
  }
  
  public function offsetUnset($offset)
  {
    $this->Remove($offset);
  }
  
  
  public function Remove($index, $length = 1)
  {
    if (($index + $length) > ($this->count()))
      throw new Exception('Out of range!', 101);
    $this->string = (($index  === 0) ? '' : substr($this->string, 0, $index)) . 
                    ((($index + $length) < $this->count()) ? substr($this->string, $index + $length) : '');
  }
  
  public function Replace($search, $replace, $start = 0, $length = NULL)
  {
    if ((0 === $start) && (NULL === $length) && (false !== strpos($this->string, $search))) :
      $this->string = substr($this->string, strpos($this->string, $search)) . $replace . substr($this->string, strpos($this->string, $search) + 1);
    elseif (false !== strpos($this->string, $search, $start)) :
    $this->string = substr($this->string, 0, $start) . 
                    str_replace($search, $replace, substr($this->string, $start, $length)) . 
                    substr($this->string, $start + $length);
    endif;
    
  }
  
  /**
   * Replace portion of the string
   *
   * @deprecated 
   * 
   * @param integer $index
   * @param string $value
   * @param integer $start
   * @param integer $length
   * 
   * @access public
   * @return void
   */
  public function ReplaceChunk($index, $value, $start = 0, $length = NULL)
  {
    if (NULL !== $length)
      $this->length = $length;
      
    switch (true) :
      case (is_string($value)) :
        $this->temp_string = new ArrayIterator(str_split($value));
          if (NULL === $length)
            $this->length = strlen($value);
        break;
      case ($value instanceof Util_StringBuilder) :
        $this->temp_string = $value->getIterator();
        if (NULL === $length)
          $this->length = $value->count();
        break;
      case (is_array($value)) :
        $this->temp_string = new ArrayIterator($value);
        if (NULL === $length)
          $this->length = count($value);
        break;
      default:
        throw new Exception('Invalid data type', 101);
    endswitch;

    $this->temp_string->seek($start);
    for($i = $start; $i < $this->length; $i++) :
      $this->offsetSet($index, $this->temp_string->current());
      $index++;
      $this->temp_string->next();
    endfor;
    $this->temp_string = NULL;
  }
  
  public function SubStr($start, $length)
  {
    return substr($this->string, $start, $length);
  }
  
}

?>