<?php

/**
 * Product model.
 */
class Model_Product extends Model
{
	protected static $_properties = array(
		'id',
		'seller_id',
		'name',
		'status' => array('default' => 'active'),
		'created_at',
		'updated_at',
	);
	
	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => true,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => true,
		),
	);
	
	protected static $_belongs_to = array(
		'seller',
	);
	
	protected static $_has_many = array(
		'options' => array(
			'model_to'   => 'Model_Product_Option',
			'conditions' => array(
				'where' => array('status' => 'active'),
			),
		),
		'metas' => array(
			'model_to' => 'Model_Product_Meta',
		),
	);
	
	/**
	 * Returns the product action link.
	 *
	 * @param string $action The action to link to.
	 *
	 * @return string
	 */
	public function link($action = '')
	{
		$uri = 'products/' . $this->id;
		if ($action) {
			$uri .= '/' . $action;
		}
		
		return Uri::create($uri);
	}
	
	/**
	 * Gets a property or relation.
	 *
	 * @param string $property The property or relation to fetch.
	 * 
	 * @return mixed
	 */
	public function & __get($property)
	{
		$value = parent::__get($property);
		if ($property == 'metas') {
			// Sort metas alphabetically by meta name property.
			uasort($value, function($a, $b) {
				return strcmp($a->name, $b->name);
			});
		}
		
		return $value;
	}
}
