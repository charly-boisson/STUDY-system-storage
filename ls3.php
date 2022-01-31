require 'vendor/autoload.php';

use Aws\S3\S3Client;  
use Aws\S3\Exception\S3Exception;


//////////////// Authentication
$s3Client = new S3Client([
<Données fournies par le client en general aws_accesss_key_id, aws_secret_access_key et endpoint_url>
]);

//////////////// Déposer un fichier dans un "bucket" que l'on peut considérer comme équivalent à un volume
//////////////// Le nom de chaque objet (key = filename) est équivalent au path complet
//////////////// Le bucket est supposé déjà créé (car chez les clients, il est en général créé par les admins)

try {
    $result = $s3Client->putObject([
        'Bucket' => $bucket,
        'Key' => $fileName,
        'SourceFile' => $file_,
    ]);
} catch (S3Exception $e) {
    echo $e->getMessage() . "\n";
}

//////////////// Récupérer le contenu d'un fichier

try {
    $result = $s3->getObject([
        'Bucket' => $bucket,
        'Key'    => $fileName
    ]);

} catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//////////////// Supprimer un objet

try
{
    $result = $s3->deleteObject([
        'Bucket' => $bucket,
        'Key'    => $keyname
    ]);

    if ($result['DeleteMarker'])
    {
        echo $keyname . ' was deleted or does not exist.' . PHP_EOL;
    } else {
        exit('Error: ' . $keyname . ' was not deleted.' . PHP_EOL);
    }
}
catch (S3Exception $e) {
    exit('Error: ' . $e->getAwsErrorMessage() . PHP_EOL);
}

//////////////// Obtenir une URL présignée d'accès à l'objet qui expirera automatiquement dans 365 jours

$cmd = $s3Client->getCommand('GetObject', [
    'Bucket' => 'my-bucket',
    'Key' => 'fileName'
]);
$request = $s3Client->createPresignedRequest($cmd, '+365 days');
$presignedUrl = (string)$request->getUri();

A partir de là, l'url obtenue peut-être stockée dans votre base pour être ajoutée dans n'importe quelle réponse http, n'importe quel template html, etc...
