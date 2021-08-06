<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientException ;
use Guzzle\Http\Exception\BadResponseException;
use App\Traits\Credentials;


trait BankVerification {

    use Credentials;

    public function verifyAccount($params) {
        $account_no = $params['account_no'];
        $ifsc_code = $params['ifsc_code'];
        $data = [
            "beneficiary_account_no" => $account_no,
            "beneficiary_ifsc" => $ifsc_code
        ];
        $data_encoded = json_encode($data);

        $client = new Client(['verify' => false]);

        $responseArray = [];

        $auth = base64_encode($this->clientId.':'.$this->clientSecret);
        $requestParams = [
            'headers'       => ['content-type' => 'application/json',
                                'authorization' => 'Basic QUk1V001TkFHMlROTUFYQzlKVlAyQlZQWVRCTEtXUzM6RUhaNDNLVVhGVTJYSlRITThKVFRGWTRXSkM0Q1oxVlY='
                               ],
            'http_errors'   => true,
            'body'          => $data_encoded
        ];

        try {
            $request  = $client->post('https://ext.digio.in:444/client/verify/bank_account',$requestParams);
            $response = $request->getBody();
            $response = json_decode($response, TRUE);
            $responseArray['message'] = 'success';
            $responseArray['data']    = $response;
            return response($responseArray);
        } catch (ClientException  $e) {
            $responseArray['message'] = 'Client Error';
            $responseArray['data']    = $e->getMessage();
            return response($responseArray,500);
        } catch (\Exception $e) {
            $responseArray['message'] = 'Server Error';
            
            $responseArray['data']    = $e->getMessage();
            return response($responseArray, 200);
        }
    }
}


















?>