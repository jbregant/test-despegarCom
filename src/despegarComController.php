<?php

include 'despegarComModel.php';

class despegarComController
{
    protected $httpResponseCode = 200;
    protected $response = [];

    function __construct($request)
    {
        try{
            switch($_SERVER['REQUEST_METHOD']){
                case 'GET':
                    if (isset($request['action'])){
                        switch ($request['action']){
                            case 'getTable':
                                $this->getReservationsTable();
                                break;
                            case 'getCities':
                                $this->getCities();
                                break;
                            case 'getAirlines':
                                $this->getAirlines();
                                break;
                            default:
                                $this->jsonResponse(404, 'Action not found');
                                break;
                        };
                    }
                    break;
                case 'POST':
                    $postData = json_decode(file_get_contents('php://input'), true);
                    if (isset($postData['action'])) {
                        switch ($postData['action']){
                            case 'addReservation':
                                $this->setReservation($postData);
                                break;
                            default:
                                $this->jsonResponse(404, 'Action not found');
                                break;
                        }
                    }
                    break;
                default:
                    $this->jsonResponse(405, 'Method not allowed');
                    break;
            }
        }catch (Exception $e){
            $this->jsonResponse(500, $e->getMessage());
        }
    }

    /**
     * set response variable with reservations data
     */
    private function getReservationsTable()
    {
        $repo = new DespegarComModel();

        $this->response = $repo->getReservationsTable();

        $this->jsonResponse();
    }

    /**
     * set response variable with cities data
     */
    private function getCities()
    {
        $repo = new DespegarComModel();

        $this->response = $repo->getCities();

        $this->jsonResponse(200);
    }

    /**
     * set response variable with airlines data
     */
    private function getAirlines()
    {
        $repo = new DespegarComModel();

        $this->response = $repo->getAirlines();

        $this->jsonResponse(200);

    }


    /**
     * set a reservation
     * @param $data
     * @throws Exception
     */
    private function setReservation($data): void
    {
        $repo = new DespegarComModel();

        // params validation
        if(!$this->reservationParamsValidations($data)){
            $this->jsonResponse(400, 'Missing/empty required parameter');
        } else {
            switch ($data['airlineId']){
                case '1': //Aerolineas Argentinas
                    $expireInDays = 7;
                    $this->generateReservation($data, $repo, $expireInDays);
                    break;
                case '2': //American Airlines
                    $expireInDays = 7;
                    $this->generateReservation($data, $repo, $expireInDays, true);
                    break;
                case '3': //Sol
                    $this->generateReservation($data, $repo, 0, null, true, 25);
                    break;
                case '4': //Turkish Airlines
                  $this->generateReservation($data, $repo, 0, null, true, 25);
                    break;
                case '5': //Qatar Airlines
                    $expireInDays = 2;
                    $this->generateReservation($data, $repo, $expireInDays, true);
                    break;
                case '6': //Air Europa
                    $this->generateReservation($data, $repo, 0, null, true, 25);
                    break;
                case '7': //Aeroméxico
                    $expireInDays = 5;
                    $this->generateReservation($data, $repo, $expireInDays, true);
                    break;
                case '8': //Latam
                    $expireInDays = 2;
                    $this->generateReservation($data, $repo, $expireInDays);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * parameters validation
     * @param $data
     * @return bool
     */
    private function reservationParamsValidations($data)
    {
        $requiredParams = ["departureCityId", "arrivalCityId", "departureDate", "airlineId"];

        foreach ($requiredParams as $requiredParam) {
            if(!isset($data["$requiredParam"]) || !array_key_exists($requiredParam, $data) || empty($requiredParam)){
                return false;
            }
        }
        return true;
    }


    /**
     *  json response
     * @param null $code
     * @param null $message
     */
    private function jsonResponse($code = null, $message = null): void
    {
        if ($code){
            $this->httpResponseCode = $code;
            if(empty($this->response)){
                $this->response['status'] = $code;
                $this->response['message'] = ($message) ? $message : '';
            }
        }

        header('Content-Type: application/json', '', $this->httpResponseCode);
        echo json_encode($this->response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * @param $data
     * @param int $expireInDays
     * @param null $businessDays
     * @param null $lastBussinessDay
     * @param null $maxMonthDay
     * @return string
     * @throws Exception
     */
    private function calculateReservationExpirationDate($data,int $expireInDays = 0, $businessDays = null, $lastBussinessDay = null, $maxMonthDay = null): string
    {

        $departureDate = new DateTime($data['departureDate']);
        $departureDateAux = new DateTime($data['departureDate']);

        if($lastBussinessDay){
            $expirationDate = $this->lastBusinessDayOfAMonth($departureDate, $maxMonthDay);
            $departureDate = new DateTime($expirationDate->format('Y-m-d'));
        } else {
            $i = 0;

            while ($i < $expireInDays) {

                $futureWeekDay = intval($departureDateAux->add(new DateInterval('P1D'))->format('N'));

                if($businessDays){
                    if ($futureWeekDay == 6 || $futureWeekDay == 7) {
                        continue;
                    }
                }

                $departureDate->setDate(intval($departureDateAux->format('Y')), intval($departureDateAux->format('m')), intval($departureDateAux->format('d')));
                $i++;
            }
        }

        return $departureDate->format('Y-m-d');
    }

    /**
     * @param $data
     * @param int $expireInDays
     * @param DespegarComModel $repo
     * @param null $businessDays
     * @param null $lastBusinessDays
     * @param null $maxMonthDay
     * @throws Exception
     */
    private function generateReservation($data, DespegarComModel $repo, int $expireInDays  = 0, $businessDays = null, $lastBusinessDays = null, $maxMonthDay = null): void
    {
        $expirationDate = $this->calculateReservationExpirationDate($data, $expireInDays, $businessDays, $lastBusinessDays, $maxMonthDay);
        $departureDateObj = new DateTime($data['departureDate']);
        $arrivalDateObj = new DateTime($data['departureDate']);
        $departureDate = $departureDateObj->format('Y-m-d g:i:s');
        $arrivalDate = $arrivalDateObj->add(new DateInterval('PT3H'))->format('Y-m-d g:i:s');

        $reservationData = [
            'departure_city_id' => $data['departureCityId'],
            'departure_date' => $departureDate,
            'arrival_city_id' => $data['arrivalCityId'],
            'arrival_date' => $arrivalDate,
            'airline_id' => $data['airlineId'],
            'reservation_expire_date' => $expirationDate
        ];

        //save data to db
        $reservation = $repo->setReservation($reservationData);

        //set response
        if ($reservation){
            $this->jsonResponse(200, 'OK');
        } else {
            $this->jsonResponse(500, 'Internal error');
        }
    }

    /**
     * set datetime object with calculated last business day of the month with customs
     * @param DateTime $date
     * @param null $maxMonthDay
     * @return datetime
     * @throws Exception
     */
    private function lastBusinessDayOfAMonth(DateTime $date, $maxMonthDay = null): datetime
    {
        $originalDate = clone $date;
        $date->modify('last day of this month');
        $date->format('Y-m-d');
        $dayOfWeek = intval($date->format('N'));
        if ($dayOfWeek == 6 || $dayOfWeek == 7) {
            while ($dayOfWeek > 5) {
                $date->sub(new DateInterval('P1D'));
            }
        }

        if($maxMonthDay){
            if(intval($originalDate->format('j')) > $maxMonthDay){
                // here we could call the lastBusinessDayOfAMonth () method recursively,
                // passing $ maxMonthDay as the second argument if we assume that the rule applied to the current month applies to the next
                $date = $this->lastBusinessDayOfAMonth($originalDate->add(new DateInterval('P1M')));
            }
        }

        return $date;
    }
}

$a = new despegarComController($_REQUEST);

