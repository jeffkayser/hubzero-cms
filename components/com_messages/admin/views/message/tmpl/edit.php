<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_messages
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
Html::behavior('tooltip');
Html::behavior('formvalidation');
Html::behavior('keepalive');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'message.cancel' || document.formvalidator.isValid($('#item-form'))) {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>
<form action="<?php echo Route::url('index.php?option=com_messages'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="width-100">
		<fieldset class="adminform">
			<div class="input-wrap">
				<?php echo $this->form->getLabel('user_id_to'); ?>
				<?php echo $this->form->getInput('user_id_to'); ?>
			</div>

			<div class="input-wrap">
				<?php echo $this->form->getLabel('subject'); ?>
				<?php echo $this->form->getInput('subject'); ?>
			</div>

			<div class="input-wrap">
				<?php echo $this->form->getLabel('message'); ?>
				<?php echo $this->form->getInput('message'); ?>
			</div>
		</fieldset>
		<input type="hidden" name="task" value="" />
		<?php echo Html::input('token'); ?>
	</div>
</form>
