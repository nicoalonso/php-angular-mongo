import { ComponentFixture, TestBed } from '@angular/core/testing';
import { TrackingSectionComponent } from '@/shared/components/tracking-section/tracking-section.component';
import { AuthorMother } from '@tests/fixtures/mothers/author.mother';

describe('TrackingSectionComponent', () => {
  let component: TrackingSectionComponent;
  let fixture: ComponentFixture<TrackingSectionComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TrackingSectionComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(TrackingSectionComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    const author = AuthorMother.cervantes();
    fixture.componentRef.setInput('entity', author);

    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should create with update data', () => {
    const author = AuthorMother.cervantes();
    author.updatedBy = 'test-user';
    author.updatedAt = new Date('2024-01-01T12:00:00Z');
    fixture.componentRef.setInput('entity', author);

    fixture.detectChanges();

    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });
});
