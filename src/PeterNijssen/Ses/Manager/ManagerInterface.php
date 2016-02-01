<?php

namespace PeterNijssen\Ses\Manager;

interface ManagerInterface
{
    /**
     * Create the identity within SES
     */
    public function create();
}
