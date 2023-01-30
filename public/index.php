<?php
// Code source permettant d'accéder aux données parking du Grand Nancyecho "Test";
//phpinfo();
require_once '../vendor/autoload.php';
$pis = [];

$db = (new MongoDB\Client('mongodb://mongo'))->selectDatabase('tdmongo');
$data = json_decode(file_get_contents('https://api.jcdecaux.com/vls/v3/stations?apiKey=frifk0jbxfefqqniqez09tw4jvk37wyf823b5j1i&contract=nancy'));
// $db->createCollection('pis');
$db = $db->selectCollection('pis'); 
// echo($db);

foreach ($data as $field) {
  $pi = [
    'nom' => $field->name,
    'address' => $field->address,
    'description' => '',
    'position' => $field->position,
    'total_stand' => $field->totalStands,
    'main_stand'=> $field->mainStands,
    'status' => $field->status,
  ];
  $pis[] = $pi;
}
// var_dump($pis);

// $db = (new MongoDB\Client('mongodb://mongo'))->selectDatabase('tdmongo');
// $data = json_decode(file_get_contents('https://geoservices.grand-nancy.org/arcgis/rest/services/public/VOIRIE_Parking/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=nom%2Cadresse%2Cplaces%2Ccapacite&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson'));
// // $db->createCollection('pis');
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
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>OpenStreetMap</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
        <!-- CSS -->
        <style>
            body{
                margin:0
            }
            #maCarte{
                height: 100vh;
            }
        </style>
    </head>
    <body>
        <div id="maCarte"></div>

        <!-- Fichiers Javascript -->
        <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>
        <script>
            // On initialise la carte
            var carte = L.map('maCarte').setView([48.693882, 6.184901], 13);
            
            // On charge les "tuiles"
            L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                // Il est toujours bien de laisser le lien vers la source des données
                attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                minZoom: 1,
                maxZoom: 20
            }).addTo(carte);
        </script>
        <script>
         
            var test = <?php echo json_encode($pis);?>;
            for (const t of test) {
                console.log(t);
                    //remet les coordonnees sur la carte
                    var marker = L.marker([t.position.latitude, t.position.longitude]).addTo(carte);
                    //affiche le nom du parking
                    marker.bindPopup(t.nom);
                    
                 

                }

            
        </script>
    </body>
</html>