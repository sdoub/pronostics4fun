<?php
//if (!preg_match('/^192\.168\.(1)\.[0-9]+$/', $_SERVER['REMOTE_ADDR'])) {
//    die("Access Denied!");
//}
echo $_SERVER['DOCUMENT_ROOT'];
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Console</title>
    </head>
    <body style='font-family: monospace;'>
        <h1>Console</h1>
        <form method='POST'>
            <fieldset>
                <legend>Commande de base</legend>
                <p><button name='pre_cmd[]' value='diff'>Diff</button></p>
                <p><button name='pre_cmd[]' value='migrate'>Migrate</button></p>
            </fieldset>
            <fieldset>
                <legend>Comande personnalisée</legend>             
                <p>php propel <input type='text' name='cmd' value='' /><input type='submit' value='Exécuter' name='submit' /> <input type='reset' value='Annuler' /></p>
            </fieldset>
        </form>
        <?php
        if ($_POST) {
            $cmd = $_POST['cmd'] ? $_POST['cmd'] : (is_array($_POST['pre_cmd']) ? $_POST['pre_cmd'][0] : false);
            if ($cmd) {
                echo "<div style='color:white;background:black;padding:10px'>";
                echo "<pre>";
                $output = array();
							$command = "php ".$_SERVER['DOCUMENT_ROOT'] . '/vendor/bin/propel ' . $cmd ;
							echo $command;
							echo PHP_EOL;
                exec($command, $output);
                foreach ($output as $o) {
                    echo htmlspecialchars($o);
                    echo PHP_EOL;
                }
                echo "</pre>";
                echo "</div>";
            } else {
                echo "<p>Comando vuoto</p>";
            }
        }
        ?>
    </body>
</html>