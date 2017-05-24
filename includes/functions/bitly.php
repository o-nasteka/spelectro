<?php function acortarUrl($url,$login,$apikey,$format = 'xml',$version = '2.0.1')
{
    // creamos la url para hacer la peticion a la API
    $uri = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$apikey.'&format='.$format;
    // cargamos el fichero xml de respuesta
    $xml = simplexml_load_file($uri);
    // devolvemos la variable con la url
    return $xml->results->nodeKeyVal->shortUrl;
 
}?>