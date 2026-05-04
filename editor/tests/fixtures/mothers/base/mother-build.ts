export type BuildMethod = (overrides?: any) => object;

export class MotherBuild {
  constructor(private method: BuildMethod) {}

  build(): object {
    return this.method();
  }
}
