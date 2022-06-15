<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class DeliveryController extends Controller
{
    public function index(): Renderable
    {
        $from = $_GET['from'] ?? '';
        $to = $_GET['to'] ?? '';
        $price = $_GET['price'] ?? '';
        $distance = 0;
        $priceTotal = 0;
        if (!empty($price) and !empty($to) and !empty($price)) {
            $distance = $this->getDistance($from, $to);
            $priceTotal = $this->countPrice($distance, $price);
        }
        return view('delivery', [
            'distance' => $distance,
            'priceTotal' => $priceTotal
        ]);
    }

    private function getDistance($addressFrom, $addressTo, $unit = ''): string
    {
        $apiKey = 'AIzaSyDYw7ZwobNRqIwngXjcMQWRILXdCaOvZhc';

        $formattedAddrFrom = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo = str_replace(' ', '+', $addressTo);

        $geocodeFrom = file_get_contents(
            'https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddrFrom . '&sensor=false&key=' . $apiKey
        );
        $outputFrom = json_decode($geocodeFrom);
        if (!empty($outputFrom->error_message)) {
            return $outputFrom->error_message;
        }

        $geocodeTo = file_get_contents(
            'https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddrTo . '&sensor=false&key=' . $apiKey
        );
        $outputTo = json_decode($geocodeTo);
        if (!empty($outputTo->error_message)) {
            return $outputTo->error_message;
        }

        $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
        $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
        $latitudeTo = $outputTo->results[0]->geometry->location->lat;
        $longitudeTo = $outputTo->results[0]->geometry->location->lng;

        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) + cos(deg2rad($latitudeFrom)) * cos(
                deg2rad($latitudeTo)
            ) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        $unit = strtoupper($unit);
        if ($unit == "K") {
            return round($miles * 1.609344, 2) . ' km';
        } elseif ($unit == "M") {
            return round($miles * 1609.344, 2) . ' meters';
        } else {
            return round($miles, 2) . ' miles';
        }
    }

    private function countPrice($distance, $price)
    {
        $totalPrice = 0;
        if ($distance <= 10) {
            $totalPrice = $this->getI($price, $totalPrice);
        } else {
            if ($distance >= 11 and $distance <= 20) {
                $totalPrice = $this->getI($price, $totalPrice);
                $totalPrice += 50;
            } else {
                if ($distance >= 21 and $distance <= 50) {
                    $totalPrice = $this->getI($price, $totalPrice);
                    $totalPrice += 100;
                } else {
                    if ($distance >= 51 and $distance <= 70) {
                        $totalPrice = $this->getI($price, $totalPrice);
                        $totalPrice += 150;
                    } else {
                        if ($distance >= 71 and $distance <= 100) {
                            $totalPrice = $this->getI($price, $totalPrice);
                            $totalPrice += 200;
                        } else {
                            $totalPrice = 'We delivery only up to 100 miles';
                        }
                    }
                }
            }
        }

        return $totalPrice;
    }

    private function getI($price, int $totalPrice): int
    {
        if ($price <= 1000) {
            $totalPrice = 120;
        }
        if ($price > 1001) {
            $totalPrice = 170;
        }
        if ($price > 1500) {
            $totalPrice = 230;
        }
        if ($price > 2000) {
            $totalPrice = 280;
        }
        if ($price > 2500) {
            $totalPrice = 320;
        }
        if ($price > 3000) {
            $totalPrice = 370;
        }

        return $totalPrice;
    }
}
