<?php
    class ShipStation {
        public function getNewOrders() {
            $url = "https://ssapi.shipstation.com/orders?orderStatus=awaiting_shipment";
            $requestType = "GET";
            $response = $this->curlRequest($url, $requestType);
            return json_decode($response, true);
        }

        public function searchForOrder($orderNumber) {
            $url = "https://ssapi.shipstation.com/orders?orderNumber=$orderNumber";
            $requestType = "GET";
            $response = $this->curlRequest($url, $requestType);
            return json_decode($response, true);
        }

        public function getLabel($json, $orderNumber) {
            $url = "https://ssapi.shipstation.com/orders/createlabelfororder";
            $requestType = "POST";
            $options = array(CURLOPT_POSTFIELDS => json_encode($json));
            $response = $this->curlRequest($url, $requestType, $options);
            $pdf = json_decode($response, true);

            if (!isset($pdf['labelData'])) return false;

            $bin = base64_decode($pdf['labelData'], true);

            if (strpos($bin, '%PDF') !== 0) return false;

            $filename = "pdf/$orderNumber.pdf"; 

            file_put_contents($filename, $bin);

            return $filename;
        }

        public function updateOrder($json) {
            $url = "https://ssapi.shipstation.com/orders/createorder";
            $requestType = "POST";
            $options = array(CURLOPT_POSTFIELDS => json_encode($json));
            $response = $this->curlRequest($url, $requestType, $options);
            return json_decode($response, true);
        }

        private function curlRequest($url, $requestType, $options = array()) {
            global $credentials;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $requestType,
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Authorization: Basic $credentials"
                ),
            ) + (array) $options);
            $response = curl_exec($curl);

//            $info = curl_getinfo($curl);
//            $txt = "#########CURL INFO###########\n";
//            $txt .= print_r($info, true);
//            $txt .= "############END TRANSMISSION########\n\n";
//            $file = fopen("responseTimes.txt", "a");
//            fwrite($file, $txt);
//            fclose($file);

            curl_close($curl);
            return $response;
        }
    }