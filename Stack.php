<?php

/**
 * This generic Stack class is useful for storing operators and values
 * while parsing infix mathematical expressions, but is completely
 * independent of any particular type of value which may be stored.
 *
 * @author Peter Wolfenden <wolfen@gmail.com>
 */

class Stack {
  public $Items = array();

  public function push($item) {
    array_push($this->Items, $item);
  }

  public function pop() {
    if (count($this->Items) == 0) {
      throw new Exception("Cannot pop from an empty stack!\n");
    }
    return array_pop($this->Items);
  }

  public function top() {
    return $this->Items[count($this->Items)-1];
  }

  public function count() {
    return count($this->Items);
  }

  public function __toString() {
    if ($this->Items == null || (count($this->Items) == 0)) {
      return "Empty stack";
    } else {
      return print_r($this->Items, true);
    }
  }
}
