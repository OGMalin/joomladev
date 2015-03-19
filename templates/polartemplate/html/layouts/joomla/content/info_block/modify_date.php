<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 * @version			3.4.0
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

?>
			<span class="modified">
				<!-- span class="icon-calendar"></span -->
				<time datetime="<?php echo JHtml::_('date', $displayData['item']->modified, 'c'); ?>" itemprop="dateModified">
					<?php if (new JDate($displayData['item']->modified) > new JDate('now - 1 day')) : ?>
						<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $displayData['item']->modified, JText::_('d.m.y H:i'))); ?>
					<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $displayData['item']->modified, JText::_('d.m.y'))); ?> 
					<?php endif; ?>
				</time>
			</span>