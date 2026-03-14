import { inject } from '@angular/core';
import { catchError, map, Observable, of } from 'rxjs';
import { HttpClient } from '@angular/common/http';
// Environment
import { environment } from '@environments/environment';
// Models
import { ListResult } from '@/shared/models/list-result';
import { ListQuery } from '@/shared/interfaces/list-query';
import { ListPagination } from '@/shared/models/list-pagination';
import { ApiResponse } from '@/shared/interfaces/api-response';

export abstract class ListService<T = unknown> {
  protected httpClient: HttpClient = inject(HttpClient);

  search(query: ListQuery): Observable<ListResult<T>> {
    const endpoint = this.buildEndpoint();
    return this.httpClient
      .get<ListResult<T>>(endpoint, {
        params: query as never,
      })
      .pipe(
        map((resp: ListResult<T>) => {
          const pagination = ListPagination.from(resp.pagination);
          const items = this.getItems(resp.items);
          return new ListResult<T>(items, pagination);
        }),
        catchError(() => {
          const result = new ListResult<T>();
          return of(result);
        }), // Handle error
      );
  }

  protected getItems(items: T[] | undefined): T[] {
    if (!items) {
      return [];
    }

    return items.map((item) => this.makeItem(item));
  }

  public getItem(id: string): Observable<T> {
    const url = this.buildEndpoint(id);
    return this.httpClient
      .get<ApiResponse<T>>(url)
      .pipe(map((resp) => this.makeItem(resp.data)));
  }

  protected buildEndpoint(id?: string): string {
    const baseEndpoint = environment.api.endpoint;
    const resource = this.getResource();

    return id
      ? `${baseEndpoint}/${resource}/${id}`
      : `${baseEndpoint}/${resource}`;
  }

  protected abstract getResource(): string;
  protected abstract makeItem(item: T): T;
}
