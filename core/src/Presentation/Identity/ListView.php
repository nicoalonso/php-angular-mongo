<?php declare(strict_types=1);

namespace App\Presentation\Identity;

use App\Domain\Identity\List\ListResult;
use App\Presentation\Identity\Exception\EntityViewClassNotExistException;
use App\Presentation\Identity\Exception\EntityViewNotSerializableException;
use Closure;

final class ListView extends Result
{
    private bool $isSerialized = false;
    private Closure $serializedMethod;

    public function __construct(
        private readonly ListResult $result,
        private readonly string     $className,
    )
    {
        parent::__construct();

        if (!class_exists($this->className)) {
            throw new EntityViewClassNotExistException($this->className);
        }

        $this->isSerialized = method_exists($this->className, 'serialize');

        if ($this->isSerialized) {
            /** @noinspection PhpClosureCanBeConvertedToFirstClassCallableInspection */
            $this->serializedMethod = Closure::fromCallable([$this->className, 'serialize']);
        } else {
            $interfaces = class_implements($this->className);
            if (!isset($interfaces['JsonSerializable'])) {
                throw new EntityViewNotSerializableException($this->className);
            }
        }
    }

    protected function json(): array
    {
        $items = [];
        foreach ($this->result->items() as $item) {
            if ($this->isSerialized) {
                $items[] = $this->serializedMethod->__invoke($item);
            } else {
                $items[] = new $this->className($item);
            }
        }

        return [
            'items' => $items,
            'pagination' => new PaginationView($this->result->pagination()),
        ];
    }
}
