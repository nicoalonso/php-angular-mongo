export abstract class Entity {
  protected constructor(
    public id: string,
    public createdBy: string = '',
    public createdAt: Date = new Date(),
    public updatedBy: string | null = null,
    public updatedAt: Date | null = null,
  ) {}

  get lastUser(): string {
    return this.updatedBy || this.createdBy;
  }

  get lastDate(): Date {
    return this.updatedAt || this.createdAt;
  }

  parse(data: Entity): void {
    this.createdBy = data.createdBy;
    this.createdAt = new Date(data.createdAt);
    this.updatedBy = data.updatedBy;
    this.updatedAt = data.updatedAt ? new Date(data.updatedAt) : null;
  }
}

// eslint-disable-next-line @typescript-eslint/no-empty-object-type
export interface EntityPayload {}
