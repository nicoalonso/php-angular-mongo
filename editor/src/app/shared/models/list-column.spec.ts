import { ListColumn } from '@/shared/models/list-column';
import { ListColumnType } from '@/shared/models/list-column-type';
import { SelectOption } from '@/shared/models/select-option';

describe('ListColumn', () => {
  it('should make column with text type', () => {
    const col = ListColumn.text('name', 'Name');

    expect(col.field).toBe('name');
    expect(col.header).toBe('Name');
    expect(col.type).toBe(ListColumnType.Text);
    expect(col.filterable).toBeTruthy();
    expect(col.sortable).toBeTruthy();
    expect(col.selectable).toBeFalsy();
    expect(col.getFilterType()).toBe('text');
  });

  it('should make column with number type', () => {
    const col = ListColumn.number('number', 'Number');

    expect(col.field).toBe('number');
    expect(col.header).toBe('Number');
    expect(col.type).toBe(ListColumnType.Number);
    expect(col.filterable).toBeTruthy();
    expect(col.sortable).toBeTruthy();
    expect(col.selectable).toBeFalsy();
    expect(col.getFilterType()).toBe('numeric');
  });

  it('should make column with currency type', () => {
    const col = ListColumn.currency('number', 'Number');

    expect(col.field).toBe('number');
    expect(col.header).toBe('Number');
    expect(col.type).toBe(ListColumnType.Currency);
    expect(col.filterable).toBeTruthy();
    expect(col.sortable).toBeTruthy();
    expect(col.selectable).toBeFalsy();
    expect(col.getFilterType()).toBe('numeric');
  });

  it('should make column with date type', () => {
    const col = ListColumn.date('createdAt', 'Created');

    expect(col.field).toBe('createdAt');
    expect(col.header).toBe('Created');
    expect(col.type).toBe(ListColumnType.Date);
    expect(col.filterable).toBeTruthy();
    expect(col.sortable).toBeTruthy();
    expect(col.selectable).toBeFalsy();
    expect(col.getFilterType()).toBe('date');
  });

  it('should make column with datetime type', () => {
    const col = ListColumn.datetime('createdAt', 'Created');

    expect(col.field).toBe('createdAt');
    expect(col.header).toBe('Created');
    expect(col.type).toBe(ListColumnType.DateTime);
    expect(col.filterable).toBeTruthy();
    expect(col.sortable).toBeTruthy();
    expect(col.selectable).toBeFalsy();
    expect(col.getFilterType()).toBe('date');
  });

  it('should make column with boolean type', () => {
    const col = ListColumn.boolean('enable', 'Enabled');

    expect(col.field).toBe('enable');
    expect(col.header).toBe('Enabled');
    expect(col.type).toBe(ListColumnType.Boolean);
    expect(col.filterable).toBeTruthy();
    expect(col.sortable).toBeTruthy();
    expect(col.selectable).toBeFalsy();
    expect(col.getFilterType()).toBe('boolean');
  });

  it('should make column with select type', () => {
    const options: SelectOption[] = [
      {
        label: 'En préstamo',
        value: 'false',
      },
      {
        label: 'Retornado',
        value: 'true',
      },
    ];
    const col = ListColumn.select('state', 'State', options);

    expect(col.field).toBe('state');
    expect(col.header).toBe('State');
    expect(col.type).toBe(ListColumnType.Text);
    expect(col.filterable).toBeTruthy();
    expect(col.sortable).toBeTruthy();
    expect(col.selectable).toBeTruthy();
    expect(col.options).toHaveLength(2);
    expect(col.getFilterType()).toBe('text');
  });

  it('should make column with no filter', () => {
    const col = ListColumn.text('name', 'Name').noFilter();

    expect(col.field).toBe('name');
    expect(col.header).toBe('Name');
    expect(col.type).toBe(ListColumnType.Text);
    expect(col.filterable).toBeFalsy();
    expect(col.sortable).toBeTruthy();
    expect(col.selectable).toBeFalsy();
  });

  it('should make column with no sort', () => {
    const col = ListColumn.text('name', 'Name').noSort();

    expect(col.field).toBe('name');
    expect(col.header).toBe('Name');
    expect(col.type).toBe(ListColumnType.Text);
    expect(col.filterable).toBeTruthy();
    expect(col.sortable).toBeFalsy();
    expect(col.selectable).toBeFalsy();
  });

  it('should run when get filter value', () => {
    const col = ListColumn.text('name', 'Name');

    expect(col.getFilterValue('text')).toBe('text');
    expect(col.getFilterValue(1234)).toBe('1234');
    expect(col.getFilterValue(true)).toBe('true');
    expect(col.getFilterValue(false)).toBe('false');
    expect(col.getFilterValue(new Date('2026-01-01'))).toBe('1767225600');
  });
});
