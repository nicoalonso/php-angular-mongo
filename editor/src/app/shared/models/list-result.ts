import { ListPagination } from '@/shared/models/list-pagination';

export class ListResult<T = unknown> {
  constructor(
    public items: T[] = [],
    public pagination: ListPagination = new ListPagination(),
  ) {}
}
