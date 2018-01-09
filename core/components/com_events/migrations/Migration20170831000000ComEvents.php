<?php

use Hubzero\Content\Migration\Base;

// No direct access
defined('_HZEXEC_') or die();

/**
 * Migration script for adding component entry for com_events
 **/
class Migration20170831000000ComEvents extends Base
{
	/**
	 * Up
	 **/
	public function up()
	{
		$this->addComponentEntry('events');
	}

	/**
	 * Down
	 **/
	public function down()
	{
		$this->deleteComponentEntry('events');
	}
}