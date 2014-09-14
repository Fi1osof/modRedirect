<?php
$xpdo_meta_map['modRedirect']= array (
  'package' => 'modRedirect',
  'version' => '1.1',
  'table' => 'redirects',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'uri' => NULL,
    'resource_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'uri' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '1024',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'uri' => 
    array (
      'alias' => 'uri',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'uri' => 
        array (
          'length' => '128',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  "aggregates"  => array(
        "Resource"  => array(
            "class" => "modResource",
            "cardinality"   => "one",
            "local" => "resource_id",
            "foreign"   => "id",
            "owner"     => "foreign",
        ), 
    ),
);
