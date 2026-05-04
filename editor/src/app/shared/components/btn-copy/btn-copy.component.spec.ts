import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BtnCopyComponent } from '@/shared/components/btn-copy/btn-copy.component';
import { ClipboardService } from '@/shared/services/clipboard.service';
import { ToastService } from '@/shared/services/toast.service';
import {
  ClipboardServiceMock,
  makeClipboardService,
} from '@tests/doubles/services/clipboard-service.mock';
import { makeToastService } from '@tests/doubles/services/toast-service.mock';

describe('BtnCopyComponent', () => {
  let component: BtnCopyComponent;
  let clipboardServiceMock: ClipboardServiceMock;
  let fixture: ComponentFixture<BtnCopyComponent>;

  beforeEach(async () => {
    clipboardServiceMock = makeClipboardService();

    await TestBed.configureTestingModule({
      imports: [BtnCopyComponent],
      providers: [
        { provide: ClipboardService, useValue: clipboardServiceMock },
        { provide: ToastService, useValue: makeToastService() },
      ],
    })
      .overrideComponent(BtnCopyComponent, {
        set: {
          providers: [
            { provide: ClipboardService, useValue: clipboardServiceMock },
          ],
        },
      })
      .compileComponents();

    fixture = TestBed.createComponent(BtnCopyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
    expect(fixture).toMatchSnapshot();
  });

  it('should fail when text is empty', () => {
    component.copy();

    expect(clipboardServiceMock.copyText).not.toHaveBeenCalled();
  });

  it('should run when copy text', () => {
    fixture.componentRef.setInput('text', 'test');
    fixture.componentRef.setInput('title', 'Test');
    fixture.detectChanges();

    component.copy();

    expect(clipboardServiceMock.copyText).toHaveBeenCalledWith('test');
  });
});
