<?php

namespace App\service;

use App\Repository\ReservationRepository;
use App\Entity\Reservation;

class ReservationService
{
    private ReservationRepository $ReservationRepository;

    public function __construct(ReservationRepository $ReservationRepository)
    {
        $this->ReservationRepository = $ReservationRepository;
    }

    public function getAllReservation()
    {
        $formedData = [];
        $datas = $this->ReservationRepository->findAll();
        foreach($datas as $data)
        {
            $formedData[] = $data->getInformation();
            

        }
        return $formedData;
    }

}
 