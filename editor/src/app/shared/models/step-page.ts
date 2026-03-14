import { MenuItem } from 'primeng/api';

export class StepPage {
  public values: string[];

  constructor(
    public step: number,
    public label: string,
    public badge: string[] = [],
    public validate: string[] = [],
  ) {
    this.values = [];
  }

  get hasBadge(): boolean {
    return this.badge.length > 0;
  }

  setValues(values: string[]): void {
    this.values = values.filter((value) => !!value);
  }
}

export class StepPageControl {
  protected steps: StepPage[] = [];

  get count(): number {
    return this.steps.length;
  }

  add(label: string, validate: string[] = [], badge: string[] = []): void {
    const index = this.steps.length;
    this.steps.push(new StepPage(index, label, badge, validate));
  }

  find(step: number): StepPage | undefined {
    return this.steps.find((s) => s.step === step);
  }

  computeBadges(step: number): string[] {
    const values: string[] = [];

    for (const stepControl of this.steps) {
      if (stepControl.step > step - 1) {
        break;
      }

      values.push(...stepControl.values);
    }

    return values;
  }

  getMenuItems(): MenuItem[] {
    return this.steps.map((step) => ({ label: step.label }));
  }
}
