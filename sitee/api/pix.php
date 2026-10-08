<?php
require __DIR__.'/bootstrap.php';
if(($_SERVER['REQUEST_METHOD']??'')!=='POST')reply(['error'=>'Método não permitido.'],405);
if(!hash_equals(csrf_token(),$_SERVER['HTTP_X_CSRF_TOKEN']??''))reply(['error'=>'Recarregue a página e tente novamente.'],403);
$session=session_id();session_write_close();$in=input();
$key=(string)($in['requestId']??'');if(!preg_match('/^[a-zA-Z0-9-]{16,80}$/',$key))reply(['error'=>'Pedido inválido.'],422);
$lock=fopen(store_path('lock',$session.$key),'c+');flock($lock,LOCK_EX);
$requestPath=store_path('request',$session.$key);$previous=read_record($requestPath);
if($previous){if(isset($previous['id'])){ $row=read_record(store_path('order',$previous['id']));if($row)reply(public_order($row)); }
 reply(['error'=>'A resposta anterior está em confirmação. Não gere outra cobrança; aguarde e confira o painel da operadora.'],409);}
try {
 $size=(string)($in['size']??'');$qty=(int)($in['quantity']??0);$amount=$config['prices'][$size][$qty]??null;
 if($amount===null)throw new DomainException('Selecione um tamanho e quantidade válidos.');
 $c=$in['customer']??[];$a=$in['address']??[];
 $name=trim((string)($c['name']??''));$email=trim((string)($c['email']??''));$cpf=preg_replace('/\D/','',(string)($c['document']??''));$phone=normalize_phone((string)($c['phone']??''));
 if(strlen($name)<3||strlen($name)>150)throw new DomainException('Preencha seu nome completo.');
 if(!filter_var($email,FILTER_VALIDATE_EMAIL))throw new DomainException('Confira o e-mail informado.');
 if(!valid_cpf($cpf))throw new DomainException('O CPF informado é inválido. Confira os 11 dígitos.');
 if(!valid_phone($phone))throw new DomainException('Telefone inválido: informe DDD + 9 dígitos para celular (começando com 9), ou DDD + 8 dígitos para telefone fixo.');
 $address=[];foreach(['zip','street','number','complement','neighborhood','city','state'] as $k)$address[$k]=trim((string)($a[$k]??''));
 $address['zip']=preg_replace('/\D/','',$address['zip']);$address['state']=strtoupper($address['state']);
 foreach(['street','number','neighborhood','city']as $k)if($address[$k]===''||strlen($address[$k])>180)throw new DomainException('Preencha o endereço de entrega.');
 if(!preg_match('/^\d{8}$/',$address['zip'])||!in_array($address['state'],explode(' ','AC AL AP AM BA CE DF ES GO MA MT MS MG PA PB PR PE PI RJ RN RS RO RR SC SP SE TO')))throw new DomainException('Confira CEP e UF.');
 $ratePath=store_path('rate',$_SERVER['REMOTE_ADDR']??'unknown');$rateLock=fopen(store_path('rate-lock',$_SERVER['REMOTE_ADDR']??'unknown'),'c+');flock($rateLock,LOCK_EX);
 $rate=read_record($ratePath)??[];$rate=array_values(array_filter($rate,fn($t)=>$t>time()-60));if(count($rate)>=3)reply(['error'=>'Aguarde um minuto antes de gerar outro PIX.'],429);$rate[]=time();write_record($ratePath,$rate);flock($rateLock,LOCK_UN);fclose($rateLock);
 $token=bin2hex(random_bytes(24));$orderRef='pedido-'.bin2hex(random_bytes(12));$webhookKey=bin2hex(random_bytes(24));
 $postback=public_base().'/api/webhook.php?key='.$webhookKey.'&ref='.$orderRef;
 $payload=['amount'=>$amount,'paymentMethod'=>'pix','customer'=>['name'=>$name,'email'=>$email,'document'=>$cpf,'phone'=>$phone,'address'=>$address],
 'items'=>[['description'=>'Cascata de Estrelas do Anjo Guardião™ ('.$size.')','quantity'=>$qty,'amount'=>$amount,'externalRef'=>$orderRef]],'pix'=>['expirationInSeconds'=>$config['pix_expiration_seconds']],'postbackUrl'=>$postback];
 write_record($requestPath,['state'=>'processing','created_at'=>time()]);
 $tx=normalize_transaction(api_request('POST','/transactions',$payload));
 if(!$tx['id']||!$tx['pixCode'])throw new RuntimeException('A operadora não retornou o código PIX. Confira o painel antes de gerar outra cobrança.');
 if($tx['amount']!==$amount)throw new RuntimeException('O valor retornado não corresponde à oferta.');
 $row=$tx+['token'=>$token,'size'=>$size,'quantity'=>$qty,'created_at'=>time(),'checked_at'=>0,'customer'=>$payload['customer'],'tracking'=>$in['tracking']??[],'webhook_key'=>$webhookKey,'reference'=>$orderRef,'meta_context'=>meta_context($in['tracking']??[],$session),'source_url'=>public_base().'/'];
 $row['expiresAt']=$row['expiresAt']?:gmdate('c',time()+$config['pix_expiration_seconds']);
 write_record(store_path('order',$row['id']),$row);write_record(store_path('reference',$orderRef),['id'=>$row['id'],'key'=>$webhookKey]);write_record($requestPath,['id'=>$row['id']]);
 reply(public_order($row));
}catch(DomainException $e){if(is_file($requestPath))unlink($requestPath);reply(['error'=>$e->getMessage()],422);}catch(Throwable $e){error_log('PIX: '.$e->getMessage());reply(['error'=>$e->getMessage()],502);}
