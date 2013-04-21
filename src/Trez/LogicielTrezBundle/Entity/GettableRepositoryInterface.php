<?php

namespace Trez\LogicielTrezBundle\Entity;

interface GettableRepositoryInterface
{
    public function getAll($id);

    public function getAllowed($id, $user_id);
}
