<?php

namespace MyApp\Controller\Action\Helper;
// require_once 'Zend/Controller/Action/Helper/Abstract.php';
use AllowDynamicProperties;

#[AllowDynamicProperties]
class NamespacedHelper extends \Zend_Controller_Action_Helper_Abstract {}
