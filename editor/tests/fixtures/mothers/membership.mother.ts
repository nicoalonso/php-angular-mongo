import { BaseMother } from '@tests/fixtures/mothers/base/base-mother';
import { MotherMapping } from '@tests/fixtures/mothers/base/mother-mapping';
import { Membership } from '@/customers/model/membership';

const ACTIVE = {
  number: 'SN00025',
  active: true,
  endedAt: [],
};

const DEACTIVATED = {
  number: 'SN00025',
  active: false,
  endedAt: ['2026-05-28', MotherMapping.DATE] as never[],
};

type MembershipFixture = typeof ACTIVE;

export class MembershipMother extends BaseMother {
  static active(this: void, overrides?: Partial<Membership>): Membership {
    return MembershipMother.create(ACTIVE, overrides);
  }

  static deactivated(this: void, overrides?: Partial<Membership>): Membership {
    return MembershipMother.create(DEACTIVATED, overrides);
  }

  protected static create(
    values: MembershipFixture,
    overrides?: Partial<Membership>,
  ): Membership {
    const fields: Membership = MembershipMother.merge<
      MembershipFixture,
      Membership
    >(values, overrides);
    return Membership.from(fields);
  }
}
