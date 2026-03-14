import {
  AfterViewInit,
  ChangeDetectionStrategy,
  Component,
  computed,
  input,
  model,
  output,
  signal,
  viewChild,
} from '@angular/core';
import { CurrencyPipe, DatePipe, DecimalPipe, NgClass } from '@angular/common';
import { FormsModule } from '@angular/forms';
// Framework
import { Table, TableLazyLoadEvent, TableModule } from 'primeng/table';
import { Button } from 'primeng/button';
import { Tooltip } from 'primeng/tooltip';
import { ToggleButton } from 'primeng/togglebutton';
import { MenuItem } from 'primeng/api';
import { Breadcrumb } from 'primeng/breadcrumb';
import { Select } from 'primeng/select';
import { FaIconComponent } from '@fortawesome/angular-fontawesome';
// Models
import { ListColumn } from '@/shared/models/list-column';
import { ListPagination } from '@/shared/models/list-pagination';
import { ListResult } from '@/shared/models/list-result';
import { ListConstraint } from '@/shared/models/list-constraint';
import { ListQuery } from '@/shared/interfaces/list-query';
// Services
import { ListService } from '@/shared/interfaces/list-service';
// Components
import {
  AdapterFnc,
  FormatterFnc,
  StylableFnc,
} from '@/shared/components/list-table/list-table-types';
import { ListColumnType } from '@/shared/models/list-column-type';

@Component({
  selector: 'app-list-table',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [
    TableModule,
    DatePipe,
    Button,
    Tooltip,
    FaIconComponent,
    ToggleButton,
    FormsModule,
    Breadcrumb,
    DecimalPipe,
    NgClass,
    Select,
    CurrencyPipe,
  ],
  templateUrl: './list-table.component.html',
  styleUrl: './list-table.component.less',
})
export class ListTableComponent implements AfterViewInit {
  private readonly defaultRowsPerPage = 10;

  dt = viewChild<Table>('dt');

  name = input.required<string>();
  columns = input.required<ListColumn[]>();
  service = input.required<ListService>();
  constraints = input<ListConstraint[]>([]);
  breadcrumb = input<MenuItem[]>([]);
  recordsLabel = input<string>('Registros');
  borderColor = input<string>('');
  showCaption = input<boolean>(true);
  canFilter = input<boolean>(true);
  adapter = input<AdapterFnc>();
  formatter = input<FormatterFnc>();
  stylable = input<StylableFnc>();

  itemClicked = output<unknown>();

  items = signal<unknown[]>([]);
  pagination = signal<ListPagination>(new ListPagination());
  totalRecords = computed<number>(() => this.pagination().total);
  rows = computed(() => this.pagination().rowsPerPage);
  loading = signal(false);
  showFilters = model(false);

  homeBreadcrumb: MenuItem = { icon: 'fas fa-home', routerLink: '/' };
  limitOptions = [5, 10, 20, 25, 30, 50, 100];

  ngAfterViewInit() {
    if (this.dt()) {
      let hasFilter = false;
      for (const filterName in this.dt()!.filters) {
        const filter = this.dt()!.filters[filterName];
        if (filter && !Array.isArray(filter) && null !== filter.value) {
          hasFilter = true;
          break;
        }
      }
      this.showFilters.set(hasFilter);
    }
  }

  onLoadData($event: TableLazyLoadEvent) {
    const query: ListQuery = {
      limit: $event.rows ?? this.defaultRowsPerPage,
    };
    if ($event.first && $event.rows) {
      query.page = 1 + Math.round($event.first / $event.rows);
    }
    // sort fields
    if ($event.multiSortMeta && $event.multiSortMeta.length) {
      query.sort = $event.multiSortMeta
        .map((sort) => `${sort.order > 0 ? '+' : '-'}${sort.field}`)
        .join(',');
    }
    // add filters
    if ($event.filters) {
      for (const filterName in $event.filters) {
        const filter = $event.filters[filterName];
        if (filter && !Array.isArray(filter) && null !== filter.value) {
          const column = this.columns().find((c) => c.field === filterName);
          if (column) {
            query[filterName] = column.getFilterValue(filter.value);
          } else {
            query[filterName] = filter.value;
          }
        }
      }
    }
    // add constraints
    if (this.constraints() && this.constraints().length) {
      for (const constraint of this.constraints()) {
        if (constraint.value === undefined) {
          continue;
        }
        const column = this.columns().find((c) => c.field === constraint.field);
        if (column && column.filterable) {
          query[constraint.field] = column.getFilterValue(constraint.value);
        }
      }
    }

    // search
    const loadingDebounce = setTimeout(() => this.loading.set(true), 200);
    this.service()
      .search(query)
      .subscribe((result: ListResult) => {
        clearTimeout(loadingDebounce);
        this.loading.set(false);

        let rows: unknown[];
        if (this.adapter()) {
          rows = result.items.map((item) => this.adapter()!(item));
        } else {
          rows = result.items;
        }

        this.items.set(rows);
        this.pagination.set(result.pagination);
      });
  }

  onClickItem(item: unknown, $event: MouseEvent) {
    $event.stopPropagation();
    this.itemClicked.emit(item);
  }

  getConstraints(): string {
    const items: string[] = [];
    for (const constraint of this.constraints()) {
      if (constraint.value === undefined) {
        continue;
      }
      const column = this.columns().find((c) => c.field === constraint.field);
      if (column && column.filterable) {
        items.push(column.header);
      }
    }
    return items.join(', ');
  }

  getColumnClass(column: ListColumn, item: unknown): string {
    if (this.constraints() && this.constraints().length > 0) {
      const constraint = this.constraints().find(
        (c) => c.field === column.field,
      );
      if (constraint !== undefined && constraint.value !== undefined) {
        return 'text-purple-600 font-bold';
      }
    }

    if (this.stylable()) {
      return this.stylable()!(column, item);
    } else if (
      column.type == ListColumnType.Number ||
      column.type == ListColumnType.Currency
    ) {
      return 'text-right';
    }

    return '';
  }

  clearFilters() {
    this.dt()!.clear();
  }

  reload() {
    this.dt()?.reset();
  }
}
