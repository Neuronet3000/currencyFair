<?php
namespace Neuronet\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brainly\JValidator\Validator as JSchemaValidator;
use Neuronet\ApiBundle\DataQueue\DataQueueAbstract;
use Neuronet\ApiBundle\DataQueue\ApiInputDataMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class InputDataController extends Controller
{
    protected $validator;
    protected $dataQueue;
    protected $schema = '{
        "tile": "input data schema",
        "type": "object",
        "properties": {
            "userId": {
                "type": "string",
                "required": true
            },
            "currencyFrom": {
                "type": "string",
                "required": true
            },
            "currencyTo": {
                "type": "string",
                "required": true
            },
            "amountSell": {
                "type": "number",
                "required": true
            },
            "amountBuy": {
                "type": "number",
                "required": true
            },
            "rate": {
                "type": "number",
                "required": true
            },
            "timePlaced": {
                "type": "string",
                "required": true
            },
            "originatingCountry": {
                "type": "string",
                "required": true
            }
        }
    }';
    
    public function __construct(
            JSchemaValidator $validator,
            DataQueueAbstract $dataQueue
            )
    {
        $this->validator = $validator;
        $this->dataQueue = $dataQueue;
    }
    
    public function inputDataAction(Request $request)
    {
        $data = $request->request->get('data');
        
        $this->validator->validate($data, $this->schema);
        $verificationResult = $this->validator->getResultCode();
        
        $responseData = ['result'=>'', 'errors'=>[]];
        $responseCode = 404;
        if ($verificationResult == 0) {
            $messg = new ApiInputDataMessage();
            $messg->initFromArray(json_decode($data, true));
            $this->dataQueue->putMessageToQueue($messg);
            $responseData['result'] = 'OK';
            $responseCode = 200;
        } else {
            $verificationErrors = $this->validator->getValidationErrors();
            $responseData['result'] = 'ERROR';
            $responseData['errors'] = $verificationErrors;
            $responseCode = 400;
        }
        
        return new JsonResponse($responseData, $responseCode);
    }
}
