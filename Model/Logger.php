<?php
class Logger
{
    public static function log($action, $entity, $id, $infoSup = "")
    {
        $logFile = __DIR__ . '/../logs/log.txt';
        $date = date('Y-m-d H:i:s');

        // Échappe les virgules dans l'info supplémentaire si nécessaire
        $infoSup = str_replace(",", " -", $infoSup);

        $line = "$action,$entity,$id,\"$infoSup\",$date" . PHP_EOL;
        file_put_contents($logFile, $line, FILE_APPEND);
    }
}
