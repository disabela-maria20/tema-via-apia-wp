<?php

add_action('rest_api_init', function () {
	register_rest_route('v1', '/product-contact', [
		'methods' => 'POST',
		'callback' => 'product_contact_enviar_contato_rest',
		'permission_callback' => '__return_true',
	]);
});

function product_contact_enviar_contato_rest($request)
{
	$email = sanitize_email($request->get_param('email'));
	$telefone = sanitize_text_field($request->get_param('telefone'));
	$mensagem = sanitize_text_field($request->get_param('mensagem'));
	$nome = sanitize_text_field($request->get_param('nome'));

	if (empty($email) || !is_email($email)) {
		return new WP_Error('no_email', 'E-mail inválido', ['status' => 400]);
	}

	if (empty($telefone) || !preg_match('/^\+?[0-9]{10,15}$/', $telefone)) {
		return new WP_Error('invalid_phone', 'Número de telefone inválido', ['status' => 400]);
	}

	$deal_products = [
		[
			'amount' => 2,
			'base_price' => 200,
			'description' => 'Tit Produto 1',
			'name' => 'Produto 1',
			'price' => 2000,
			'total' => 400
		]
	];

	$result = product_contact_enviar_contato_rd_station($email, $telefone, $mensagem, $nome, $deal_products);

	return rest_ensure_response($result);
}

function product_contact_enviar_contato_rd_station($email, $telefone, $mensagem, $nome, $deal_products)
{
	$url = 'https://crm.rdstation.com/api/v1/deals?token=66b0d0105e54ad0022d2ba27';

	$body = json_encode([
		'deal' => [
			'name' => 'Teste Site JS'
		],
		'contacts' => [
			[
				'birthday' => [
					'day' => 11,
					'month' => 9,
					'year' => 1979
				],
				'emails' => [
					['email' => $email]
				],
				'phones' => [
					[
						'phone' => $telefone,
						'type' => 'celular'
					]
				],
				'name' => $nome,
				'title' => $mensagem
			]
		],
		'deal_products' => $deal_products
	]);

	// Enviar a requisição
	$response = wp_remote_post($url, [
		'method' => 'POST',
		'headers' => [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
		],
		'body' => $body,
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
