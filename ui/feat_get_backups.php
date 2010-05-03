<?php
	header('Content-Type: text/html; charset=utf-8');
	
	include_once("../../db_inc/database.php");
    require_once '../client/BackupPcApiConfig.php';
    require_once '../client/BackupPcApi.php';
	
	if(isset($_GET['type']) && in_array($_GET['type'], array('tarhely', 'mysql'))) {
	    
	    $type = $_GET['type'];
	    
	    require_once '../client/BackupPcApiConfig.php';
	    require_once '../client/BackupPcApi.php';
	    
	    $config = new BackupPcApiConfig();
	    $api = new BackupPcApi($config);
	    
	    $requestParams = array();
	    $requestParams['type'] = $type;
	    $requestParams['request'] = 'servers-list';
	    
	    $response = $api->get($requestParams);
	    
	    $errors = array();
	    if(isset($response['error'])) {
	        $errors[] = $response['error'];
	    }
	    else {
	        $servers = $response;
	    }
	    
	    if(isset($_GET['server'])) {
	        
	        $requestParams['request'] = 'revisions-list';
	        $requestParams['server'] = $_GET['server'];
	        $response = $api->get($requestParams);
	        //var_dump($response); die;
    	    if(isset($response->error)) {
    	        $errors[] = $response->error;
    	    }
    	    else {
    	        $revisions = $response;
    	    }	        
	    }
	}
	else {
	    $type = '';
	}
	
	if(!empty($_POST)) {
		
	    $params = array();
        $params['type'] = $type;
		$params['server'] = $_GET['server'];
		$params['revision'] = $_GET['revision'];
		
		if(!empty($_POST['backup'])) {
		    
    		$params['path'] = trim($_POST['backup']);
    		
            $config = new BackupPcApiConfig();
            $api = new BackupPcApi($config);
            
            $backup = trim($_POST['backup']);
            
    		$requestParams = array();
    		$requestParams['type'] = $type;
    		$requestParams['request'] = 'backup-exists';
    		$requestParams['server'] = $_GET['server'];
    		$requestParams['revision'] = $_GET['revision'];
    		$requestParams['backup'] = $backup;
    		
    		$response = $api->get($requestParams);
    		
    		
    		if(!$response->result) {
    		    
    		    $errors[] = 'Nincs ilyen backup, próbálkozz mással';
    		}
    		else {
    		    $errors[] = 'A backup készítés elindúlt!';

        		$jsonParams = json_encode($params);
        		
        		
                $szolgadd = new Table($DB['szolgadd']);
                $szolgadd->_server = "106";
                $szolgadd->_funct = 'restore_init';
                $szolgadd->_param1 = $jsonParams;
                
                $szolgadd->insertObj();
                
    		    
    		}
		}
		
	}
?>		

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Backup visszaállítás</title>
		<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" href="backup.css" type="text/css" media="screen" charset="utf-8" />
        
		<script type="text/javascript" src = "http://code.jquery.com/jquery-1.4.min.js"></script>
		
	</head>
	
	<body>
		
		<div id="container">
			<h1 id = "header">
			    <a href="feat_get_backups.php" class = "title <?= $type === '' ? ' selected_' : '' ?>">Backup visszaállítás:</a>
			    <a href = "?type=tarhely" class = "type <?= $type === 'tarhely' ? ' selected' : '' ?>"> <?//= $type === 'tarhely' ? '&raquo; ' : '' ?>tárhely</a>
			    <a href = "?type=mysql" class = "type <?= $type === 'mysql' ? ' selected' : '' ?>"><?//= $type === 'mysql' ? '&raquo; ' : '' ?> mysql</a>
			</h1>
    
			<?php if(isset($servers) && !empty($servers)) : ?>
        		<div id="sidebar">
        			<ul>
    					<?php foreach($servers as $server) : ?>
    						
    						<li><a href="?type=<?=$type?>&amp;server=<?= $server; ?>" class = "server <?= isset($_GET['server']) && $_GET['server'] == $server ? 'selected-server' : '' ?>" title = "<?= $server; ?>">&raquo;&nbsp;<?= $server ?></a></li>
    						
    					<?php endforeach; ?>
        			</ul>
        		</div>
			<?php endif; ?>
			<div id="content">
			    
			    <?php if(!empty($errors)) : ?>
			        <div id = "errors" class = "hidden">
        		        <?php foreach($errors as $error) : ?>
        		            <p><?= $error; ?></p>
        		        <?php endforeach; ?>    
			            
			        </div>
			        
			    <?php endif; ?>

    			<?php if(isset($revisions) && !empty($revisions)) : ?>
    				<div id="revisions">
    					<p>
    						<?php foreach($revisions as $rev) : ?>
    							<a href = "?type=<?=$type?>&amp;server=<?= $_GET['server']; ?>&amp;revision=<?= $rev->number ?>" 
    							   class = "revision <?= isset($_GET['revision']) && $_GET['revision'] == $rev->number ? 'selected-revision' : '' ?>">
    							   <?= $rev->date ?></a>
    						<?php endforeach; ?>
    					</p>
    				</div>
			        				
    				<?php if(isset($_GET['revision'])) : ?>
    					<div id="backup">
    						<form action="" method="post" accept-charset="utf-8">
    							<p class = "hidden_">
    								<input type="text" name="backup" value="" size = "75"/> <strong id = "show-help">?</strong>
    							</p>
    							<div id = "help" class = "hidden_">
    							    <?php if($type === 'tarhely') : ?>
    								   Az <strong>útvonalat</strong> adjuk meg, a tárhelynévtől elindúlva a könyvtárig amit vissza szeretnénk állítani. <br />
    								<?php endif; ?>
    								
    								<?php if($type === 'mysql') : ?>
    								    Az adatbázis <strong>nevét</strong> kell megadni.
    								<?php endif; ?>
    							</div>
    							<p><input type="submit" value="Visszaállít &rarr;" /></p>
    							
    							<input type="hidden" name = "server" value = "<?= $_GET['server']; ?>" />
    							<input type="hidden" name = "revision" value = "<?= $_GET['revision']; ?>" />
    							
    						</form>
    					</div>
    				<?php endif; ?>
    			<?php endif; ?>
			</div> <!-- content -->
			
		</div> <!-- container -->       


		<script type="text/javascript" charset="utf-8">
			
			$(document).ready(function() {
				$('#show-help').bind('click', function() {
					$('#help').slideToggle();
					return false;
				});
				
				$('input[type=submit]').bind('click', function() {
				    
				    if(jQuery.trim($('input[name=backup]').val()) === '') {
				        
				        alert('Mező kitöltése kötelező');
				        
				        return false;
				    }
				    return true;
				});
				
				var errors = $('#errors');
				if(jQuery.trim(errors.html()) !== '') {
				   errors.slideDown(function() {
    				    setTimeout(function() {
    				        errors.slideUp();
    				    }, 3000);
				    });
				}
				
				var content = $('#content')
				if(jQuery.trim(content.html()) === '') {
				    content.hide();
				}
			});		
		
		</script>
		
	</body>
		
</html>