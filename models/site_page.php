<?php

class SitePage extends AppModel {
	public $name = 'SitePage';
	public $actsAs = array('Ordered' => array('foreign_key' => false));
}
