<?php 

namespace Openbuildings\PHPUnitSpiderling;

use Openbuildings\Spiderling\Exception_Notfound;

/**
 * Phpunit_Framework_Constraint_Locator definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
class Constraint_Locator extends \PHPUnit_Framework_Constraint {
	
	protected $_type;
	protected $_selector;
	protected $_filters;

	function __construct($type, $selector, array $filters = array())
	{
		$this->_type = $type;
		$this->_selector = $selector;
		$this->_filters = $filters;
	}

	protected function matches($other)
	{
		try 
		{
			$other->find(array($this->_type, $this->_selector, $this->_filters));
			return TRUE;
		} 
		catch (Exception_Notfound $excption) 
		{
			return FALSE;
		}
	}

	public function failureDescription($other)
	{
		if ($other->is_root())
		{
			$node_string = 'HTML page';
		}
		else
		{
			$node_string = $other->tag_name();

			if ($id = $other->attribute('id'))
			{
				$node_string .= '#'.$id;
			}

			if ($class = $other->attribute('class'))
			{
				$node_string .= '.'.join('.', explode(' ', $class));
			}
		}
		return "$node_string ".$this->toString();
	}

	/**
	 * Returns a string representation of the constraint.
	 *
	 * @return string
	 */
	public function toString()
	{
		return "has '{$this->_type}' selector '{$this->_selector}', filter ".json_encode($this->_filters);
	}
}