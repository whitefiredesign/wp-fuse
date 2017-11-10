<?php namespace Fuse; ?>
<?php


class Auth0_Helper  {

    /**
     * Check the Auth0 API connection
     * @param $domain
     * @param $client_id
     * @param $client_secret
     * @return array
     */
    public static function get_token($domain, $client_id, $client_secret) {
        $output = array(
            'error'     => false,
            'message'   => 'Connected to API successfully'
        );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => add_http($domain, true) . "/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"client_id\":\"" . $client_id . "\",\"client_secret\":\"" . $client_secret . "\",\"audience\":\"" . add_http($domain, true) . "/api/v2/\",\"grant_type\":\"client_credentials\"}",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));

        $response       = json_decode(curl_exec($curl));
        $output['data'] = $response;
        $err            = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $output['error']    = true;
            $output['message']  = $err;
        }

        if(isset($response->error)) {
            $output['error']   = true;
            $output['message'] = $response->error_description . ' - check your Client ID and Client Secret';
        }
        
        return $output;
    }

    /**
     * Returns the Auth0 callback URL
     * @return string
     */
    public static function get_callback_url() {
        return get_site_url() . '/?processing-auth0=true';
    }
}