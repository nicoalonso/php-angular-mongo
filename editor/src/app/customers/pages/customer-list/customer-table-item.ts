import { ListColumn } from '@/shared/models/list-column';
import { Customer } from '@/customers/model/customer';
import { shortText } from '@/shared/utils/text-utils';
import {
  AdapterFnc,
  FormatterFnc,
  StylableFnc,
  TableIdentifier,
} from '@/shared/components/list-table/list-table-types';

const customerColumns: ListColumn[] = [
  ListColumn.text('id', 'ID'),
  ListColumn.text('name', 'Nombre'),
  ListColumn.text('surname', 'Apellidos'),
  ListColumn.text('number', 'Carnet'),
  ListColumn.boolean('active', 'Estado'),
  ListColumn.text('city', 'Localidad'),
  ListColumn.datetime('createdAt', 'Creado en'),
];

interface CustomerTableItem extends TableIdentifier {
  id: string;
  name: string;
  surname: string;
  number: string;
  active: boolean;
  city: string;
  createdAt: Date;
}

const customerTableAdapter: AdapterFnc<Customer, CustomerTableItem> = (
  value: Customer,
): CustomerTableItem => ({
  _id: value.id,
  id: shortText(value.id, 8),
  name: value.name,
  surname: value.surname,
  number: value.membership.number,
  active: value.membership.active,
  city: value.address.city,
  createdAt: value.createdAt,
});

const customerFormatter: FormatterFnc = (
  column: ListColumn,
  value: unknown,
): string => {
  if (column.field == 'active') {
    return (value as boolean) ? 'Activo' : 'Inactivo';
  }
  return value as string;
};

const customerStylable: StylableFnc = (
  column: ListColumn,
  item: unknown,
): string => {
  if (column.field == 'active') {
    return (item as CustomerTableItem).active
      ? 'font-bold text-green-600'
      : 'font-bold text-red-600';
  }
  return '';
};

export const customerAdapter = customerTableAdapter as AdapterFnc;

export { customerColumns, customerFormatter, customerStylable };
