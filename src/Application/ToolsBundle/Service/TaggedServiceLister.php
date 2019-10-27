<?php
namespace App\Application\ToolsBundle\Service;

class TaggedServiceLister {
    protected $tags;
    protected $services;

    public function __construct() {
        $this->tags = array();
        $this->services = array();
    }
    
    public function addServiceId($tag, $id) {
        $this->services[$tag][]= $id;
    }
    
    public function getServiceIds($tag) {
        return $this->services[$tag];
    }
}
