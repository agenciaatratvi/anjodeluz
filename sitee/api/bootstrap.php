<?php
declare(strict_types=1);
$config = require dirname(__DIR__) . '/config.php';
const STORE_PREFIX = "<?php http_response_code(404); exit; ?>\n";
function reply(array $data, int $code = 200): void {
    http_response_code($code); header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store'); echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); exit;
}
function start_session(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off','samesite'=>'Lax']);
        session_start();
    }
}
function csrf_token(): string { start_session(); return $_SESSION['csrf'] ??= bin2hex(random_bytes(24)); }
function input(): array {
    $raw = file_get_contents('php://input', false, null, 0, 16385);
    if (strlen($raw) > 16384) reply(['error'=>'Requisição muito grande.'],413);
    $data = json_decode($raw,true); if (!is_array($data)) reply(['error'=>'JSON inválido.'],400); return $data;
}
function store_path(string $kind, string $id): string { return dirname(__DIR__) . '/storage/' . $kind . '-' . hash('sha256',$id) . '.php'; }
function read_record(string $path): ?array {
    if (!is_file($path)) return null;
    $f=fopen($path,'rb'); flock($f,LOCK_SH); $s=stream_get_contents($f); flock($f,LOCK_UN); fclose($f);
    return json_decode(substr($s,strlen(STORE_PREFIX)),true);
}
function write_record(string $path,array $row): void {
    $tmp=$path.'.'.bin2hex(random_bytes(6)).'.tmp.php';
    if (file_put_contents($tmp,STORE_PREFIX.json_encode($row,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),LOCK_EX)===false) throw new RuntimeException('Não foi possível salvar o pedido.');
    chmod($tmp,0600); if (!rename($tmp,$path)) throw new RuntimeException('Não foi possível atualizar o pedido.');
}
function api_request(string $method,string $path,?array $body=null): array {
    global $config;
    if (!extension_loaded('curl')) throw new RuntimeException('Ative a extensão cURL do PHP na hospedagem.');
    $ch=curl_init(rtrim($config['api_base'],'/').$path);
    $opts=[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_CONNECTTIMEOUT=>8,CURLOPT_TIMEOUT=>$method==='GET'?15:25,CURLOPT_FOLLOWLOCATION=>false,CURLOPT_HTTPHEADER=>['Accept: application/json','Content-Type: application/json','X-Api-Key: '.$config['api_key']]];
    if ($body!==null) $opts[CURLOPT_POSTFIELDS]=json_encode($body,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    curl_setopt_array($ch,$opts);$raw=curl_exec($ch);$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);$errno=curl_errno($ch);curl_close($ch);
    if ($errno) throw new RuntimeException('Não foi possível confirmar a resposta do pagamento. Aguarde antes de tentar novamente.');
    $data=json_decode((string)$raw,true);
    if ($code<200||$code>=300) {
        error_log('InvictusPay HTTP '.$code.' endpoint '.$path);
        $msg=is_array($data) && is_string($data['message']??null) ? $data['message'] : 'A operadora não aceitou o pagamento (HTTP '.$code.').';
        if ($code===401||$code===403) $msg='A chave da InvictusPay não foi aceita. Verifique o config.php.';
        // Não expõe respostas completas, dados do cliente ou credenciais.
        throw new DomainException(mb_safe($msg));
    }
    if (!is_array($data)) throw new RuntimeException('Resposta inválida da operadora.');return $data;
}
function mb_safe(string $value): string { return substr(strip_tags($value),0,240); }
function transaction_data(array $r): array {
    foreach (['data','transaction'] as $k) if (isset($r[$k])&&is_array($r[$k])) $r=$r[$k];
    if (isset($r['transaction'])&&is_array($r['transaction'])) $r=$r['transaction'];return $r;
}
function normalize_transaction(array $r): array {
    $d=transaction_data($r);$p=is_array($d['pix']??null)?$d['pix']:[];
    return ['id'=>(string)($d['id']??$d['transaction_id']??$d['txId']??''),'status'=>strtolower((string)($d['status']??'pending')),'amount'=>(int)($d['amount']??0),
      'pixCode'=>(string)($p['qrcode']??$p['qrCode']??$p['qr_code']??$p['copyPaste']??$p['copy_paste']??$p['brcode']??$p['code']??$p['pix_qrcode_text']??$d['pix_qrcode']??$d['qr_code']??''),
      'expiresAt'=>$p['expirationDate']??$p['expiresAt']??$p['expires_at']??$d['expires_at']??null];
}
function valid_cpf(string $cpf): bool {
    if (!preg_match('/^\d{11}$/',$cpf)||preg_match('/^(\d)\1{10}$/',$cpf)) return false;
    for($j=9;$j<11;$j++){ $sum=0;for($i=0;$i<$j;$i++)$sum+=(int)$cpf[$i]*($j+1-$i);$v=($sum*10)%11;if($v===10)$v=0;if($v!==(int)$cpf[$j])return false;}return true;
}
function normalize_phone(string $phone): string {
    $n=preg_replace('/\D/','',$phone);
    if(in_array(strlen($n),[12,13],true)&&str_starts_with($n,'55'))$n=substr($n,2);
    return $n;
}
function valid_phone(string $phone): bool {
    return (bool)preg_match('/^[1-9]\d(?:9\d{8}|[2-8]\d{7})$/',$phone);
}
function public_base(): string {
    global $config;if($config['site_url']) { $u=rtrim($config['site_url'],'/');if(!str_starts_with($u,'https://'))throw new DomainException('O site_url precisa começar com https://.');return $u; }
    $host=$_SERVER['SERVER_NAME']??'';
    if(!preg_match('/^[a-zA-Z0-9.-]+$/',$host))throw new DomainException('Configure site_url no config.php.');
    $dir=str_replace('\\','/',dirname(dirname($_SERVER['SCRIPT_NAME']??'/api/pix.php')));
    return 'https://'.$host.($dir==='/'?'':$dir);
}
function refresh_order(array $row): array {
    $tx=normalize_transaction(api_request('GET','/transactions/'.rawurlencode($row['id'])));
    if($tx['id']!==$row['id']||$tx['amount']!==$row['amount'])throw new RuntimeException('A confirmação não corresponde ao pedido.');
    $row['status']=$tx['status'];$row['checked_at']=time();
    if($row['status']==='paid')$row['paid_at_ts']??=time();
    write_record(store_path('order',$row['id']),$row);
    if($row['status']==='paid')$row['meta_purchase_sent']=meta_send_event($row,'Purchase','purchase_'.$row['id']);
    return $row;
}
function public_order(array $row): array { return array_intersect_key($row,array_flip(['id','status','amount','pixCode','expiresAt','token','size','quantity'])); }
require_once __DIR__.'/meta.php';
