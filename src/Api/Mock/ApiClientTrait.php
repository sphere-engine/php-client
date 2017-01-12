<?php

namespace SphereEngine\Api\Mock;

use SphereEngine\Api\Model\HttpApiResponse;

trait ApiClientTrait 
{
    private function isAccessTokenCorrect()
	{
		return $this->accessToken == "correctAccessToken";
	}

    /**
     * Returns value under key in associative array
     * @param array $array associative array
     * @param string $key key to be looked in array
     * @param boolean $null_if_not_exists function returns null if value doesn't exists, otherwise exception is thrown
     * @throws \Exception if $null_if_not_exists is false and the value doesn't exist
     * @return mixed
     */
    private function getParam($array, $key, $null_if_not_exists = false)
    {
        if (isset($array[$key])) {
            return $array[$key];
        } else {
            if ($null_if_not_exists) {
                return null;
            } else {
                throw new \Exception('Lack of ' . $key . ' parameter');
            }
        }
    }

    /**
     * Gets json path to data from json path from routing
     * @param   string  $routingJsonPath   path to routing json
     * @throws \Exception on nonexisting JSON file
     * @throws \Exception on nonexisting data in JSON
     * @return string
     */
    private function getDataPath($routingJsonPath)
    {
        if (file_exists('./client-commons/mockRouting.json')) {
            $mockRouting = json_decode(file_get_contents('./client-commons/mockRouting.json'), true);
            $pathArray = explode('/', $routingJsonPath);

            foreach ($pathArray as $p) {
                if (isset($mockRouting[$p])) {
                    $mockRouting = $mockRouting[$p];
                } else {
                    throw new \Exception('There is no ' . $routingJsonPath . ' path in ./client-commons/mockRouting.json file');
                }
            }

            return $mockRouting;
        } else {
            throw new \Exception('There is no ./client-commons/mockRouting.json file. Please pull data from client-commons submodule.');
        }
    }

    /**
     * Gets data from JSON mock file.
     * @param   string  $routingJsonPath   path to routing json
     * @throws \Exception on nonexisting JSON file
     * @throws \Exception on nonexisting data in JSON
     * @return SphereEngine\Api\Model\HttpApiResponse
     */
    private function getMockData($routingJsonPath)
    {
        if (file_exists('./client-commons/mockData.json')) {
            $dataJsonPath = $this->getDataPath($routingJsonPath);
            $mockData = json_decode(file_get_contents('./client-commons/mockData.json'), true);
            $pathArray = explode('/', $dataJsonPath);

            foreach ($pathArray as $p) {
                if (isset($mockData[$p])) {
                    $mockData = $mockData[$p];
                } else {
                    throw new \Exception('There is no ' . $dataJsonPath . ' path in ./client-commons/mockData.json file');
                }
            }

            $httpCode = (isset($mockData['httpCode'])) ? $mockData['httpCode'] : 0;
            $httpBody = (isset($mockData['httpBody'])) ? $mockData['httpBody'] : '';
            $connErrno = (isset($mockData['connErrno'])) ? $mockData['connErrno'] : 0;
            $connError = (isset($mockData['connError'])) ? $mockData['connError'] : '';

            if (is_array($httpBody)) {
                $httpBody = json_encode($httpBody);
            }

            return new HttpApiResponse($httpCode, $httpBody, $connErrno, $connError);
        } else {
            throw new \Exception('There is no ./client-commons/mockData.json file. Please pull data from client-commons submodule.');
        }
    }
}