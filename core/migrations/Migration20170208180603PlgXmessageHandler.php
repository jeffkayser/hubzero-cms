<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

use Hubzero\Content\Migration\Base;

/**
 * Migration script for adding anonymous flag to xmessage table
 **/
class Migration20170208180603PlgXmessageHandler extends Base
{
	/**
	 * Up
	 **/
	public function up()
	{
		if ($this->db->tableExists('#__xmessage') && !$this->db->tableHasField('#__xmessage', 'anonymous'))
		{
			$query = "ALTER TABLE `#__xmessage` ADD `anonymous` TINYINT(2)  NOT NULL  DEFAULT '0'";
			$this->db->setQuery($query);
			$this->db->query();
		}
	}

	/**
	 * Down
	 **/
	public function down()
	{
		if ($this->db->tableExists('#__xmessage') && $this->db->tableHasField('#__xmessage', 'anonymous'))
		{
			$query = "ALTER TABLE `#__xmessage` DROP `anonymous`";
			$this->db->setQuery($query);
			$this->db->query();
		}
	}
}
