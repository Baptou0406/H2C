<?php

namespace App\service;

use App\Repository\InterventionRepository;
use App\Entity\Intervention;

class InterventionService
{
    private InterventionRepository $InterventionRepository;

    public function __construct(InterventionRepository $InterventionRepository)
    {
        $this->InterventionRepository = $InterventionRepository;
    }

    public function getAllIntervention()
    {
        $formedData = [];
        $datas = $this->InterventionRepository->findAll();
        foreach($datas as $data)
        {
            $formedData[] = $data->getInformation();
            

        }
        return $formedData;
    }

}
 