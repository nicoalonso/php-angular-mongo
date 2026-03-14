import js from '@eslint/js';
import globals from 'globals';
import tseslint from '@typescript-eslint/eslint-plugin';
import tsparser from '@typescript-eslint/parser';
import angular from '@angular-eslint/eslint-plugin';
import prettier from 'eslint-plugin-prettier';
import importPlugin from 'eslint-plugin-import';
import preferArrow from 'eslint-plugin-prefer-arrow';

export default [
  {
    files: ['src/**/*.ts'],
    ignores: ['src/environments/envs.ts', '**/*.js'],
    languageOptions: {
      parser: tsparser,
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
    plugins: {
      '@typescript-eslint': tseslint,
      '@angular-eslint': angular,
      prettier,
      import: importPlugin,
      'prefer-arrow': preferArrow,
    },
    rules: {
      ...js.configs.recommended.rules,
      ...tseslint.configs.recommended.rules,
      ...angular.configs.recommended.rules,
      'multiline-ternary': 'off',
      'no-return-assign': 'off',
      'no-undef': 'warn',
      '@angular-eslint/prefer-inject': 'warn',
      '@angular-eslint/no-output-on-prefix': 'warn',
      '@typescript-eslint/no-empty-object-type': 'warn',
    },
  },
];
