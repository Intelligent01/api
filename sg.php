<?php
require_once './vendor/autoload.php';
function send_verification_main(){
    $json_config = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../env.json");
    $config = json_decode($json_config,true);


    // Configure API key authorization: api-key
    $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $config['email_api_key']);

    // Uncomment below line to configure authorization using: partner-key
    // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', $config['email_api_key']);

    $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
        // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        // This is optional, `GuzzleHttp\Client` will be used as default.
        new GuzzleHttp\Client(),
        $config
    );
    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
    $sendSmtpEmail['to'] = array(array('email'=>'poornachandran24680@gmail.com', 'name'=>'Poornachandran C K'));
    $sendSmtpEmail['templateId'] = 2;
    // $sendSmtpEmail['sender'] =  ['name' => 'Brevo', 'email' => '7c150c001@smtp-brevo.com'];
    // $sendSmtpEmail['subject'] = "welcome !";
    // $sendSmtpEmail['htmlContent'] = '<html><body><h1>This is a transactional email {{params.name}}</h1></body></html>';
    $sendSmtpEmail['params'] = array('name'=>'John', 'surname'=>'Doe');
    $sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'custom_header_1:custom_value_1|custom_header_2:custom_value_2');

    try {
        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        print_r($result);
    } catch (Exception $e) {
        echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
    }
}

send_verification_main();