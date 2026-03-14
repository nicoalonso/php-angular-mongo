import { Environment } from "./environment-type";
import * as env from './envs';

const {
  APP_VERSION = 'beta',
  APP_API_URL = '',
} = env as never;

export const environment: Environment = {
  app: {
    version: APP_VERSION,
  },
  api: {
    endpoint: APP_API_URL,
  },
};
