import { ListColumn } from '@/shared/models/list-column';
import {
  AdapterFnc,
  TableIdentifier,
} from '@/shared/components/list-table/list-table-types';
import { Borrow, borrowStateOptions } from '@/borrows/model/borrow';

export const borrowColumns: ListColumn[] = [
  ListColumn.text('id', 'ID'),
  ListColumn.text('customer', 'Cliente'),
  ListColumn.text('customerNumber', 'Carnet de socio'),
  ListColumn.date('borrowDate', 'Fecha'),
  ListColumn.text('number', 'Número de préstamo'),
  ListColumn.select('returned', 'Estado', borrowStateOptions),
  ListColumn.number('total', 'Total').noFilter().noSort(),
];

interface BorrowTableItem extends TableIdentifier {
  id: string;
  customer: string;
  customerNumber: string;
  borrowDate: Date;
  number: string;
  returned: string;
  total: number;
}

const borrowTableAdapter: AdapterFnc<Borrow, BorrowTableItem> = (
  value: Borrow,
): BorrowTableItem => ({
  _id: value.id,
  id: value.id.slice(0, 8),
  customer: value.customer.fullName,
  customerNumber: value.customer.number,
  borrowDate: value.borrowDate,
  number: value.number,
  returned: value.state,
  total: value.totalBooks,
});

export const borrowAdapter = borrowTableAdapter as AdapterFnc;
