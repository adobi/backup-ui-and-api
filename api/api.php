<?php  

    $request = $_GET['request'];
    $type = $_GET['type'];
    
    if(!$request || !$type) die;
    
    require_once 'BackupPcConfig.php';
    require_once 'BackupPc.php';
    
    $backupPc = new BackupPc($type);
    
    switch($request) {
        
        case 'servers-list':
            echo $backupPc->toJson($backupPc->getServersList());
            break;
        case 'revisions-list':
            
            $server  = $_GET['server'];
            if(!in_array($server, $backupPc->getServersList())) echo 0;
            
            echo $backupPc->toJson($backupPc->getRevisionsList($server));
            break;
        case 'backup-exists':
            
            $server  = $_GET['server'];
            if(!in_array($server, $backupPc->getServersList())) echo 0;
            
            $revision  = $_GET['revision'];
            if(!in_array($server, $backupPc->getRevisionsList($server))) echo 0;
            
            echo $backupPc->toJson($backupPc->isThereBackup($server, $revision, $backup));
            break;
        default:
            echo 0;
    }

?>