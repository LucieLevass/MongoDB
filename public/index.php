<?php
// Code source permettant d'accéder aux données parking du Grand Nancyecho "Test";
phpinfo();

// $pis = [];

// $db = (new MongoDB\Client('mongodb://mongo'))->selectDatabase('tdmongo');
// $data = json_decode(file_get_contents('https://geoservices.grand-nancy.org/arcgis/rest/services/public/VOIRIE_Parking/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=nom%2Cadresse%2Cplaces%2Ccapacite&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson'));
// $db->createCollection('pis');
// $db = $db->selectCollection('pis'); 

// foreach ($data->features as $feature) {
//   $pi = [
//     'name' => $feature->attributes->NOM,
//     'address' => $feature->attributes->ADRESSE,
//     'description' => '',
//     'category' => [
//       'name' => 'parking',
//       'icon' => 'fa-square-parking',
//       'color' => 'blue'
//     ],
//     'geometry' => $feature->geometry,
//     'places' => $feature->attributes->PLACES,
//     'capacity' => $feature->attributes->CAPACITE,
//   ];
//   $pis[] = $pi;
// }

// if (count($pis) > 0) {
//   $res = $db->insertMany($pis);
// }
