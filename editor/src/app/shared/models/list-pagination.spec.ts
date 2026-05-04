import { ListPagination } from '@/shared/models/list-pagination';

describe('ListPagination', () => {
  it('should run when create', () => {
    const pagination = new ListPagination();

    expect(pagination).toBeTruthy();
    expect(pagination.total).toBe(0);
    expect(pagination.rowsPerPage).toBe(10);
    expect(pagination.page).toBe(1);
    expect(pagination.totalPages).toBe(1);
    expect(pagination.showPagination).toBeFalsy();
  });

  it('should create from another pagination', () => {
    const pagination = ListPagination.from({
      total: 100,
      rowsPerPage: 20,
      page: 2,
      totalPages: 5,
    } as ListPagination);

    expect(pagination).toBeTruthy();
    expect(pagination.total).toBe(100);
    expect(pagination.rowsPerPage).toBe(20);
    expect(pagination.page).toBe(2);
    expect(pagination.totalPages).toBe(5);
    expect(pagination.showPagination).toBeTruthy();
  });
});
