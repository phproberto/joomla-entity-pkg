<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017-2019 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Users;

defined('_JEXEC') || die;

use Phproberto\Joomla\Entity\Users\PublicUserGroup;
use Phproberto\Joomla\Entity\Users\PredefinedViewLevel;

/**
 * PublicViewLevel entity.
 *
 * @since   __DEPLOY_VERSION__
 */
class PublicViewLevel extends PredefinedViewLevel
{
	/**
	 * View level title.
	 *
	 * @const
	 */
	const TITLE = 'Public';

	/**
	 * Predefined data to load the group.
	 *
	 * @return  array
	 */
	public static function predefinedData()
	{
		return [
			'title' => self::TITLE,
			'rules' => json_encode(
				[
					PublicUserGroup::instanceOrCreate()->id()
				]
			)
		];
	}
}
