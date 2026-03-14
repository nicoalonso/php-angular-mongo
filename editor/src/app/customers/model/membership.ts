export class Membership {
  constructor(
    public number: string,
    public active: boolean,
    public endedAt: Date | null,
  ) {}

  public static from(item: Membership): Membership {
    return new Membership(
      item.number,
      item.active,
      item.endedAt ? new Date(item.endedAt) : null,
    );
  }
}
