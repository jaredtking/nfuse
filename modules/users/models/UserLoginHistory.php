<?php

/**
 * @package infuse\framework
 * @author Jared King <j@jaredtking.com>
 * @link http://jaredtking.com
 * @version 0.1.15.2
 * @copyright 2013 Jared King
 * @license MIT
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
	associated documentation files (the "Software"), to deal in the Software without restriction,
	including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
	and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
	subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or
	substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
	LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace infuse\models;

class UserLoginHistory extends \infuse\Model
{
	public static $properties = array(
		'id' => array(
			'type' => 'id'
		),
		'uid' => array(
			'type' => 'uid'
		),
		'timestamp' => array(
			'type' => 'date'
		),
		'type' => array(
			'type' => 'enum',
			'enum' => array(
				0 => 'Regular',
				1 => 'Facebook',
				2 => 'Twitter',
				3 => 'OAuth'
			),
			'db_type' => 'tinyint',
			'length' => 1
		),
		'ip' => array(
			'type' => 'text',
			'length' => 15
		)
	);
}