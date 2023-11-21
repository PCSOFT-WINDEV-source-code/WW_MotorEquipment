<?php 



// querystring = chaine entière retournée par Paybox lors du retour au site (méthode GET)
//               Comme le précise la documentation Paybox, la valeur de la variable PBX_RETOUR
//               doit être obligatoirement en dernière position pour que cela fonctionne
// pubkeyfile = chemin d'accès complet au fichier de la clé public Paybox

// RETOUR : "1" si signature validée, "0" si signature erronée et "-1" en cas d'erreur.
function pbxtestsign($querystring,$pubkeyfile)
{
//recupere la signature (à la fin)
$debut = strrpos($querystring,"&")+1;
$longueur = strpos($querystring,"=",$debut)-$debut;
$signpbxvarname = substr($querystring,$debut,$longueur);
$signature = $_GET[$signpbxvarname];
//surtout pas de urldecode car $_GET decode déjà !!
//en base 64
$b64decode = base64_decode($signature);

//recupère tous les autres paramètres reçus
$position = strpos($querystring,$signpbxvarname);
$verifdata = substr($querystring,0,$position-1);

//Fichier clé public
$fp = fopen($pubkeyfile,"r");
$certificat = fread($fp,filesize($pubkeyfile));
$clepublic = openssl_pkey_get_public($certificat);
//on verifie que cel concorde
return openssl_verify($verifdata,$b64decode,$clepublic);
}

//Appel avec tous les paramètres reçus
echo pbxtestsign($_SERVER["QUERY_STRING"],"./pubkey.txt");

 ?>


