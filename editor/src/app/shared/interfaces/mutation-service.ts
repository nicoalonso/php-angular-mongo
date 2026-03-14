import { map, Observable } from 'rxjs';
// Models
import { Entity, EntityPayload } from '@/shared/models/entity';
import { ListService } from '@/shared/interfaces/list-service';
import { ApiResponse } from '@/shared/interfaces/api-response';

export abstract class MutationService<T extends Entity> extends ListService<T> {
  public createItem(item: EntityPayload): Observable<T> {
    const url = this.buildEndpoint();
    return this.httpClient
      .post<ApiResponse<T>>(url, item)
      .pipe(map((resp) => resp.data));
  }

  public updateItem(id: string, item: EntityPayload): Observable<void> {
    const url = this.buildEndpoint(id);
    return this.httpClient.put<void>(url, item);
  }

  public removeItem(item: T): Observable<void> {
    const url = this.buildEndpoint(item.id);
    return this.httpClient.delete<void>(url);
  }
}
