<?php
ini_set('display_errors', 1);
$xpdo_meta_map = array ();

$this->map['modResource']['composites']['Redirects'] = array(
    'class' => 'modRedirect',
    'local' => 'id',
    'foreign' => 'resource_id',
    'cardinality' => 'many',
    'owner' => 'local',
);
