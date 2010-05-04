<?php  

    /**
     * ad egy interfeszt, hogy tudjunk matatni a backuppc-n 
     *
     */
    class BackupPc {
        
        /**
         * a backup tipusa
         *
         * @var string
         */
        private $type;
        
        private $config;
        
        public function __construct($type, $config = null) {
            
            if(!in_array($type, array('tarhely', 'mysql'))) {
                
                return false;
            }
            
            $this->type = $type;

            if(is_null($config)) {
                
                $bpConfig = new BackupPcConfig();
                $this->config = $bpConfig->get();
            }
            else {
                
                $this->config = $config->get();
            }
        }
        
        public function getConfig() {
            
            return $this->config;
        }

        /**
         * visszaadja a szerverek listajat amikrol van backup
         *
         * @return array
         */
        public function getServersList() {
            
            require_once 'BackupPcFinder.php';
            
            switch($this->type) {
                
                case 'tarhely':
                        
                    $finder = new BackupPcFinder($this->config['path_to_tarhely_backups']);
                    
                    return $finder->listDirs(); 
                    
                    break;
                case 'mysql':
                    
                    $finder = new BackupPcFinder($this->config['path_to_mysql_backups']);
                    
                    return $finder->listDirs();
                    
                    break;
            }
        }

        /**
         * visszaadja a $server-hez tartozo reviziokat
         *
         * @param string $server a szerver neve
         * @return array
         */
        public function getRevisionsList($server) {
            
            require_once 'BackupPcParser.php';
            
            switch($this->type) {
                
                case 'tarhely':

                    $file = $this->config['path_to_tarhely_backups'].$server.DIRECTORY_SEPARATOR .$this->config['backups_file_name'];
                    
                    $parser = new BackupPcParser($file);
                    
                    return $parser->parseBackupsFile();
                    
                    break;
                case 'mysql':
                
                    $file = $this->config['path_to_mysql_backups'].$server;
                    
                    $parser = new BackupPcParser($file);
                    
                    return $parser->parseMySqlDirectories();
                    
                    break;
                default:
            }
        }

        /**
         * megnezi, hogy letezik e a keresett backup
         *
         * @param string $server a szerver neve
         * @param integer $revision a revizioszam
         * @param string $backup a keresett backup
         * @return boolean
         */
        public function isThereBackup($server, $revision, $backup) {
            
            require_once 'BackupPcFinder.php';
                    
            switch($this->type) {
                
                case 'tarhely':
                    /*
                        mivel a backuppc mindent f-es prefixekkel tarol, valahogy ossze kell hakolni a $backup-bol a megfelelo formatumot
                    */
                    
                    
                    $directory = $this->config['path_to_tarhely_backups'] . 
                                 $server . 
                                 DIRECTORY_SEPARATOR . 
                                 $revision .
                                 DIRECTORY_SEPARATOR . 
                                 'fhome'.
                                 DIRECTORY_SEPARATOR .
                                 implode("/", array_map(array('BackupPc', 'addF'), explode("/", $backup)));
                                 
                    echo $directory;
                    break;
                case 'mysql':
                    
                     $directory = $this->config['path_to_mysql_backups'] . 
                                 $server . 
                                 DIRECTORY_SEPARATOR . 
                                 $revision .
                                 DIRECTORY_SEPARATOR . 
                                 $backup;
                    break;
                default:
            }
            
            $finder = new BackupPcFinder($directory);
                    
            return array('result' => $finder->exists()); 
        }
        
        /**
         * mivel a backuppc minden konyvtar neve ele egy f betut pakol, ezert amikor keressuk, akkor eloallitjuk ezt a formatumot
         *
         * @param string $string 
         * @return string
         */
        public function addF($string) {
            
            return 'f'.$string;
        }
        
        public function toJson($array) {
            
            return json_encode($array);
        }

    }

?>