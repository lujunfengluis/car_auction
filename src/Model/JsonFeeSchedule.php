<?php
namespace App\Model;

use Exception;

class JsonFeeSchedule implements FeeScheduleInterface
{
    public function list(): array
    {
        $jsonContent = $this->readFile();
        $list = json_decode($jsonContent, true); 

        if ($list === null) {
            throw new Exception('Error decoding JSON source data', 502);
        }

        return $list;
    }

    private function readFile(): string
    {
        $json = file_get_contents(__DIR__.'/fee_schedule.json');

        if ($json === false) {
            throw new Exception('Source data not found', 502);
        }

        return $json;
    }
}