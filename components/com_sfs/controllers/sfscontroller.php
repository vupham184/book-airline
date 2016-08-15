<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

abstract class SfsController extends JController {

    public function __construct( $config = array() ) {
        $config['model_prefix'] = 'SfsModel';
        parent::__construct($config);
    }

}