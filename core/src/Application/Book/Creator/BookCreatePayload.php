<?php declare(strict_types=1);

namespace App\Application\Book\Creator;

use App\Application\Book\Creator\Payload\BookDetailPayload;
use App\Application\Book\Creator\Payload\BookSalePayload;
use App\Domain\Identity\Payload;

class BookCreatePayload extends Payload
{
    private string $title;
    private string $description;
    private string $authorId;
    private string $editorialId;
    private BookDetailPayload $detail;
    private BookSalePayload $sale;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->title = $this->data->toString('title');
        $this->description = $this->data->toString('description');
        $this->authorId = $this->data->toString('authorId');
        $this->editorialId = $this->data->toString('editorialId');

        $this->detail = new BookDetailPayload($this->data->toArray('detail'));
        $this->sale = new BookSalePayload($this->data->toArray('sale'));
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function getEditorialId(): string
    {
        return $this->editorialId;
    }

    public function getDetail(): BookDetailPayload
    {
        return $this->detail;
    }

    public function getSale(): BookSalePayload
    {
        return $this->sale;
    }
}
