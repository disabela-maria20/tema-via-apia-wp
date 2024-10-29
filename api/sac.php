<?php
function enviar_contato_sac_rd_station($email, $telefone, $mensagem, $nome)
{
  $url = 'https://crm.rdstation.com/api/v1/contacts?token=' . RD_STATION_TOKEN;

  $body = json_encode([
    'contact' => [
      'emails' => [
        ['email' => $email]
      ],
      'phones' => [
        [
          'phone' => $telefone,
          'type'  => 'Telefone/Whatsapp'
        ]
      ],
      'title' => $mensagem,
      'name'  => $nome
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
    return ['success' => false, 'message' => 'Erro ao conectar com o SAC'];
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
  register_rest_route('contato_sac/v1', '/enviar-contato', [
    'methods' => 'POST',
    'callback' => 'handle_contato_sac',
    'permission_callback' => '__return_true'
  ]);
});

function handle_contato_sac($request)
{
  $params = $request->get_json_params();

  $email = sanitize_email($params['email']);
  $telefone = sanitize_text_field($params['telefone']);
  $mensagem = sanitize_textarea_field($params['mensagem']);
  $nome = sanitize_text_field($params['nome']);

  return enviar_contato_sac_rd_station($email, $telefone, $mensagem, $nome);
}
