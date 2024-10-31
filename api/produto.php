<?php
add_action('rest_api_init', function () {
    register_rest_route('v1', '/produto', [
        'methods' => 'POST',
        'callback' => 'product_contact_enviar_contato_rest',
        'permission_callback' => '__return_true',
    ]);
});

function product_contact_enviar_contato_rest($request)
{
    // Obter todos os dados do corpo da requisição
    $body = json_decode($request->get_body(), true);
    
    // Se precisar enviar os dados para o RD Station
    $result = product_contact_enviar_contato_rd_station($body);

    return rest_ensure_response($result);
}

function product_contact_enviar_contato_rd_station($data)
{
    $url = 'https://crm.rdstation.com/api/v1/deals?token=' . RD_STATION_TOKEN;

    // Enviar a requisição
    $response = wp_remote_post($url, [
        'method' => 'POST',
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($data),
    ]);

    // Verificar se houve erro na requisição
    if (is_wp_error($response)) {
        return ['success' => false, 'message' => 'Erro ao conectar com o SAC'];
    }

    // Obter o status e o corpo da resposta
    $status_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    // Retornar o resultado com base no status da resposta
    if ($status_code === 200) {
        return ['success' => true, 'message' => 'Contato enviado com sucesso!'];
    } else {
        return ['success' => false, 'message' => 'Falha ao enviar o contato: ' . $response_body];
    }
}
