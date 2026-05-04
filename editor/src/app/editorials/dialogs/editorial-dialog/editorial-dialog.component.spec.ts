import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { EditorialServiceStub } from '@tests/doubles/services/editorial-service.stub';
import { EditorialService } from '@/editorials/services/editorial.service';
import { EditorialDialogComponent } from '@/editorials/dialogs/editorial-dialog/editorial-dialog.component';
import { Ref } from '@tests/fixtures/ref';

describe('EditorialDialogComponent', () => {
  let editorialService: EditorialServiceStub;
  let component: EditorialDialogComponent;
  let fixture: ComponentFixture<EditorialDialogComponent>;

  beforeEach(async () => {
    editorialService = new EditorialServiceStub();

    await TestBed.configureTestingModule({
      imports: [EditorialDialogComponent],
      providers: [
        provideAnimationsAsync(),
        { provide: EditorialService, useValue: editorialService },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(EditorialDialogComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    editorialService.attachAll();
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when add filters', () => {
    editorialService.attachAll();
    fixture.componentRef.setInput('visible', true);
    component.form.patchValue({ name: 'test' });
    fixture.detectChanges();

    expect(editorialService.query).toEqual({ name: 'test' });
  });

  it('should run when select an editorial', (done) => {
    const editorial = editorialService.attach(Ref.EditorialAnaya);
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    component.selected.subscribe((value) => {
      expect(value).toEqual(editorial);
      done();
    });

    const event = new MouseEvent('click');
    component.onClickItem(editorial, event);

    expect(component.visible()).toBeFalsy();
  });
});
