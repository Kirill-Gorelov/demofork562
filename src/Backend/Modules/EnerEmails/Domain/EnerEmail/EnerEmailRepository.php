<?php

namespace Backend\Modules\EnerEmails\Domain\EnerEmail;

use Doctrine\ORM\EntityRepository;
use Backend\Core\Engine\Model as BackendModel;

class EnerEmailRepository extends EntityRepository
{
    public function update()
    {
        $this->getEntityManager()->flush();
    }

    public function add(EnerEmail $eneremail)
    {
        $this->getEntityManager()->persist($eneremail);
        $this->getEntityManager()->flush();
    }

    public function delete(EnerEmail $eneremail): void
    {
        $this->getEntityManager()->remove($eneremail);
        $this->getEntityManager()->flush();
    }

    public function get($id): array
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT * FROM email_template WHERE id = ?',
            [$id]
        );

    }
}