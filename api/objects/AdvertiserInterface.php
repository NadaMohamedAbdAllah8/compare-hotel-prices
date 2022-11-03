<?php
namespace Api\Objects;

interface AdvertiserInterface
{

    // read advertisers
    public function read();

    // create advertiser
    public function create();

    // delete the advertiser
    public function delete();

}