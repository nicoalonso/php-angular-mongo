import type { Config } from 'jest';
import { createCjsPreset } from 'jest-preset-angular/presets/index.js';

export default {
  ...createCjsPreset(),
  setupFilesAfterEnv: ['<rootDir>/setup-jest.ts'],
  moduleNameMapper: {
    '\\.(css|less|scss|sss|styl)$': '<rootDir>/node_modules/jest-css-modules',
    '@/(.*)$': '<rootDir>/src/app/$1',
    '@environments/(.*)$': '<rootDir>/src/environments/$1',
    '@tests/(.*)$': '<rootDir>/tests/$1',
  },
  coveragePathIgnorePatterns: ['/node_modules/', '<rootDir>/tests/'],
} satisfies Config;
