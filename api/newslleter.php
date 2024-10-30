<?php

// Endpoint customizado para inscrição na newsletter com o RD Station
add_action('rest_api_init', function () {
  register_rest_route('newsletter/v1', '/subscribe', [
    'methods' => 'POST',
    'callback' => 'newsletter_subscribe',
    'permission_callback' => '__return_true'
  ]);
});

function enviar_contato_rd_station($email)
{
  $url = 'https://crm.rdstation.com/api/v1/contacts?token=' . RD_STATION_TOKEN;
  $body = json_encode([
    'contact' => [
      'emails' => [
        ['email' => $email],
      ],
      'name' => 'newsletter'
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
add_action('rest_api_init', function () {
  register_rest_route('api/v1', '/enviar-contato', [
    'methods' => 'POST',
    'callback' => 'enviar_contato_rest',
    'permission_callback' => '__return_true',
  ]);
});

function enviar_contato_rest($request)
{
  $email = sanitize_email($request->get_param('email'));

  if (empty($email)) {
    return new WP_Error('no_email', 'E-mail é obrigatório', ['status' => 400]);
  }

  $result = enviar_contato_rd_station($email);

  return rest_ensure_response($result);
}

