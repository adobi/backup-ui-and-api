<?php

        $what = $_GET['what'];

        $flag = 1;
        $path = "/var/lib/backuppc/pc/";
        switch($what) {

                case 'servers':

                        break;
                case 'revisions':

                        //if($_GET['server'])
                        $path .= $_GET['server'].'/';
                        break;
                case 'available-revisions':

                break;
                default:
                        $flag = 0;
        }

        $output = array();

        if($flag) {
            // beolvassuk a backups nevu filejat a backuppcnek, es ebbol szedjuk ki az egyes reviziok datumait

            if($what === 'revisions') {

                $backupFile = file_get_contents($path.'backups');
    
                $lines = explode("\n", $backupFile);
                $revisionDates = array();
                if(is_array($lines)) {
    
                    foreach($lines as $i => $line) {
    
                        $line = trim($line);
    
                        if($line !== '') {
    
                            $fields = explode("\t", $line);
                            $rev = trim($fields[0]);
                            $field = trim($fields[2]);
                            $date = date("Y.m.d", $field);
                            $dateParts = explode('.', $date);
                            $revisionDates[$rev] = $dateParts[1].".".$dateParts[2];
                        }
                    }
                }
            }

            $path = escapeshellcmd($path);
            $dirs = scandir($path);

            if($dirs) {

                    foreach($dirs as $dir) {

                            if(is_dir($path.$dir) && $dir !== '.' && $dir !== '..'&& $dir !== 'new') {
                                    //$output[] = $dir;
                                    if($what === 'revisions')
                                    $tmp = array('name'=>$dir, 'date'=>$revisionDates[$dir]);
                                    else $tmp = $dir;
                                    $output[] = $tmp;
                            }
                    }
            }
        }

        echo json_encode($output);
        die;

?>