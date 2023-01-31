<?php
// Code source permettant d'accéder aux données parking du Grand Nancyecho "Test";
//phpinfo();
require_once '../vendor/autoload.php';
$pis = [];

global $db; 
$db = (new MongoDB\Client('mongodb://mongo'))->selectDatabase('tdmongo');
$db->dropCollection('voiture');
$db->dropCollection('velo');
$data = json_decode(file_get_contents('https://api.jcdecaux.com/vls/v3/stations?apiKey=frifk0jbxfefqqniqez09tw4jvk37wyf823b5j1i&contract=nancy'));
$db->createCollection('velo');
$db = $db->selectCollection('velo'); 

// var_dump($db);

  


// echo($db);

foreach ($data as $field) {
  $velo = [
    'nom' => $field->name,
    'address' => $field->address,
    'description' => '',
    'position' => $field->position,
    'total_stand' => $field->totalStands,
    'main_stand'=> $field->mainStands,
    'status' => $field->status,
  ];
  $velos[] = $velo;
}
if (count($velos) > 0) {
    $res = $db->insertMany($velos);
  }

$findMethodResult = $db->find();
$test = [];
$i = 0;
foreach ($findMethodResult as $entry) {
    $test[$i] = $entry;
    $i++;
}
// var_dump($pis);

$db = (new MongoDB\Client('mongodb://mongo'))->selectDatabase('tdmongo');
$data = json_decode(file_get_contents('https://geoservices.grand-nancy.org/arcgis/rest/services/public/VOIRIE_Parking/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=nom%2Cadresse%2Cplaces%2Ccapacite&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson'));
$db->createCollection('voiture');
$db = $db->selectCollection('voiture'); 

foreach ($data->features as $feature) {
  $voiture = [
    'name' => $feature->attributes->NOM,
    'address' => $feature->attributes->ADRESSE,
    'description' => '',
    'category' => [
      'name' => 'parking',
      'icon' => 'fa-square-parking',
      'color' => 'blue'
    ],
    'geometry' => $feature->geometry,
    'places' => $feature->attributes->PLACES,
    'capacity' => $feature->attributes->CAPACITE,
  ];
  $voitures[] = $voiture;

}
if (count($voitures) > 0) {
  $res = $db->insertMany($voitures);
}

$findMethodResult2 = $db->find();
$test2 = [];
$i = 0;
foreach ($findMethodResult2 as $entry) {
    $test2[$i] = $entry;
    $i++;
}

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
        <h1>Carte</h1>
        <div id="maCarte"></div>
        <p>Les marqueurs bleu correspondent aux vélos Stan de Nancy et les marqueurs rouge correspondent aux parking de Nancy</p>

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
           
            var test = <?php echo json_encode($test); ?>;
            var test2 = <?php echo json_encode($test2); ?>;
            console.log(test);
            
            for (const t of test) {
                console.log(t.position.latitude);
                    //remet les coordonnees sur la carte
                    var marker = L.marker([t.position.latitude, t.position.longitude]).addTo(carte);
                    //affiche le nom du parking
                    marker.bindPopup(t.nom+"<br/>"+t.status+"<br/>"+t.total_stand.availabilities.bikes+" vélos disponibles"+"<br/>"+t.total_stand.availabilities.stands+" places disponibles");

                }
            for (const t of test2)
            {
                let redMarker = L.icon({iconUrl: "https://github.com/pointhi/leaflet-color-markers/blob/master/img/marker-icon-red.png?raw=true",
                    conSize: [25, 41]});
                var marker2 = L.marker([t.geometry.y, t.geometry.x],{icon: redMarker}).addTo(carte);
                    if(t.places == null)
                    {
                        t.places = 0;
                    }
                    marker2.bindPopup(t.name+"<br/>"+t.places+" places disponibles"+"<br/>"+t.capacity+" places totales");
            }
        </script>
        
    </body>
</html>