<?php

use Lib\Security;

echo json_encode(Security::claveSecreta());

$passw = Security::encrypt('123456');

echo json_encode($passw);

echo json_encode(Security::validPass('123456', $passw));

echo json_encode(Security::crearToken(Security::claveSecreta(), ['mipass1234']));

