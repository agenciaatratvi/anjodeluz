<?php
require __DIR__.'/bootstrap.php';
if(($_SERVER['REQUEST_METHOD']??'')!=='POST')reply(['error'=>'Método não permitido.'],405);
if(!hash_equals(csrf_token(),$_SERVER['HTTP_X_CSRF_TOKEN']??''))reply(['error'=>'Sessão inválida.'],403);
$session=session_id();session_write_close();$in=input();$size=(string)($in['size']??'');$qty=(int)($in['quantity']??0);$amount=$config['prices'][$size][$qty]??null;$id=(string)($in['eventId']??'');
if(!$amount||!preg_match('/^checkout_[a-zA-Z0-9-]{10,80}$/',$id))reply(['error'=>'Evento inválido.'],422);
$row=['size'=>$size,'quantity'=>$qty,'amount'=>$amount,'created_at'=>time(),'meta_context'=>meta_context($in['tracking']??[],$session),'source_url'=>public_base().'/'];
$ok=meta_send_event($row,'InitiateCheckout',$id);reply(['sent'=>$ok],$ok?200:502);
