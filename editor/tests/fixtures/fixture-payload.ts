import { existsSync, readFileSync } from 'fs';
import { join } from 'path';

export class FixturePayload<T> {
  protected overrides: Partial<T> = {};

  override(overrides: Partial<T>): this {
    this.overrides = overrides;
    return this;
  }

  load(name: string): T {
    const filePath = join(__dirname, 'Payload', `${name}.json`);

    if (!existsSync(filePath)) {
      throw new Error(`Fixture not found: ${filePath}`);
    }

    const raw = readFileSync(filePath, 'utf-8');
    const data: T = JSON.parse(raw) as T;

    return { ...data, ...this.overrides };
  }
}
