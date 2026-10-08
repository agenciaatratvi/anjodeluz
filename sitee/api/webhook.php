<?php
require __DIR__.'/bootstrap.php';
if(($_SERVER['REQUEST_METHOD']??'')!=='POST')reply(['error'=>'Método não permitido.'],405);
$ref=read_record(store_path('reference',(string)($_GET['ref']??'')));
if(!$ref||!hash_equals($ref['key'],(string)($_GET['key']??'')))reply(['error'=>'Não autorizado.'],403);
$data=input();$id=(string)($data['transaction']['id']??'');
if($id!==$ref['id'])reply(['error'=>'Pedido inválido.'],400);
$row=read_record(store_path('order',$id));
try {$row=refresh_order($row);if($row['status']==='paid'&&!($row['meta_purchase_sent']??false))reply(['received'=>false,'retry'=>true],503);reply(['received'=>true]);}catch(Throwable $e){reply(['received'=>false],503);}
