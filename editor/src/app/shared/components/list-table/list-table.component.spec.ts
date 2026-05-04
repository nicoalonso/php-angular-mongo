import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideRouter } from '@angular/router';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { ListTableComponent } from '@/shared/components/list-table/list-table.component';
import { ListColumn } from '@/shared/models/list-column';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';

describe('ListTableComponent', () => {
  let component: ListTableComponent;
  let fixture: ComponentFixture<ListTableComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ListTableComponent],
      providers: [provideRouter([])],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(ListTableComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    let columns: ListColumn[] = [
      ListColumn.text('ID', 'id'),
      ListColumn.text('name', 'Name'),
      ListColumn.text('realName', 'Real Name'),
    ];
    let authorService = new AuthorServiceStub();
    authorService.attachAll();

    fixture.componentRef.setInput('name', 'test-table');
    fixture.componentRef.setInput('columns', columns);
    fixture.componentRef.setInput('service', authorService);

    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });
});
