import { ListColumn } from '@/shared/models/list-column';
import {
  AdapterFnc,
  TableIdentifier,
} from '@/shared/components/list-table/list-table-types';
import { Sale } from '@/sales/model/sale';
import { shortText } from '@/shared/utils/text-utils';

export const saleColumns: ListColumn[] = [
  ListColumn.text('id', 'ID'),
  ListColumn.text('customer', 'Cliente'),
  ListColumn.date('date', 'Fecha'),
  ListColumn.text('number', 'Número de factura'),
  ListColumn.number('total', 'Total').noFilter().noSort(),
];

interface SaleTableItem extends TableIdentifier {
  id: string;
  customer: string;
  date: Date;
  number: string;
  total: number;
}

const saleTableAdapter: AdapterFnc<Sale, SaleTableItem> = (
  value: Sale,
): SaleTableItem => ({
  _id: value.id,
  id: shortText(value.id, 8),
  customer: value.customer.name,
  date: value.invoice.date,
  number: value.number,
  total: value.invoice.total,
});

export const saleAdapter = saleTableAdapter as AdapterFnc;
