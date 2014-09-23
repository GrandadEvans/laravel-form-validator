<?php

namespace Grandadevans\GenerateForm\Interfaces;


interface PathInterface {

    public function setFullPath(array $pathInfo);

    public function getFullPath();

    function sanitizePath($path);
} 
