<?php

namespace AndreKeher\WPDP;

class Columns
{
    private $postTypes;
    private $columns;
    private $dataFunction;
    
    public function __construct($postTypes)
    {
        $this->postTypes = (array) $postTypes;
        $this->columns = [];
    }
    
    public function getColumns()
    {
        return $this->columns;
    }
    
    public function setColumns($columns)
    {
        $this->columns = (array) $columns;
    }
    
    public function appendColumn($column)
    {
        $this->columns = array_merge($this->columns, (array) $column);
    }
    
    public function removeColumn($key)
    {
        if (isset($this->columns[$key])) {
            unset($this->columns[$key]);
        }
    }
    
    public function removeColumns($keys)
    {
        foreach ($keys as $key) {
            if (isset($this->columns[$key])) {
                unset($this->columns[$key]);
            }
        }
    }
    
    public function setDataFunction(callable $function)
    {
        $this->dataFunction = $function;
    }
    
    public function filterColumns($columns)
    {
        return array_merge($columns, $this->columns);
    }
    
    public function init()
    {
        if (empty($this->dataFunction)) {
            wp_die(__('Please, add the data function.'));
        }
        foreach ($this->postTypes as $postType) {
            add_filter(sprintf('manage_%s_posts_columns', $postType), array($this, 'filterColumns'));
            add_action(sprintf('manage_%s_posts_custom_column', $postType), $this->dataFunction, 10, 2);   
        }
    }
}
