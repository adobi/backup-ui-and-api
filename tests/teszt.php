<?php  

    require_once 'BackupPcConfig.php';
    require_once 'BackupPc.php';

    $backup = new BackupPc('mysql');
    
    echo '<pre>';
    //var_dump($backup->getConfig());
    //var_dump($backup->getServersList());
    
    //var_dump($backup->getRevisionsList('alfa'));
    
    //var_dump($backup->isThereBackup('alfa', '1', 'adobi'));
    var_dump($backup->isThereBackup('golf', '20100429', 'alma.sql.gz'));
    //var_dump(array_merge(array(), array('alma'=>'hello alma', 'korte'=>'hello korte')));golf.Cons_helpsesk.20100429.sql
?>