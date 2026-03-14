<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Book;

use App\Application\Book\List\BookList;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\List\ListQuery;
use App\Presentation\Identity\ListView;
use App\Presentation\V1\Book\BookListView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class BookListController
{
    public function __invoke(Request $request, BookList $lister): Response
    {
        try {
            $params = $request->query->all();
            $query = ListQuery::fromParams($params);
            $list = $lister->dispatch($query);
            $result = new ListView($list, BookListView::class);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
