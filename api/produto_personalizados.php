<?php
add_action('rest_api_init', function () {
    register_rest_route('v1', '/produto', array(
        'methods' => 'POST',
        'callback' => 'handle_produto_submission',
    ));
  });
  
function handle_produto_submission(WP_REST_Request $request) {
    $data = $request->get_json_params();
    
    $to = 'isabela10014@hotmail.com';
    $subject = 'Novo contato do formulário de personalizadas';
    $message = "Novo contato:\n\n";
    $message .= "Nome/Empresa: " . sanitize_text_field($data['deal']['name']) . "\n";
    $message .= "CPF/CNPJ: " . sanitize_text_field($data['contacts'][0]['title']) . "\n";
    $message .= "Email: " . sanitize_email($data['contacts'][0]['emails'][0]['email']) . "\n";
    $message .= "WhatsApp: " . sanitize_text_field($data['contacts'][0]['phones'][0]['phone']) . "\n";
    $message .= "Quantidade: " . floatval($data['deal_products'][0]['total']) . "\n";
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    wp_mail($to, $subject, $message, $headers);
  
    return new WP_REST_Response(array('success' => true), 200);
}