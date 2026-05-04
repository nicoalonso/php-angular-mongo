import { Ref } from '@tests/fixtures/ref';
import { ListQuery } from '@/shared/interfaces/list-query';
import { Observable, of } from 'rxjs';
import { ListResult } from '@/shared/models/list-result';
import { ListPagination } from '@/shared/models/list-pagination';
import { EntityPayload } from '@/shared/models/entity';

export abstract class EntityServiceStub<T> {
  protected repositoryData: Map<Ref, T> = new Map();
  public query: ListQuery | null = null;
  public list: T[] = [];
  public read: T | null = null;
  public createPayload: EntityPayload | null = null;
  public updatePayload: EntityPayload | null = null;
  public removed: T | null = null;

  protected constructor() {
    this.makeFixtures();
  }

  public search(query: ListQuery): Observable<ListResult<T>> {
    this.query = query;
    const pagination = new ListPagination(this.list.length);
    const result = new ListResult<T>(this.list, pagination);
    return of(result);
  }

  public getItem(_id: string): Observable<T> {
    return of(this.read as T);
  }

  public createItem(item: EntityPayload): Observable<T> {
    this.createPayload = item;
    return of(item as unknown as T);
  }

  public updateItem(_id: string, item: EntityPayload): Observable<void> {
    this.updatePayload = item;
    return of(undefined);
  }

  public removeItem(item: T): Observable<void> {
    this.removed = item;
    return of(undefined);
  }

  attachAll(): T[] {
    this.list = Array.from(this.repositoryData.values());
    return this.list;
  }

  attach(ref: Ref): T {
    const item: T = this.get(ref);
    this.list.push(item);
    return item;
  }

  put(ref: Ref): T {
    const item: T = this.get(ref);
    this.read = item;
    return item;
  }

  get(ref: Ref): T {
    if (!this.repositoryData.has(ref)) {
      throw new Error(`No fixture found for ref: ${ref}`);
    }

    return this.repositoryData.get(ref)!;
  }

  protected abstract makeFixtures(): void;

  protected addFixture(ref: Ref, item: T): void {
    this.repositoryData.set(ref, item);
  }
}
