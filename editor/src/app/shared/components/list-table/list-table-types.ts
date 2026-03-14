import { ListColumn } from '@/shared/models/list-column';

export type AdapterFnc<T = unknown, U = unknown> = (data: T) => U;

export type FormatterFnc = (column: ListColumn, value: unknown) => string;

export type StylableFnc = (column: ListColumn, value: unknown) => string;

export interface TableIdentifier {
  _id: string;
}
