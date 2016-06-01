<?php

    return array(
        'server_mode' => (PHP_SAPI === 'cli') ? 'Cli' : 'Http',
        'app_path'=>'apps',
        'ctrl_path'=>'ctrl',
        'project'=>array(
            'name'=>'zphp_chat',                 
        	'view_mode'=>'String',   		
        	'ctrl_name'=>'a',				
        	'method_name'=>'m',				
        )
    );
