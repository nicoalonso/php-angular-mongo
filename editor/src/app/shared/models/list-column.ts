import { ListColumnType } from '@/shared/models/list-column-type';
import { SelectOption } from '@/shared/models/select-option';

export class ListColumn {
  public selectable: boolean = false;
  public options: SelectOption[] = [];

  constructor(
    public field: string,
    public header: string,
    public type: ListColumnType = ListColumnType.Text,
    public filterable: boolean = true,
    public sortable: boolean = true,
  ) {}

  static text(name: string, label: string): ListColumn {
    return new ListColumn(name, label, ListColumnType.Text);
  }

  static number(name: string, label: string): ListColumn {
    return new ListColumn(name, label, ListColumnType.Number);
  }

  static currency(name: string, label: string): ListColumn {
    return new ListColumn(name, label, ListColumnType.Currency);
  }

  static date(name: string, label: string): ListColumn {
    return new ListColumn(name, label, ListColumnType.Date);
  }

  static datetime(name: string, label: string): ListColumn {
    return new ListColumn(name, label, ListColumnType.DateTime);
  }

  static boolean(name: string, label: string): ListColumn {
    return new ListColumn(name, label, ListColumnType.Boolean);
  }

  static select(
    name: string,
    label: string,
    options: SelectOption[],
  ): ListColumn {
    const col = new ListColumn(name, label, ListColumnType.Text);
    col.selectable = true;
    col.options = options;
    return col;
  }

  noFilter(): ListColumn {
    this.filterable = false;
    return this;
  }

  noSort(): ListColumn {
    this.sortable = false;
    return this;
  }

  getFilterType(): string {
    let filterType: string;

    switch (this.type) {
      case ListColumnType.Number:
      case ListColumnType.Currency:
        filterType = 'numeric';
        break;
      case ListColumnType.Boolean:
        filterType = 'boolean';
        break;
      case ListColumnType.Date:
      case ListColumnType.DateTime:
        filterType = 'date';
        break;
      default:
        filterType = 'text';
    }

    return filterType;
  }

  getFilterValue(value: unknown): string {
    if (value instanceof Date) {
      return (value.getTime() / 1000).toString();
    } else if (value instanceof Number) {
      return value.toString();
    } else if (value instanceof Boolean) {
      return value ? 'true' : 'false';
    }

    return value as string;
  }
}
