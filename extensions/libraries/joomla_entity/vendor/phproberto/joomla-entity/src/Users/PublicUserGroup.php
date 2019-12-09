<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017-2019 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Users;

defined('_JEXEC') || die;

use Phproberto\Joomla\Entity\Users\PredefinedUserGroup;

/**
 * User Group entity.
 *
 * @since   __DEPLOY_VERSION__
 */
class PublicUserGroup extends PredefinedUserGroup
{
	/**
	 * Parent group identifier.
	 *
	 * @const
	 */
	const PARENT_ID = 0;

	/**
	 * Predefined data to load the group.
	 *
	 * @return  array
	 */
	public static function predefinedData()
	{
		return [
			'parent_id' => self::PARENT_ID
		];
	}
}
