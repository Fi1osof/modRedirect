<?php
// container_suffix

if($modx->getOption('friendly_urls')){


    if(!function_exists('modRedirect_write_redirect')){
        function modRedirect_write_redirect($resource){
            global $modx;
            $uri = $resource->uri;
            
            // Если не совпадает, то записываем его в базу
            
            // Пытаемся получить имеющийся объект редиректа
            // или создаем новый
            $data = array(
                "uri"   => $uri,
            );
            
            if(!$redirect = $modx->getObject('modRedirect', $data)){
                $redirect = $modx->newObject('modRedirect', $data);
            }
            
            $redirect->Resource = $resource;
            
            $redirect->save();
            
            /*
                Обновляем дочерние документы
            */
            if($Children = $resource->Children){
                foreach($Children as $Child){
                    modRedirect_write_redirect($Child);
                }
            }
            
            return;
        }
    }

    switch($modx->event->name){
        
        case 'OnBeforeDocFormSave':
            
            $data = $scriptProperties['data'];
            // Пытаемся получить объект документа
            if(
                !empty($scriptProperties['resource']) 
                AND $object = & $scriptProperties['resource']
                AND $original = $modx->getObject('modResource', $object->id)
            ){ 
                
                // Проверяем совпадает ли УРЛ
                if($original->uri != $object->getAliasPath($object->alias)){
                    modRedirect_write_redirect($original);
                } 
            }
            
            break;
        
        
        case 'OnPageNotFound':
            
            if(empty($modx->resource)){ 
                
                $identifier = $modx->resourceIdentifier;
                
                $container_suffix = $modx->getOption('container_suffix');
                
                $preg = str_replace('/', '\/', $container_suffix);
                $preg = "/{$preg}$/";
                
                
                if(!preg_match($preg, $identifier)){
                    $identifier2 = $identifier . $container_suffix;
                }
                else{
                    $identifier2 = preg_replace($preg, '', $identifier);
                }
                 
                
                // Пытаемся получить объект для редиректа
                if(
                    $redirect = $modx->getObject('modRedirect', array(
                        "uri"   => $identifier,
                        "OR:uri:="   => $identifier2,
                    ))
                    AND $url = $modx->makeUrl($redirect->resource_id)
                ){
                    $modx->sendRedirect($url, array(
                        'responseCode' => 'HTTP/1.1 301 Moved Permanently'
                    ));
                    return;
                }
            }
            
            break;
            
            
        /*
            На сортировку документов
        */    
        case 'OnResourceBeforeSort':
            
            // $modx->log(1, print_r($scriptProperties, 1));
            
            if(!empty($scriptProperties['nodes'])){
                foreach($scriptProperties['nodes'] as $node){
                    if(
                        $resource = $modx->getObject('modResource', $node['id'])
                        AND (
                            $resource->context_key != $node['context']
                            OR $resource->parent != $node['parent']
                        )
                    ){
                        modRedirect_write_redirect($resource);
                    }
                }
            }
            
            break;
    }
    
}