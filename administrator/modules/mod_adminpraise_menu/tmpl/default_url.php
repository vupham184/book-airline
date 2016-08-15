<?php
/**
 * @package     Square One
 * @link        www.squareonecms.org
 * @copyright   Copyright 2011 Square One and Open Source Matters. All Rights Reserved.
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$class = '';
// Note. It is important to remove spaces between elements.
if(strpos($item->img, 'class:') === 0) {
    $class = 'class="icon-16-'. str_replace('class:', '', $item->img).'"';
} else {
    if($item->img) {
        $class = 'style="background-image: url('.$item->img.');"';
    }
}
?>

<?php

switch ($item->browserNav) :
    default:
    case 0:
        ?>
        <a <?php echo $class; ?>href="<?php echo $item->flink; ?>" title="<?php echo JText::_($item->title); ?>">
            <?php if($item->deeper) : ?>
                <span class="subarrow"></span>
            <?php endif; ?>
            <span class="component-image"></span>
            <?php if($item->deeper) : ?>
                <span class="parent-name">
                    <?php echo JText::_($item->title); ?>
                </span>
            <?php else : ?>
                <?php echo JText::_($item->title); ?>
            <?php endif; ?>
        </a>
        <?php
        break;
    case 1:
        // _blank
        ?>
        <a <?php echo $class; ?>href="<?php echo $item->flink; ?>" target="_blank" title="<?php echo JText::_($item->title); ?>">
            <?php if($item->deeper) : ?>
                <span class="subarrow"></span>
            <?php endif; ?>
            <span class="component-image"></span>
            <?php if($item->deeper) : ?>
            <span class="parent-name">
                    <?php echo JText::_($item->title); ?>
                </span>
            <?php else : ?>
                <?php echo JText::_($item->title); ?>
            <?php endif; ?>
        </a>
        <?php
        break;
    case 2:
        // window.open
        $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$params->get('window_open');
        ?>
        <a <?php echo $class; ?>href="<?php echo $item->flink; ?>" target="_blank" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;"  title="<?php echo JText::_($item->title); ?>">
            <?php if($item->deeper) : ?>
                <span class="subarrow"></span>
            <?php endif; ?>
            <span class="component-image"></span>
            <?php if($item->deeper) : ?>
            <span class="parent-name">
                    <?php echo JText::_($item->title); ?>
                </span>
            <?php else : ?>
                <?php echo JText::_($item->title); ?>
            <?php endif; ?>
        </a>
        <?php
        break;
endswitch;
?>