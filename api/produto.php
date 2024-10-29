<?php
function enviar_contato_sac_rd_station($email, $telefone, $mensagem, $nome, $deal_products)
{
  $url = 'https://crm.rdstation.com/api/v1/deals?token=' . RD_STATION_TOKEN;

  $body = json_encode([
    'deal' => [
      'name' => 'Negociação',
    ],
    'contacts' => [
      [
        'emails' => [
          ['email' => $email]
        ],
        'phones' => [
          [
            'phone' => $telefone,
            'type' => 'cellphone'
          ]
        ],
        'name' => $nome,
        'title' => $mensagem
      ]
    ],
    'deal_products' => $deal_products
  ]);

  $response = wp_remote_post($url, [
    'method' => 'POST',
    'headers' => [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json'
    ],
    'body' => $body,
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
  register_rest_route('produto-contato/v1', '/enviar-contato', [
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
  $deal_products = $params['deal_products']; // Obtém os produtos da negociação

  return enviar_contato_sac_rd_station($email, $telefone, $mensagem, $nome, $deal_products);
}
