import { provideRouter, Router } from '@angular/router';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FaIconLibrary } from '@fortawesome/angular-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fireEvent } from '@testing-library/dom';
import { findOneByContent } from '@tests/helpers/search-dom';
import { EditorialServiceStub } from '@tests/doubles/services/editorial-service.stub';
import { EditorialService } from '@/editorials/services/editorial.service';
import EditorialListPage from '@/editorials/pages/editorial-list/editorial-list.page';

describe('EditorialListPage', () => {
  let editorialService: EditorialServiceStub;
  let component: EditorialListPage;
  let fixture: ComponentFixture<EditorialListPage>;

  beforeEach(async () => {
    editorialService = new EditorialServiceStub();

    await TestBed.configureTestingModule({
      imports: [EditorialListPage],
      providers: [
        provideRouter([]),
        { provide: EditorialService, useValue: editorialService },
      ],
    }).compileComponents();

    const library = TestBed.inject(FaIconLibrary);
    library.addIconPacks(fas, far);

    fixture = TestBed.createComponent(EditorialListPage);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    editorialService.attachAll();
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should click on create', () => {
    editorialService.attachAll();
    fixture.detectChanges();

    const router = TestBed.inject(Router);
    const navigateSpy = jest.spyOn(router, 'navigate');

    const button = findOneByContent<HTMLButtonElement>(
      fixture,
      'p-button button',
      'Nueva Editorial',
    );
    fireEvent.click(button);

    expect(navigateSpy).toHaveBeenCalledWith(['editorials', 'create']);
  });
});
