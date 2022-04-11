<?php

namespace Web\InterChat\Repository;
use Web\InterChat\Model\Database\User;

interface Repository {

    public function save(User $u);
    
    public function deleteAll();
}