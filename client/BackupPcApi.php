<?php  

    class BackupPcApi {
        
        private $config;
        
        public function __construct($config = null) {
            
            if(is_null($config)) {

                $bpConfig = new BackupPcConfig();
                $this->config = $bpConfig->get();
            }
            else {

                $this->config = $config->get();
            }
        }
        
        public function get($params) {
            
            $uri = array();
            foreach($params as $name=>$value) {
                $uri[] = $name.'='.urlencode($value);
            }
            $uri = join('&', $uri);
            
            //echo $this->config['api_url'] . '?' . $uri;
            return 
                json_decode(
                    file_get_contents(
                        $this->config['api_url'] . '?' . $uri
                    )
                );
        }
    }


?>