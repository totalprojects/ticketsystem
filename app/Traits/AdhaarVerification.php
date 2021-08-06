<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Guzzle\Http\Exception\ClientException ;
use Guzzle\Http\Exception\BadResponseException;
use App\Traits\Credentials;
use App\Traits\ProcessImage;
use Aadhaar;


trait AdhaarVerification {

    use Credentials,ProcessImage;

    public function verifyAdhaar(Request $request) {

        $pan_front = $request->file('pan_front');

        //return $pan_front;
        $pan_back = !empty($request->file('pan_back')) ? $request->file('pan_back') : '';

        $data = [
            "front_part" => $pan_front,
            "back_part"  =>  $pan_back,
            "unique_request_id" => (mt_rand()*time()/mt_rand()),
            "should_verify" => true
        ];
        $data_encoded = json_encode($data);

        $client = new Client(['verify' => false]);
        $destinationPath = $this->processImage($pan_front);
        $responseArray = [];
       // return $pan_front->getClientOriginalName();
        $auth = base64_encode($this->clientId.':'.$this->clientSecret);
        $requestParams = [
            'headers'       => [
                                'authorization' => 'Basic QUk1V001TkFHMlROTUFYQzlKVlAyQlZQWVRCTEtXUzM6RUhaNDNLVVhGVTJYSlRITThKVFRGWTRXSkM0Q1oxVlY='
                               ],
            'multipart' => [
                                [
                                    'Content-type' => 'multipart/form-data',
                                    'name'     => 'front_part',
                                    'contents' => Psr7\Utils::tryFopen($destinationPath, 'r'),
                                    'filename' => $pan_front->getClientOriginalName()
                                ],
            ],
            'http_errors'   => true,
            'body' => $data_encoded
        ];

        try {
            $request  = $client->post('https://ext.digio.in:444/v3/client/kyc/analyze/file/idcard',$requestParams);
            $response = $request->getBody();
            $response = json_decode($response, TRUE);
            $verification_id = isset($response['id_no']) ? $response['id_no'] : false;
            $msg = 'success';
            if ($verification_id) {
                $panDup = Aadhaar::where('verification_id', $verification_id)->get();
                $http_cond = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
                $port = $_SERVER['SERVER_PORT'];
                if(empty($port)) {
                    $serverName = $_SERVER['SERVER_NAME'];
                    $basePath = $http_cond.'://'.$serverName;
                } else {
                    $serverName = $_SERVER['SERVER_NAME'];
                    $basePath = $http_cond.'://'.$serverName.':'.$port;
                }
                
                $destinationPath = str_replace("/var/www/html",$basePath,$destinationPath);
                if($panDup->Count()==0) {
                    
                    $dataSet = [
                        'crm_id' => 0,
                        'verification_id'   => $verification_id,
                        'customer_name'     => $response['name'] ?? 'NULL',
                        'dob'               => !empty($response['dob']) ? $response['dob'] : 'NULL',
                        'image'             => $destinationPath,
                        'fathers_name'      => $response['fathers_name'] ?? 'NULL',
                        'address'           => $response['address'] ?? 'NULL',
                        'gender'             => $response['gender'] ?? 'NULL',
                        'status'            => 1,
                        'created_at'        => date('Y-m-d H:i:s'),
                        'updated_at'        => date('Y-m-d H:i:s')
                    ];

                    Aadhaar::insert($dataSet);
                    
                } else {

                    $removeold = file_exists($panDup[0]->image) ? unlink($panDup[0]->image) : false;
                    Aadhaar::where('verification_id',$verification_id)->update(['image'=>$destinationPath,'updated_at'=>date('Y-m-d H:i:s')]);

                }
            } else {
                $msg = 'failed';
            }

            $responseArray['message'] = $msg;
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