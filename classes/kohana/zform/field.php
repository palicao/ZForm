<?php defined('SYSPATH') or die('No direct script access.');

/**
* ZForm field: The base class for all Zform field types
*
* @package    ZForm
* @author     Azuka Okuleye
* @copyright  (c) 2009 Azuka Okuleye
* @license    http://zahymaka.com/license.html
*/
abstract class Kohana_ZForm_Field
{
	/**
	 * Form field attributes
	 * @var array
	 */
	protected $_attributes  = array();
	/**
	 * Valid configuration items
	 * @var array
	 */
	protected $_config      = array();
	/**
	 * Data about the column from ORM
	 * @var array
	 */
	protected $_extra       = array();
	/**
	 * Form field id
	 * @var string
	 */
	protected $_id          = NULL;
	/**
	 * Form field name
	 * @var string
	 */
	protected $_name        = NULL;
	/**
	 * Form field label
	 * @var string
	 */
	protected $_label       = NULL;
	/**
	 * Form field value
	 * @var string
	 */
	protected $_value       = NULL;
	/**
	 * Form field help text. 
	 * @var string
	 */
	protected $_help_text   = NULL;
	/**
	 * Wrapper zform/wrappers/$view
	 * @var string
	 */
	protected $_wrapper     = 'default';
	
	/**
	 * Render the field
	 * @return string
	 */
	abstract public function render();
	
	/**
	 * Create a new field
	 * @param string $name
	 * @param string $id
	 * @param string $label
	 * @param array $config
	 * @param array $attributes
	 * @param array $extra 
	 */
	public function __construct($name, $id, $label, array $config = NULL, array $attributes = NULL, array $extra = NULL)
	{
		$this->_attributes = $this->_attributes + (array) $attributes;
		$this->_config     = Arr::overwrite($this->_config, (array) $config);
		$this->_extra      = (array) $extra;
		
		$this->_name       = $name;
		$this->_label      = $label;
		$this->_id         = $id;
	}
	
	/**
	 * Get the value
	 * @param mixed $name
	 * @return mixed
	 */
	public function  __get($name)
	{
		if ($name === 'value')
			return $this->_value;
		elseif (isset($this->_config[$name]))
			return $this->_config[$name];
		else
		{
			throw new Kohana_Exception('The :property: property does not exist in the :class: class',
				array(':property:' => $name, ':class:' => get_class($this)));
		}
	}
	
	/**
	 * Set the value
	 * @param mixed $name
	 * @param mixed $value 
	 */
	public function  __set($name, $value)
	{
		if ($name === 'value')
			$this->_set_value($value);
		elseif (isset($this->_config[$name]))
			return $this->_config[$name] = $value;
		else
		{
			throw new Kohana_Exception('The :property: property does not exist in the :class: class',
				array(':property:' => $name, ':class:' => get_class($this)));
		}
	}
	
	/**
	 * String representation
	 * @return string
	 */
	public function  __oString()
	{
		return $this->render();
	}
	
	/**
	 * Render the field
	 * @return string
	 */
	public function form_field()
	{
		return $this->render();
	}
	
	/**
	 * Render the field
	 * @return string
	 */
	public function form_label()
	{
		return Form::label($this->_id, $this->_label);
	}
	
	/**
	 * Display single field (and optionally label) in a wrapper
	 * @param array $attributes
	 * @return string 
	 */
	public function single_field(array $attributes)
	{
		return View::factory('zform/wrappers/' . $this->_wrapper)->set('field', $this)->set('attributes', $attributes);
	}
	
	/**
	 * Value formatted for the database
	 * @return string
	 */
	public function db_value()
	{
		return $this->_value;
	}

	/**
	 * Set the value to the default
	 */
	public function set_default()
	{
		$this->value = Arr::get($this->_extra, Kohana::config('zcolumns.default.default_column.default'));
	}
	
	/**
	 * Set the value. Override to handle array and other value types
	 * @param mixed $value 
	 */
	protected function _set_value($value)
	{
		$this->_value = $value;
	}
}