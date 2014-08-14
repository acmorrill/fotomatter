<?php
class Tag extends AppModel {
    
    public $hasAndBelongsToMany = array('Photo');
	
	public $actsAs = array('Ordered' => array('foreign_key' => false));
    
}