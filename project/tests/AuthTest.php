<?php

namespace Api;

use Mocks\Request as Request;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    
    public function testRegisterToken()
    {
        $auth_data = [
            'client_id' => 'client_test_id',
            'email' => 'test_email@email.com',
            'name' => 'Test name',
            'url' => 'endpoint_test_url'
        ];
        $data_to_return = ['data' => ['sl_token' => 'test_sl_token']];
        $request = new Request();
        $request->setDataToReturn($data_to_return);
    
        $auth = new Auth($request, $auth_data);
        $auth->registerToken();
        
        $this->assertEquals($data_to_return['data']['sl_token'], $auth->getToken());
    }
}
