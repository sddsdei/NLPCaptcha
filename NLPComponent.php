<?php

class NLPComponent extends Component {

    function NLP_Validate($nlpIdentifier, $nlpAnswer) {

        if ((empty($nlpAnswer) || empty($nlpIdentifier))) {
            return false;
        }

        $validate_key = '027c0508925b8b3da118941f3ff8da1a'; //Your Validate Key

        $validate_url = 'https://call.nlpcaptcha.in/index.php/ad/validate';

        $arr_params = array('ValidateKey' => $validate_key, 'Identifier' => $nlpIdentifier, 'Answer' => $nlpAnswer);

        if (function_exists('curl_init')) {
            $response = $this->curl_post($validate_url, $arr_params);
        } else {
            $response = $this->post_request($validate_url, $arr_params);
        }
        $responseArr = $this->NLP_Split_Response($response);
        if ($responseArr[0] == "success") {
            return true;
        } else {
            //--developer purpose only
            //echo "error: ".$responseArr[1]."<br />";
            //--uncomment to view error message
            return false;
        }
    }

    /**
     * Send a POST requst using cURL 
     * @param string $url to request 
     * @param array $post values to send 
     * @param array $options for cURL 
     * @return string 
     */
    function curl_post($url, array $post = NULL, array $options = array()) {
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 40,
            CURLOPT_POSTFIELDS => http_build_query($post, "", "&")
        );

        $defaults[CURLOPT_TIMEOUT] = 0;
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    /* Functions to Peform Validation Post in Case Curl support is Not Available */

    function post_request($url, $data, $referer = '') {

        // Convert the data array into URL Parameters like a=b&foo=bar etc.
        $data = http_build_query($data);

        // parse the given URL
        $url = parse_url($url);

        if ($url['scheme'] != 'http') {

            $scheme = "ssl://";
            $port = 443;
        } else {
            $port = 80;
        }
        $host = $url['host'];
        // extract host and path:

        $path = $url['path'];

        // open a socket connection on port 80 - timeout: 30 sec
        $fp = fsockopen($scheme . $host, $port, $errno, $errstr, 30);

        if ($fp) {

            // send the request headers:
            fputs($fp, "POST $path HTTP/1.1\r\n");
            fputs($fp, "Host: $host\r\n");

            if ($referer != '')
                fputs($fp, "Referer: $referer\r\n");

            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: " . strlen($data) . "\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $data);

            $result = '';
            while (!feof($fp)) {
                // receive the results of the request
                $result .= fgets($fp, 128);
            }
        } else {
            return false;
        }

        // close the socket connection:
        fclose($fp);

        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);

        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';

        // return as structured array:

        return $content;
    }

    function NLP_Split_Response($response) {
        $spliter = ":@NLP@:"; // Don't change or remove this otherwise application won't work;
        $dataArr = array();
        $dataArr = explode($spliter, $response);
        return $dataArr;
    }

}
