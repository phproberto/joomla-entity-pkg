<?php
/**
 * Joomla! entity library.
 *
 * @copyright  Copyright (C) 2017-2019 Roberto Segura López, Inc. All rights reserved.
 * @license    See COPYING.txt
 */

namespace Phproberto\Joomla\Entity\Categories;

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;
use Phproberto\Joomla\Entity\Collection;
use Phproberto\Joomla\Entity\ComponentEntity;
use Phproberto\Joomla\Entity\Traits as EntityTraits;
use Phproberto\Joomla\Entity\Core\Traits as CoreTraits;
use Phproberto\Joomla\Entity\Core\Contracts\Publishable;
use Phproberto\Joomla\Entity\Users\Traits as UsersTraits;
use Phproberto\Joomla\Entity\Validation\Contracts\Validable;
use Phproberto\Joomla\Entity\Validation\Traits\HasValidation;
use Phproberto\Joomla\Entity\Translation\Contracts\Translatable;
use Phproberto\Joomla\Entity\Translation\Traits\HasTranslations;
use Phproberto\Joomla\Entity\Categories\Validation\CategoryValidator;

/**
 * Stub to test Entity class.
 *
 * @since   1.0.0
 */
class Category extends ComponentEntity implements Publishable, Translatable, Validable
{
	use CoreTraits\HasAccess, CoreTraits\HasAncestors, CoreTraits\HasAsset, CoreTraits\HasAssociations, CoreTraits\HasChildren;
	use CoreTraits\HasDescendants, CoreTraits\HasLevel, CoreTraits\HasMetadata, CoreTraits\HasParams, CoreTraits\HasParent;
	use CoreTraits\HasState;
	use HasTranslations, HasValidation;
	use UsersTraits\HasAuthor, UsersTraits\HasEditor;

	/**
	 * Get the list of column aliases.
	 *
	 * @return  array
	 */
	public function columnAliases()
	{
		return array(
			'created_by'  => 'created_user_id',
			'modified_by' => 'modified_user_id'
		);
	}

	/**
	 * Get a table.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  \JTable
	 */
	public function table($name = '', $prefix = null, $options = array())
	{
		\JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_categories/tables');

		$name = $name ?: 'Category';
		$prefix = $prefix ?: 'CategoriesTable';

		return parent::table($name, $prefix, $options);
	}

	/**
	 * Load associations from DB.
	 *
	 * @return  array
	 *
	 * @codeCoverageIgnore
	 */
	protected function loadAssociations()
	{
		if (!$this->hasId())
		{
			return array();
		}

		return \JLanguageAssociations::getAssociations(
			$this->get('extension'),
			'#__categories',
			'com_categories.item',
			$this->id(),
			'id',
			'alias',
			''
		);
	}


	/**
	 * Load associated translations from DB.
	 *
	 * @return  Collection
	 */
	protected function loadTranslations()
	{
		$ids = $this->associationsIds();

		if (empty($ids))
		{
			return new Collection;
		}

		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select('c.*')
			->from($db->qn('#__categories', 'c'))
			->where('c.id IN (' . implode(',', ArrayHelper::toInteger($ids)) . ')');

		$db->setQuery($query);

		$categories = array_map(
			function ($item)
			{
				return static::find($item->id)->bind($item);
			},
			$db->loadObjectList() ?: array()
		);

		return new Collection($categories);
	}

	/**
	 * Search entity ancestors.
	 *
	 * @param   array  $options  Search options. For filters, limit, ordering, etc.
	 *
	 * @return  Collection
	 */
	public function searchAncestors(array $options = [])
	{
		if (!$this->hasId())
		{
			return new Collection;
		}

		$options = array_merge(['list.limit' => 0], $options);
		$options['filter.descendant_id'] = $this->id();

		return Collection::fromData(CategorySearcher::instance($options)->search(), self::class);
	}

	/**
	 * Search entity children.
	 *
	 * @param   array  $options  Search options. For filters, limit, ordering, etc.
	 *
	 * @return  Collection
	 */
	public function searchChildren(array $options = [])
	{
		if (!$this->hasId())
		{
			return new Collection;
		}

		$options = array_merge(['list.limit' => 0], $options);
		$options['filter.parent_id'] = $this->id();

		return Collection::fromData(CategorySearcher::instance($options)->search(), self::class);
	}

	/**
	 * Search entity descendants.
	 *
	 * @param   array  $options  Search options. For filters, limit, ordering, etc.
	 *
	 * @return  Collection
	 */
	public function searchDescendants(array $options = [])
	{
		if (!$this->hasId())
		{
			return new Collection;
		}

		$options = array_merge(['list.limit' => 0], $options);
		$options['filter.ancestor_id'] = $this->id();

		return Collection::fromData(CategorySearcher::instance($options)->search(), self::class);
	}

	/**
	 * Retrieve entity validator.
	 *
	 * @return  CategoryValidator
	 *
	 * @since   1.7.0
	 */
	public function validator()
	{
		if (null === $this->validator)
		{
			$this->validator = new CategoryValidator($this);
		}

		return $this->validator;
	}
}
