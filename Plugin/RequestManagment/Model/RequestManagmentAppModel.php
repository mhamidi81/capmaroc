<?php

App::uses('AppModel', 'Model');

class RequestManagmentAppModel extends AppModel {
  public $tablePrefix = 'rqm_';
  public $actsAs = array('Containable');
}
