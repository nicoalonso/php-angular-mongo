import { ListColumn } from '@/shared/models/list-column';
import {
  AdapterFnc,
  TableIdentifier,
} from '@/shared/components/list-table/list-table-types';
import { Purchase } from '@/purchase/model/purchase';
import { shortText } from '@/shared/utils/text-utils';

export const purchaseColumns: ListColumn[] = [
  ListColumn.text('id', 'ID'),
  ListColumn.text('provider', 'Proveedor'),
  ListColumn.date('purchasedAt', 'Fecha'),
  ListColumn.text('invoiceNumber', 'Número de factura'),
  ListColumn.number('invoiceTotal', 'Total').noFilter().noSort(),
];

interface PurchaseTableItem extends TableIdentifier {
  id: string;
  provider: string;
  purchasedAt: Date;
  invoiceNumber: string;
  invoiceTotal: number;
}

const purchaseTableAdapter: AdapterFnc<Purchase, PurchaseTableItem> = (
  value: Purchase,
): PurchaseTableItem => ({
  _id: value.id,
  id: shortText(value.id, 8),
  provider: value.provider.name,
  purchasedAt: value.purchasedAt,
  invoiceNumber: value.invoice.number,
  invoiceTotal: value.invoice.total,
});

export const purchaseAdapter = purchaseTableAdapter as AdapterFnc;
