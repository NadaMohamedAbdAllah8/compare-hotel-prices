<?php

interface DatabaseModel
{
    public function read();
    public function create();
    public function delete();
}