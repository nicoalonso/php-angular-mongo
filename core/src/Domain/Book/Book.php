<?php declare(strict_types=1);

namespace App\Domain\Book;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorDescriptor;
use App\Domain\Book\Exception\TitleEmptyException;
use App\Domain\Editorial\Editorial;
use App\Domain\Editorial\EditorialDescriptor;
use App\Domain\Identity\Entity;

class Book extends Entity
{
    private string $title;
    private string $description;
    private AuthorDescriptor $author;
    private EditorialDescriptor $editorial;
    private BookDetail $detail;
    private BookSale $sale;
    private int $stock;

    public function __construct(
        string     $title,
        string     $description,
        Author     $author,
        Editorial  $editorial,
        BookDetail $detail,
        BookSale   $sale,
        string     $createdBy,
    )
    {
        parent::__construct($createdBy);
        $this->check($title);

        $this->title = $title;
        $this->description = $description;
        $this->author = $author->getDescriptor();
        $this->editorial = $editorial->getDescriptor();
        $this->detail = $detail;
        $this->sale = $sale;
        $this->stock = 0;
    }

    public function modify(
        string     $title,
        string     $description,
        Author     $author,
        Editorial  $editorial,
        BookDetail $detail,
        BookSale   $sale,
        string     $updatedBy,
    ): void
    {
        $this->check($title);

        $this->title = $title;
        $this->description = $description;
        $this->author = $author->getDescriptor();
        $this->editorial = $editorial->getDescriptor();
        $this->detail = $detail;
        $this->sale = $sale;
        $this->updated($updatedBy);
    }

    private function check(string $title): void
    {
        if (empty($title)) {
            throw new TitleEmptyException();
        }
    }

    public function getDescriptor(): BookDescriptor
    {
        return new BookDescriptor(
            $this->id,
            $this->title,
            $this->detail->getIsbn(),
        );
    }

    public function changeStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthor(): AuthorDescriptor
    {
        return $this->author;
    }

    public function getEditorial(): EditorialDescriptor
    {
        return $this->editorial;
    }

    public function getDetail(): BookDetail
    {
        return $this->detail;
    }

    public function getSale(): BookSale
    {
        return $this->sale;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
}
