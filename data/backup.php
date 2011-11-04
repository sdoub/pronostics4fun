#!/usr/local/bin/php
<?
echo "Votre base est en cours de sauvegarde.......

";
$filename= strftime("pronostilxp4f-%Y%m%d",time()). ".sql";
system("mysqldump --host=mysql51-39.perso --user=pronostilxp4f --password=jQjspq2q pronostilxp4f > " . $filename );
echo "Compression du fichier.....

";
system("gzip " . $filename);
echo "C'est fini. Vous pouvez récupérer la base par FTP";
?>