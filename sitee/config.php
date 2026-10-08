<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }
return [
    'api_key' => 'sk_kJLsZV4cFIqsUw18jqm6iQS5g0CmzFkFEaUpac9mBOwW8MT1hhrTuf4i',
    'api_base' => 'https://api.invictuspayv2.com.br/api/v1',
    // Opcional: URL da pasta onde o site foi instalado, sempre com https://.
    'site_url' => '',
    'pix_expiration_seconds' => 1200,
    // Pixel e API de Conversões da Meta, somente no servidor.
    'facebook_pixel_id' => '1056976433990223',
    'facebook_capi_token' => 'EAATqO03bU58BSpLhcUlqALtAuZBS7bcNetwyMJxqtVxlqsXgyakCHD15uZBs48vIJwvXEbuhmMsZCaEVZCMGP2rZAEvXThpfkZBKVF4UMptUmOQFOWGmX4zJH3wAZCkn4vXRmcZAqU1nVaLwcWLmRraMDfBaUt5IjQn2c4nAkiZBnK6NE4aIKoFT8EqzHmAKdVUYlbwZDZD',
    'facebook_graph_base' => 'https://graph.facebook.com',
    'facebook_graph_version' => 'v26.0',
    // Opcional: código da aba Testar Eventos. Deixe vazio em produção.
    'facebook_test_event_code' => '',
    'prices' => ['30cm' => [1 => 4790, 2 => 8790], '100cm' => [1 => 9790, 2 => 14990]],
];
