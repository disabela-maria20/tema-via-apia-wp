<?php

// Endpoint customizado para inscrição no SAC com o RD Station
add_action('rest_api_init', function () {
  register_rest_route('sac/v1', '/subscribe', [
    'methods' => 'POST',
    'callback' => 'sac_enviar_contato_rest', // Nome da função alterado
    'permission_callback' => '__return_true',
  ]);
});

function sac_enviar_contato_rd_station($email, $name, $phone, $title) 
{
  $url = 'https://crm.rdstation.com/api/v1/contacts?token=' . RD_STATION_TOKEN;

  $body = json_encode([
    'contact' => [
      'emails' => [
        ['email' => $email],
      ],
      'name' => $name,
      'mobile_phone' => $phone,
      // 'phones' => [
      //   ["phone" => $phone, "type" => "whatsapp"] // Corrigido para "whatsapp"
      // ], 
      'title' => $title
    ]
  ]);

  $response = wp_remote_post($url, [
    'method'    => 'POST',
    'headers'   => [
      'Content-Type' => 'application/json',
      'Accept'       => 'application/json'
    ],
    'body'      => $body,
  ]);

  if (is_wp_error($response)) {
    return ['success' => false, 'message' => 'Erro ao conectar com o RD Station'];
  }

  $status_code = wp_remote_retrieve_response_code($response);
  $response_body = wp_remote_retrieve_body($response);

  if ($status_code === 200) {
    return ['success' => true, 'message' => 'Contato enviado com sucesso!'];
  } else {
    return ['success' => false, 'message' => 'Falha ao enviar o contato: ' . $response_body];
  }
}

function sac_enviar_contato_rest($request) 
{
  $email = sanitize_email($request->get_param('email'));
  $name = sanitize_text_field($request->get_param('name'));
  $phone = sanitize_text_field($request->get_param('mobile_phone'));
  $title = sanitize_text_field($request->get_param('title'));
  if (empty($email)) {
    return new WP_Error('no_email', 'E-mail é obrigatório', ['status' => 400]);
  }

  $result = sac_enviar_contato_rd_station($email, $name, $phone, $title); 

  return rest_ensure_response($result);
}
