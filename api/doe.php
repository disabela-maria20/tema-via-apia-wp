<?php 

add_action('rest_api_init', function () {
  register_rest_route('api/v1', '/send-email', array(
      'methods' => 'POST',
      'callback' => 'send_custom_email',
  ));
});


function send_custom_email(WP_REST_Request $request) {
  var_dump( $request);
  $email = sanitize_email($request->get_param('email'));
  $message = sanitize_textarea_field($request->get_param('mensagem'));
  
  if (empty($email) || empty($message)) {
      return new WP_REST_Response(['error' => 'Email e mensagem são obrigatórios.'], 400);
  }
  
  $subject = 'Contato de Doação';
  $headers = ['Content-Type: text/html; charset=UTF-8'];
  
  $sent = wp_mail($email, $subject, nl2br($message), $headers);
  
  if ($sent) {
      return new WP_REST_Response(['success' => 'Email enviado com sucesso.'], 200);
  } else {
      return new WP_REST_Response(['error' => 'Erro ao enviar email.'], 500);
  }
}