<?php  

    /*
    require_once 'BackupPcConfig.php';
    require_once 'BackupPc.php';

    $backup = new BackupPc('mysql');
    */
    echo '<pre>';
    //var_dump($backup->getConfig());
    //var_dump($backup->getServersList());
    
    //var_dump($backup->getRevisionsList('alfa'));
    
    //var_dump($backup->isThereBackup('alfa', '1', 'adobi'));
    //var_dump($backup->isThereBackup('golf', '20100429', 'alma.sql.gz'));

    //var_dump(array_merge(array(), array('alma'=>'hello alma', 'korte'=>'hello korte')));golf.Cons_helpsesk.20100429.sql
    
    
    class T {
        
        public function add_f($string) {
            return "f".$string;    
        }
    }
    
    //$class = new ReflectionClass("T");
    //$method = $class->getMethod('add_f')->getName();
    //var_dump(get_class_methods("T"));
    var_dump(implode("/", array_map(array('T', 'add_f'), explode("/", "adobi_hello/web"))));
?>