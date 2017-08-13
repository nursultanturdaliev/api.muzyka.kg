<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 8/12/17
 * Time: 11:07 PM
 */

namespace AppBundle\Formatter;


use AppBundle\Entity\User;

class UserFormatter implements FormatterInterface
{

	/**
	 * @param $value User
	 *
	 * @return array
	 */
	public function format($value)
	{
		return [
			'first_name' => $value->getFirstName(),
			'last_name'  => $value->getLastName()
		];
	}
}