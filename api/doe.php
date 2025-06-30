<?php
// Adicionar ação AJAX para enviar doação
add_action('wp_ajax_enviar_doacao_cesta', 'enviar_doacao_cesta');
add_action('wp_ajax_nopriv_enviar_doacao_cesta', 'enviar_doacao_cesta');

function enviar_doacao_cesta() {
    // Verificar nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'doacao_cesta_nonce')) {
        wp_send_json_error('Requisição inválida.');
    }

    // Sanitizar dados
    $dados = array(
        'nome' => sanitize_text_field($_POST['nome']),
        'cpfCnpj' => sanitize_text_field($_POST['cpfCnpj']),
        'whatsapp' => sanitize_text_field($_POST['whatsapp']),
        'email' => sanitize_email($_POST['email']),
        'quantidade' => intval($_POST['quantidade']),
        'conheceu' => sanitize_text_field($_POST['conheceu']),
        'titulo_cesta' => sanitize_text_field($_POST['titulo_cesta']),
        'valor_cesta' => sanitize_text_field($_POST['valor_cesta']),
        'total' => sanitize_text_field($_POST['total'])
    );

    // Get the vendor email from the form or use admin email as fallback
    $email_vendedor = !empty($_POST['e_mail_do_vendedor']) ? 
        sanitize_email($_POST['e_mail_do_vendedor']) : 
        get_option('admin_email');
    
    $assunto = 'Nova doação de cesta básica: ' . $dados['titulo_cesta'];
    
    $mensagem = "
        <h2>Nova doação de cesta básica</h2>
        <p><strong>Nome/Empresa:</strong> {$dados['nome']}</p>
        <p><strong>CPF/CNPJ:</strong> {$dados['cpfCnpj']}</p>
        <p><strong>WhatsApp:</strong> {$dados['whatsapp']}</p>
        <p><strong>E-mail:</strong> {$dados['email']}</p>
        <p><strong>Conheceu a ONG por:</strong> {$dados['conheceu']}</p>
        <p><strong>Cesta:</strong> {$dados['titulo_cesta']}</p>
        <p><strong>Quantidade:</strong> {$dados['quantidade']}</p>
        <p><strong>Valor unitário:</strong> {$dados['valor_cesta']}</p>
        <p><strong>Total:</strong> {$dados['total']}</p>
    ";

    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    $enviado = wp_mail($email_vendedor, $assunto, $mensagem, $headers);

    if (!is_email($email_vendedor)) {
        wp_send_json_error('Endereço de e-mail do vendedor inválido.');
    }
    
        if (!is_email($email_vendedor)) {
            wp_send_json_error('Endereço de e-mail do vendedor inválido.');
        }
    if ($enviado) {
        wp_send_json_success('Formulário enviado com sucesso!');
    } else {
        wp_send_json_error('Erro ao enviar e-mail.');
    }
}