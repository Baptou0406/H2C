<?php

namespace App\service;

use App\Repository\ConsommableRepository;
use App\Entity\Consommable;

class ConsommableService
{
    private ConsommableRepository $consommableRepository;

    public function __construct(ConsommableRepository $consommableRepository)
    {
        $this->consommableRepository = $consommableRepository;
    }

    public function getAllConsommable()
    {
        $formedData = [];
        $datas = $this->consommableRepository->findAll();
        foreach($datas as $data)
        {
            $formedData[] = $data->getInformation();
            

        }
        return $formedData;
    }

}
 