<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Book\Book;
use App\Domain\Book\BookCollection;
use App\Domain\Book\BookRepository;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<Book>
 */
final class BookRepositoryStub extends EntityRepositoryStub implements BookRepository
{
    public function __construct(
        private readonly ?AuthorRepositoryStub    $repoAuthor = null,
        private readonly ?EditorialRepositoryStub $repoEditorial = null,
    ) {
        parent::__construct();
    }

    public function obtainByTitle(string $title): ?Book
    {
        return $this->read;
    }

    public function obtainByAuthor(string $authorId, ?int $limit = null): BookCollection
    {
        return new BookCollection($this->list);
    }

    public function obtainByEditorial(string $editorialId, ?int $limit = null): BookCollection
    {
        return new BookCollection($this->list);
    }

    protected function makeFixtures(): void
    {
        $shakespeare = $this->repoAuthor?->get(Ref::AuthorShakespeare);
        $cervantes = $this->repoAuthor?->get(Ref::AuthorCervantes);
        $anaya = $this->repoEditorial?->get(Ref::EditorialAnaya);

        $romeoAndJuliet = BookMother::romeoAndJuliet(author: $shakespeare, editorial: $anaya);
        $this->addFixture(Ref::BookRomeoAndJuliet, $romeoAndJuliet);

        $donQuijote = BookMother::donQuijote(author: $cervantes, editorial: $anaya);
        $this->addFixture(Ref::BookDonQuijote, $donQuijote);
    }
}
