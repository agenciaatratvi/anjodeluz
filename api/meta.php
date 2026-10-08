<?php
declare(strict_types=1);
if(realpath($_SERVER['SCRIPT_FILENAME']??'')===__FILE__){http_response_code(404);exit;}
function meta_context(array $tracking,string $session): array {
    $u=['client_user_agent'=>substr($_SERVER['HTTP_USER_AGENT']??'',0,500),'external_id'=>[hash('sha256',$session)]];
    $ip=$_SERVER['REMOTE_ADDR']??'';if(filter_var($ip,FILTER_VALIDATE_IP))$u['client_ip_address']=$ip;
    foreach(['fbp'=>'_fbp','fbc'=>'_fbc']as $key=>$cookie){$v=(string)($_COOKIE[$cookie]??'');if(preg_match('/^fb\.\d\.\d+\.[A-Za-z0-9_.-]+$/',$v))$u[$key]=$v;}
    $click=(string)($tracking['fbclid']??'');
    if(!isset($u['fbc'])&&preg_match('/^[A-Za-z0-9_-]{5,500}$/',$click))$u['fbc']='fb.1.'.(int)round(microtime(true)*1000).'.'.$click;
    return array_filter($u,fn($v)=>$v!==''&&$v!==[]);
}
function meta_send_event(array $row,string $name,string $eventId): bool {
    global $config;
    if(empty($config['facebook_capi_token']))return true;
    if($name==='Purchase'&&($row['status']??'')!=='paid')return false;
    $path=store_path('meta',$eventId);$lock=fopen(store_path('meta-lock',$eventId),'c+');
    if(!$lock)return false;
    flock($lock,LOCK_EX);
    try {
        $state=read_record($path)??[];if(!empty($state['sent']))return true;
        // Limita consultas do navegador; webhook solicita nova tentativa em caso de falha.
        if(($state['last_attempt']??0)>time()-10)return false;
        $u=$row['meta_context']??[];$c=$row['customer']??[];
        if(!empty($c['email']))$u['em']=[hash('sha256',strtolower(trim($c['email'])))];
        if(!empty($c['phone']))$u['ph']=[hash('sha256','55'.normalize_phone($c['phone']))];
        $eventTime=$state['event_time']??($name==='Purchase'?($row['paid_at_ts']??time()):($row['created_at']??time()));
        $contentId='guardian-angel-starfall-001-'.$row['size'];
        $event=['event_name'=>$name,'event_time'=>$eventTime,'event_id'=>$eventId,'action_source'=>'website','event_source_url'=>$row['source_url']??public_base().'/','user_data'=>$u,
            'custom_data'=>['value'=>$row['amount']/100,'currency'=>'BRL','content_type'=>'product','content_ids'=>[$contentId],'contents'=>[['id'=>$contentId,'quantity'=>$row['quantity']]],'num_items'=>$row['quantity']]];
        if($name==='Purchase')$event['custom_data']['order_id']=$row['id'];
        $payload=['data'=>[$event],'access_token'=>$config['facebook_capi_token']];
        if(!empty($config['facebook_test_event_code']))$payload['test_event_code']=$config['facebook_test_event_code'];
        $state=['event_name'=>$name,'event_id'=>$eventId,'event_time'=>$eventTime,'last_attempt'=>time(),'attempts'=>($state['attempts']??0)+1,'sent'=>false];
        write_record($path,$state);
        $url=rtrim($config['facebook_graph_base'],'/').'/'.$config['facebook_graph_version'].'/'.$config['facebook_pixel_id'].'/events';
        $ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_CONNECTTIMEOUT=>3,CURLOPT_TIMEOUT=>8,CURLOPT_FOLLOWLOCATION=>false,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_POSTFIELDS=>json_encode($payload,JSON_UNESCAPED_SLASHES)]);
        $raw=curl_exec($ch);$status=curl_getinfo($ch,CURLINFO_HTTP_CODE);$errno=curl_errno($ch);curl_close($ch);$r=json_decode((string)$raw,true);
        $state['http_status']=$status;$state['events_received']=(int)($r['events_received']??0);$state['error_code']=$r['error']['code']??null;$state['error_subcode']=$r['error']['error_subcode']??null;
        $state['sent']=!$errno&&$status>=200&&$status<300&&$state['events_received']>=1;
        write_record($path,$state);
        if(!$state['sent'])error_log('Meta CAPI '.$name.' HTTP '.$status.' error_code '.($state['error_code']??$errno));
        return $state['sent'];
    }catch(Throwable $e){error_log('Meta CAPI: falha ao enviar evento.');return false;}
    finally{flock($lock,LOCK_UN);fclose($lock);}
}
