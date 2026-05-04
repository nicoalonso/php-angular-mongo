import { Confirmation } from 'primeng/api';
import { Observable } from 'rxjs';

export class ConfirmationServiceStub {
  public requireConfirmation$: Observable<Confirmation> =
    new Observable<Confirmation>();
  public data: Confirmation | null = null;

  confirm(confirmation: Confirmation): this {
    this.data = confirmation;
    return this;
  }

  close(): this {
    this.data = null;
    return this;
  }

  onAccept(): void {}

  public accept(): void {
    if (this.data?.accept) {
      this.data.accept();
    }
  }
}
