<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Book\Book;
use App\Domain\Book\BookCollection;
use App\Domain\Book\BookRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends  MongoRepository<Book>
 */
final class MongoBookRepository extends MongoRepository implements BookRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function obtainByTitle(string $title): ?Book
    {
        return $this->findOneBy(['title' => $title]);
    }

    public function obtainByAuthor(string $authorId, ?int $limit = null): BookCollection
    {
        $books = $this->findBy(['author.id' => $authorId], limit: $limit);

        return new BookCollection($books);
    }

    public function obtainByEditorial(string $editorialId, ?int $limit = null): BookCollection
    {
        $books = $this->findBy(['editorial.id' => $editorialId], limit: $limit);

        return new BookCollection($books);
    }
}
