export class ListPagination {
  constructor(
    public total: number = 0,
    public rowsPerPage: number = 10,
    public page: number = 1,
    public totalPages: number = 1,
  ) {}

  public static from(pagination: ListPagination): ListPagination {
    if (!pagination) {
      return new ListPagination();
    }

    return new ListPagination(
      pagination.total,
      pagination.rowsPerPage,
      pagination.page,
      pagination.totalPages,
    );
  }

  get showPagination(): boolean {
    return this.totalPages > 1;
  }
}
