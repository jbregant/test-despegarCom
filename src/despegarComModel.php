<?php

include 'db.php';

class DespegarComModel
{
    /**
     * @return array
     */
    public function getReservationsTable()
    {
        $reservations = [];
        $conn = DB::connect();
        $query = "SELECT a.id, a.arrival_date, a.reservation_expire_date, a.departure_date, a.arrival_date,
                 b.name as departure_city, b.state as departure_state, b.country as departure_country,
                 c.name as arrival_city, c.state as arrival_state, c.country as arrival_country, d.name as airline from reservations as a
                      INNER JOIN cities as b ON a.departure_city_id = b.id
                      INNER JOIN cities as c ON a.arrival_city_id = c.id
                      INNER JOIN airlines as d ON a.airline_id = d.id";

        foreach ($conn->query($query) as $row) {
            $departureCity = utf8_encode($row['departure_city']) . ', ' . $row['departure_state'] . ', ' . $row['departure_country'];
            $arrivalCity = utf8_encode($row['arrival_city']) . ', ' . $row['arrival_state'] . ', ' . $row['arrival_country'];

            $reservations['data'][] = [
                $departureCity,
                utf8_encode($row['departure_date']),
                $arrivalCity,
                utf8_encode($row['arrival_date']),
                utf8_encode($row['airline']),
                utf8_encode($row['reservation_expire_date']),
            ];
        }

        DB::disconnect();

        return $reservations;
    }

    /**
     * @return array
     */
    public function getCities()
    {
        $cities = [];
        $conn = DB::connect();
        $query = "SELECT * from cities";

        foreach ($conn->query($query) as $row) {
            $cities['data'][] = [
                'id' => utf8_encode($row['id']),
                'city' => utf8_encode($row['name']),
                'state' => utf8_encode($row['state']),
                'country' => utf8_encode($row['country']),
            ];
        }

        DB::disconnect();

        return $cities;
    }

    /**
     * @return array
     */
    public function getAirlines()
    {
        $airlines = [];
        $conn = DB::connect();
        $query = "SELECT * from airlines";

        foreach ($conn->query($query) as $row) {
            $airlines['data'][] = [
                'id' => utf8_encode($row['id']),
                'name' => utf8_encode($row['name']),
            ];
        }

        DB::disconnect();

        return $airlines;
    }

    /**
     * @return boolean
     */
    public function setReservation($data)
    {
        $conn = DB::connect();

        $query = $conn->prepare('INSERT INTO reservations (departure_city_id, departure_date, arrival_city_id, arrival_date, airline_id, reservation_expire_date)
                  VALUES (:departure_city_id, :departure_date, :arrival_city_id, :arrival_date, :airline_id, :reservation_expire_date)');

        if($query->execute($data)){
            DB::disconnect();
            return true;
        } else {
            DB::disconnect();
            return false;
        }
    }
}