<?php
require __DIR__.'/bootstrap.php';
if(($_SERVER['REQUEST_METHOD']??'')!=='GET')reply(['error'=>'Método não permitido.'],405);
$id=(string)($_GET['id']??'');$row=read_record(store_path('order',$id));
if(!$row||!hash_equals($row['token'],$_SERVER['HTTP_X_ORDER_TOKEN']??''))reply(['error'=>'Pedido não encontrado.'],404);
try {if(time()-($row['checked_at']??0)>=8)$row=refresh_order($row);reply(['id'=>$row['id'],'status'=>$row['status'],'amount'=>$row['amount']]);}
catch(Throwable $e){reply(['error'=>'Não foi possível consultar o pagamento agora.'],502);}
