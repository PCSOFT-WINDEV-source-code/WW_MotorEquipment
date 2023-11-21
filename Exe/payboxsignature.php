<?php 



// querystring = chaine enti�re retourn�e par Paybox lors du retour au site (m�thode GET)
//               Comme le pr�cise la documentation Paybox, la valeur de la variable PBX_RETOUR
//               doit �tre obligatoirement en derni�re position pour que cela fonctionne
// pubkeyfile = chemin d'acc�s complet au fichier de la cl� public Paybox

// RETOUR : "1" si signature valid�e, "0" si signature erron�e et "-1" en cas d'erreur.
function pbxtestsign($querystring,$pubkeyfile)
{
//recupere la signature (� la fin)
$debut = strrpos($querystring,"&")+1;
$longueur = strpos($querystring,"=",$debut)-$debut;
$signpbxvarname = substr($querystring,$debut,$longueur);
$signature = $_GET[$signpbxvarname];
//surtout pas de urldecode car $_GET decode d�j� !!
//en base 64
$b64decode = base64_decode($signature);

//recup�re tous les autres param�tres re�us
$position = strpos($querystring,$signpbxvarname);
$verifdata = substr($querystring,0,$position-1);

//Fichier cl� public
$fp = fopen($pubkeyfile,"r");
$certificat = fread($fp,filesize($pubkeyfile));
$clepublic = openssl_pkey_get_public($certificat);
//on verifie que cel concorde
return openssl_verify($verifdata,$b64decode,$clepublic);
}

//Appel avec tous les param�tres re�us
echo pbxtestsign($_SERVER["QUERY_STRING"],"./pubkey.txt");

 ?>


