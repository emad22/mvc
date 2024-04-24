<?php
namespace System\View;
interface ViewInterface{
    /**
     * Get the view output
     */
    public function getOutput();

    /**
     * Convert the View object to string in printing
     * i.e echo $object
     */
    public function __toString();
}