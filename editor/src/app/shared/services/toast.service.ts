import { inject, Injectable } from '@angular/core';
import { MessageService, ToastMessageOptions } from 'primeng/api';

export interface ToastInterface {
  success: (message: ToastMessageOptions) => ToastInterface;
  info: (message: ToastMessageOptions) => ToastInterface;
  warn: (message: ToastMessageOptions) => ToastInterface;
  error: (message: ToastMessageOptions) => ToastInterface;
  add: (message: ToastMessageOptions) => ToastInterface;
}

@Injectable({
  providedIn: 'root',
})
export class ToastService implements ToastInterface {
  private readonly topRightKey: string = 'tr';
  private readonly topCenterKey: string = 'tc';
  private readonly bottomRightKey: string = 'br';
  private readonly bottomCenterKey: string = 'bc';

  private messageService: MessageService = inject(MessageService);

  topRight(): ToastInterface {
    return this.wrapper(this.topRightKey);
  }

  topCenter(): ToastInterface {
    return this.wrapper(this.topCenterKey);
  }

  bottomRight(): ToastInterface {
    return this.wrapper(this.bottomRightKey);
  }

  bottomCenter(): ToastInterface {
    return this.wrapper(this.bottomCenterKey);
  }

  success(message: ToastMessageOptions): ToastInterface {
    this.add({ ...message, severity: 'success' });
    return this;
  }

  info(message: ToastMessageOptions): ToastInterface {
    this.add({ ...message, severity: 'info' });
    return this;
  }

  warn(message: ToastMessageOptions): ToastInterface {
    this.add({ ...message, severity: 'warn' });
    return this;
  }

  error(message: ToastMessageOptions): ToastInterface {
    this.add({ ...message, severity: 'error' });
    return this;
  }

  add(message: ToastMessageOptions): ToastInterface {
    if (!message.key) {
      message.key = this.bottomCenterKey;
    }
    this.messageService.add(message);
    return this;
  }

  clear(): void {
    this.messageService.clear();
  }

  private wrapper(position: string): ToastInterface {
    // eslint-disable-next-line @typescript-eslint/no-this-alias
    const service = this;
    return {
      success: function (message: ToastMessageOptions): ToastInterface {
        service.success({ ...message, key: position });
        return this;
      },
      info: function (message: ToastMessageOptions): ToastInterface {
        service.info({ ...message, key: position });
        return this;
      },
      warn: function (message: ToastMessageOptions): ToastInterface {
        service.warn({ ...message, key: position });
        return this;
      },
      error: function (message: ToastMessageOptions): ToastInterface {
        service.error({ ...message, key: position });
        return this;
      },
      add: function (message: ToastMessageOptions): ToastInterface {
        service.add({ ...message, key: position });
        return this;
      },
    };
  }
}
