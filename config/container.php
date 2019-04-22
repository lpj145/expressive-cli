<?php

return new class implements \Psr\Container\ContainerInterface {

    public function get($id)
    {
        return $id;
    }

    public function has($id)
    {
        return true;
    }

};
