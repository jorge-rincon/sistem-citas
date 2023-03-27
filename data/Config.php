<?php

class Config{

    public function configDB()
    {
        return [
          'db' => [
            'host' => '127.0.0.1',
            'user' => 'root',
            'pass' => '',
            'name' => 'pandorafms',
            'options' => [
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
          ]
        ];
    }


}