import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { Ref } from '@tests/fixtures/ref';
import { AuthorServiceStub } from '@tests/doubles/services/author-service.stub';
import { AuthorService } from '@/authors/services/author.service';
import { AuthorDialogComponent } from '@/authors/dialogs/author-dialog/author-dialog.component';

describe('AuthorDialogComponent', () => {
  let authorsService: AuthorServiceStub;
  let component: AuthorDialogComponent;
  let fixture: ComponentFixture<AuthorDialogComponent>;

  beforeEach(async () => {
    authorsService = new AuthorServiceStub();

    await TestBed.configureTestingModule({
      imports: [AuthorDialogComponent],
      providers: [
        provideAnimationsAsync(),
        { provide: AuthorService, useValue: authorsService },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(AuthorDialogComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    authorsService.attachAll();
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should run when add filters', () => {
    authorsService.attachAll();
    fixture.componentRef.setInput('visible', true);
    component.form.patchValue({ name: 'test' });
    fixture.detectChanges();

    expect(authorsService.query).toEqual({ name: 'test' });
  });

  it('should run when select an author', (done) => {
    const author = authorsService.attach(Ref.AuthorShakespeare);
    fixture.componentRef.setInput('visible', true);
    fixture.detectChanges();

    component.selected.subscribe((value) => {
      expect(value).toEqual(author);
      done();
    });

    const event = new MouseEvent('click');
    component.onClickItem(author, event);

    expect(component.visible()).toBeFalsy();
  });
});
