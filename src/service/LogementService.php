<?php

namespace App\service;

use App\Repository\LogementRepository;
use App\Entity\Logement;

class LogementService
{
    private LogementRepository $logementRepository;

    public function __construct(LogementRepository $logementRepository)
    {
        $this->logementRepository = $logementRepository;
    }

    public function getAllLogement()
    {
        $formedData = [];
        $datas = $this->logementRepository->findAll();
        foreach($datas as $data)
        {
            $formedData[] = $data->getInformation();
            

        }
        return $formedData;
    }

}
 