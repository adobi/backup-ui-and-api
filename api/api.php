<?php  

    $request = $_GET['request'];
    $type = $_GET['type'];
    
    if(!$request || !$type) die;
    
    require_once 'BackupPcConfig.php';
    require_once 'BackupPc.php';
    
    $backupPcConfig = new BackupPcConfig(array(
        //'path_to_tarhely_backups' => '/var/lib/backuppc/pc/',
        //'path_to_mysql_backups' => '/var/lib/backuppc/sqlpackup/'
    ));
    $backupPc = new BackupPc($type, $backupPcConfig);

    switch($request) {
        
        case 'servers-list':
            echo $backupPc->toJson($backupPc->getServersList());
            break;
        case 'revisions-list':
            
            $server  = $_GET['server'];
            if(!in_array($server, $backupPc->getServersList())) {
                echo $backupPc->toJson(array('error'=>'Nincs a szerverről backup'));
                break;
            }
            echo $backupPc->toJson($backupPc->getRevisionsList($server));
            break;
        case 'backup-exists':
            
            $server  = $_GET['server'];
            
            if(!in_array($server, $backupPc->getServersList())) {
                echo $backupPc->toJson(array('error'=>'Nincs ilyen szerverünk'));
                break;
            }
            $revision  = $_GET['revision'];
            $revisions = $backupPc->getRevisionsList($server);
            $flag = 0;
            if($revisions) {
                foreach($revisions as $rev) {
                    if($rev['number'] == $revision) {
                        $flag = 1;
                        break;
                    }
                }
            }
            if(!$flag) {
                echo $backupPc->toJson(array('error'=>'Nincs ilyen revizió'));
                break;
            }
            if($type === 'mysql')
                $backup = $server.'.'.urldecode($_GET['backup']).'.'.$revision.'.sql.gz';
            else 
                $backup = urldecode($_GET['backup']);
            
            if(empty($backup)) {
                echo $backupPc->toJson(array('error'=>'Nincs backup megadva'));
                break;
            }
//            if($type == 'mysql') {
//                echo $backupPc->toJson($backupPc->isThereBackup($server, $revision, $backup));
//            }
//            else {
                echo $backupPc->toJson($backupPc->isThereBackup($server, $revision, $backup));
//            }
            break;
        default:
            echo 0;
    }

?>