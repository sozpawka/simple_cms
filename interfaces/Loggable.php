<?php

interface Loggable
{
    public function addLog($userId, $actionId);
}