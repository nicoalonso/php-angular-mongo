<?php declare(strict_types=1);

namespace App\Domain\Book;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<Book>
 */
interface BookRepository extends IdentityRepository
{
    public function obtainByTitle(string $title): ?Book;
    public function obtainByAuthor(string $authorId, ?int $limit = null): BookCollection;
    public function obtainByEditorial(string $editorialId, ?int $limit = null): BookCollection;
}
