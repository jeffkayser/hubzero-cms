<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

$field = strtolower(JRequest::getWord('field', ''));

$offerings = $this->course->offerings(array(
	'available' => true, 
	'sort'      => 'publish_up'
));

$action = strtolower(JRequest::getWord('action', ''));

Hubzero_Document::addComponentScript('com_courses', 'assets/js/courses.overview');
?>
<div id="content-header"<?php if ($this->course->get('logo')) { echo ' class="with-identity"'; } ?>>
	<h2>
		<?php echo $this->escape(stripslashes($this->course->get('title'))); ?>
	</h2>
	<?php if ($this->course->get('logo')) { ?>
	<p class="course-identity">
		<img src="/site/courses/<?php echo $this->course->get('id'); ?>/<?php echo $this->course->get('logo'); ?>" alt="<?php echo JText::_('Course logo'); ?>" />
	</p>
	<?php } ?>
	<p id="page_identity">
		<a class="icon-browse browse" href="<?php echo JRoute::_('index.php?option=' . $this->option . '&controller=' . $this->controller . '&task=browse'); ?>">
			<?php echo JText::_('Course catalog'); ?>
		</a>
	</p>
</div>

<div class="course section intro">
	<div class="aside">
	<?php
$c = 0;
if ($offerings->total())
{
	foreach ($offerings as $offering) 
	{
		if (!$offering->isAvailable())
		{
			continue;
		}
		$c++;

		$controls = '';

		/*if ($this->sections)
		{
			foreach ($this->sections as $section)
			{
				if (isset($section['controls']) && $section['controls'] != '') 
				{
					$controls = $section['controls'];
				}
			}
		}*/

		if (!$controls) 
		{
			$memberships = $offering->membership();
			if (!count($memberships))
			{
				$memberships[] = new CoursesModelMember(JFactory::getUser()->get('id'), $this->course->get('id'), $offering->get('id'));
			}

			$mng  = -1;
			$last = '';
			foreach ($memberships as $membership)
			{
				$cur  = ($membership->get('offering_id') ? $membership->get('offering_id') : $offering->get('id')) . '-';
				$cur .= ($membership->get('section_id') ? $offering->section($membership->get('section_id'))->get('alias') : $offering->section()->get('alias'));

				if ($cur == $last || $mng == $offering->get('id'))
				{
					continue;
				}
				$last = $cur;

				// If they're a course level manager
				if ($membership->get('course_id') && !$membership->get('section_id') && !$membership->get('student'))
				{
					$mng = $offering->get('id');

					// Get the default section
					$dflt = $offering->section('__default');
					if (!$dflt->exists())
					{
						// No default? Get the first in the list
						$dflt = $offering->sections()->fetch('first');
					}
					$offering->section($dflt->get('alias'));
				?>
			<div class="offering-info">
				<table>
					<tbody>
						<tr>
							<th scope="row"><?php echo JText::_('Offering:'); ?></th>
							<td>
								<?php echo $this->escape(stripslashes($offering->get('title'))); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo JText::_('Section:'); ?></th>
							<td>
								<?php echo $offering->sections()->total() > 1 ? JText::_('--') : $this->escape(stripslashes($dflt->get('title'))); ?>
							</td>
						</tr>
					</tbody>
				</table>
				<?php if ($offering->sections()->total() > 1) { ?>
				<div class="btn-group-wrap">
					<div class="btn-group dropdown">
						<a class="btn" href="<?php echo JRoute::_($offering->link('enter')); ?>"><?php echo $this->escape(stripslashes($dflt->get('title'))); ?></a>
						<span class="btn dropdown-toggle"></span>
						<ul class="dropdown-menu">
						<?php 
						foreach ($offering->sections() as $section) 
						{
							// Skip the default
							if ($section->get('alias') == $dflt->get('alias'))
							{
								continue;
							}
							$offering->section($section->get('id'));
							?>
							<li>
								<a href="<?php echo JRoute::_($offering->link()); ?>">
									<?php echo $this->escape(stripslashes($section->get('title'))); ?>
								</a>
							</li>
							<?php
						}
						?>
						</ul>
						<div class="clear"></div>
					</div><!-- /btn-group -->
				</div>
				<?php } else { ?>
				<p>
					<a class="outline btn" href="<?php echo JRoute::_($offering->link('enter')); ?>">
						<?php echo JText::_('Access Course'); ?>
					</a>
				</p>
				<?php } ?>
			</div><!-- / .offering-info -->
					<?php
				}
				else
				{
					?>
			<div class="offering-info">
				<table>
					<tbody>
						<tr>
							<th scope="row"><?php echo JText::_('Offering:'); ?></th>
							<td>
								<?php echo $this->escape(stripslashes($offering->get('title'))); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo JText::_('Section:'); ?></th>
							<td>
								<?php echo ($membership->get('section_id') ? $this->escape(stripslashes($offering->section($membership->get('section_id'))->get('title'))) : $this->escape(stripslashes($offering->section()->get('title')))); ?>
							</td>
						</tr>
					</tbody>
				</table>
			<?php if ($offering->access('view', 'section') || $this->course->isStudent()) { //$this->course->isManager() ?>
				<p>
					<a class="outline btn" href="<?php echo JRoute::_($offering->link('enter')); ?>">
						<?php echo JText::_('Access Course'); ?>
					</a>
				</p>
			<?php } else if ($offering->section()->get('enrollment') != 2) { ?>
				<p>
					<a class="enroll btn" href="<?php echo JRoute::_($offering->link('enroll')); ?>">
						<?php echo JText::_('Enroll in Course'); ?>
					</a>
				</p>
			<?php } ?>
			</div><!-- / .offering-info -->
				<?php
				}
			}
		} else {
			echo '<div class="offering-info">' . $controls . '</div><!-- / .offering-info -->';
		}
	}
}
if (!$c)
{
	?>
		<div class="offering-info">
			<p>
				<?php echo JText::_('No offerings available.'); ?>
			</p>
		</div><!-- / .offering-info -->
	<?php
}
?>
	</div><!-- / .aside -->
	<div class="subject">
		<?php if (($field == 'blurb' || $field == 'tags') && $this->course->access('edit', 'course')) { ?>
			<form action="<?php echo JRoute::_('index.php?option=' . $this->option); ?>" class="form-inplace" method="post">
				<label for="field_blurb">
					<?php
						ximport('Hubzero_Wiki_Editor');
						$editor =& Hubzero_Wiki_Editor::getInstance();
						echo $editor->display('course[blurb]', 'field_blurb', stripslashes($this->course->get('blurb')), '', '50', '5');
					?>
				</label>

				<label for="actags">
					<?php echo JText::_('Tags'); ?>

					<?php 
					JPluginHelper::importPlugin( 'hubzero' );
					$dispatcher =& JDispatcher::getInstance();
					$tf = $dispatcher->trigger( 'onGetMultiEntry', array(array('tags', 'tags', 'actags','', $this->course->tags('string'))) );
					$tf = implode("\n", $tf);

					if ($tf) {
						echo $tf;
					} else { ?>
						<input type="text" name="tags" id="actags" value="<?php echo $this->escape($this->couse->tags('string')); ?>" />
					<?php } ?>

					<span class="hint">These are keywords that describe your course and will help people find it when browsing, searching, or viewing related content. <?php echo JText::_('COM_COURSES_FIELD_TAGS_HINT'); ?></span>
				</label>

				<p class="submit">
					<input type="submit" class="btn btn-success" value="<?php echo JText::_('Save'); ?>" />
					<a class="btn btn-secondary" href="<?php echo JRoute::_($this->course->link()); ?>">
						<?php echo JText::_('Cancel'); ?>
					</a>
				</p>

				<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
				<input type="hidden" name="controller" value="course" />
				<input type="hidden" name="task" value="save" />

				<?php echo JHTML::_('form.token'); ?>

				<input type="hidden" name="gid" value="<?php echo $this->escape($this->course->get('alias')); ?>" />
				<input type="hidden" name="course[id]" value="<?php echo $this->escape($this->course->get('id')); ?>" />
				<input type="hidden" name="course[alias]" value="<?php echo $this->escape($this->course->get('alias')); ?>" />
			</form>
		<?php } else { ?>
			<?php if ($this->course->access('edit', 'course')) { ?>
				<div class="manager-options">
					<a class="icon-edit btn btn-secondary" href="<?php echo JRoute::_($this->course->link() . '&task=edit&field=blurb'); ?>">
						<?php echo JText::_('Edit'); ?>
					</a>
					<span><strong>Short description</strong></span>
				</div>
			<?php } ?>
			<p>
				<?php echo $this->escape(stripslashes($this->course->get('blurb'))); ?>
			</p>

			<?php echo $this->course->tags('cloud'); ?>
		<?php } ?>
	</div><!-- / .subject -->
	<div class="clear"></div>
</div><!-- / .course section intro -->

<?php if ($this->course->access('edit', 'course') && !$c) { ?>
<div class="course section intro offering-help">
	<div class="aside">
		<p>
			<a class="icon-add btn" id="add-offering" href="<?php echo JRoute::_($this->course->link() . '&task=newoffering'); ?>"><?php echo JText::_('Create an offering'); ?></a>
		</p>
	</div><!-- / .aside -->
	<div class="subject">
		<p>
			<strong>This course needs an offering!</strong></p>
			An offering is a collection of materials (lectures, quizzes, etc.) that represents a version or edition of a course. Generally, a significant change in course materials would be considered a new offering.
		</p>
	</div><!-- / .subject -->
	<div class="clear"></div>
</div><!-- / .course section intro offering-help -->
<?php } ?>

<div class="course section">
	<div class="aside">
		<?php
		if ($this->course->access('edit', 'course')) 
		{
			?>
			<div class="manager-options">
				<a class="icon-edit btn btn-secondary" id="manage-instructors" href="<?php echo JRoute::_($this->course->link() . '&task=instructors'); ?>">
					<?php echo JText::_('Manage'); ?>
				</a>
				<span><strong>Instructors/Managers</strong></span>
			</div>
			<?php
		}
		$instructors = $this->course->instructors();
		if (count($instructors) > 0) 
		{
		?>
		<div class="course-instructors" data-bio-length="200">
			<h3>
				<?php echo (count($instructors) > 1) ? JText::_('About the Instructors') : JText::_('About the Instructor'); ?>
			</h3>
			<?php
			ximport('Hubzero_View_Helper_Html');

			foreach ($instructors as $i)
			{
				$view = new JView(array(
					'name'   => 'course',
					'layout' => '_instructor'
				));
				$view->biolength  = 200;
				$view->instructor = Hubzero_User_Profile::getInstance($i->get('user_id'));
				$view->display();
			}
			?>
		</div>
		<?php
		}
		else
		{
		?>
		<div class="course-instructors-none">
			<?php echo JText::_('There are currently no instructors associated with this course.'); ?>
		</div>
		<?php
		}
		?>
		<?php
		if ($this->sections)
		{
			foreach ($this->sections as $section)
			{
				if ($section['metadata'] != '') 
				{
					echo $section['metadata'];
				}
			}
		}
		?>
	</div><!-- / .aside -->

	<div class="subject">

		<ul class="sub-menu">
			<?php
			if ($action == 'addpage')
			{
				$this->active = '';
			}
			if ($this->cats)
			{
				$i = 1;
				foreach ($this->cats as $cat)
				{
					$name = key($cat);
					if ($name != '') 
					{
						$url = JRoute::_('index.php?option=' . $this->option . '&gid=' . $this->course->get('alias') . '&active=' . $name);

						if (strtolower($name) == $this->active) 
						{
							$pathway = JFactory::getApplication()->getPathway();
							$pathway->addItem($cat[$name], $url);

							if ($this->active != 'overview') 
							{
								$document = JFactory::getDocument();
								$document->setTitle($document->getTitle() . ': ' . $cat[$name]);
							}
							if ($this->isPage)
							{
								$this->isPage = $name;
							}
						}
						?>
						<li id="sm-<?php echo $i; ?>"<?php echo (strtolower($name) == $this->active) ? ' class="active"' : ''; ?>>
							<a class="tab" data-rel="<?php echo $name; ?>" href="<?php echo $url; ?>">
								<span><?php echo $this->escape($cat[$name]); ?></span>
							</a>
						</li>
						<?php
						$i++;
					}
				}
			}
			?>
		<?php if ($this->course->access('edit', 'course')) { ?>
			<li class="add-page">
				<a class="icon-add tab" href="<?php echo JRoute::_($this->course->link() . '&action=addpage'); ?>">
					<?php echo JText::_('PLG_COURSES_PAGES_ADD_PAGE'); ?>
				</a>
			</li>
		<?php } ?>
		</ul>

		<?php
		foreach ($this->notifications as $notification) 
		{
			echo "<p class=\"{$notification['type']}\">{$notification['message']}</p>";
		}

		if (($action == 'addpage' || $action == 'editpage') && $this->course->access('edit', 'course'))
		{
			$page = $this->course->page($this->active);
			?>
		<div class="inner-section" id="addpage-section">
			<form action="<?php echo JRoute::_($this->course->link()); ?>" class="form-inplace" method="post">
				<fieldset>
					<div class="grid">
						<div class="col span-half">
							<label for="field-title">
								<?php echo JText::_('PLG_COURSES_PAGES_FIELD_TITLE'); ?> <span class="required"><?php echo JText::_('PLG_COURSES_PAGES_REQUIRED'); ?></span>
								<input type="text" name="page[title]" id="field-title" value="<?php echo $this->escape(stripslashes($page->get('title'))); ?>" />
								<span class="hint"><?php echo JText::_('PLG_COURSES_PAGES_FIELD_TITLE_HINT'); ?></span>
							</label>
						</div>
						<div class="col span-half omega">
							<label for="field-url">
								<?php echo JText::_('PLG_COURSES_PAGES_FIELD_ALIAS'); ?> <span class="optional"><?php echo JText::_('PLG_COURSES_PAGES_OPTINAL'); ?></span>
								<input type="text" name="page[url]" id="field-url" value="<?php echo $this->escape(stripslashes($page->get('url'))); ?>" />
								<span class="hint"><?php echo JText::_('PLG_COURSES_PAGES_FIELD_ALIAS_HINT'); ?></span>
							</label>
						</div>
					</div>

					<label for="field_description">
						<?php
							ximport('Hubzero_Wiki_Editor');
							$editor = Hubzero_Wiki_Editor::getInstance();
							echo $editor->display('page[content]', 'field_content', stripslashes($page->get('content')), '', '50', '50');
						?>
					</label>

					<p class="submit">
						<input type="submit" class="btn btn-success" value="<?php echo JText::_('Save'); ?>" />
						<a class="btn btn-secondary" href="<?php echo JRoute::_($this->course->link()); ?>">
							<?php echo JText::_('Cancel'); ?>
						</a>
					</p>

					<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
					<input type="hidden" name="controller" value="course" />
					<input type="hidden" name="task" value="savepage" />

					<?php echo JHTML::_('form.token'); ?>

					<input type="hidden" name="gid" value="<?php echo $this->escape($this->course->get('alias')); ?>" />
					<input type="hidden" name="page[id]" value="<?php echo $this->escape($page->get('id')); ?>" />
					<input type="hidden" name="page[alias]" value="<?php echo $this->escape($page->get('alias')); ?>" />
					<input type="hidden" name="page[course_id]" value="<?php echo $this->course->get('id'); ?>" />
					<input type="hidden" name="page[section_id]" value="0" />
					<input type="hidden" name="page[offering_id]" value="0" />
				</fieldset>
			</form>
		</div>
			<?php
		}
		elseif ($this->sections)
		{
			$k = 0;
			foreach ($this->sections as $section)
			{
				if ($section['html'] != '') 
				{
					?>
					<div class="inner-section" id="<?php echo $section['name']; ?>-section">
						<?php if ($this->course->access('edit', 'course') && $this->isPage) { ?>
							<div class="manager-options">
								<a class="icon-error btn btn-secondary btn-danger" href="<?php echo JRoute::_($this->course->link() . '&active=' . $this->isPage . '&task=deletepage'); ?>">
									<?php echo JText::_('Delete'); ?>
								</a>
								<a class="icon-edit btn btn-secondary" href="<?php echo JRoute::_($this->course->link() . '&active=' . $this->isPage . '&action=editpage'); ?>">
									<?php echo JText::_('Edit'); ?>
								</a>
								<span><strong>Page contents</strong></span>
							</div>
						<?php } ?>
						<?php echo $section['html']; ?>
					</div><!-- / .inner-section -->
					<?php 
				}
				$k++;
			}
		}
		?>
	</div><!-- / .subject -->
	<div class="clear"></div>
</div><!-- / .course section -->

<?php
JPluginHelper::importPlugin('courses');

$after = JDispatcher::getInstance()->trigger('onCourseViewAfter', array($this->course));
if ($after && count($after) > 0) { ?>
<div class="below course section">
	<?php echo implode("\n", $after); ?>
</div><!-- / .course section -->
<?php } ?>