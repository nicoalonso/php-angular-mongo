<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Sequence\SequenceNumber;
use App\Domain\Sequence\SequenceNumberRepository;
use App\Domain\Sequence\SequenceType;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<SequenceNumber>
 */
final class MongoSequenceNumberRepository extends MongoRepository implements SequenceNumberRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SequenceNumber::class);
    }

    public function obtainByType(SequenceType $type): SequenceNumber
    {
        $number = $this->findOneBy(['type' => $type->value]);
        if (null === $number) {
            $number = new SequenceNumber($type);
        } else {
            $number->next();
        }

        return $number;
    }

    public function nextNumber(SequenceType $type): SequenceNumber
    {
        $number = $this->findOneBy(['type' => $type->value]);
        if (null === $number) {
            $number = new SequenceNumber($type);
        } else {
            $number->next();
        }

        $this->save($number);
        return $number;
    }
}
